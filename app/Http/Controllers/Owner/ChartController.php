<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\OperationalExpense;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function index()
    {
        return view('owner.charts.index');
    }

    public function data(Request $request)
    {
        $user = auth()->user();
        $period = $request->get('period', '6months');
        $now = Carbon::now();

        $labels = [];
        $incomeData = [];
        $expenseData = [];
        $profitData = [];

        switch ($period) {
            case '6months':
                for ($i = 5; $i >= 0; $i--) {
                    $month = $now->copy()->subMonths($i);
                    $labels[] = $month->translatedFormat('M Y');
                    $income = Transaction::where('business_id', $user->business_id)
                        ->where('type', 'masuk')
                        ->whereMonth('transaction_date', $month->month)
                        ->whereYear('transaction_date', $month->year)
                        ->sum('amount');
                    $exp = Transaction::where('business_id', $user->business_id)
                        ->where('type', 'keluar')
                        ->whereMonth('transaction_date', $month->month)
                        ->whereYear('transaction_date', $month->year)
                        ->sum('amount');
                    $opex = OperationalExpense::where('business_id', $user->business_id)
                        ->whereMonth('expense_date', $month->month)
                        ->whereYear('expense_date', $month->year)
                        ->sum('amount');
                    $incomeData[] = (float) $income;
                    $expenseData[] = (float) ($exp + $opex);
                    $profitData[] = (float) ($income - $exp - $opex);
                }
                break;
            case '12months':
                for ($i = 11; $i >= 0; $i--) {
                    $month = $now->copy()->subMonths($i);
                    $labels[] = $month->translatedFormat('M Y');
                    $income = Transaction::where('business_id', $user->business_id)
                        ->where('type', 'masuk')
                        ->whereMonth('transaction_date', $month->month)
                        ->whereYear('transaction_date', $month->year)
                        ->sum('amount');
                    $exp = Transaction::where('business_id', $user->business_id)
                        ->where('type', 'keluar')
                        ->whereMonth('transaction_date', $month->month)
                        ->whereYear('transaction_date', $month->year)
                        ->sum('amount');
                    $opex = OperationalExpense::where('business_id', $user->business_id)
                        ->whereMonth('expense_date', $month->month)
                        ->whereYear('expense_date', $month->year)
                        ->sum('amount');
                    $incomeData[] = (float) $income;
                    $expenseData[] = (float) ($exp + $opex);
                    $profitData[] = (float) ($income - $exp - $opex);
                }
                break;
            case '30days':
                for ($i = 29; $i >= 0; $i--) {
                    $day = $now->copy()->subDays($i);
                    $labels[] = $day->format('d M');
                    $income = Transaction::where('business_id', $user->business_id)
                        ->where('type', 'masuk')
                        ->whereDate('transaction_date', $day->toDateString())
                        ->sum('amount');
                    $exp = Transaction::where('business_id', $user->business_id)
                        ->where('type', 'keluar')
                        ->whereDate('transaction_date', $day->toDateString())
                        ->sum('amount');
                    $opex = OperationalExpense::where('business_id', $user->business_id)
                        ->whereDate('expense_date', $day->toDateString())
                        ->sum('amount');
                    $incomeData[] = (float) $income;
                    $expenseData[] = (float) ($exp + $opex);
                    $profitData[] = (float) ($income - $exp - $opex);
                }
                break;
        }

        return response()->json([
            'labels' => $labels,
            'income' => $incomeData,
            'expense' => $expenseData,
            'profit' => $profitData,
        ]);
    }
}
