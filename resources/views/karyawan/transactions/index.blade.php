@extends('layouts.app')

@section('title', 'Riwayat Kas Masuk Manual')

@section('content')
<!-- Page Header -->
<div class="uk-page-header">
    <div>
        <h2 class="uk-page-title">Riwayat Kas Masuk Manual</h2>
        <p class="uk-page-subtitle">Kelola dan pantau seluruh catatan penerimaan kas masuk non-produk Anda.</p>
    </div>
    <a href="{{ route('karyawan.transactions.create') }}" class="btn btn-sm btn-uk-primary rounded-pill px-3.5 shadow-xs fw-bold">
        <i class="fas fa-plus me-1.5"></i>Catat Kas Masuk Baru
    </a>
</div>

<!-- Filter Form -->
<div class="card uk-card border-0 mb-4">
    <div class="card-body p-3 p-md-4">
        <form method="GET" action="{{ route('karyawan.transactions.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="start_date" class="form-label fw-semibold small text-dark mb-1">Dari Tanggal</label>
                <input type="date" class="form-control form-control-sm" id="start_date" name="start_date" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-4">
                <label for="end_date" class="form-label fw-semibold small text-dark mb-1">Sampai Tanggal</label>
                <input type="date" class="form-control form-control-sm" id="end_date" name="end_date" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-uk-primary rounded-pill px-3 flex-grow-1">
                    <i class="fas fa-filter me-1.5"></i>Filter
                </button>
                @if(request('start_date') || request('end_date'))
                    <a href="{{ route('karyawan.transactions.index') }}" class="btn btn-sm btn-uk-secondary rounded-pill px-3" title="Reset Filter">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Table Card -->
<div class="card uk-card border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table uk-table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 60px;">No</th>
                        <th>Tanggal & Waktu</th>
                        <th>Kategori</th>
                        <th>Sumber / Keterangan</th>
                        <th class="text-end">Jumlah</th>
                        <th class="text-center pe-4" style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                    <tr>
                        <td class="ps-4 text-muted small">{{ $transactions->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $transaction->transaction_date->format('d/m/Y') }}</div>
                            <small class="text-muted" style="font-size: 0.72rem;">{{ $transaction->created_at->format('H:i') }} WIB</small>
                        </td>
                        <td>
                            <span class="badge badge-uk-accent text-dark fw-medium">
                                {{ $transaction->category->name ?? '-' }}
                            </span>
                        </td>
                        <td>
                            @if($transaction->is_sale)
                                <div class="mb-1">
                                    <span class="badge badge-uk-primary">
                                        <i class="fas fa-receipt me-1"></i>{{ $transaction->invoice_number ?? 'Penjualan' }}
                                    </span>
                                </div>
                            @endif
                            <div class="fw-medium text-dark">{{ $transaction->source ?? '-' }}</div>
                            @if($transaction->description)
                                <small class="text-muted d-block text-truncate" style="max-width: 280px; font-size: 0.75rem;">{{ $transaction->description }}</small>
                            @endif
                        </td>
                        <td class="text-end fw-bold text-success" style="font-size: 0.95rem;">
                            + Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                        </td>
                        <td class="text-center pe-4">
                            @if($transaction->is_sale)
                                <a href="{{ route('karyawan.sales.show', $transaction->id) }}" class="btn btn-xs btn-uk-primary rounded-pill px-2.5" title="Lihat Struk Penjualan">
                                    <i class="fas fa-receipt me-1"></i>Struk
                                </a>
                            @else
                                <a href="{{ route('karyawan.transactions.show', $transaction->id) }}" class="btn btn-xs btn-uk-outline rounded-pill px-2.5" title="Lihat Detail Transaksi">
                                    <i class="fas fa-eye me-1"></i>Detail
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="uk-empty-state py-4">
                                <div class="uk-empty-icon">
                                    <i class="fas fa-inbox"></i>
                                </div>
                                <div class="uk-empty-title">Belum Ada Catatan Kas Masuk</div>
                                <p class="uk-empty-desc mb-3">Catat kas masuk non-produk Anda seperti jasa, servis, atau pendapatan lain.</p>
                                <a href="{{ route('karyawan.transactions.create') }}" class="btn btn-sm btn-uk-primary rounded-pill px-3.5 fw-bold">
                                    <i class="fas fa-plus me-1.5"></i>Catat Kas Masuk
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($transactions->hasPages())
    <div class="card-footer bg-transparent border-top d-flex justify-content-between align-items-center flex-wrap gap-2 px-4 py-3">
        <small class="text-muted">
            Menampilkan <span class="fw-semibold text-dark">{{ $transactions->firstItem() }}</span> - <span class="fw-semibold text-dark">{{ $transactions->lastItem() }}</span> dari <span class="fw-semibold text-dark">{{ $transactions->total() }}</span> data
        </small>
        <div>
            {{ $transactions->withQueryString()->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
