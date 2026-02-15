<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth; // PASTIKAN IMPORT INI ADA
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
        $transactions = Transaction::where('id_user', Auth::user()->id)
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
    public function store(Request $request)
    {
        $customMessages = [
            'items.required' => 'Keranjang belanja tidak boleh kosong. Silakan pilih menu terlebih dahulu.',
            'items.array' => 'Format item keranjang tidak valid.',
            'items.*.menu_id.required' => 'ID menu harus dipilih.',
            'items.*.menu_id.exists' => 'Menu yang dipilih tidak tersedia.',
            'items.*.qty.required' => 'Jumlah item harus diisi.',
            'items.*.qty.integer' => 'Jumlah item harus berupa angka.',
            'items.*.qty.min' => 'Jumlah item minimal 1.',
            'metode_pembayaran.required' => 'Metode pembayaran harus dipilih.',
            'metode_pembayaran.in' => 'Metode pembayaran tidak valid.',
            'mode_pesanan.required' => 'Jenis pesanan harus dipilih.',
            'mode_pesanan.in' => 'Jenis pesanan tidak valid.',
            'dibayar.required' => 'Jumlah dibayar harus diisi.',
            'dibayar.integer' => 'Jumlah dibayar harus berupa angka.',
            'dibayar.min' => 'Jumlah dibayar tidak boleh negatif.',
        ];

        $validator = Validator::make($request->all(), [
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|integer|exists:menu,id',
            'items.*.qty' => 'required|integer|min:1',
            'metode_pembayaran' => 'required|in:Tunai,QRIS,Transfer Bank,E-Wallet',
            'dibayar' => 'required|integer|min:0',
            'nomor_meja' => 'nullable|string|max:10',
            'mode_pesanan' => 'required|in:Dine In,Take Away'
        ], $customMessages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal. Periksa kembali input Anda.',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        DB::beginTransaction();
        try {
            // Gabungkan qty per menu untuk mencegah duplikasi item yang sama
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

                if (!$menu || !$menu->is_active) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Menu tidak tersedia atau sudah dinonaktifkan.',
                        'errors' => ['items' => ['Terdapat menu yang sudah tidak tersedia.']]
                    ], 422);
                }

                if ($menu->stok <= 0) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Stok menu {$menu->nama_menu} habis dan tidak dapat ditransaksikan.",
                        'errors' => ['items' => ["Stok menu {$menu->nama_menu} habis."]]
                    ], 422);
                }

                if ($qty > $menu->stok) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Stok menu {$menu->nama_menu} tidak mencukupi. Tersisa {$menu->stok}.",
                        'errors' => ['items' => ["Jumlah pesanan {$menu->nama_menu} melebihi stok tersedia."]]
                    ], 422);
                }

                $itemSubtotal = $menu->harga * $qty;
                $subtotal += $itemSubtotal;

                $items[] = [
                    'menu_id' => $menuId,
                    'harga' => $menu->harga,
                    'qty' => $qty,
                    'subtotal' => $itemSubtotal,
                    'catatan' => ''
                ];
            }


            $pajak = $subtotal * 0.1;
            $total = $subtotal + $pajak;
            $isNonCashPayment = in_array($validated['metode_pembayaran'], ['QRIS', 'Transfer Bank', 'E-Wallet']);
            $dibayar = $isNonCashPayment ? $total : $validated['dibayar'];
            $kembalian = $isNonCashPayment ? 0 : $dibayar - $total;

            if ($kembalian < 0) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Pembayaran tidak mencukupi. Total: Rp. ' . number_format($total, 0, ',', '.'),
                    'errors' => ['dibayar' => ['Uang yang dibayar kurang dari total pembayaran.']]
                ], 422);
            }


            $userId = Auth::user()->id;

            $transaction = Transaction::create([
                'id_user' => $userId,
                'tanggal' => now(),
                'nomor_meja' => $validated['nomor_meja'] ?? null,
                'mode_pesanan' => $validated['mode_pesanan'],
                'subtotal' => $subtotal,
                'pajak' => $pajak,
                'total' => $total,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'dibayar' => $dibayar,
                'kembalian' => $kembalian
            ]);

            foreach ($items as $item) {
                $item['transaksi_id'] = $transaction->id;
                TransactionDetail::create($item);
                $menu = $menus->get($item['menu_id']);
                $menu->stok -= $item['qty'];
                $menu->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil!',
                'transaction_id' => $transaction->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage(),
                'errors' => ['server' => [$e->getMessage()]]
            ], 500);
        }
    }

    /**
     * Display the specified transaction's receipt.
     */
    public function receipt(Transaction $transaction)
    {
        // --- INI ADALAH BARIS YANG MENYEBABKAN ERROR (SEBELUM DIPERBAIKI) ---
        // --- GUNAKAN CARA YANG BENAR UNTUK MEMBANDINGKAN ID USER ---
        if ($transaction->id_user !== Auth::user()->id) {
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
        if ($transaction->id_user !== Auth::user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $transaction->load('details.menu');

        return view('kasir.transaction.show', compact('transaction'));
    }
}
