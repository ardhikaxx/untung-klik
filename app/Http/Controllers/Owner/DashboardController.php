<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\CapitalEntry;
use App\Models\OperationalExpense;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $business = $user->business;
        $period = $request->get('period', 'month');

        $query = Transaction::where('business_id', $user->business_id);
        $expenseQuery = OperationalExpense::where('business_id', $user->business_id);

        $now = Carbon::now();
        switch ($period) {
            case 'today':
                $query->whereDate('transaction_date', $now->toDateString());
                $expenseQuery->whereDate('expense_date', $now->toDateString());
                break;
            case 'week':
                $startOfWeek = $now->copy()->startOfWeek();
                $query->whereBetween('transaction_date', [$startOfWeek, $now]);
                $expenseQuery->whereBetween('expense_date', [$startOfWeek, $now]);
                break;
            case 'month':
                $query->whereMonth('transaction_date', $now->month)
                    ->whereYear('transaction_date', $now->year);
                $expenseQuery->whereMonth('expense_date', $now->month)
                    ->whereYear('expense_date', $now->year);
                break;
            case 'year':
                $query->whereYear('transaction_date', $now->year);
                $expenseQuery->whereYear('expense_date', $now->year);
                break;
            case 'custom':
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $start = $request->start_date;
                    $end = $request->end_date;
                    $query->whereBetween('transaction_date', [$start, $end]);
                    $expenseQuery->whereBetween('expense_date', [$start, $end]);
                }
                break;
        }

        $totalIncome = (clone $query)->where('type', 'masuk')->sum('amount');
        $totalExpense = (clone $query)->where('type', 'keluar')->sum('amount');
        $totalCapital = CapitalEntry::where('business_id', $user->business_id)->sum('amount');
        $totalOperational = (clone $expenseQuery)->sum('amount');
        $netProfit = $totalIncome - $totalExpense - $totalOperational;

        $recentTransactions = Transaction::where('business_id', $user->business_id)
            ->with('user', 'category')
            ->latest('transaction_date')
            ->latest('id')
            ->limit(10)
            ->get();

        $transactionCount = (clone $query)->count();

        return view('owner.dashboard', compact(
            'business', 'totalIncome', 'totalExpense', 'totalCapital',
            'totalOperational', 'netProfit', 'recentTransactions',
            'transactionCount', 'period'
        ));
    }
}
