<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Default ke hari ini jika tidak ada filter tanggal
        $today = Carbon::today()->format('Y-m-d');
        $startDate = $request->input('start_date', $today);
        $endDate = $request->input('end_date', $today);

        // Pastikan format tanggal valid
        try {
            $startDate = Carbon::parse($startDate)->startOfDay()->format('Y-m-d H:i:s');
            $endDate = Carbon::parse($endDate)->endOfDay()->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            // Jika format tanggal tidak valid, gunakan hari ini
            $startDate = Carbon::today()->startOfDay()->format('Y-m-d H:i:s');
            $endDate = Carbon::today()->endOfDay()->format('Y-m-d H:i:s');
        }

        // Query dengan filter tanggal yang konsisten
        $transactionsQuery = Transaction::with('user')
            ->whereBetween('tanggal', [$startDate, $endDate]);

        // Hitung total transaksi dan pendapatan dengan query yang sama
        $totalTransactions = $transactionsQuery->count();
        $totalRevenue = $transactionsQuery->sum('total') ?? 0;

        // Hitung total produk
        $totalProducts = Menu::count();

        // Ambil data transaksi terbaru dengan limit
        $recentTransactions = $transactionsQuery
            ->orderBy('tanggal', 'desc')
            ->limit(10)
            ->get();

        // Format tanggal untuk tampilan
        $displayStartDate = Carbon::parse($startDate)->format('Y-m-d');
        $displayEndDate = Carbon::parse($endDate)->format('Y-m-d');

        return view('admin.dashboard', compact(
            'totalTransactions',
            'totalRevenue',
            'totalProducts',
            'recentTransactions',
            'displayStartDate',
            'displayEndDate'
        ));
    }
}
