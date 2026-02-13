<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        
        $transactions = Transaction::with('user')
            ->whereBetween('tanggal', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('tanggal', 'desc')
            ->get();
        
        $totalRevenue = $transactions->sum('total');
        $totalTransactions = $transactions->count();
        
        return view('admin.reports.index', compact(
            'transactions',
            'startDate',
            'endDate',
            'totalRevenue',
            'totalTransactions'
        ));
    }
}