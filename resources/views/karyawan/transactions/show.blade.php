@extends('layouts.app')

@section('title', 'Detail Kas Masuk')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 col-xl-7">
        <!-- Page Header -->
        <div class="uk-page-header mb-4">
            <div>
                <h2 class="uk-page-title">Detail Kas Masuk</h2>
                <p class="uk-page-subtitle">Informasi lengkap rincian transaksi kas masuk non-produk.</p>
            </div>
            <a href="{{ route('karyawan.transactions.index') }}" class="btn btn-sm btn-uk-outline rounded-pill px-3">
                <i class="fas fa-arrow-left me-1.5"></i>Kembali ke Riwayat
            </a>
        </div>

        <div class="card uk-card border-0">
            <div class="card-body p-4">
                @if($transaction->is_sale)
                <div class="card uk-card border-0 mb-4" style="background: rgba(125, 226, 209, 0.15); border-left: 4px solid var(--uk-primary) !important;">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <span class="fw-bold text-dark"><i class="fas fa-receipt me-1 text-primary"></i>Struk Penjualan Kasir #{{ $transaction->invoice_number ?? $transaction->id }}</span>
                            <div class="small text-muted">Cetak nota thermal atau bagikan struk ke WhatsApp pelanggan</div>
                        </div>
                        <a href="{{ route('karyawan.sales.show', $transaction->id) }}" class="btn btn-sm btn-uk-primary rounded-pill px-3 fw-bold">
                            <i class="fas fa-receipt me-1.5"></i>Buka Struk
                        </a>
                    </div>
                </div>
                @endif

                <!-- Amount Voucher Pod -->
                <div class="text-center p-4 rounded-3 mb-4" style="background: var(--uk-surface); border: 1px solid var(--uk-border);">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 52px; height: 52px; background: rgba(51, 153, 137, 0.15); color: var(--uk-primary);">
                        <i class="fas fa-arrow-down fa-lg"></i>
                    </div>
                    <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.05em;">Nominal Kas Masuk</small>
                    <div class="fw-bold fs-2 text-primary mt-1">+ Rp {{ number_format($transaction->amount, 0, ',', '.') }}</div>
                    <span class="badge badge-uk-primary mt-1">Kas Masuk Terverifikasi</span>
                </div>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="p-3 rounded-3 border bg-light h-100">
                            <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Tanggal & Waktu</small>
                            <span class="fw-semibold text-dark fs-6 d-block mt-1">
                                <i class="fas fa-calendar-alt text-primary me-1.5"></i>{{ $transaction->transaction_date->format('d/m/Y') }}
                                <span class="text-muted small fw-normal">({{ $transaction->created_at->format('H:i') }} WIB)</span>
                            </span>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="p-3 rounded-3 border bg-light h-100">
                            <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Kategori</small>
                            <span class="badge badge-uk-accent text-dark fw-medium mt-1">
                                {{ $transaction->category->name ?? '-' }}
                            </span>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="p-3 rounded-3 border bg-light h-100">
                            <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Sumber / Pembayar</small>
                            <span class="fw-semibold text-dark fs-6 d-block mt-1">{{ $transaction->source ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="p-3 rounded-3 border bg-light h-100">
                            <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Metode Pembayaran</small>
                            <span class="fw-semibold text-dark fs-6 d-block mt-1">
                                <i class="fas fa-wallet text-primary me-1.5"></i>{{ ucfirst($transaction->payment_method ?? 'Tunai') }}
                            </span>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="p-3 rounded-3 border bg-light">
                            <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Keterangan Rinci</small>
                            <span class="text-dark d-block mt-1" style="line-height: 1.6;">{{ $transaction->description ?: 'Tidak ada catatan tambahan.' }}</span>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="p-3 rounded-3 border bg-light">
                            <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Petugas Kasir</small>
                            <span class="fw-semibold text-dark d-block mt-1">
                                <i class="fas fa-user-check text-primary me-1.5"></i>{{ $transaction->user->name ?? '-' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                    <a href="{{ route('karyawan.transactions.index') }}" class="btn btn-sm btn-uk-secondary rounded-pill px-4">
                        <i class="fas fa-arrow-left me-1.5"></i>Kembali ke Riwayat Kas Masuk
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
