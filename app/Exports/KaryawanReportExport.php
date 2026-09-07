<?php

namespace App\Exports;

use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KaryawanReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $transactions;

    protected $totalAmount;

    protected $date;

    protected $business;

    protected $user;

    public function __construct($transactions, $totalAmount, $date, $business, $user)
    {
        $this->transactions = $transactions;
        $this->totalAmount = $totalAmount;
        $this->date = $date;
        $this->business = $business;
        $this->user = $user;
    }

    public function collection(): Enumerable
    {
        return $this->transactions;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'No. Invoice',
            'Kategori',
            'Pelanggan / Sumber',
            'Rincian Produk / Deskripsi',
            'Nominal',
            'Metode Bayar',
        ];
    }

    public function map($transaction): array
    {
        $itemsText = $transaction->is_sale && $transaction->items->isNotEmpty()
            ? $transaction->items->map(fn ($i) => $i->product_name.' ('.$i->quantity.'x)')->implode(', ')
            : ($transaction->description ?? '-');

        return [
            $transaction->transaction_date->format('d/m/Y'),
            $transaction->invoice_number ?? '-',
            $transaction->category?->name ?? '-',
            $transaction->customer_name ?: ($transaction->source ?? '-'),
            $itemsText,
            (float) $transaction->amount,
            ucfirst($transaction->payment_method ?? 'Tunai'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
