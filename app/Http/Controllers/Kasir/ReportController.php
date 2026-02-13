<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Build base query with filters.
     */
    protected function buildQuery(Request $request)
    {
        $query = Transaction::with(['user', 'details.menu']);

        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $query->whereBetween('tanggal', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        if ($request->filled('cashier_id')) {
            $query->where('id_user', $request->input('cashier_id'));
        }

        if ($request->filled('metode_pembayaran')) {
            $query->where('metode_pembayaran', $request->input('metode_pembayaran'));
        }

        if ($request->filled('transaction_id')) {
            $query->where('id', $request->input('transaction_id'));
        }

        // Quick filter: high value
        if ($request->filled('quick_filter') && $request->input('quick_filter') === 'high_value') {
            $query->where('total', '>', 100000);
        }

        return $query->orderBy('tanggal', 'desc');
    }

    /**
     * Report index: transaction history, filters, quick actions data.
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $transactions = $this->buildQuery($request)->paginate(20)->withQueryString();

        $cashiers = User::where('role', 'kasir')->orderBy('nama_lengkap')->get(['id', 'nama_lengkap']);
        $paymentMethods = ['Tunai', 'QRIS', 'Transfer Bank', 'E-Wallet'];

        // Quick actions data
        $todayStart = now()->startOfDay();
        $todayEnd = now()->endOfDay();
        $weekStart = now()->startOfWeek();

        $dailySummary = [
            'total_transactions' => Transaction::whereBetween('tanggal', [$todayStart, $todayEnd])->count(),
            'total_revenue' => Transaction::whereBetween('tanggal', [$todayStart, $todayEnd])->sum('total'),
        ];
        $dailySummary['avg_value'] = $dailySummary['total_transactions'] > 0
            ? (int) round($dailySummary['total_revenue'] / $dailySummary['total_transactions'])
            : 0;

        $currentUserId = Auth::id();
        $cashierShiftSummary = [
            'total_transactions' => Transaction::where('id_user', $currentUserId)
                ->whereBetween('tanggal', [$todayStart, $todayEnd])
                ->count(),
            'total_sales' => Transaction::where('id_user', $currentUserId)
                ->whereBetween('tanggal', [$todayStart, $todayEnd])
                ->sum('total'),
        ];

        $lowStockItems = Menu::where('stok', '<', 10)->where('is_active', true)->orderBy('stok')->get(['id', 'nama_menu', 'stok', 'kategori']);

        $popularItemsToday = DB::table('transaksi_detail')
            ->join('transaksi', 'transaksi_detail.transaksi_id', '=', 'transaksi.id')
            ->join('menu', 'transaksi_detail.menu_id', '=', 'menu.id')
            ->whereBetween('transaksi.tanggal', [$todayStart, $todayEnd])
            ->select('menu.nama_menu', DB::raw('SUM(transaksi_detail.qty) as total_qty'))
            ->groupBy('menu.id', 'menu.nama_menu')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        $todayTransactionsCount = Transaction::whereBetween('tanggal', [$todayStart, $todayEnd])->count();
        $weekTransactionsCount = Transaction::whereBetween('tanggal', [$weekStart, $todayEnd])->count();

        return view('kasir.reports.index', compact(
            'transactions',
            'startDate',
            'endDate',
            'cashiers',
            'paymentMethods',
            'dailySummary',
            'cashierShiftSummary',
            'lowStockItems',
            'popularItemsToday',
            'todayTransactionsCount',
            'weekTransactionsCount'
        ));
    }

    /**
     * Get single transaction details (for modal). JSON.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'details.menu']);
        return response()->json([
            'id' => $transaction->id,
            'tanggal' => $transaction->tanggal->format('d/m/Y H:i'),
            'cashier' => $transaction->user ? $transaction->user->nama_lengkap : '-',
            'mode_pesanan' => $transaction->mode_pesanan,
            'nomor_meja' => $transaction->nomor_meja,
            'metode_pembayaran' => $transaction->metode_pembayaran,
            'subtotal' => $transaction->subtotal,
            'pajak' => $transaction->pajak,
            'total' => $transaction->total,
            'dibayar' => $transaction->dibayar,
            'kembalian' => $transaction->kembalian,
            'details' => $transaction->details->map(function ($d) {
                return [
                    'menu' => $d->menu ? $d->menu->nama_menu : '-',
                    'qty' => $d->qty,
                    'harga' => $d->harga,
                    'subtotal' => $d->subtotal,
                    'catatan' => $d->catatan,
                ];
            }),
        ]);
    }

    /**
     * Export filtered transactions to CSV.
     */
    public function export(Request $request): StreamedResponse
    {
        $query = $this->buildQuery($request);
        $transactions = $query->with('user')->get();

        $filename = 'laporan-transaksi-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($transactions) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM
            fputcsv($handle, [
                'ID', 'Tanggal', 'Kasir', 'Mode', 'Meja', 'Metode Bayar', 'Subtotal', 'Pajak', 'Total', 'Dibayar', 'Kembalian'
            ]);

            foreach ($transactions as $t) {
                fputcsv($handle, [
                    $t->id,
                    $t->tanggal->format('d/m/Y H:i'),
                    $t->user ? $t->user->nama_lengkap : '',
                    $t->mode_pesanan,
                    $t->nomor_meja ?? '',
                    $t->metode_pembayaran,
                    $t->subtotal,
                    $t->pajak,
                    $t->total,
                    $t->dibayar,
                    $t->kembalian,
                ]);
            }
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Receipt view for print (single transaction). Used by iframe/print window.
     */
    public function receipt(Transaction $transaction)
    {
        $transaction->load(['user', 'details.menu']);
        return view('kasir.reports.receipt-print', compact('transaction'));
    }

    /**
     * Today's summary report (for print).
     */
    public function todayReport()
    {
        $todayStart = now()->startOfDay();
        $todayEnd = now()->endOfDay();
        $transactions = Transaction::with('user')
            ->whereBetween('tanggal', [$todayStart, $todayEnd])
            ->orderBy('tanggal', 'desc')
            ->get();
        $totalRevenue = $transactions->sum('total');
        $totalCount = $transactions->count();
        $avgValue = $totalCount > 0 ? (int) round($totalRevenue / $totalCount) : 0;
        return view('kasir.reports.today-print', compact('transactions', 'totalRevenue', 'totalCount', 'avgValue'));
    }
}
