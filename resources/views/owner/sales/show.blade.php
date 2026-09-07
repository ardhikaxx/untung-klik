@extends('layouts.app')

@section('title', 'Nota Penjualan ' . $sale->formatted_invoice_number)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2 d-print-none">
    <div>
        <h4 class="fw-bold mb-1">Nota Penjualan {{ $sale->formatted_invoice_number }}</h4>
        <p class="text-muted mb-0">Rincian bukti transaksi penjualan produk dan pembayaran kasir.</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('owner.sales.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Riwayat Penjualan
        </a>
        <a href="{{ route('owner.sales.create') }}" class="btn btn-outline-success">
            <i class="fas fa-plus-circle me-1"></i>+ Transaksi Baru
        </a>
        <button type="button" class="btn btn-success" onclick="shareToWhatsApp()">
            <i class="fab fa-whatsapp me-1"></i>Kirim ke WA
        </button>
        <button type="button" class="btn btn-primary" onclick="window.print()">
            <i class="fas fa-print me-1"></i>Cetak Struk
        </button>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7 col-md-9">
        <div class="card border shadow-sm print-area">
            <div class="card-body p-4 p-sm-5">
                <!-- Header Toko / Struk -->
                <div class="text-center border-bottom pb-4 mb-4">
                    <div class="d-flex justify-content-center align-items-center gap-2 mb-2">
                        <div class="p-2 rounded-circle bg-success bg-opacity-10 text-success d-inline-flex">
                            <i class="fas fa-store fs-4"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-0">
                            {{ auth()->user()->business ? auth()->user()->business->name : 'Untung Klik' }}
                        </h4>
                    </div>
                    @if(auth()->user()->business && auth()->user()->business->address)
                        <p class="text-muted small mb-1">{{ auth()->user()->business->address }}</p>
                    @endif
                    @if(auth()->user()->business && auth()->user()->business->phone)
                        <p class="text-muted small mb-0"><i class="fas fa-phone-alt me-1"></i>{{ auth()->user()->business->phone }}</p>
                    @endif
                </div>

                <!-- Informasi Nota -->
                <div class="row g-2 mb-4 small">
                    <div class="col-sm-6">
                        <div class="text-muted">No. Faktur:</div>
                        <div class="fw-bold text-dark fs-6">{{ $sale->formatted_invoice_number }}</div>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <div class="text-muted">Tanggal & Jam:</div>
                        <div class="fw-semibold text-dark">
                            {{ $sale->transaction_date->format('d F Y') }} {{ $sale->created_at ? $sale->created_at->format('H:i') : '' }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted">Pelanggan:</div>
                        <div class="fw-semibold text-dark">
                            {{ $sale->customer_name ?: 'Pelanggan Umum' }}
                            @if($sale->customer_phone)
                                <span class="text-muted small">({{ $sale->customer_phone }})</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <div class="text-muted">Kasir / Petugas:</div>
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
                                        <div class="fw-semibold text-dark">{{ $item->product_name }}</div>
                                        @if($item->product && $item->product->sku)
                                            <div class="text-muted" style="font-size: 0.75rem;">SKU: {{ $item->product->sku }}</div>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
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
                                <td colspan="3" class="text-end fw-bold fs-6 pt-2">TOTAL AKHIR:</td>
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
                    <div class="p-3 bg-light rounded-3 mb-4 small text-muted">
                        <strong>Catatan:</strong> {{ $sale->description }}
                    </div>
                @endif

                <!-- Pesan Penutup Struk -->
                <div class="text-center border-top pt-4 text-muted small">
                    <p class="mb-1 fw-bold text-dark">Terima Kasih Atas Kunjungan Anda!</p>
                    <p class="mb-0 text-muted" style="font-size: 0.75rem;">Barang yang sudah dibeli tidak dapat ditukar/dikembalikan tanpa nota ini.</p>
                    <p class="mb-0 mt-2 text-secondary" style="font-size: 0.7rem;">Untung Klik - Buku Kas & Kasir Digital UMKM</p>
                </div>
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
            background: #fff !important;
            color: #000 !important;
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
        const storeName = "{{ auth()->user()->business ? auth()->user()->business->name : 'Untung Klik' }}";
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
