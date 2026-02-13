<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Default ke hari ini
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

        // Ambil ID kasir yang sedang login
        $kasirId = Auth::id();

        // Query transaksi untuk kasir yang sedang login
        $transactionsQuery = Transaction::where('id_user', $kasirId)
            ->whereBetween('tanggal', [$startDate, $endDate]);

        // Hitung total transaksi dan pendapatan
        $totalTransactions = $transactionsQuery->count();
        $totalRevenue = $transactionsQuery->sum('total') ?? 0;

        // Ambil 5 transaksi terakhir
        $recentTransactions = Transaction::where('id_user', $kasirId)
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();

        // Format tanggal untuk tampilan
        $displayStartDate = Carbon::parse($startDate)->format('Y-m-d');
        $displayEndDate = Carbon::parse($endDate)->format('Y-m-d');

        return view('kasir.dashboard', compact(
            'totalTransactions',
            'totalRevenue',
            'recentTransactions',
            'displayStartDate',
            'displayEndDate'
        ));
    }
}
