@extends('layouts.app')

@section('title', 'Laporan Penjualan Harian')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--uk-dark);">Laporan Penjualan Harian</h4>
        <p class="text-muted small mb-0"><i class="fas fa-calendar-alt me-1 text-primary"></i>Ringkasan transaksi dan penerimaan kas kasir harian</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('karyawan.export.pdf', ['date' => $date]) }}" class="btn btn-sm btn-uk-outline text-danger" target="_blank">
            <i class="fas fa-file-pdf me-1"></i>Export PDF
        </a>
        <a href="{{ route('karyawan.export.excel', ['date' => $date]) }}" class="btn btn-sm btn-uk-outline text-success">
            <i class="fas fa-file-excel me-1"></i>Export Excel
        </a>
    </div>
</div>

<div class="card uk-card border-0 mb-4">
    <div class="card-body p-3 p-md-4">
        <form method="GET" action="{{ route('karyawan.reports.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="date" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">Pilih Tanggal Laporan</label>
                <input type="date" class="form-control" id="date" name="date" value="{{ $date }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-uk-primary w-100">
                    <i class="fas fa-search me-1"></i>Tampilkan
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card uk-stat-card border-0 h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="uk-stat-icon" style="background: rgba(149, 198, 35, 0.18); color: #95C623;">
                            <i class="fas fa-cash-register fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="uk-stat-label">Penjualan Kasir</div>
                        <div class="uk-stat-value text-success" style="font-size: 1.25rem;">Rp {{ number_format($posSalesTotal, 0, ',', '.') }}</div>
                        <small class="text-muted">{{ $posSalesCount }} nota &bull; {{ $totalItemsSold }} item</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card uk-stat-card border-0 h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="uk-stat-icon" style="background: rgba(14, 71, 73, 0.12); color: var(--uk-primary);">
                            <i class="fas fa-hand-holding-usd fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="uk-stat-label">Kas Masuk Manual</div>
                        <div class="uk-stat-value text-warning" style="font-size: 1.25rem;">Rp {{ number_format($manualCashTotal, 0, ',', '.') }}</div>
                        <small class="text-muted">{{ $manualCashCount }} catatan non-produk</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card uk-stat-card border-0 h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="uk-stat-icon" style="background: rgba(14, 71, 73, 0.12); color: var(--uk-primary);">
                            <i class="fas fa-money-bill-wave fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="uk-stat-label">Uang Tunai (Laci)</div>
                        <div class="uk-stat-value" style="font-size: 1.25rem; color: var(--uk-primary);">Rp {{ number_format($cashTotal, 0, ',', '.') }}</div>
                        <small class="text-muted">Pembayaran cash fisik</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card uk-stat-card border-0 h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="uk-stat-icon" style="background: rgba(0, 38, 38, 0.08); color: var(--uk-dark);">
                            <i class="fas fa-receipt fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="uk-stat-label">Total Seluruh Kas</div>
                        <div class="uk-stat-value" style="font-size: 1.25rem; color: var(--uk-dark);">Rp {{ number_format($totalAmount, 0, ',', '.') }}</div>
                        <small class="text-muted">{{ $totalCount }} transaksi hari ini</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card uk-card border-0">
    <div class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h6 class="mb-0 fw-bold" style="color: var(--uk-dark);">
            <i class="fas fa-list me-2 text-primary"></i>Daftar Transaksi - {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
        </h6>
        <span class="badge badge-uk-accent text-dark fw-medium">Total {{ $totalCount }} Catatan</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table uk-table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 50px;">No</th>
                        <th>Tipe & Item</th>
                        <th>Pelanggan / Keterangan</th>
                        <th class="text-center">Metode</th>
                        <th class="text-end">Jumlah</th>
                        <th class="text-center pe-4" style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                    <tr>
                        <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>
                        <td>
                            @if($transaction->is_sale)
                                <div class="mb-1">
                                    <span class="badge badge-uk-primary">
                                        <i class="fas fa-shopping-bag me-1"></i>Penjualan Produk
                                    </span>
                                    @if($transaction->invoice_number)
                                        <span class="badge badge-uk-accent text-dark ms-1">{{ $transaction->invoice_number }}</span>
                                    @endif
                                </div>
                                @if($transaction->items->isNotEmpty())
                                    <div class="small text-muted">
                                        {{ $transaction->items->pluck('product_name')->take(2)->join(', ') }}
                                        @if($transaction->items->count() > 2)
                                            <span class="badge bg-light text-secondary border ms-1">+{{ $transaction->items->count() - 2 }} item</span>
                                        @endif
                                    </div>
                                @endif
                            @else
                                <div class="mb-1">
                                    <span class="badge badge-uk-accent text-dark fw-medium">
                                        <i class="fas fa-hand-holding-usd me-1 text-primary"></i>Kas Masuk Manual
                                    </span>
                                </div>
                                <div class="small text-muted">
                                    Kategori: {{ $transaction->category->name ?? 'Kas Masuk' }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="small fw-semibold text-dark">
                                {{ $transaction->customer_name ?: ($transaction->source ?: '-') }}
                            </div>
                            @if($transaction->description && $transaction->description !== $transaction->source)
                                <div class="text-muted" style="font-size: 0.75rem;">{{ $transaction->description }}</div>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border small">
                                {{ ucfirst($transaction->payment_method ?? 'Tunai') }}
                            </span>
                        </td>
                        <td class="text-end fw-bold text-success">
                            + Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                        </td>
                        <td class="text-center pe-4">
                            @if($transaction->is_sale)
                                <a href="{{ route('karyawan.sales.show', $transaction) }}" class="btn btn-sm btn-uk-outline" title="Lihat Struk Nota">
                                    <i class="fas fa-receipt text-success me-1"></i>Nota
                                </a>
                            @else
                                <a href="{{ route('karyawan.transactions.show', $transaction) }}" class="btn btn-sm btn-uk-outline" title="Lihat Detail Kas">
                                    <i class="fas fa-eye text-primary me-1"></i>Detail
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="mb-3">
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background: rgba(14, 71, 73, 0.1); color: var(--uk-primary);">
                                    <i class="fas fa-inbox fa-lg"></i>
                                </div>
                            </div>
                            <h6 class="fw-bold mb-1" style="color: var(--uk-dark);">Tidak Ada Transaksi</h6>
                            <p class="text-muted small mb-0">Tidak ada transaksi tercatat pada tanggal yang dipilih</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
