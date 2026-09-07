<?php

namespace App\Http\Controllers;

use App\Exports\OwnerReportExport;
use App\Models\CapitalEntry;
use App\Models\OperationalExpense;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportPdf(Request $request)
    {
        $user = auth()->user();
        $period = $request->get('period', 'month');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        [$transactions, $totalIncome, $totalExpense, $totalCapital, $totalOperational, $netProfit, $periodLabel] =
            $this->getData($user, $period, $startDate, $endDate);

        $business = $user->business;

        $pdf = \PDF::loadView('owner.reports.pdf', compact(
            'transactions', 'totalIncome', 'totalExpense', 'totalCapital',
            'totalOperational', 'netProfit', 'periodLabel', 'business', 'user'
        ));

        return $pdf->download('laporan-keuangan-'.str_replace(' ', '-', strtolower($periodLabel)).'.pdf');
    }

    public function exportExcel(Request $request)
    {
        $user = auth()->user();
        $period = $request->get('period', 'month');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        [$transactions, $totalIncome, $totalExpense, $totalCapital, $totalOperational, $netProfit, $periodLabel] =
            $this->getData($user, $period, $startDate, $endDate);

        $business = $user->business;

        return Excel::download(
            new OwnerReportExport($transactions, $totalIncome, $totalExpense, $totalCapital, $totalOperational, $netProfit, $periodLabel, $business),
            'laporan-keuangan-'.str_replace(' ', '-', strtolower($periodLabel)).'.xlsx'
        );
    }

    private function getData($user, $period, $startDate, $endDate): array
    {
        $query = Transaction::where('business_id', $user->business_id)->with('user', 'category');
        $capitalQuery = CapitalEntry::where('business_id', $user->business_id);
        $expenseQuery = OperationalExpense::where('business_id', $user->business_id);

        $now = Carbon::now();
        $periodLabel = '';

        switch ($period) {
            case 'today':
                $query->whereDate('transaction_date', $now->toDateString());
                $capitalQuery->whereDate('entry_date', $now->toDateString());
                $expenseQuery->whereDate('expense_date', $now->toDateString());
                $periodLabel = 'Hari Ini - '.format_date_id($now);
                break;
            case 'week':
                $start = $now->copy()->startOfWeek();
                $query->whereBetween('transaction_date', [$start, $now]);
                $capitalQuery->whereBetween('entry_date', [$start, $now]);
                $expenseQuery->whereBetween('expense_date', [$start, $now]);
                $periodLabel = format_date_id($start).' - '.format_date_id($now);
                break;
            case 'month':
                $query->whereMonth('transaction_date', $now->month)->whereYear('transaction_date', $now->year);
                $capitalQuery->whereMonth('entry_date', $now->month)->whereYear('entry_date', $now->year);
                $expenseQuery->whereMonth('expense_date', $now->month)->whereYear('expense_date', $now->year);
                $periodLabel = $now->translatedFormat('F Y');
                break;
            case 'year':
                $query->whereYear('transaction_date', $now->year);
                $capitalQuery->whereYear('entry_date', $now->year);
                $expenseQuery->whereYear('expense_date', $now->year);
                $periodLabel = 'Tahun '.$now->year;
                break;
            case 'custom':
                if ($startDate && $endDate) {
                    $query->whereBetween('transaction_date', [$startDate, $endDate]);
                    $capitalQuery->whereBetween('entry_date', [$startDate, $endDate]);
                    $expenseQuery->whereBetween('expense_date', [$startDate, $endDate]);
                    $periodLabel = format_date_id($startDate).' - '.format_date_id($endDate);
                }
                break;
        }

        $transactions = $query->latest('transaction_date')->get();
        $totalIncome = $transactions->where('type', 'masuk')->sum('amount');
        $totalExpense = $transactions->where('type', 'keluar')->sum('amount');
        $totalCapital = $capitalQuery->sum('amount');
        $totalOperational = $expenseQuery->sum('amount');
        $netProfit = $totalIncome - $totalExpense - $totalOperational;

        return [$transactions, $totalIncome, $totalExpense, $totalCapital, $totalOperational, $netProfit, $periodLabel];
    }
}
