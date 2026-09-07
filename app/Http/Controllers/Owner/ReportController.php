<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\CapitalEntry;
use App\Models\OperationalExpense;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
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

        $totalIncome = (clone $query)->where('type', 'masuk')->sum('amount');
        $totalExpense = (clone $query)->where('type', 'keluar')->sum('amount');
        $transactionCount = (clone $query)->count();
        $totalCapital = CapitalEntry::where('business_id', $user->business_id)->sum('amount');
        $totalOperational = $expenseQuery->sum('amount');
        $netProfit = $totalIncome - $totalExpense - $totalOperational;

        $transactions = $query->latest('transaction_date')->latest('id')->paginate(15);

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

    public function sales(Request $request)
    {
        $user = auth()->user();
        $businessId = $user->business_id;

        $period = $request->get('period', 'month');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $cashierId = $request->get('user_id');
        $paymentMethod = $request->get('payment_method');

        $query = Transaction::where('business_id', $businessId)
            ->where('is_sale', true)
            ->with(['items.product', 'user']);

        $now = Carbon::now();
        $periodLabel = '';

        switch ($period) {
            case 'today':
                $query->whereDate('transaction_date', $now->toDateString());
                $periodLabel = 'Hari Ini ('.format_date_id($now).')';
                break;
            case 'yesterday':
                $yesterday = $now->copy()->subDay();
                $query->whereDate('transaction_date', $yesterday->toDateString());
                $periodLabel = 'Kemarin ('.format_date_id($yesterday).')';
                break;
            case 'week':
                $start = $now->copy()->startOfWeek();
                $query->whereBetween('transaction_date', [$start, $now]);
                $periodLabel = 'Minggu Ini ('.format_date_id($start).' - '.format_date_id($now).')';
                break;
            case 'month':
                $query->whereMonth('transaction_date', $now->month)->whereYear('transaction_date', $now->year);
                $periodLabel = 'Bulan '.$now->translatedFormat('F Y');
                break;
            case 'year':
                $query->whereYear('transaction_date', $now->year);
                $periodLabel = 'Tahun '.$now->year;
                break;
            case 'custom':
                if ($startDate && $endDate) {
                    $query->whereBetween('transaction_date', [$startDate, $endDate]);
                    $periodLabel = format_date_id($startDate).' - '.format_date_id($endDate);
                } else {
                    $periodLabel = 'Semua Periode';
                }
                break;
            default:
                $periodLabel = 'Semua Waktu';
                break;
        }

        if ($cashierId) {
            $query->where('user_id', $cashierId);
        }

        if ($paymentMethod) {
            $query->where('payment_method', $paymentMethod);
        }

        $totalSales = (clone $query)->sum('amount');
        $totalDiscount = (clone $query)->sum('discount');
        $totalTransactions = (clone $query)->count();

        $saleIds = (clone $query)->pluck('id');
        $itemsQuery = TransactionItem::whereIn('transaction_id', $saleIds);

        $totalItemsSold = (clone $itemsQuery)->sum('quantity');

        $allItems = (clone $itemsQuery)->get();
        $totalHpp = $allItems->sum(function ($item) {
            return $item->quantity * ($item->purchase_price ?: 0);
        });

        $grossProfit = $totalSales - $totalHpp;
        $profitMargin = $totalSales > 0 ? round(($grossProfit / $totalSales) * 100, 1) : 0;
        $averageOrderValue = $totalTransactions > 0 ? round($totalSales / $totalTransactions) : 0;

        // Bestseller ranking
        $bestsellers = TransactionItem::whereIn('transaction_id', $saleIds)
            ->selectRaw('product_id, product_name, SUM(quantity) as total_qty, SUM(subtotal) as total_revenue, SUM(quantity * purchase_price) as total_cost')
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        // Payment method breakdown
        $paymentBreakdown = (clone $query)
            ->selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('payment_method')
            ->get();

        // Cashier breakdown
        $cashierBreakdown = (clone $query)
            ->selectRaw('user_id, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('user_id')
            ->with('user')
            ->get();

        $transactions = $query->latest('transaction_date')->latest('id')->paginate(15);
        $cashiers = User::where('business_id', $businessId)->get();

        return view('owner.reports.sales', compact(
            'transactions', 'totalSales', 'totalDiscount', 'totalTransactions',
            'totalItemsSold', 'totalHpp', 'grossProfit', 'profitMargin',
            'averageOrderValue', 'bestsellers', 'paymentBreakdown', 'cashierBreakdown',
            'cashiers', 'period', 'periodLabel', 'startDate', 'endDate', 'cashierId', 'paymentMethod'
        ));
    }
}
