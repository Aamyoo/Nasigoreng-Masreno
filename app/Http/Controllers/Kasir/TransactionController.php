<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Midtrans\Config;
use Midtrans\Snap;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil hanya transaksi milik kasir yang sedang login
        $transactions = Transaction::where('id_user', Auth::id())
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('kasir.transaction.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $menus = Menu::where('is_active', 1)
            ->where('stok', '>', 0)
            ->get();

        return view('kasir.transaction.create', compact('menus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {


        $validator = Validator::make($request->all(), [
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|integer|exists:menu,id',
            'items.*.qty' => 'required|integer|min:1',
            'metode_pembayaran' => 'required|in:Tunai,QRIS,Transfer Bank,E-Wallet',
            'dibayar' => 'required|integer|min:0',
            'nomor_meja' => 'nullable|string|max:10',
            'mode_pesanan' => 'required|in:Dine In,Take Away',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal. Periksa kembali input Anda.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        DB::beginTransaction();

        try {
            $requestedQuantities = [];
            foreach ($validated['items'] as $item) {
                $menuId = (int) $item['menu_id'];
                $requestedQuantities[$menuId] = ($requestedQuantities[$menuId] ?? 0) + (int) $item['qty'];
            }

            // Lock data menu agar stok aman saat diproses bersamaan
            $menus = Menu::whereIn('id', array_keys($requestedQuantities))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $subtotal = 0;
            $items = [];

            foreach ($requestedQuantities as $menuId => $qty) {
                $menu = $menus->get($menuId);

                if (! $menu || ! $menu->is_active) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Menu tidak tersedia atau sudah dinonaktifkan.',
                    ], 422);
                }

                if ($qty > $menu->stok) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Stok menu {$menu->nama_menu} tidak mencukupi. Tersisa {$menu->stok}.",
                    ], 422);
                }

                $itemSubtotal = $menu->harga * $qty;
                $subtotal += $itemSubtotal;

                $items[] = [
                    'menu_id' => $menuId,
                    'harga' => $menu->harga,
                    'qty' => $qty,
                    'subtotal' => $itemSubtotal,
                    'catatan' => '',
                ];
            }


            $pajak = (int) round($subtotal * 0.1);
            $total = $subtotal + $pajak;
            $isNonCash = in_array($validated['metode_pembayaran'], ['QRIS', 'Transfer Bank', 'E-Wallet'], true);
            $dibayar = $isNonCash ? 0 : (int) $validated['dibayar'];
            $kembalian = $isNonCash ? 0 : $dibayar - $total;

            if (! $isNonCash && $kembalian < 0) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Pembayaran tidak mencukupi.',
                ], 422);
            }


            $orderId = 'TRX-' . now()->format('YmdHis') . '-' . strtoupper(substr(uniqid(), -6));


            $transaction = Transaction::create([
                'id_user' => Auth::id(),
                'tanggal' => now(),
                'nomor_meja' => $validated['nomor_meja'] ?? null,
                'mode_pesanan' => $validated['mode_pesanan'],
                'subtotal' => $subtotal,
                'pajak' => $pajak,
                'total' => $total,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'dibayar' => $dibayar,
                'kembalian' => $kembalian,
                'payment_status' => $isNonCash ? 'pending' : 'settlement',
                'midtrans_order_id' => $isNonCash ? $orderId : null,
                'midtrans_transaction_status' => $isNonCash ? 'pending' : null,
            ]);

            foreach ($items as $item) {
                TransactionDetail::create($item + ['transaksi_id' => $transaction->id]);
                $menu = $menus->get($item['menu_id']);
                $menu->decrement('stok', $item['qty']);
            }

            $snapToken = null;
            if ($isNonCash) {
                Config::$serverKey = config('midtrans.serverKey');
                Config::$clientKey = config('midtrans.clientKey');
                Config::$isProduction = (bool) config('midtrans.isProduction', false);
                Config::$isSanitized = (bool) config('midtrans.isSanitized', true);
                Config::$is3ds = (bool) config('midtrans.is3ds', true);

                $lineItems = [];
                foreach ($items as $item) {
                    $menu = $menus->get($item['menu_id']);
                    $lineItems[] = [
                        'id' => (string) $item['menu_id'],
                        'price' => (int) $item['harga'],
                        'quantity' => (int) $item['qty'],
                        'name' => $menu->nama_menu,
                    ];
                }

                $lineItems[] = [
                    'id' => 'tax',
                    'price' => (int) $pajak,
                    'quantity' => 1,
                    'name' => 'Pajak 10%',
                ];

                $params = [
                    'transaction_details' => [
                        'order_id' => $orderId,
                        'gross_amount' => (int) $total,
                    ],
                    'item_details' => $lineItems,
                    'customer_details' => [
                        'first_name' => Auth::user()->nama_lengkap ?? Auth::user()->name ?? Auth::user()->username,
                        'email' => Auth::user()->email,
                    ],
                    'enabled_payments' => ['qris', 'bank_transfer', 'gopay', 'shopeepay'],
                ];

                $snapToken = Snap::getSnapToken($params);
                $transaction->update(['midtrans_snap_token' => $snapToken]);;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dibuat.',
                'transaction_id' => $transaction->id,
                'snap_token' => $snapToken,
                'is_non_cash' => $isNonCash,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updatePaymentStatus(Request $request, Transaction $transaction): JsonResponse
    {
        if ($transaction->id_user !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:pending,settlement,expire,cancel,deny',
            'transaction_status' => 'nullable|string|max:50',
        ]);

        $transaction->update([
            'payment_status' => $request->string('status')->toString(),
            'midtrans_transaction_status' => $request->string('transaction_status')->toString() ?: $transaction->midtrans_transaction_status,
            'dibayar' => $request->status === 'settlement' ? $transaction->total : $transaction->dibayar,
            'kembalian' => 0,
        ]);

        return response()->json(['success' => true]);
    }

    public function receipt(Transaction $transaction)
    {
        if ($transaction->id_user !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $transaction->load('user', 'details.menu');

        return view('kasir.transaction.receipt', compact('transaction'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        // Pastikan kasir hanya bisa lihat transaksinya sendiri
        if ($transaction->id_user !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $transaction->load('details.menu');

        return view('kasir.transaction.show', compact('transaction'));
    }
}
