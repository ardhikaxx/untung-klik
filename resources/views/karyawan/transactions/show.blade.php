@extends('layouts.app')

@section('title', 'Detail Kas Masuk')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7 col-xl-6">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('karyawan.transactions.index') }}" class="btn btn-outline-secondary btn-sm me-3">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h5 class="fw-bold mb-1">Detail Kas Masuk</h5>
                <p class="text-muted mb-0 small">Informasi lengkap catatan penerimaan kas masuk manual</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                @if($transaction->is_sale)
                <div class="alert alert-success border-0 shadow-sm mb-4 text-start">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <span class="fw-bold text-success"><i class="fas fa-receipt me-1"></i>Struk Invoice #{{ $transaction->invoice_number ?? $transaction->id }}</span>
                            <div class="small text-muted">Cetak nota thermal atau kirim bukti bayar ke WhatsApp pelanggan</div>
                        </div>
                        <a href="{{ route('karyawan.sales.show', $transaction->id) }}" class="btn btn-sm btn-success">
                            <i class="fas fa-print me-1"></i>Buka Struk
                        </a>
                    </div>
                </div>
                @endif

                <div class="text-center mb-4">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; background-color: #dcfce7;">
                        <i class="fas fa-arrow-down" style="color: #16a34a; font-size: 1.5rem;"></i>
                    </div>
                    <h3 class="fw-bold mb-1" style="color: #16a34a;">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</h3>
                    <span class="badge" style="background-color: #dcfce7; color: #16a34a;">Kas Masuk</span>
                </div>

                <hr class="my-4">

                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small mb-1">Tanggal</div>
                    <div class="col-sm-8 fw-semibold">{{ $transaction->transaction_date->format('d/m/Y') }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small mb-1">Kategori</div>
                    <div class="col-sm-8">
                        <span class="badge" style="background-color: #dcfce7; color: #16a34a;">{{ $transaction->category->name ?? '-' }}</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small mb-1">Sumber</div>
                    <div class="col-sm-8 fw-semibold">{{ $transaction->source ?? '-' }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small mb-1">Deskripsi</div>
                    <div class="col-sm-8">{{ $transaction->description ?? '-' }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small mb-1">Metode Pembayaran</div>
                    <div class="col-sm-8 fw-semibold">{{ ucfirst($transaction->payment_method ?? '-') }}</div>
                </div>

                <div class="row">
                    <div class="col-sm-4 text-muted small mb-1">Dicatat Oleh</div>
                    <div class="col-sm-8 fw-semibold">{{ $transaction->user->name ?? '-' }}</div>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('karyawan.transactions.index') }}" class="btn btn-outline-secondary w-100">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Riwayat Kas Masuk
            </a>
        </div>
    </div>
</div>
@endsection
