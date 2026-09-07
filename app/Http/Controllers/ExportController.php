<?php

namespace App\Http\Controllers;

use App\Exports\OwnerReportExport;
use App\Exports\OwnerSalesReportExport;
use App\Models\CapitalEntry;
use App\Models\OperationalExpense;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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

        return $pdf->download('laporan-keuangan-'.Str::slug($periodLabel).'.pdf');
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
            'laporan-keuangan-'.Str::slug($periodLabel).'.xlsx'
        );
    }

    public function exportSalesPdf(Request $request)
    {
        $user = auth()->user();
        $business = $user->business;

        $data = $this->getSalesData($user, $request);

        $pdf = \PDF::loadView('owner.reports.sales-pdf', array_merge($data, [
            'business' => $business,
            'user' => $user,
        ]))->setPaper('a4', 'landscape');

        $safePeriod = Str::slug(str_replace(['(', ')'], '', $data['periodLabel']));

        return $pdf->download('laporan-penjualan-'.$safePeriod.'.pdf');
    }

    public function exportSalesExcel(Request $request)
    {
        $user = auth()->user();
        $business = $user->business;

        $data = $this->getSalesData($user, $request);

        $safePeriod = Str::slug(str_replace(['(', ')'], '', $data['periodLabel']));

        return Excel::download(
            new OwnerSalesReportExport(
                $data['sales'],
                $data['totalSales'],
                $data['totalHpp'],
                $data['grossProfit'],
                $data['periodLabel'],
                $business
            ),
            'laporan-penjualan-'.$safePeriod.'.xlsx'
        );
    }

    private function getData($user, $period, $startDate, $endDate): array
    {
        $query = Transaction::where('business_id', $user->business_id)->with('user', 'category');
        $expenseQuery = OperationalExpense::where('business_id', $user->business_id);

        $now = Carbon::now();
        $periodLabel = '';

        switch ($period) {
            case 'today':
                $query->whereDate('transaction_date', $now->toDateString());
                $expenseQuery->whereDate('expense_date', $now->toDateString());
                $periodLabel = 'Hari Ini - '.format_date_id($now);
                break;
            case 'week':
                $start = $now->copy()->startOfWeek();
                $query->whereBetween('transaction_date', [$start, $now]);
                $expenseQuery->whereBetween('expense_date', [$start, $now]);
                $periodLabel = format_date_id($start).' - '.format_date_id($now);
                break;
            case 'month':
                $query->whereMonth('transaction_date', $now->month)->whereYear('transaction_date', $now->year);
                $expenseQuery->whereMonth('expense_date', $now->month)->whereYear('expense_date', $now->year);
                $periodLabel = $now->translatedFormat('F Y');
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

        return [$transactions, $totalIncome, $totalExpense, $totalCapital, $totalOperational, $netProfit, $periodLabel];
    }

    private function getSalesData($user, Request $request): array
    {
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

        $sales = $query->latest('transaction_date')->latest('id')->get();

        $totalSales = $sales->sum('amount');
        $totalDiscount = $sales->sum('discount');
        $totalTransactions = $sales->count();

        $saleIds = $sales->pluck('id');
        $itemsQuery = TransactionItem::whereIn('transaction_id', $saleIds);
        $totalItemsSold = (clone $itemsQuery)->sum('quantity');

        $allItems = (clone $itemsQuery)->get();
        $totalHpp = $allItems->sum(function ($item) {
            return $item->quantity * ($item->purchase_price ?: 0);
        });

        $grossProfit = $totalSales - $totalHpp;
        $profitMargin = $totalSales > 0 ? round(($grossProfit / $totalSales) * 100, 1) : 0;
        $averageOrderValue = $totalTransactions > 0 ? round($totalSales / $totalTransactions) : 0;

        $bestsellers = TransactionItem::whereIn('transaction_id', $saleIds)
            ->selectRaw('product_id, product_name, SUM(quantity) as total_qty, SUM(subtotal) as total_revenue, SUM(quantity * purchase_price) as total_cost')
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        return compact(
            'sales', 'totalSales', 'totalDiscount', 'totalTransactions',
            'totalItemsSold', 'totalHpp', 'grossProfit', 'profitMargin',
            'averageOrderValue', 'bestsellers', 'periodLabel'
        );
    }
}
