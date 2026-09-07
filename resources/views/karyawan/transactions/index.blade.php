@extends('layouts.app')

@section('title', 'Riwayat Penjualan Saya')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
    <div>
        <h5 class="fw-bold mb-1">Riwayat Penjualan Saya</h5>
        <p class="text-muted mb-0 small">Kelola dan lihat semua penjualan yang telah dicatat</p>
    </div>
    <a href="{{ route('karyawan.transactions.create') }}" class="btn btn-success">
        <i class="fas fa-plus me-2"></i>Catat Penjualan Baru
    </a>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('karyawan.transactions.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label for="start_date" class="form-label small fw-semibold">Dari Tanggal</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-4">
                <label for="end_date" class="form-label small fw-semibold">Sampai Tanggal</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
                @if(request('start_date') || request('end_date'))
                    <a href="{{ route('karyawan.transactions.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Reset
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="width: 50px; font-size: 0.8125rem;">No</th>
                        <th style="font-size: 0.8125rem;">Tanggal</th>
                        <th style="font-size: 0.8125rem;">Kategori</th>
                        <th style="font-size: 0.8125rem;">Sumber / Deskripsi</th>
                        <th class="text-end" style="font-size: 0.8125rem;">Jumlah</th>
                        <th class="text-center pe-3" style="width: 80px; font-size: 0.8125rem;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                    <tr>
                        <td class="ps-3">{{ $transactions->firstItem() + $loop->index }}</td>
                        <td>{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge" style="background-color: #dcfce7; color: #16a34a;">{{ $transaction->category->name ?? '-' }}</span>
                        </td>
                        <td>{{ $transaction->source ?? '-' }}</td>
                        <td class="text-end fw-semibold" style="color: #16a34a;">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                        <td class="text-center pe-3">
                            <a href="{{ route('karyawan.transactions.show', $transaction->id) }}" class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="fas fa-inbox d-block mb-2" style="font-size: 2.5rem; color: #d1d5db;"></i>
                            <p class="text-muted mb-1">Belum ada data transaksi</p>
                            <a href="{{ route('karyawan.transactions.create') }}" class="text-decoration-none">Catat penjualan pertama Anda</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($transactions->hasPages())
    <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
        <small class="text-muted">
            Menampilkan {{ $transactions->firstItem() }} - {{ $transactions->lastItem() }} dari {{ $transactions->total() }} data
        </small>
        <div>
            {{ $transactions->withQueryString()->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
