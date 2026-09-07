<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan Harian - {{ $business->name ?? 'Untung Klik' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 12px;
            color: #1a1d23;
            line-height: 1.5;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid #22c55e;
        }

        .header h1 {
            font-size: 18px;
            font-weight: 700;
            color: #1a1d23;
            margin-bottom: 4px;
        }

        .header .subtitle {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 2px;
        }

        .header .date {
            font-size: 12px;
            color: #6b7280;
        }

        .summary {
            display: flex;
            gap: 16px;
            margin-bottom: 24px;
        }

        .summary-card {
            flex: 1;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px 16px;
        }

        .summary-card .label {
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .summary-card .value {
            font-size: 18px;
            font-weight: 700;
            color: #22c55e;
        }

        .summary-card .value-count {
            font-size: 18px;
            font-weight: 700;
            color: #2563eb;
        }

        .info {
            margin-bottom: 20px;
            font-size: 11px;
            color: #6b7280;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        thead th {
            background-color: #f3f4f6;
            font-size: 11px;
            font-weight: 600;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            padding: 8px 10px;
            text-align: left;
            border-bottom: 2px solid #e5e7eb;
        }

        thead th:last-child {
            text-align: right;
        }

        tbody td {
            padding: 8px 10px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 12px;
        }

        tbody td:last-child {
            text-align: right;
            font-weight: 600;
            color: #22c55e;
        }

        .total-row td {
            border-top: 2px solid #e5e7eb;
            border-bottom: none;
            font-weight: 700;
            font-size: 13px;
            padding-top: 10px;
        }

        .total-row td:last-child {
            color: #22c55e;
        }

        .footer {
            margin-top: 24px;
            padding-top: 12px;
            border-top: 1px solid #e5e7eb;
            font-size: 10px;
            color: #9ca3af;
            text-align: center;
        }

        .empty-state {
            text-align: center;
            padding: 30px;
            color: #9ca3af;
        }

        .empty-state p {
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $business->name ?? 'Untung Klik' }}</h1>
        <div class="subtitle">LAPORAN PENJUALAN HARIAN</div>
        <div class="date">{{ \Carbon\Carbon::parse($date)->format('d F Y') }}</div>
    </div>

    <div class="summary">
        <div class="summary-card">
            <div class="label">Total Penjualan</div>
            <div class="value">Rp {{ number_format($totalAmount, 0, ',', '.') }}</div>
        </div>
        <div class="summary-card">
            <div class="label">Jumlah Transaksi</div>
            <div class="value-count">{{ $transactions->count() }}</div>
        </div>
    </div>

    <div class="info">
        Dicetak oleh: {{ $user->name }} | {{ now()->format('d/m/Y H:i') }}
    </div>

    @if($transactions->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th>Kategori</th>
                <th>Sumber</th>
                <th>Deskripsi</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $transaction->category->name ?? '-' }}</td>
                <td>{{ $transaction->source ?? '-' }}</td>
                <td>{{ $transaction->description ?? '-' }}</td>
                <td>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="4">TOTAL</td>
                <td>Rp {{ number_format($totalAmount, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
    @else
    <div class="empty-state">
        <p>Tidak ada transaksi pada tanggal ini</p>
    </div>
    @endif

    <div class="footer">
        {{ $business->name ?? 'Untung Klik' }} &mdash; Laporan ini dihasilkan secara otomatis oleh sistem
    </div>
</body>
</html>
