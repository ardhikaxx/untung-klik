@extends('layouts.app')

@section('title', 'Laporan Penjualan Harian')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
    <div>
        <h5 class="fw-bold mb-1">Laporan Penjualan Harian</h5>
        <p class="text-muted mb-0 small">Ringkasan penjualan berdasarkan tanggal</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('karyawan.export.pdf', ['date' => $date]) }}" class="btn btn-outline-danger btn-sm" target="_blank">
            <i class="fas fa-file-pdf me-1"></i>Export PDF
        </a>
        <a href="{{ route('karyawan.export.excel', ['date' => $date]) }}" class="btn btn-outline-success btn-sm">
            <i class="fas fa-file-excel me-1"></i>Export Excel
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('karyawan.reports.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label for="date" class="form-label small fw-semibold">Tanggal Laporan</label>
                <input type="date" class="form-control" id="date" name="date" value="{{ $date }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i>Tampilkan
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: #dcfce7;">
                            <i class="fas fa-cash-register" style="color: #16a34a; font-size: 1.25rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-1 small fw-semibold">Penjualan Kasir</p>
                        <h5 class="mb-0 fw-bold text-success">Rp {{ number_format($posSalesTotal, 0, ',', '.') }}</h5>
                        <small class="text-muted">{{ $posSalesCount }} transaksi &bull; {{ $totalItemsSold }} item</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: #fef3c7;">
                            <i class="fas fa-hand-holding-usd" style="color: #d97706; font-size: 1.25rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-1 small fw-semibold">Kas Masuk Manual</p>
                        <h5 class="mb-0 fw-bold text-warning">Rp {{ number_format($manualCashTotal, 0, ',', '.') }}</h5>
                        <small class="text-muted">{{ $manualCashCount }} catatan non-produk</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: #e0f2fe;">
                            <i class="fas fa-money-bill-wave" style="color: #0284c7; font-size: 1.25rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-1 small fw-semibold">Uang Tunai (Laci Kas)</p>
                        <h5 class="mb-0 fw-bold text-info">Rp {{ number_format($cashTotal, 0, ',', '.') }}</h5>
                        <small class="text-muted">Pembayaran cash</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: #dbeafe;">
                            <i class="fas fa-receipt" style="color: #2563eb; font-size: 1.25rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-1 small fw-semibold">Total Seluruh Kas</p>
                        <h5 class="mb-0 fw-bold text-primary">Rp {{ number_format($totalAmount, 0, ',', '.') }}</h5>
                        <small class="text-muted">{{ $totalCount }} transaksi total</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h6 class="mb-0 fw-semibold">Daftar Transaksi - {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</h6>
        <span class="badge bg-light text-secondary border">Total {{ $totalCount }} Catatan</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="font-size: 0.8125rem;">No</th>
                        <th style="font-size: 0.8125rem;">Tipe & Item</th>
                        <th style="font-size: 0.8125rem;">Pelanggan / Keterangan</th>
                        <th class="text-center" style="font-size: 0.8125rem;">Metode</th>
                        <th class="text-end" style="font-size: 0.8125rem;">Jumlah</th>
                        <th class="text-center" style="font-size: 0.8125rem;">Nota / Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                    <tr>
                        <td class="ps-3">{{ $loop->iteration }}</td>
                        <td>
                            @if($transaction->is_sale)
                                <div class="mb-1">
                                    <span class="badge bg-success">
                                        <i class="fas fa-shopping-bag me-1"></i>Penjualan Produk
                                    </span>
                                    @if($transaction->invoice_number)
                                        <span class="badge bg-light text-dark border ms-1">{{ $transaction->invoice_number }}</span>
                                    @endif
                                </div>
                                @if($transaction->items->isNotEmpty())
                                    <div class="small text-muted">
                                        {{ $transaction->items->pluck('product_name')->take(2)->join(', ') }}
                                        @if($transaction->items->count() > 2)
                                            <span class="badge bg-light text-secondary">+{{ $transaction->items->count() - 2 }} item</span>
                                        @endif
                                    </div>
                                @endif
                            @else
                                <div class="mb-1">
                                    <span class="badge" style="background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a;">
                                        <i class="fas fa-hand-holding-usd me-1"></i>Kas Masuk Manual
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
                            Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            @if($transaction->is_sale)
                                <a href="{{ route('karyawan.sales.show', $transaction) }}" class="btn btn-sm btn-outline-success" title="Lihat Struk Nota">
                                    <i class="fas fa-receipt me-1"></i>Nota
                                </a>
                            @else
                                <a href="{{ route('karyawan.transactions.show', $transaction) }}" class="btn btn-sm btn-outline-secondary" title="Lihat Detail Kas">
                                    <i class="fas fa-eye me-1"></i>Detail
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="fas fa-inbox d-block mb-2" style="font-size: 2.5rem; color: #d1d5db;"></i>
                            <p class="text-muted mb-0">Tidak ada transaksi pada tanggal ini</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
