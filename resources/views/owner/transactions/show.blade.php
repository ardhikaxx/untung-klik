@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--uk-dark);">Detail Transaksi Kas</h4>
        <p class="text-muted small mb-0">Rincian lengkap arsip transaksi keuangan buku kas</p>
    </div>
    <a href="{{ route('owner.transactions.index') }}" class="btn btn-uk-outline">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

@if($transaction->is_sale)
<div class="card uk-card border-0 mb-4" style="background: rgba(14, 71, 73, 0.08); border-left: 4px solid var(--uk-primary) !important;">
    <div class="card-body p-3 p-md-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h6 class="fw-bold mb-1" style="color: var(--uk-dark);">
                <i class="fas fa-receipt me-2 text-primary"></i>Transaksi Penjualan Kasir ({{ $transaction->invoice_number ?? ('PJ-'.$transaction->id) }})
            </h6>
            <div class="small text-muted">Transaksi ini terhubung langsung dengan modul kasir penjualan, nota belanja, pemotongan stok otomatis, dan diskon.</div>
        </div>
        <a href="{{ route('owner.sales.show', $transaction) }}" class="btn btn-uk-primary btn-sm">
            <i class="fas fa-external-link-alt me-1"></i>Buka Struk Lengkap
        </a>
    </div>
</div>
@endif

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card uk-card border-0">
            <div class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0" style="color: var(--uk-dark);">
                    <i class="fas fa-file-invoice-dollar me-2 text-primary"></i>Voucher Bukti Transaksi
                </h6>
                @if($transaction->type === 'masuk')
                    <span class="badge badge-uk-success fs-6"><i class="fas fa-arrow-down me-1"></i>Uang Masuk</span>
                @else
                    <span class="badge badge-uk-danger fs-6"><i class="fas fa-arrow-up me-1"></i>Uang Keluar</span>
                @endif
            </div>
            <div class="card-body p-4">
                <!-- Amount Banner -->
                <div class="p-4 rounded-3 text-center mb-4" style="background: var(--uk-bg-light); border: 1px solid var(--uk-border);">
                    <small class="text-muted text-uppercase fw-semibold letter-spacing" style="font-size: 0.75rem;">Nominal Transaksi</small>
                    <div class="fw-bold fs-2 mt-1 {{ $transaction->type === 'masuk' ? 'text-success' : 'text-danger' }}">
                        {{ $transaction->type === 'masuk' ? '+' : '-' }} Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="p-3 rounded-3 border bg-white h-100">
                            <small class="text-muted d-block text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Tanggal Transaksi</small>
                            <span class="fw-semibold text-dark fs-6 d-block mt-1">
                                <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                {{ $transaction->transaction_date->format('d F Y') }}
                            </span>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="p-3 rounded-3 border bg-white h-100">
                            <small class="text-muted d-block text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Metode Pembayaran</small>
                            <span class="fw-semibold text-dark fs-6 d-block mt-1">
                                <i class="fas fa-wallet me-2 text-primary"></i>
                                {{ ucfirst($transaction->payment_method ?? 'Tunai') }}
                            </span>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="p-3 rounded-3 border bg-white h-100">
                            <small class="text-muted d-block text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Kategori</small>
                            <span class="fw-semibold text-dark fs-6 d-block mt-1">
                                <i class="fas fa-tag me-2 text-primary"></i>
                                {{ $transaction->category->name ?? 'Tanpa Kategori' }}
                            </span>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="p-3 rounded-3 border bg-white h-100">
                            <small class="text-muted d-block text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Sumber / Pihak Terkait</small>
                            <span class="fw-semibold text-dark fs-6 d-block mt-1">
                                <i class="fas fa-building me-2 text-primary"></i>
                                {{ $transaction->source ?: '-' }}
                            </span>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="p-3 rounded-3 border bg-white">
                            <small class="text-muted d-block text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Keterangan Rinci</small>
                            <p class="text-dark mb-0 mt-1" style="line-height: 1.6;">
                                {{ $transaction->description ?: 'Tidak ada keterangan tambahan.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Pencatat & Audit Info -->
        <div class="card uk-card border-0 mb-4">
            <div class="card-header bg-transparent py-3 border-bottom">
                <h6 class="fw-bold mb-0" style="color: var(--uk-dark);">
                    <i class="fas fa-user-check me-2 text-primary"></i>Pencatat & Audit
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white fs-5" style="width: 44px; height: 44px; background-color: var(--uk-dark);">
                        {{ strtoupper(substr($transaction->user->name ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <div class="fw-bold text-dark">{{ $transaction->user->name ?? '-' }}</div>
                        <span class="badge badge-uk-accent text-dark fw-medium">{{ ucfirst($transaction->user->role ?? 'User') }}</span>
                    </div>
                </div>

                <hr class="my-3 text-muted opacity-25">

                <div class="mb-2">
                    <small class="text-muted d-block text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Waktu Pencatatan</small>
                    <span class="small fw-semibold text-dark">{{ $transaction->created_at->format('d/m/Y H:i') }} WIB</span>
                </div>

                <div>
                    <small class="text-muted d-block text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Terakhir Dimodifikasi</small>
                    <span class="small fw-semibold text-dark">{{ $transaction->updated_at->format('d/m/Y H:i') }} WIB</span>
                </div>
            </div>
        </div>

        <!-- Action Card -->
        <div class="card uk-card border-0">
            <div class="card-body p-4 d-flex flex-column gap-2">
                @if(!$transaction->is_sale)
                    <a href="{{ route('owner.transactions.edit', $transaction) }}" class="btn btn-uk-primary w-100">
                        <i class="fas fa-edit me-1"></i>Edit Data Transaksi
                    </a>
                @endif
                <form action="{{ route('owner.transactions.destroy', $transaction) }}" method="POST" class="delete-form w-100">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-uk-outline text-danger w-100 btn-delete">
                        <i class="fas fa-trash-alt me-1"></i>Hapus Transaksi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.querySelector('.btn-delete')?.addEventListener('click', function() {
        var form = this.closest('form');
        Swal.fire({
            title: 'Hapus Transaksi?',
            text: 'Transaksi yang dihapus akan dikeluarkan dari buku kas dan tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#E55812',
            cancelButtonColor: '#002626',
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
