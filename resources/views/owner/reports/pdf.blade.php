<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan - {{ $business->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            color: #002626;
            background-color: #FFFFFF;
            line-height: 1.5;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #0E4749;
        }

        .header h1 {
            font-size: 20px;
            font-weight: 700;
            color: #002626;
            margin-bottom: 4px;
        }

        .header .subtitle {
            font-size: 14px;
            color: #0E4749;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .header .period {
            font-size: 13px;
            color: #002626;
            background-color: rgba(14, 71, 73, 0.08);
            display: inline-block;
            padding: 4px 16px;
            border-radius: 4px;
            border: 1px solid rgba(14, 71, 73, 0.25);
        }

        .business-info {
            margin-bottom: 20px;
        }

        .business-info p {
            margin-bottom: 2px;
            font-size: 11px;
            color: #002626;
        }

        .business-info .business-name {
            font-size: 13px;
            font-weight: 600;
            color: #002626;
        }

        .summary {
            margin-bottom: 25px;
        }

        .summary h2 {
            font-size: 14px;
            font-weight: 700;
            color: #002626;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid rgba(0, 38, 38, 0.12);
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 10px;
        }

        .summary-item {
            background-color: #F4F7F6;
            border: 1px solid rgba(0, 38, 38, 0.1);
            border-radius: 6px;
            padding: 12px;
            text-align: center;
        }

        .summary-item .label {
            font-size: 10px;
            color: #002626;
            opacity: 0.75;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .summary-item .value {
            font-size: 14px;
            font-weight: 700;
        }

        .text-success { color: #95C623; }
        .text-danger { color: #E55812; }
        .text-primary { color: #0E4749; }
        .text-warning { color: #E55812; }
        .text-info { color: #0E4749; }

        .transactions {
            margin-bottom: 30px;
        }

        .transactions h2 {
            font-size: 14px;
            font-weight: 700;
            color: #002626;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid rgba(0, 38, 38, 0.12);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        thead {
            background-color: rgba(14, 71, 73, 0.08);
        }

        th {
            padding: 8px 10px;
            text-align: left;
            font-weight: 600;
            color: #002626;
            border-bottom: 2px solid rgba(0, 38, 38, 0.2);
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        td {
            padding: 7px 10px;
            border-bottom: 1px solid rgba(0, 38, 38, 0.08);
            vertical-align: middle;
        }

        tr:nth-child(even) {
            background-color: rgba(14, 71, 73, 0.02);
        }

        .badge-masuk {
            background-color: rgba(149, 198, 35, 0.15);
            color: #0E4749;
            border: 1px solid rgba(149, 198, 35, 0.4);
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: 600;
        }

        .badge-keluar {
            background-color: rgba(229, 88, 18, 0.1);
            color: #E55812;
            border: 1px solid rgba(229, 88, 18, 0.25);
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: 600;
        }

        .text-end { text-align: right; }

        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid rgba(0, 38, 38, 0.12);
            text-align: center;
            font-size: 10px;
            color: #002626;
            opacity: 0.7;
        }

        @media print {
            body { padding: 0; }
            .header { page-break-inside: avoid; }
            .transactions { page-break-before: auto; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $business->name }}</h1>
        @if ($business->address)
            <p style="font-size: 11px; color: #002626; opacity: 0.8; margin-bottom: 2px;">{{ $business->address }}</p>
        @endif
        @if ($business->phone)
            <p style="font-size: 11px; color: #002626; opacity: 0.8; margin-bottom: 8px;">Telp: {{ $business->phone }}</p>
        @endif
        <div class="subtitle">LAPORAN KEUANGAN</div>
        <div class="period">{{ $periodLabel }}</div>
    </div>

    <div class="summary">
        <h2>Ringkasan Keuangan</h2>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="label">Total Pemasukan</div>
                <div class="value text-success">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Total Pengeluaran</div>
                <div class="value text-danger">Rp {{ number_format($totalExpense, 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Total Modal</div>
                <div class="value text-primary">Rp {{ number_format($totalCapital, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="label">Pengeluaran Operasional</div>
                <div class="value text-warning">Rp {{ number_format($totalOperational, 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Laba Bersih</div>
                <div class="value {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}">
                    Rp {{ number_format($netProfit, 0, ',', '.') }}
                </div>
            </div>
            <div class="summary-item">
                <div class="label">Jumlah Transaksi</div>
                <div class="value text-info">{{ $transactions->count() }}</div>
            </div>
        </div>
    </div>

    <div class="transactions">
        <h2>Detail Transaksi</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th>Kategori</th>
                    <th>Sumber/Keterangan</th>
                    <th class="text-end">Jumlah</th>
                    <th>User</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $index => $transaction)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                        <td>
                            @if ($transaction->type === 'masuk')
                                <span class="badge-masuk">Pemasukan</span>
                            @else
                                <span class="badge-keluar">Pengeluaran</span>
                            @endif
                        </td>
                        <td>{{ $transaction->category->name ?? '-' }}</td>
                        <td>
                            @if ($transaction->type === 'masuk')
                                {{ $transaction->source ?? '-' }}
                            @else
                                {{ $transaction->description ?? '-' }}
                            @endif
                        </td>
                        <td class="text-end" style="font-weight: 600; color: {{ $transaction->type === 'masuk' ? '#95C623' : '#E55812' }};">
                            {{ $transaction->type === 'masuk' ? '+' : '-' }} Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                        </td>
                        <td>{{ $transaction->user->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 20px; color: #002626; opacity: 0.6;">
                            Tidak ada data transaksi
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB</p>
        <p>{{ $business->name }} &mdash; Sistem Untung Klik</p>
    </div>
</body>
</html>
