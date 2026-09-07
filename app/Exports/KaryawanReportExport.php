<?php

namespace App\Exports;

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

    public function collection()
    {
        return $this->transactions;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Kategori',
            'Sumber/Keterangan',
            'Nominal',
            'Metode Bayar',
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->transaction_date->format('d/m/Y'),
            $transaction->category?->name ?? '-',
            $transaction->source ?? $transaction->description ?? '-',
            (float) $transaction->amount,
            $transaction->payment_method ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
