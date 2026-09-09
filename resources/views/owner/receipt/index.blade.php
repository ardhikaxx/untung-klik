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
                <div style="width: 36px; height: 36px; border-radius: 8px; background-color: rgba(14, 71, 73, 0.12); color: var(--uk-primary); display: flex; align-items: center; justify-content: center; font-size: 1rem;">
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
                               placeholder="Contoh: Toko Berkah / {{ config('app.name', 'Untung Klik') }}"
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

            <!-- Kartu Mockup Struk Fisik Thermal -->
            <div class="thermal-receipt-stage p-2 p-sm-3">
                <div class="thermal-receipt-paper">
                    <!-- Header Nota Mockup -->
                    <div class="thermal-header">
                        <div class="thermal-store-name" id="previewName">
                            {{ $business->name ?: config('app.name', 'Untung Klik') }}
                        </div>
                        <div class="thermal-store-desc" id="previewType" style="{{ $business->type ? '' : 'display: none;' }}">
                            {{ $business->type }}
                        </div>
                        <div class="thermal-store-contact" id="previewAddress" style="{{ $business->address ? '' : 'display: none;' }}">
                            {{ $business->address }}
                        </div>
                        <div class="thermal-store-contact" id="previewPhoneWrap" style="{{ $business->phone ? '' : 'display: none;' }}">
                            Telp/WA: <span id="previewPhone">{{ $business->phone }}</span>
                        </div>
                    </div>

                    <div class="thermal-divider"></div>

                    <!-- Meta Transaksi Mockup -->
                    <div class="thermal-meta-list">
                        <div class="thermal-meta-item">
                            <span class="thermal-meta-label">No. Faktur</span>
                            <span class="thermal-meta-val font-monospace">INV-{{ date('Ymd') }}-0042</span>
                        </div>
                        <div class="thermal-meta-item">
                            <span class="thermal-meta-label">Waktu</span>
                            <span class="thermal-meta-val">{{ date('d/m/Y H:i') }}</span>
                        </div>
                        <div class="thermal-meta-item">
                            <span class="thermal-meta-label">Kasir</span>
                            <span class="thermal-meta-val">{{ auth()->user()->name }}</span>
                        </div>
                        <div class="thermal-meta-item">
                            <span class="thermal-meta-label">Pelanggan</span>
                            <span class="thermal-meta-val">Pelanggan Umum</span>
                        </div>
                    </div>

                    <div class="thermal-divider"></div>

                    <!-- Rincian Item Mockup Thermal -->
                    <div class="thermal-items-list">
                        @forelse($previewProducts->take(2) as $prod)
                            <div class="thermal-item-entry">
                                <div class="thermal-item-name">{{ $prod->name }}</div>
                                <div class="thermal-item-sub">
                                    <span>1 x {{ format_rupiah($prod->selling_price) }}</span>
                                    <span class="fw-bold">{{ format_rupiah($prod->selling_price) }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="thermal-item-entry">
                                <div class="thermal-item-name">Beras Premium 5kg</div>
                                <div class="thermal-item-sub">
                                    <span>1 x Rp 75.000</span>
                                    <span class="fw-bold">Rp 75.000</span>
                                </div>
                            </div>
                            <div class="thermal-item-entry">
                                <div class="thermal-item-name">Minyak Goreng Pouch 2L</div>
                                <div class="thermal-item-sub">
                                    <span>1 x Rp 37.000</span>
                                    <span class="fw-bold">Rp 37.000</span>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    @php
                        $displayItems = $previewProducts->take(2);
                        $mockSubtotal = $displayItems->isNotEmpty() ? (float) $displayItems->sum('selling_price') : 112000;
                        $mockDiscount = $mockSubtotal >= 100000 ? 5000 : 0;
                        $mockTotal = $mockSubtotal - $mockDiscount;
                        $mockCash = ceil($mockTotal / 50000) * 50000;
                        if ($mockCash <= $mockTotal) {
                            $mockCash += 50000;
                        }
                        $mockChange = $mockCash - $mockTotal;
                    @endphp

                    <div class="thermal-divider"></div>

                    <!-- Total Kalkulasi Mockup -->
                    <div class="thermal-calc-container">
                        <div class="thermal-calc-row">
                            <span>Subtotal</span>
                            <span>{{ format_rupiah($mockSubtotal) }}</span>
                        </div>
                        @if($mockDiscount > 0)
                        <div class="thermal-calc-row text-danger">
                            <span>Potongan / Diskon</span>
                            <span>- {{ format_rupiah($mockDiscount) }}</span>
                        </div>
                        @endif
                        <div class="thermal-divider-thick"></div>
                        <div class="thermal-grand-total">
                            <span>TOTAL AKHIR</span>
                            <span>{{ format_rupiah($mockTotal) }}</span>
                        </div>
                        <div class="thermal-divider-thick"></div>
                        <div class="thermal-calc-row">
                            <span>Metode Bayar</span>
                            <span class="fw-bold">Tunai</span>
                        </div>
                        <div class="thermal-calc-row">
                            <span>Uang Diterima</span>
                            <span>{{ format_rupiah($mockCash) }}</span>
                        </div>
                        <div class="thermal-calc-row fw-bold text-dark">
                            <span>Kembalian</span>
                            <span>{{ format_rupiah($mockChange) }}</span>
                        </div>
                    </div>

                    <div class="thermal-divider"></div>

                    <!-- Footer & Barcode Mockup -->
                    <div class="thermal-footer">
                        <div class="thermal-footer-msg" id="previewFooter">
                            {{ $business->receipt_footer ?: 'Terima Kasih Atas Kunjungan Anda!' }}
                        </div>
                        <div class="thermal-footer-terms" id="previewNote" style="{{ $business->receipt_note ? '' : 'display: none;' }}">
                            {{ $business->receipt_note ?: 'Barang yang sudah dibeli tidak dapat ditukar/dikembalikan tanpa bukti nota ini.' }}
                        </div>

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
                            <div class="thermal-barcode-caption font-monospace">INV-{{ date('Ymd') }}-0042</div>
                        </div>

                        <div class="thermal-watermark">
                            Untung Klik &bull; Kasir &amp; Buku Kas Digital
                        </div>
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
        document.getElementById('type').value = 'Sistem Kasir & Buku Kas Digital UMKM';
        document.getElementById('address').value = 'Jl. Merdeka No. 123';
        document.getElementById('phone').value = '081234567890';
        document.getElementById('receipt_footer').value = 'Terima Kasih Atas Kunjungan Anda!';
        document.getElementById('receipt_note').value = 'Barang yang sudah dibeli tidak dapat ditukar/dikembalikan tanpa bukti nota ini.';
        syncPreview();
    }
</script>
@endpush
