<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\CapitalEntry;
use App\Models\OperationalExpense;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $period = $request->get('period', 'month');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = Transaction::where('business_id', $user->business_id)->with('user', 'category');
        $expenseQuery = OperationalExpense::where('business_id', $user->business_id)->with('category');

        $now = Carbon::now();
        $periodLabel = '';

        switch ($period) {
            case 'today':
                $query->whereDate('transaction_date', $now->toDateString());
                $expenseQuery->whereDate('expense_date', $now->toDateString());
                $periodLabel = 'Hari Ini ('.format_date_id($now).')';
                break;
            case 'week':
                $start = $now->copy()->startOfWeek();
                $query->whereBetween('transaction_date', [$start, $now]);
                $expenseQuery->whereBetween('expense_date', [$start, $now]);
                $periodLabel = 'Minggu Ini ('.format_date_id($start).' - '.format_date_id($now).')';
                break;
            case 'month':
                $query->whereMonth('transaction_date', $now->month)->whereYear('transaction_date', $now->year);
                $expenseQuery->whereMonth('expense_date', $now->month)->whereYear('expense_date', $now->year);
                $periodLabel = 'Bulan '.$now->translatedFormat('F Y');
                break;
            case 'year':
                $query->whereYear('transaction_date', $now->year);
                $expenseQuery->whereYear('expense_date', $now->year);
                $periodLabel = 'Tahun '.$now->year;
                break;
            case 'custom':
                if ($startDate && $endDate) {
                    $query->whereBetween('transaction_date', [$startDate, $endDate]);
                    $expenseQuery->whereBetween('expense_date', [$startDate, $endDate]);
                    $periodLabel = format_date_id($startDate).' - '.format_date_id($endDate);
                }
                break;
        }

        $transactions = $query->latest('transaction_date')->get();
        $totalIncome = $transactions->where('type', 'masuk')->sum('amount');
        $totalExpense = $transactions->where('type', 'keluar')->sum('amount');
        $totalCapital = CapitalEntry::where('business_id', $user->business_id)->sum('amount');
        $totalOperational = $expenseQuery->sum('amount');
        $netProfit = $totalIncome - $totalExpense - $totalOperational;
        $transactionCount = $transactions->count();

        return view('owner.reports.index', compact(
            'transactions', 'totalIncome', 'totalExpense', 'totalCapital',
            'totalOperational', 'netProfit', 'transactionCount', 'period', 'periodLabel',
            'startDate', 'endDate'
        ));
    }

    public function custom(Request $request)
    {
        return $this->index($request);
    }
}
