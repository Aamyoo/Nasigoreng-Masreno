<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today()->format('Y-m-d');

        $startDateInput = $request->start_date ?? $today;
        $endDateInput   = $request->end_date ?? $today;

        try {
            $startDate = Carbon::parse($startDateInput)->startOfDay();
            $endDate   = Carbon::parse($endDateInput)->endOfDay();
        } catch (\Exception $e) {
            $startDate = Carbon::today()->startOfDay();
            $endDate   = Carbon::today()->endOfDay();
        }

        // BASE QUERY
        $baseQuery = Transaction::with('user')
            ->whereBetween('tanggal', [$startDate, $endDate]);

        $totalTransactions = (clone $baseQuery)->count();
        $totalRevenue      = (clone $baseQuery)->sum('total') ?? 0;
        $totalProducts     = Menu::count();

        $recentTransactions = (clone $baseQuery)
            ->orderBy('tanggal', 'desc')
            ->limit(10)
            ->get();

        // ================= CHART =================
        $chartData = Transaction::selectRaw('DATE(tanggal) as date, SUM(total) as total')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $chartDates = $chartData->pluck('date')->map(function ($date) {
            return Carbon::parse($date)->format('d M');
        });

        $chartTotals = $chartData->pluck('total');

        return view('admin.dashboard', compact(
            'totalTransactions',
            'totalRevenue',
            'totalProducts',
            'recentTransactions',
            'chartDates',
            'chartTotals',
            'startDateInput',
            'endDateInput'
        ));
    }
}
