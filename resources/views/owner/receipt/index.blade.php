@extends('layouts.app')

@section('title', 'Pengaturan Bagian Nota & Struk')

@section('content')
<!-- Page Header -->
<div class="uk-page-header">
    <div>
        <h2 class="uk-page-title">Pengaturan Bagian Nota & Struk</h2>
        <p class="uk-page-subtitle">Sesuaikan identitas toko, alamat cabang, kontak WhatsApp, dan ketentuan penutup pada nota transaksi kasir.</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route('owner.sales.index') }}" class="btn btn-sm btn-uk-outline rounded-pill px-3">
            <i class="fas fa-receipt me-1.5"></i>Riwayat Penjualan
        </a>
        <a href="{{ route('owner.sales.create') }}" class="btn btn-sm btn-uk-primary rounded-pill px-3.5 shadow-xs fw-bold">
            <i class="fas fa-cash-register me-1.5"></i>Buka Kasir
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Formulir Pengaturan Nota -->
    <div class="col-12 col-lg-7">
        <div class="uk-card p-3 p-md-4">
            <div class="d-flex align-items-center gap-2 pb-3 mb-4 border-bottom">
                <div style="width: 36px; height: 36px; border-radius: 8px; background-color: rgba(51, 153, 137, 0.12); color: var(--uk-primary); display: flex; align-items: center; justify-content: center; font-size: 1rem;">
                    <i class="fas fa-sliders"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0 text-dark">Informasi Identitas Nota</h5>
                    <small class="text-muted">Data di bawah ini langsung diterapkan pada seluruh nota dan struk kasir toko.</small>
                </div>
            </div>

            <form method="POST" action="{{ route('owner.receipt.update') }}" id="receiptForm">
                @csrf
                @method('PUT')

                <!-- Nama Toko / Usaha -->
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold small text-dark mb-1">
                        Nama Toko / Usaha <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0">
                            <i class="fas fa-store"></i>
                        </span>
                        <input type="text"
                               class="form-control border-start-0 @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name', $business->name) }}"
                               placeholder="Contoh: Galeri E-Bike Uwinfly & NUV"
                               required
                               oninput="syncPreview()">
                    </div>
                    <div class="form-text small" style="font-size: 0.72rem;">Nama bisnis atau merk toko yang dicetak paling atas pada nota.</div>
                    @error('name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Slogan / Subjudul Usaha -->
                <div class="mb-3">
                    <label for="type" class="form-label fw-semibold small text-dark mb-1">
                        Slogan / Bidang Usaha (Opsional)
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0">
                            <i class="fas fa-tag"></i>
                        </span>
                        <input type="text"
                               class="form-control border-start-0 @error('type') is-invalid @enderror"
                               id="type"
                               name="type"
                               value="{{ old('type', $business->type) }}"
                               placeholder="Contoh: Dealer Resmi Sepeda & Motor Listrik"
                               oninput="syncPreview()">
                    </div>
                    <div class="form-text small" style="font-size: 0.72rem;">Deskripsi singkat toko di bawah nama usaha.</div>
                    @error('type')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Alamat Lengkap Toko -->
                <div class="mb-3">
                    <label for="address" class="form-label fw-semibold small text-dark mb-1">
                        Alamat Toko / Lokasi Cabang
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0 align-items-start pt-2">
                            <i class="fas fa-location-dot"></i>
                        </span>
                        <textarea class="form-control border-start-0 @error('address') is-invalid @enderror"
                                  id="address"
                                  name="address"
                                  rows="2"
                                  placeholder="Contoh: Jl. Soekarno-Hatta No. 210, Bandung"
                                  oninput="syncPreview()">{{ old('address', $business->address) }}</textarea>
                    </div>
                    <div class="form-text small" style="font-size: 0.72rem;">Alamat fisik toko yang membantu pelanggan mengenali lokasi usaha.</div>
                    @error('address')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Nomor Telepon / WhatsApp -->
                <div class="mb-3">
                    <label for="phone" class="form-label fw-semibold small text-dark mb-1">
                        Nomor Telepon / WhatsApp Toko
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0">
                            <i class="fas fa-phone-alt"></i>
                        </span>
                        <input type="text"
                               class="form-control border-start-0 @error('phone') is-invalid @enderror"
                               id="phone"
                               name="phone"
                               value="{{ old('phone', $business->phone) }}"
                               placeholder="Contoh: 081234567890"
                               oninput="syncPreview()">
                    </div>
                    <div class="form-text small" style="font-size: 0.72rem;">Nomor kontak layanan pelanggan atau nomor konfirmasi nota.</div>
                    @error('phone')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Pesan Penutup Nota -->
                <div class="mb-3">
                    <label for="receipt_footer" class="form-label fw-semibold small text-dark mb-1">
                        Pesan Penutup Struk (Footer Heading)
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0">
                            <i class="fas fa-comment-dots"></i>
                        </span>
                        <input type="text"
                               class="form-control border-start-0 @error('receipt_footer') is-invalid @enderror"
                               id="receipt_footer"
                               name="receipt_footer"
                               value="{{ old('receipt_footer', $business->receipt_footer) }}"
                               placeholder="Contoh: Terima Kasih Atas Kunjungan Anda!"
                               oninput="syncPreview()">
                    </div>
                    <div class="form-text small" style="font-size: 0.72rem;">Ucapan penutup yang dicetak tebal di bagian bawah struk.</div>
                    @error('receipt_footer')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Ketentuan Retur / Catatan Kaki Nota -->
                <div class="mb-4">
                    <label for="receipt_note" class="form-label fw-semibold small text-dark mb-1">
                        Ketentuan / Kebijakan Toko (Catatan Bawah Nota)
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0 align-items-start pt-2">
                            <i class="fas fa-shield-halved"></i>
                        </span>
                        <textarea class="form-control border-start-0 @error('receipt_note') is-invalid @enderror"
                                  id="receipt_note"
                                  name="receipt_note"
                                  rows="2"
                                  placeholder="Contoh: Barang yang sudah dibeli tidak dapat ditukar/dikembalikan tanpa bukti nota ini."
                                  oninput="syncPreview()">{{ old('receipt_note', $business->receipt_note) }}</textarea>
                    </div>
                    <div class="form-text small" style="font-size: 0.72rem;">Informasi garansi servis, ketentuan pengembalian produk, atau jam buka toko.</div>
                    @error('receipt_note')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex align-items-center justify-content-between pt-3 border-top flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <button type="button" class="btn btn-sm btn-uk-outline rounded-pill px-3" onclick="resetToDefaults()">
                            <i class="fas fa-rotate-left me-1.5"></i>Reset Bawaan Aplikasi
                        </button>
                        <button type="button" class="btn btn-sm btn-uk-secondary rounded-pill px-3" onclick="loadGaleriPreset()">
                            <i class="fas fa-store me-1.5"></i>Template Galeri E-Bike
                        </button>
                    </div>
                    <button type="submit" class="btn btn-sm btn-uk-primary rounded-pill px-4 py-2 shadow-xs fw-bold">
                        <i class="fas fa-save me-1.5"></i>Simpan Pengaturan Nota
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Pratinjau Langsung Struk Kasir (Live Preview) -->
    <div class="col-12 col-lg-5">
        <div class="position-sticky" style="top: 24px;">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-eye text-primary"></i>
                    <h6 class="fw-bold mb-0 text-dark">Pratinjau Nota (Live Preview)</h6>
                </div>
                <span class="badge badge-uk-primary" style="font-size: 0.68rem;">Format 80mm & 58mm</span>
            </div>

            <!-- Kartu Mockup Struk Fisik -->
            <div class="uk-card p-4 shadow-sm border" style="background-color: #ffffff; border-radius: 12px; font-family: 'Courier New', Courier, monospace;">
                <!-- Header Nota Mockup -->
                <div class="text-center border-bottom pb-3 mb-3">
                    <div class="d-flex justify-content-center align-items-center gap-2 mb-1">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background-color: var(--uk-primary); color: #FFFAFB; display: flex; align-items: center; justify-content: center; font-size: 0.95rem;">
                            <i class="fas fa-store"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-0 font-sans-serif" id="previewName" style="letter-spacing: -0.02em; font-family: system-ui, sans-serif;">
                            {{ $business->name ?: config('app.name', 'Untung Klik') }}
                        </h5>
                    </div>
                    <div class="text-muted small mb-1" id="previewType" style="font-size: 0.75rem; {{ $business->type ? '' : 'display: none;' }}">
                        {{ $business->type }}
                    </div>
                    <div class="text-muted small mb-0.5" id="previewAddress" style="font-size: 0.72rem; line-height: 1.4; {{ $business->address ? '' : 'display: none;' }}">
                        {{ $business->address }}
                    </div>
                    <div class="text-muted small mb-0" id="previewPhoneWrap" style="font-size: 0.72rem; {{ $business->phone ? '' : 'display: none;' }}">
                        <i class="fas fa-phone-alt me-1 text-primary"></i><span id="previewPhone">{{ $business->phone }}</span>
                    </div>
                </div>

                <!-- Meta Transaksi Mockup -->
                <div class="small mb-3" style="font-size: 0.72rem; line-height: 1.5; color: var(--uk-dark-secondary);">
                    <div class="d-flex justify-content-between">
                        <span>No. Faktur:</span>
                        <span class="fw-bold text-dark font-monospace">INV-{{ date('Ymd') }}-0042</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Tanggal:</span>
                        <span>{{ date('d F Y H:i') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Kasir:</span>
                        <span>{{ auth()->user()->name }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Pelanggan:</span>
                        <span>Pelanggan Umum</span>
                    </div>
                </div>

                <!-- Garis Pemisah Titik-titik -->
                <div class="border-top border-dashed my-2" style="border-top-style: dashed !important;"></div>

                <!-- Rincian Item Mockup -->
                <div class="small mb-2" style="font-size: 0.72rem;">
                    @forelse($previewProducts->take(2) as $prod)
                        <div class="d-flex justify-content-between fw-bold mb-0.5">
                            <span class="text-truncate me-2" style="max-width: 180px;">{{ $prod->name }}</span>
                            <span>{{ format_rupiah($prod->selling_price) }}</span>
                        </div>
                        <div class="text-muted ps-2 mb-1.5" style="font-size: 0.68rem;">1 x @ {{ format_rupiah($prod->selling_price) }}</div>
                    @empty
                        <div class="d-flex justify-content-between fw-bold mb-0.5">
                            <span class="text-truncate me-2">Sepeda Listrik D7S</span>
                            <span>Rp 4.250.000</span>
                        </div>
                        <div class="text-muted ps-2 mb-1.5" style="font-size: 0.68rem;">1 x @ Rp 4.250.000</div>
                        <div class="d-flex justify-content-between fw-bold mb-0.5">
                            <span class="text-truncate me-2">Helm Exclusive Uwinfly</span>
                            <span>Rp 150.000</span>
                        </div>
                        <div class="text-muted ps-2 mb-1.5" style="font-size: 0.68rem;">1 x @ Rp 150.000</div>
                    @endforelse
                </div>

                @php
                    $displayItems = $previewProducts->take(2);
                    $mockSubtotal = $displayItems->isNotEmpty() ? (float) $displayItems->sum('selling_price') : 4400000;
                    $mockDiscount = $mockSubtotal >= 500000 ? 50000 : 0;
                    $mockTotal = $mockSubtotal - $mockDiscount;
                    $mockCash = ceil($mockTotal / 100000) * 100000;
                    if ($mockCash <= $mockTotal) {
                        $mockCash += 50000;
                    }
                    $mockChange = $mockCash - $mockTotal;
                @endphp

                <!-- Total Kalkulasi Mockup -->
                <div class="border-top border-dashed pt-2 mb-3" style="border-top-style: dashed !important; font-size: 0.75rem;">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Subtotal:</span>
                        <span>{{ format_rupiah($mockSubtotal) }}</span>
                    </div>
                    @if($mockDiscount > 0)
                    <div class="d-flex justify-content-between mb-1 text-muted">
                        <span>Diskon:</span>
                        <span>- {{ format_rupiah($mockDiscount) }}</span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between fw-bold text-dark fs-6 pt-1 border-top" style="border-color: var(--uk-border) !important;">
                        <span>TOTAL:</span>
                        <span>{{ format_rupiah($mockTotal) }}</span>
                    </div>
                    <div class="d-flex justify-content-between pt-1 text-muted">
                        <span>Tunai:</span>
                        <span>{{ format_rupiah($mockCash) }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-muted">
                        <span>Kembalian:</span>
                        <span>{{ format_rupiah($mockChange) }}</span>
                    </div>
                </div>

                <!-- Footer Nota Mockup -->
                <div class="text-center border-top border-dashed pt-3" style="border-top-style: dashed !important;">
                    <p class="fw-bold text-dark mb-1 font-sans-serif" id="previewFooter" style="font-size: 0.78rem; font-family: system-ui, sans-serif;">
                        {{ $business->receipt_footer ?: 'Terima Kasih Atas Kunjungan Anda!' }}
                    </p>
                    <p class="text-muted mb-2" id="previewNote" style="font-size: 0.68rem; line-height: 1.4; {{ $business->receipt_note ? '' : 'display: none;' }}">
                        {{ $business->receipt_note ?: 'Barang yang sudah dibeli tidak dapat ditukar/dikembalikan tanpa bukti nota ini.' }}
                    </p>
                    <div class="text-secondary" style="font-size: 0.62rem; opacity: 0.75;">
                        Untung Klik - Sistem Kasir & Buku Kas Digital
                    </div>
                </div>
            </div>

            <!-- Petunjuk Penggunaan -->
            <div class="uk-card p-3 mt-3">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="fas fa-lightbulb text-primary"></i>
                    <span class="fw-bold small text-dark">Informasi Tambahan</span>
                </div>
                <p class="text-muted small mb-0" style="font-size: 0.75rem; line-height: 1.6;">
                    Perubahan nama, alamat, dan nomor telepon juga akan otomatis terpasang pada struk cetak PDF dan format teks pengiriman pesan nota ke WhatsApp pelanggan.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function syncPreview() {
        const nameVal = document.getElementById('name').value.trim();
        const typeVal = document.getElementById('type').value.trim();
        const addressVal = document.getElementById('address').value.trim();
        const phoneVal = document.getElementById('phone').value.trim();
        const footerVal = document.getElementById('receipt_footer').value.trim();
        const noteVal = document.getElementById('receipt_note').value.trim();

        // Nama
        document.getElementById('previewName').innerText = nameVal || '{{ config('app.name', 'Untung Klik') }}';

        // Slogan/Type
        const previewType = document.getElementById('previewType');
        if (typeVal) {
            previewType.innerText = typeVal;
            previewType.style.display = 'block';
        } else {
            previewType.style.display = 'none';
        }

        // Alamat
        const previewAddress = document.getElementById('previewAddress');
        if (addressVal) {
            previewAddress.innerText = addressVal;
            previewAddress.style.display = 'block';
        } else {
            previewAddress.style.display = 'none';
        }

        // Telepon
        const phoneWrap = document.getElementById('previewPhoneWrap');
        const previewPhone = document.getElementById('previewPhone');
        if (phoneVal) {
            previewPhone.innerText = phoneVal;
            phoneWrap.style.display = 'block';
        } else {
            phoneWrap.style.display = 'none';
        }

        // Pesan Penutup
        document.getElementById('previewFooter').innerText = footerVal || 'Terima Kasih Atas Kunjungan Anda!';

        // Catatan Kaki
        const previewNote = document.getElementById('previewNote');
        if (noteVal) {
            previewNote.innerText = noteVal;
            previewNote.style.display = 'block';
        } else {
            previewNote.style.display = 'none';
        }
    }

    function resetToDefaults() {
        document.getElementById('name').value = '{{ config('app.name', 'Untung Klik') }}';
        document.getElementById('type').value = 'Sistem Buku Kas Digital & Keuangan Usaha UMKM';
        document.getElementById('address').value = 'Jl. Soekarno-Hatta No. 210, Bandung';
        document.getElementById('phone').value = '081234567890';
        document.getElementById('receipt_footer').value = 'Terima Kasih Atas Kunjungan Anda!';
        document.getElementById('receipt_note').value = 'Barang yang sudah dibeli tidak dapat ditukar/dikembalikan tanpa bukti nota ini.';
        syncPreview();
    }

    function loadGaleriPreset() {
        document.getElementById('name').value = 'Galeri E-Bike Uwinfly & NUV';
        document.getElementById('type').value = 'Dealer Resmi Sepeda & Motor Listrik';
        document.getElementById('address').value = 'Jl. Soekarno-Hatta No. 210, Bandung';
        document.getElementById('phone').value = '081234567890';
        document.getElementById('receipt_footer').value = 'Terima Kasih Atas Kunjungan Anda!';
        document.getElementById('receipt_note').value = 'Barang yang sudah dibeli tidak dapat ditukar/dikembalikan tanpa bukti nota ini.';
        syncPreview();
    }
</script>
@endpush
