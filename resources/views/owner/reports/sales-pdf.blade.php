<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan & Produk - {{ $business->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #131515;
            background-color: #FFFAFB;
            line-height: 1.4;
            padding: 24px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2.5px solid #339989;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            color: #131515;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .header .subtitle {
            font-size: 13px;
            color: #2B2C28;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .header .period {
            font-size: 11px;
            color: #131515;
            background-color: rgba(125, 226, 209, 0.2);
            display: inline-block;
            padding: 4px 14px;
            border-radius: 4px;
            border: 1px solid #7DE2D1;
            font-weight: 600;
        }

        .meta-table {
            width: 100%;
            margin-bottom: 18px;
            border-collapse: collapse;
        }

        .meta-table td {
            font-size: 10.5px;
            color: #2B2C28;
            border: none;
            padding: 2px 0;
        }

        .section-title {
            font-size: 12.5px;
            font-weight: bold;
            color: #131515;
            margin-bottom: 8px;
            padding-bottom: 4px;
            border-bottom: 1px solid rgba(43, 44, 40, 0.12);
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .kpi-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 6px;
            margin-bottom: 18px;
        }

        .kpi-box {
            background-color: #FFFAFB;
            border: 1px solid rgba(43, 44, 40, 0.12);
            border-radius: 5px;
            padding: 8px 10px;
            text-align: center;
        }

        .kpi-box .label {
            font-size: 9px;
            color: #2B2C28;
            opacity: 0.8;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 3px;
        }

        .kpi-box .value {
            font-size: 13px;
            font-weight: bold;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-bottom: 20px;
        }

        table.data-table thead {
            background-color: rgba(125, 226, 209, 0.15);
        }

        table.data-table th {
            padding: 6px 8px;
            text-align: left;
            font-weight: bold;
            color: #131515;
            border-top: 1px solid rgba(43, 44, 40, 0.15);
            border-bottom: 1.5px solid rgba(43, 44, 40, 0.2);
            font-size: 9.5px;
            text-transform: uppercase;
        }

        table.data-table td {
            padding: 6px 8px;
            border-bottom: 1px solid rgba(43, 44, 40, 0.08);
            vertical-align: middle;
        }

        table.data-table tr:nth-child(even) td {
            background-color: rgba(125, 226, 209, 0.04);
        }

        .text-end { text-align: right; }
        .text-center { text-align: center; }

        .text-success { color: #339989; }
        .text-danger { color: #2B2C28; }
        .text-primary { color: #339989; }
        .text-muted { color: #2B2C28; opacity: 0.75; }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
        }

        .badge-success {
            background-color: rgba(51, 153, 137, 0.15);
            color: #339989;
            border: 1px solid rgba(51, 153, 137, 0.3);
        }
        .badge-info {
            background-color: rgba(125, 226, 209, 0.25);
            color: #131515;
            border: 1px solid #7DE2D1;
        }
        .badge-secondary {
            background-color: rgba(43, 44, 40, 0.1);
            color: #2B2C28;
            border: 1px solid rgba(43, 44, 40, 0.2);
        }

        .footer {
            margin-top: 25px;
            padding-top: 10px;
            border-top: 1px solid rgba(43, 44, 40, 0.12);
            font-size: 9.5px;
            color: #2B2C28;
            opacity: 0.7;
            text-align: center;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $business->name }}</h1>
        <div class="subtitle">Laporan Penjualan Produk & Analisis Laba Kotor</div>
        <div class="period">Periode: {{ $periodLabel }}</div>
    </div>

    <table class="meta-table">
        <tr>
            <td style="width: 50%;"><strong>Alamat / Kontak:</strong> {{ $business->phone ?? '-' }} | {{ $business->address ?? '-' }}</td>
            <td style="width: 50%;" class="text-end"><strong>Dicetak Pada:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }} WIB</td>
        </tr>
        <tr>
            <td><strong>Penanggung Jawab:</strong> {{ $user->name }} (Owner)</td>
            <td class="text-end"><strong>Total Transaksi:</strong> {{ $totalTransactions }} Transaksi ({{ $totalItemsSold }} Produk)</td>
        </tr>
    </table>

    <!-- KPI Ringkasan Metrik -->
    <div class="section-title">Ringkasan Kinerja Penjualan</div>
    <table class="kpi-table">
        <tr>
            <td class="kpi-box" style="width: 25%;">
                <div class="label">Total Omset Penjualan</div>
                <div class="value text-success">{{ format_rupiah($totalSales) }}</div>
            </td>
            <td class="kpi-box" style="width: 25%;">
                <div class="label">Total Modal / HPP</div>
                <div class="value text-muted">{{ format_rupiah($totalHpp) }}</div>
            </td>
            <td class="kpi-box" style="width: 25%;">
                <div class="label">Total Laba Kotor</div>
                <div class="value text-success">{{ format_rupiah($grossProfit) }}</div>
            </td>
            <td class="kpi-box" style="width: 25%;">
                <div class="label">Margin Keuntungan</div>
                <div class="value text-primary">{{ $profitMargin }}%</div>
            </td>
        </tr>
    </table>

    @if($bestsellers->isNotEmpty())
    <!-- Produk Paling Laris (Top 5) -->
    <div class="section-title">Peringkat Produk Terlaris (Bestsellers)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 40px;">No</th>
                <th>Nama Produk</th>
                <th class="text-center" style="width: 100px;">Qty Terjual</th>
                <th class="text-end" style="width: 130px;">Total Omset</th>
                <th class="text-end" style="width: 130px;">Laba Kotor</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bestsellers->take(5) as $idx => $best)
            <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
                <td><strong>{{ $best->product_name }}</strong></td>
                <td class="text-center">{{ number_format($best->total_qty) }}</td>
                <td class="text-end">{{ format_rupiah($best->total_revenue) }}</td>
                <td class="text-end text-success fw-bold">{{ format_rupiah($best->total_revenue - $best->total_cost) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Rincian Transaksi Penjualan -->
    <div class="section-title">Rincian Transaksi Penjualan</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 85px;">No. Invoice</th>
                <th style="width: 75px;">Tanggal</th>
                <th style="width: 75px;">Kasir</th>
                <th style="width: 85px;">Pelanggan</th>
                <th>Rincian Produk (Qty)</th>
                <th class="text-end" style="width: 80px;">HPP</th>
                <th class="text-end" style="width: 80px;">Diskon</th>
                <th class="text-end" style="width: 90px;">Total (Netto)</th>
                <th class="text-end" style="width: 85px;">Laba Kotor</th>
                <th class="text-center" style="width: 60px;">Metode</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $sale)
            <tr>
                <td><strong>{{ $sale->invoice_number ?? ('PJ-' . $sale->id) }}</strong></td>
                <td>{{ $sale->transaction_date->format('d/m/Y') }}<br><span class="text-muted" style="font-size: 8.5px;">{{ $sale->transaction_date->format('H:i') }}</span></td>
                <td>{{ $sale->user?->name ?? '-' }}</td>
                <td>
                    {{ $sale->customer_name ?: 'Umum' }}
                    @if($sale->customer_phone)
                        <br><span class="text-muted" style="font-size: 8px;">{{ $sale->customer_phone }}</span>
                    @endif
                </td>
                <td>
                    @foreach($sale->items as $item)
                        <div>{{ $item->product_name }} <span class="text-muted">({{ $item->quantity }}x)</span></div>
                    @endforeach
                </td>
                <td class="text-end text-muted">{{ format_rupiah($sale->total_hpp) }}</td>
                <td class="text-end text-muted">{{ $sale->discount > 0 ? format_rupiah($sale->discount) : '-' }}</td>
                <td class="text-end"><strong>{{ format_rupiah($sale->amount) }}</strong></td>
                <td class="text-end text-success"><strong>{{ format_rupiah($sale->gross_profit) }}</strong></td>
                <td class="text-center">
                    <span class="badge {{ $sale->payment_method === 'Tunai' ? 'badge-success' : 'badge-info' }}">
                        {{ $sale->payment_method ?? 'Tunai' }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center" style="padding: 20px;">Tidak ada data penjualan pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
        @if($sales->isNotEmpty())
        <tfoot>
            <tr style="background-color: rgba(125, 226, 209, 0.15); font-weight: bold;">
                <td colspan="5" class="text-end">TOTAL KESELURUHAN:</td>
                <td class="text-end text-muted">{{ format_rupiah($totalHpp) }}</td>
                <td class="text-end text-muted">{{ format_rupiah($totalDiscount) }}</td>
                <td class="text-end text-success">{{ format_rupiah($totalSales) }}</td>
                <td class="text-end text-success">{{ format_rupiah($grossProfit) }}</td>
                <td></td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer">
        Dicetak secara otomatis oleh sistem digital <strong>Untung Klik</strong> &bull; Copyright &copy; {{ date('Y') }} Yanuar Ardhika Rahmadhani Ubaidillah
    </div>
</body>
</html>
