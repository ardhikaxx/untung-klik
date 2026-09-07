@extends('layouts.app')

@section('title', 'Nota Penjualan #TRX-' . str_pad($sale->id, 5, '0', STR_PAD_LEFT))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1">Nota Penjualan</h4>
        <p class="text-muted mb-0">Rincian struk belanja transaksi yang telah dicatat</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('karyawan.sales.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
        <button type="button" class="btn btn-outline-primary" onclick="window.print()">
            <i class="fas fa-print me-1"></i>Cetak Struk
        </button>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border shadow-sm print-area">
            <div class="card-body p-4 p-md-5">
                <div class="text-center border-bottom pb-4 mb-4">
                    <div class="d-flex justify-content-center align-items-center gap-2 mb-2">
                        <i class="fas fa-wallet text-success fs-3"></i>
                        <h4 class="fw-bold text-dark mb-0">{{ auth()->user()->business ? auth()->user()->business->name : 'Untung Klik' }}</h4>
                    </div>
                    @if(auth()->user()->business && auth()->user()->business->address)
                        <p class="text-muted small mb-1">{{ auth()->user()->business->address }}</p>
                    @endif
                    @if(auth()->user()->business && auth()->user()->business->phone)
                        <p class="text-muted small mb-0"><i class="fas fa-phone-alt me-1"></i>{{ auth()->user()->business->phone }}</p>
                    @endif
                </div>

                <div class="row mb-4 small">
                    <div class="col-sm-6 mb-2">
                        <div class="text-muted">No. Nota Transaksi:</div>
                        <div class="fw-bold text-dark fs-6">#TRX-{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</div>
                    </div>
                    <div class="col-sm-6 mb-2 text-sm-end">
                        <div class="text-muted">Tanggal:</div>
                        <div class="fw-bold text-dark">{{ $sale->transaction_date->format('d F Y') }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted">Kasir / Petugas:</div>
                        <div class="fw-semibold text-dark">{{ $sale->user ? $sale->user->name : '-' }}</div>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <div class="text-muted">Pembayaran:</div>
                        <div class="fw-semibold text-success">{{ $sale->payment_method ?: 'Tunai' }}</div>
                    </div>
                </div>

                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light text-center small fw-bold">
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th class="text-start">Nama Produk</th>
                                <th style="width: 130px;">Harga Satuan</th>
                                <th style="width: 100px;">Jumlah</th>
                                <th style="width: 150px;" class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sale->items as $idx => $item)
                                <tr>
                                    <td class="text-center text-muted small">{{ $idx + 1 }}</td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $item->product_name }}</div>
                                    </td>
                                    <td class="text-end small">{{ format_rupiah($item->unit_price) }}</td>
                                    <td class="text-center fw-bold">{{ $item->quantity }}</td>
                                    <td class="text-end fw-bold text-dark">{{ format_rupiah($item->subtotal) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3 text-muted">
                                        {{ $sale->description }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <td colspan="4" class="text-end fw-bold fs-6">TOTAL AKHIR:</td>
                                <td class="text-end fw-bold fs-5 text-success">{{ format_rupiah($sale->amount) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($sale->description)
                    <div class="p-3 bg-light rounded-3 mb-4 small text-muted">
                        <strong>Catatan:</strong> {{ $sale->description }}
                    </div>
                @endif

                <div class="text-center border-top pt-4 text-muted small">
                    <p class="mb-1 fw-semibold">Terima Kasih!</p>
                    <p class="mb-0 text-secondary" style="font-size: 0.75rem;">Sistem Pembukuan Digital: Untung Klik</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .print-area, .print-area * {
            visibility: visible;
        }
        .print-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>
@endpush
@endsection
