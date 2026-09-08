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
            color: #131515;
            background-color: #FFFAFB;
            line-height: 1.5;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid #339989;
        }

        .header h1 {
            font-size: 18px;
            font-weight: 700;
            color: #131515;
            margin-bottom: 4px;
        }

        .header .subtitle {
            font-size: 14px;
            font-weight: 600;
            color: #2B2C28;
            margin-bottom: 2px;
        }

        .header .date {
            font-size: 12px;
            color: #2B2C28;
            opacity: 0.8;
        }

        .summary {
            display: flex;
            gap: 16px;
            margin-bottom: 24px;
        }

        .summary-card {
            flex: 1;
            border: 1px solid rgba(43, 44, 40, 0.12);
            background-color: #FFFAFB;
            border-radius: 8px;
            padding: 12px 16px;
        }

        .summary-card .label {
            font-size: 11px;
            color: #2B2C28;
            opacity: 0.8;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .summary-card .value {
            font-size: 18px;
            font-weight: 700;
            color: #339989;
        }

        .summary-card .value-count {
            font-size: 18px;
            font-weight: 700;
            color: #131515;
        }

        .info {
            margin-bottom: 20px;
            font-size: 11px;
            color: #2B2C28;
            opacity: 0.8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        thead th {
            background-color: rgba(125, 226, 209, 0.15);
            font-size: 11px;
            font-weight: 600;
            color: #131515;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            padding: 8px 10px;
            text-align: left;
            border-bottom: 2px solid rgba(43, 44, 40, 0.2);
        }

        thead th:last-child {
            text-align: right;
        }

        tbody td {
            padding: 8px 10px;
            border-bottom: 1px solid rgba(43, 44, 40, 0.08);
            font-size: 12px;
        }

        tbody td:last-child {
            text-align: right;
            font-weight: 600;
            color: #339989;
        }

        .total-row td {
            border-top: 2px solid rgba(43, 44, 40, 0.2);
            border-bottom: none;
            font-weight: 700;
            font-size: 13px;
            padding-top: 10px;
        }

        .total-row td:last-child {
            color: #339989;
        }

        .footer {
            margin-top: 24px;
            padding-top: 12px;
            border-top: 1px solid rgba(43, 44, 40, 0.12);
            font-size: 10px;
            color: #2B2C28;
            opacity: 0.7;
            text-align: center;
        }

        .empty-state {
            text-align: center;
            padding: 30px;
            color: #2B2C28;
            opacity: 0.7;
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
                <th style="width: 35px;">No</th>
                <th>Kategori & Invoice</th>
                <th>Pelanggan / Sumber</th>
                <th>Rincian Produk / Deskripsi</th>
                <th style="text-align: right;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    {{ $transaction->category->name ?? '-' }}
                    @if($transaction->invoice_number)
                        <br><span style="font-size: 10px; color: #339989; font-weight: bold;">#{{ $transaction->invoice_number }}</span>
                    @endif
                </td>
                <td>
                    {{ $transaction->customer_name ?: ($transaction->source ?? '-') }}
                    @if($transaction->customer_name && $transaction->source)
                        <br><span style="font-size: 10px; color: #2B2C28; opacity: 0.8;">({{ $transaction->source }})</span>
                    @endif
                </td>
                <td>
                    @if($transaction->is_sale && $transaction->items->isNotEmpty())
                        @foreach($transaction->items as $item)
                            <div style="font-size: 11px;">{{ $item->product_name }} <span style="color: #2B2C28; opacity: 0.8;">({{ $item->quantity }}x)</span></div>
                        @endforeach
                    @else
                        {{ $transaction->description ?? '-' }}
                    @endif
                </td>
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
