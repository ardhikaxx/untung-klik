@extends('layouts.app')

@section('title', 'Nota Penjualan ' . $sale->formatted_invoice_number)

@section('content')
<!-- Page Header & Action Buttons -->
<div class="uk-page-header d-print-none">
    <div>
        <h2 class="uk-page-title">Nota Penjualan {{ $sale->formatted_invoice_number }}</h2>
        <p class="uk-page-subtitle">Rincian bukti transaksi penjualan kasir, rincian belanja, dan status pembayaran.</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route('owner.sales.index') }}" class="btn btn-sm btn-uk-outline rounded-pill px-3">
            <i class="fas fa-arrow-left me-1.5"></i>Riwayat
        </a>
        <a href="{{ route('owner.receipt.index') }}" class="btn btn-sm btn-uk-outline rounded-pill px-3">
            <i class="fas fa-sliders-h me-1.5"></i>Atur Nota
        </a>
        <a href="{{ route('owner.sales.create') }}" class="btn btn-sm btn-uk-secondary rounded-pill px-3">
            <i class="fas fa-plus me-1.5"></i>Transaksi Baru
        </a>
        <button type="button" class="btn btn-sm btn-uk-primary rounded-pill px-3.5 shadow-xs" onclick="shareToWhatsApp()">
            <i class="fab fa-whatsapp me-1.5"></i>Kirim ke WA
        </button>
        <button type="button" class="btn btn-sm btn-uk-secondary rounded-pill px-3" onclick="window.print()">
            <i class="fas fa-print me-1.5"></i>Cetak Struk
        </button>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7 col-md-9">
        <div class="uk-card print-area p-4 p-sm-5">
            <!-- Header Toko / Struk -->
            <div class="text-center border-bottom pb-4 mb-4">
                <div class="d-flex justify-content-center align-items-center gap-2 mb-2">
                    <div style="width: 40px; height: 40px; border-radius: 10px; background-color: var(--uk-primary); color: #FFFAFB; display: flex; align-items: center; justify-content: center; font-size: 1.15rem;">
                        <i class="fas fa-store"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-0" style="letter-spacing: -0.02em;">
                        {{ $sale->business ? $sale->business->name : (auth()->user()->business ? auth()->user()->business->name : 'Untung Klik') }}
                    </h4>
                </div>
                @php
                    $bizType = $sale->business ? $sale->business->type : (auth()->user()->business ? auth()->user()->business->type : null);
                    $bizAddr = $sale->business ? $sale->business->address : (auth()->user()->business ? auth()->user()->business->address : null);
                    $bizPhone = $sale->business ? $sale->business->phone : (auth()->user()->business ? auth()->user()->business->phone : null);
                @endphp
                @if($bizType)
                    <p class="text-muted small mb-1" style="font-size: 0.78rem;">{{ $bizType }}</p>
                @endif
                @if($bizAddr)
                    <p class="text-muted small mb-1">{{ $bizAddr }}</p>
                @endif
                @if($bizPhone)
                    <p class="text-muted small mb-0"><i class="fas fa-phone-alt me-1 text-primary"></i>{{ $bizPhone }}</p>
                @endif
            </div>

            <!-- Informasi Nota -->
            <div class="row g-2 mb-4 small">
                <div class="col-sm-6">
                    <div class="text-muted" style="font-size: 0.75rem;">No. Faktur:</div>
                    <div class="fw-bold text-dark fs-6">{{ $sale->formatted_invoice_number }}</div>
                </div>
                <div class="col-sm-6 text-sm-end">
                    <div class="text-muted" style="font-size: 0.75rem;">Tanggal & Jam:</div>
                    <div class="fw-semibold text-dark">
                        {{ $sale->transaction_date->format('d F Y') }} {{ $sale->created_at ? $sale->created_at->format('H:i') : '' }}
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="text-muted" style="font-size: 0.75rem;">Pelanggan:</div>
                    <div class="fw-semibold text-dark">
                        {{ $sale->customer_name ?: 'Pelanggan Umum' }}
                        @if($sale->customer_phone)
                            <span class="text-muted small">({{ $sale->customer_phone }})</span>
                        @endif
                    </div>
                </div>
                <div class="col-sm-6 text-sm-end">
                    <div class="text-muted" style="font-size: 0.75rem;">Kasir / Petugas:</div>
                    <div class="fw-semibold text-dark">{{ $sale->user ? $sale->user->name : '-' }}</div>
                </div>
            </div>

            <!-- Tabel Item -->
            <div class="table-responsive mb-4">
                <table class="table align-middle mb-0">
                    <thead class="table-light small">
                        <tr>
                            <th class="ps-2">Item Produk</th>
                            <th class="text-center" style="width: 80px;">Qty</th>
                            <th class="text-end" style="width: 120px;">Harga</th>
                            <th class="text-end pe-2" style="width: 130px;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sale->items as $item)
                            <tr>
                                <td class="ps-2">
                                    <div class="fw-semibold text-dark" style="font-size: 0.85rem;">{{ $item->product_name }}</div>
                                    @if($item->product && $item->product->sku)
                                        <div class="text-muted" style="font-size: 0.72rem;">SKU: {{ $item->product->sku }}</div>
                                    @endif
                                </td>
                                <td class="text-center small">{{ $item->quantity }}</td>
                                <td class="text-end small">{{ format_rupiah($item->unit_price) }}</td>
                                <td class="text-end pe-2 fw-semibold text-dark">{{ format_rupiah($item->subtotal) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-3 text-muted">
                                    {{ $sale->description ?: 'Penjualan Produk' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="border-top">
                        @if($sale->discount > 0)
                        <tr>
                            <td colspan="3" class="text-end text-muted small pt-3">Subtotal:</td>
                            <td class="text-end pe-2 fw-semibold text-dark pt-3">{{ format_rupiah($sale->subtotal) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end text-danger small">Potongan / Diskon:</td>
                            <td class="text-end pe-2 fw-semibold text-danger">- {{ format_rupiah($sale->discount) }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td colspan="3" class="text-end fw-bold fs-6 pt-2 text-dark">TOTAL AKHIR:</td>
                            <td class="text-end pe-2 fw-bold fs-5 text-success pt-2">{{ format_rupiah($sale->amount) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end text-muted small">Metode Pembayaran:</td>
                            <td class="text-end pe-2 fw-semibold text-dark">{{ $sale->payment_method ?: 'Tunai' }}</td>
                        </tr>
                        @if($sale->cash_received !== null)
                        <tr>
                            <td colspan="3" class="text-end text-muted small">Uang Diterima:</td>
                            <td class="text-end pe-2 fw-semibold text-dark">{{ format_rupiah($sale->cash_received) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end text-muted small">Kembalian:</td>
                            <td class="text-end pe-2 fw-bold text-success">{{ format_rupiah($sale->cash_change ?: 0) }}</td>
                        </tr>
                        @endif
                    </tfoot>
                </table>
            </div>

            @if($sale->description)
                <div class="p-3 bg-light rounded-3 mb-4 small text-muted border">
                    <strong>Catatan:</strong> {{ $sale->description }}
                </div>
            @endif

            <!-- Pesan Penutup Struk -->
            <div class="text-center border-top pt-4 text-muted small">
                @php
                    $bizFooter = ($sale->business && $sale->business->receipt_footer) ? $sale->business->receipt_footer : (auth()->user()->business && auth()->user()->business->receipt_footer ? auth()->user()->business->receipt_footer : 'Terima Kasih Atas Kunjungan Anda!');
                    $bizNote = ($sale->business && $sale->business->receipt_note) ? $sale->business->receipt_note : (auth()->user()->business && auth()->user()->business->receipt_note ? auth()->user()->business->receipt_note : 'Barang yang sudah dibeli tidak dapat ditukar/dikembalikan tanpa bukti nota ini.');
                @endphp
                <p class="mb-1 fw-bold text-dark">{{ $bizFooter }}</p>
                @if($bizNote)
                    <p class="mb-0 text-muted" style="font-size: 0.75rem;">{{ $bizNote }}</p>
                @endif
                <p class="mb-0 mt-2 text-secondary" style="font-size: 0.7rem;">Untung Klik - Sistem Buku Kas Digital & Kasir UMKM</p>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        @page {
            margin: 4mm;
            size: auto;
        }
        body {
            background: #FFFAFB !important;
            color: #131515 !important;
        }
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
            width: 100% !important;
            max-width: 480px;
            margin: 0 auto;
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
        }
        .d-print-none {
            display: none !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function shareToWhatsApp() {
        const storeName = "{{ $sale->business ? $sale->business->name : (auth()->user()->business ? auth()->user()->business->name : 'Untung Klik') }}";
        const invoiceNo = "{{ $sale->formatted_invoice_number }}";
        const dateStr = "{{ $sale->transaction_date->format('d/m/Y') }}";
        const customer = "{{ $sale->customer_name ?: 'Pelanggan Umum' }}";
        const phone = "{{ $sale->customer_phone }}";
        const total = "{{ format_rupiah($sale->amount) }}";
        const payment = "{{ $sale->payment_method ?: 'Tunai' }}";

        let itemsText = "";
        @foreach($sale->items as $item)
            itemsText += "- {{ $item->product_name }} ({{ $item->quantity }}x) = {{ format_rupiah($item->subtotal) }}\n";
        @endforeach

        let message = `*NOTA PEMBAYARAN - ${storeName}*\n`;
        message += `No. Faktur: ${invoiceNo}\n`;
        message += `Tanggal: ${dateStr}\n`;
        message += `Pelanggan: ${customer}\n`;
        message += `--------------------------------\n`;
        message += `*Rincian Belanja:*\n${itemsText}`;
        message += `--------------------------------\n`;
        @if($sale->discount > 0)
            message += `Diskon: {{ format_rupiah($sale->discount) }}\n`;
        @endif
        message += `*TOTAL BAYAR: ${total}*\n`;
        message += `Metode: ${payment}\n`;
        @if($sale->cash_received !== null)
            message += `Uang Diterima: {{ format_rupiah($sale->cash_received) }}\n`;
            message += `Kembalian: {{ format_rupiah($sale->cash_change ?: 0) }}\n`;
        @endif
        message += `--------------------------------\n`;
        message += `Terima kasih telah berbelanja di *${storeName}*! 🙏`;

        let targetPhone = phone.replace(/[^0-9]/g, '');
        if (targetPhone.startsWith('0')) {
            targetPhone = '62' + targetPhone.substring(1);
        }

        const url = targetPhone
            ? `https://api.whatsapp.com/send?phone=${targetPhone}&text=${encodeURIComponent(message)}`
            : `https://api.whatsapp.com/send?text=${encodeURIComponent(message)}`;

        window.open(url, '_blank');
    }
</script>
@endpush
@endsection
