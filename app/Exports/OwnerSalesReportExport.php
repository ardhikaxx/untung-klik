<?php

namespace App\Exports;

use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OwnerSalesReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $sales;

    protected $totalSales;

    protected $totalHpp;

    protected $grossProfit;

    protected $periodLabel;

    protected $business;

    public function __construct($sales, $totalSales, $totalHpp, $grossProfit, $periodLabel, $business)
    {
        $this->sales = $sales;
        $this->totalSales = $totalSales;
        $this->totalHpp = $totalHpp;
        $this->grossProfit = $grossProfit;
        $this->periodLabel = $periodLabel;
        $this->business = $business;
    }

    public function collection(): Enumerable
    {
        return $this->sales;
    }

    public function headings(): array
    {
        return [
            'No. Invoice',
            'Tanggal & Waktu',
            'Kasir / Petugas',
            'Nama Pelanggan',
            'No. HP',
            'Rincian Produk (Qty)',
            'Total Modal (HPP)',
            'Subtotal Produk',
            'Diskon',
            'Total Omset (Netto)',
            'Laba Kotor',
            'Metode Pembayaran',
        ];
    }

    public function map($sale): array
    {
        $itemsSummary = $sale->items->map(function ($item) {
            return $item->product_name.' ('.$item->quantity.'x)';
        })->implode(', ');

        return [
            $sale->invoice_number ?? ('PJ-'.$sale->id),
            $sale->transaction_date->format('d/m/Y H:i'),
            $sale->user?->name ?? '-',
            $sale->customer_name ?: 'Pelanggan Umum',
            $sale->customer_phone ?: '-',
            $itemsSummary ?: '-',
            (float) $sale->total_hpp,
            (float) $sale->subtotal,
            (float) ($sale->discount ?: 0),
            (float) $sale->amount,
            (float) $sale->gross_profit,
            ucfirst($sale->payment_method ?? 'Tunai'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
