<?php

namespace App\Exports;

use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OwnerReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $transactions;

    protected $totalIncome;

    protected $totalExpense;

    protected $totalCapital;

    protected $totalOperational;

    protected $netProfit;

    protected $periodLabel;

    protected $business;

    public function __construct($transactions, $totalIncome, $totalExpense, $totalCapital, $totalOperational, $netProfit, $periodLabel, $business)
    {
        $this->transactions = $transactions;
        $this->totalIncome = $totalIncome;
        $this->totalExpense = $totalExpense;
        $this->totalCapital = $totalCapital;
        $this->totalOperational = $totalOperational;
        $this->netProfit = $netProfit;
        $this->periodLabel = $periodLabel;
        $this->business = $business;
    }

    public function collection(): Enumerable
    {
        return $this->transactions;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Tipe',
            'Kategori',
            'Sumber/Keterangan',
            'Nominal',
            'Pencatat',
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->transaction_date->format('d/m/Y'),
            $transaction->type === 'masuk' ? 'Uang Masuk' : 'Uang Keluar',
            $transaction->category?->name ?? '-',
            $transaction->source ?? $transaction->description ?? '-',
            (float) $transaction->amount,
            $transaction->user->name,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
