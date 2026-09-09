@extends('layouts.app')

@section('title', 'Nota Penjualan ' . $sale->formatted_invoice_number)

@section('content')
<!-- Page Header & Action Buttons -->
<div class="uk-page-header d-print-none">
    <div>
        <h2 class="uk-page-title">Nota Penjualan {{ $sale->formatted_invoice_number }}</h2>
        <p class="uk-page-subtitle">Rincian bukti transaksi kasir dan pembayaran pelanggan toko.</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route('karyawan.sales.index') }}" class="btn btn-sm btn-uk-outline rounded-pill px-3">
            <i class="fas fa-arrow-left me-1.5"></i>Riwayat
        </a>
        <a href="{{ route('karyawan.sales.create') }}" class="btn btn-sm btn-uk-secondary rounded-pill px-3">
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

<div class="thermal-receipt-stage">
    <div class="thermal-receipt-paper print-area">
        <!-- Header Toko / Struk Thermal -->
        <div class="thermal-header">
            <div class="thermal-store-name">
                {{ $sale->business ? $sale->business->name : (auth()->user()->business ? auth()->user()->business->name : 'Untung Klik') }}
            </div>
            @php
                $bizType = $sale->business ? $sale->business->type : (auth()->user()->business ? auth()->user()->business->type : null);
                $bizAddr = $sale->business ? $sale->business->address : (auth()->user()->business ? auth()->user()->business->address : null);
                $bizPhone = $sale->business ? $sale->business->phone : (auth()->user()->business ? auth()->user()->business->phone : null);
            @endphp
            @if($bizType)
                <div class="thermal-store-desc">{{ $bizType }}</div>
            @endif
            @if($bizAddr)
                <div class="thermal-store-contact">{{ $bizAddr }}</div>
            @endif
            @if($bizPhone)
                <div class="thermal-store-contact">Telp/WA: {{ $bizPhone }}</div>
            @endif
        </div>

        <div class="thermal-divider"></div>

        <!-- Informasi Transaksi Kasir -->
        <div class="thermal-meta-list">
            <div class="thermal-meta-item">
                <span class="thermal-meta-label">No. Faktur</span>
                <span class="thermal-meta-val font-monospace">{{ $sale->formatted_invoice_number }}</span>
            </div>
            <div class="thermal-meta-item">
                <span class="thermal-meta-label">Waktu</span>
                <span class="thermal-meta-val">{{ $sale->transaction_date->format('d/m/Y') }} {{ $sale->created_at ? $sale->created_at->format('H:i') : '' }}</span>
            </div>
            <div class="thermal-meta-item">
                <span class="thermal-meta-label">Kasir</span>
                <span class="thermal-meta-val">{{ $sale->user ? $sale->user->name : '-' }}</span>
            </div>
            <div class="thermal-meta-item">
                <span class="thermal-meta-label">Pelanggan</span>
                <span class="thermal-meta-val">{{ $sale->customer_name ?: 'Pelanggan Umum' }}</span>
            </div>
            @if($sale->customer_phone)
            <div class="thermal-meta-item">
                <span class="thermal-meta-label">Kontak</span>
                <span class="thermal-meta-val">{{ $sale->customer_phone }}</span>
            </div>
            @endif
        </div>

        <div class="thermal-divider"></div>

        <!-- Rincian Item Belanjaan Kasir -->
        <div class="thermal-items-list">
            @forelse($sale->items as $item)
                <div class="thermal-item-entry">
                    <div class="thermal-item-name">{{ $item->product_name }}</div>
                    <div class="thermal-item-sub">
                        <span>{{ $item->quantity }} x {{ format_rupiah($item->unit_price) }}</span>
                        <span class="fw-bold">{{ format_rupiah($item->subtotal) }}</span>
                    </div>
                </div>
            @empty
                <div class="thermal-item-entry">
                    <div class="thermal-item-name">{{ $sale->description ?: 'Penjualan Produk' }}</div>
                    <div class="thermal-item-sub">
                        <span>1 x {{ format_rupiah($sale->amount) }}</span>
                        <span class="fw-bold">{{ format_rupiah($sale->amount) }}</span>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="thermal-divider"></div>

        <!-- Perhitungan & Pembayaran -->
        <div class="thermal-calc-container">
            <div class="thermal-calc-row">
                <span>Total Item ({{ $sale->items->sum('quantity') ?: 1 }})</span>
                <span>{{ format_rupiah($sale->subtotal) }}</span>
            </div>
            @if($sale->discount > 0)
            <div class="thermal-calc-row text-danger">
                <span>Potongan / Diskon</span>
                <span>- {{ format_rupiah($sale->discount) }}</span>
            </div>
            @endif
            <div class="thermal-divider-thick"></div>
            <div class="thermal-grand-total">
                <span>TOTAL AKHIR</span>
                <span>{{ format_rupiah($sale->amount) }}</span>
            </div>
            <div class="thermal-divider-thick"></div>
            <div class="thermal-calc-row">
                <span>Metode Bayar</span>
                <span class="fw-bold">{{ $sale->payment_method ?: 'Tunai' }}</span>
            </div>
            @if($sale->cash_received !== null)
            <div class="thermal-calc-row">
                <span>Uang Diterima</span>
                <span>{{ format_rupiah($sale->cash_received) }}</span>
            </div>
            <div class="thermal-calc-row fw-bold text-dark">
                <span>Kembalian</span>
                <span>{{ format_rupiah($sale->cash_change ?: 0) }}</span>
            </div>
            @endif
        </div>

        @if($sale->description)
            <div class="thermal-divider"></div>
            <div class="text-muted" style="font-size: 0.72rem; line-height: 1.35;">
                <strong>Catatan:</strong> {{ $sale->description }}
            </div>
        @endif

        <div class="thermal-divider"></div>

        <!-- Pesan Penutup Struk & Kebijakan Toko -->
        <div class="thermal-footer">
            @php
                $bizFooter = ($sale->business && $sale->business->receipt_footer) ? $sale->business->receipt_footer : (auth()->user()->business && auth()->user()->business->receipt_footer ? auth()->user()->business->receipt_footer : 'Terima Kasih Atas Kunjungan Anda!');
                $bizNote = ($sale->business && $sale->business->receipt_note) ? $sale->business->receipt_note : (auth()->user()->business && auth()->user()->business->receipt_note ? auth()->user()->business->receipt_note : 'Barang yang sudah dibeli tidak dapat ditukar/dikembalikan tanpa bukti nota ini.');
            @endphp
            <div class="thermal-footer-msg">{{ $bizFooter }}</div>
            @if($bizNote)
                <div class="thermal-footer-terms">{{ $bizNote }}</div>
            @endif

            <!-- Barcode Thermal Visual -->
            <div class="thermal-barcode-wrapper">
                <svg width="220" height="38" viewBox="0 0 220 38" xmlns="http://www.w3.org/2000/svg" class="thermal-barcode-img">
                    <rect x="0" y="0" width="220" height="38" fill="#ffffff" />
                    <g fill="#111827">
                        <rect x="10" y="2" width="3" height="34"/>
                        <rect x="15" y="2" width="1" height="34"/>
                        <rect x="18" y="2" width="4" height="34"/>
                        <rect x="24" y="2" width="2" height="34"/>
                        <rect x="28" y="2" width="1" height="34"/>
                        <rect x="31" y="2" width="3" height="34"/>
                        <rect x="36" y="2" width="2" height="34"/>
                        <rect x="40" y="2" width="4" height="34"/>
                        <rect x="46" y="2" width="1" height="34"/>
                        <rect x="49" y="2" width="3" height="34"/>
                        <rect x="54" y="2" width="2" height="34"/>
                        <rect x="58" y="2" width="1" height="34"/>
                        <rect x="61" y="2" width="4" height="34"/>
                        <rect x="67" y="2" width="2" height="34"/>
                        <rect x="71" y="2" width="3" height="34"/>
                        <rect x="76" y="2" width="1" height="34"/>
                        <rect x="79" y="2" width="3" height="34"/>
                        <rect x="84" y="2" width="4" height="34"/>
                        <rect x="90" y="2" width="2" height="34"/>
                        <rect x="94" y="2" width="1" height="34"/>
                        <rect x="97" y="2" width="3" height="34"/>
                        <rect x="102" y="2" width="2" height="34"/>
                        <rect x="106" y="2" width="4" height="34"/>
                        <rect x="112" y="2" width="1" height="34"/>
                        <rect x="115" y="2" width="3" height="34"/>
                        <rect x="120" y="2" width="2" height="34"/>
                        <rect x="124" y="2" width="4" height="34"/>
                        <rect x="130" y="2" width="1" height="34"/>
                        <rect x="133" y="2" width="3" height="34"/>
                        <rect x="138" y="2" width="2" height="34"/>
                        <rect x="142" y="2" width="4" height="34"/>
                        <rect x="148" y="2" width="1" height="34"/>
                        <rect x="151" y="2" width="3" height="34"/>
                        <rect x="156" y="2" width="2" height="34"/>
                        <rect x="160" y="2" width="3" height="34"/>
                        <rect x="165" y="2" width="1" height="34"/>
                        <rect x="168" y="2" width="4" height="34"/>
                        <rect x="174" y="2" width="2" height="34"/>
                        <rect x="178" y="2" width="1" height="34"/>
                        <rect x="181" y="2" width="3" height="34"/>
                        <rect x="186" y="2" width="2" height="34"/>
                        <rect x="190" y="2" width="4" height="34"/>
                        <rect x="196" y="2" width="1" height="34"/>
                        <rect x="199" y="2" width="3" height="34"/>
                        <rect x="204" y="2" width="2" height="34"/>
                    </g>
                </svg>
                <div class="thermal-barcode-caption font-monospace">{{ $sale->formatted_invoice_number }}</div>
            </div>

            <div class="thermal-watermark">
                Untung Klik &bull; Kasir &amp; Buku Kas Digital
            </div>
        </div>
    </div>
</div>

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
