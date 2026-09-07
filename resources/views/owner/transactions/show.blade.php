@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Detail Transaksi</h4>
        <p class="text-muted mb-0">Informasi lengkap transaksi</p>
    </div>
    <a href="{{ route('owner.transactions.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">
                    <i class="fas fa-file-invoice me-2"></i>Informasi Transaksi
                </h6>
                @if($transaction->type === 'masuk')
                    <span class="badge bg-success fs-6">Uang Masuk</span>
                @else
                    <span class="badge bg-danger fs-6">Uang Keluar</span>
                @endif
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3">
                            <small class="text-muted d-block mb-1">Tanggal Transaksi</small>
                            <span class="fw-semibold fs-5">
                                <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                {{ $transaction->transaction_date->format('d F Y') }}
                            </span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-3 p-3">
                            <small class="text-muted d-block mb-1">Jumlah</small>
                            <span class="fw-bold fs-3 {{ $transaction->type === 'masuk' ? 'text-success' : 'text-danger' }}">
                                {{ $transaction->type === 'masuk' ? '+' : '-' }} Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-3 p-3">
                            <small class="text-muted d-block mb-1">Kategori</small>
                            <span class="fw-semibold">
                                <i class="fas fa-tag me-2 text-secondary"></i>
                                {{ $transaction->category->name ?? 'Tidak ada kategori' }}
                            </span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-3 p-3">
                            <small class="text-muted d-block mb-1">Metode Pembayaran</small>
                            <span class="fw-semibold">
                                <i class="fas fa-credit-card me-2 text-secondary"></i>
                                {{ ucfirst($transaction->payment_method ?? '-') }}
                            </span>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="border rounded-3 p-3">
                            <small class="text-muted d-block mb-1">Sumber</small>
                            <span class="fw-semibold">
                                <i class="fas fa-store me-2 text-secondary"></i>
                                {{ $transaction->source ?: '-' }}
                            </span>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="border rounded-3 p-3">
                            <small class="text-muted d-block mb-1">Keterangan</small>
                            <span class="fw-semibold">
                                <i class="fas fa-align-left me-2 text-secondary"></i>
                                {{ $transaction->description ?: '-' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border shadow-sm mb-3">
            <div class="card-header bg-white">
                <h6 class="fw-bold mb-0">
                    <i class="fas fa-user me-2"></i>Pencatat
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center me-3"
                         style="width: 48px; height: 48px;">
                        <span class="text-success fw-bold">
                            {{ strtoupper(substr($transaction->user->name, 0, 1)) }}
                        </span>
                    </div>
                    <div>
                        <div class="fw-semibold">{{ $transaction->user->name }}</div>
                        <small class="text-muted">{{ $transaction->user->role }}</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border shadow-sm mb-3">
            <div class="card-header bg-white">
                <h6 class="fw-bold mb-0">
                    <i class="fas fa-clock me-2"></i>Riwayat
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted d-block">Dibuat pada</small>
                    <span class="fw-semibold">{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div>
                    <small class="text-muted d-block">Terakhir diperbarui</small>
                    <span class="fw-semibold">{{ $transaction->updated_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2">
            <a href="{{ route('owner.transactions.edit', $transaction) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i>Edit Transaksi
            </a>
            <form action="{{ route('owner.transactions.destroy', $transaction) }}" method="POST" class="d-grid delete-form">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-danger btn-delete">
                    <i class="fas fa-trash me-1"></i>Hapus Transaksi
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.querySelector('.btn-delete').addEventListener('click', function() {
        var form = this.closest('form');
        Swal.fire({
            title: 'Hapus Transaksi?',
            text: 'Transaksi yang dihapus tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>
@endpush
@endsection
