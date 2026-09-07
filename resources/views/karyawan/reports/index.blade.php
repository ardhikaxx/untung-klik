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
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: #dcfce7;">
                            <i class="fas fa-coins" style="color: #16a34a; font-size: 1.25rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-1 small">Total Penjualan</p>
                        <h4 class="mb-0 fw-bold">Rp {{ number_format($totalAmount, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: #dbeafe;">
                            <i class="fas fa-receipt" style="color: #2563eb; font-size: 1.25rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-1 small">Jumlah Transaksi</p>
                        <h4 class="mb-0 fw-bold">{{ $totalCount }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom">
        <h6 class="mb-0 fw-semibold">Daftar Transaksi - {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="font-size: 0.8125rem;">No</th>
                        <th style="font-size: 0.8125rem;">Tipe & Item</th>
                        <th style="font-size: 0.8125rem;">Pelanggan / Keterangan</th>
                        <th class="text-end" style="font-size: 0.8125rem;">Jumlah</th>
                        <th class="text-center" style="font-size: 0.8125rem;">Nota</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                    <tr>
                        <td class="ps-3">{{ $loop->iteration }}</td>
                        <td>
                            @if($transaction->is_sale)
                                <span class="badge bg-success mb-1">
                                    <i class="fas fa-shopping-bag me-1"></i>Penjualan Produk
                                </span>
                                @if($transaction->items->isNotEmpty())
                                    <div class="small text-muted">
                                        {{ $transaction->items->pluck('product_name')->take(2)->join(', ') }}
                                        @if($transaction->items->count() > 2)
                                            <span class="badge bg-light text-secondary">+{{ $transaction->items->count() - 2 }} item</span>
                                        @endif
                                    </div>
                                @endif
                            @else
                                <span class="badge" style="background-color: #dcfce7; color: #16a34a;">
                                    {{ $transaction->category->name ?? 'Kas Masuk' }}
                                </span>
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
                        <td class="text-end fw-bold text-success">
                            Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            @if($transaction->is_sale)
                                <a href="{{ route('karyawan.sales.show', $transaction) }}" class="btn btn-sm btn-outline-primary" title="Lihat Nota">
                                    <i class="fas fa-receipt"></i>
                                </a>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
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
