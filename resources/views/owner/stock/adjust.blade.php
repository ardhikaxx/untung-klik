@extends('layouts.app')

@section('title', 'Penyesuaian Stok')

@section('content')
<!-- Page Header -->
<div class="uk-page-header">
    <div>
        <h2 class="uk-page-title">Penyesuaian Stok & Opname</h2>
        <p class="uk-page-subtitle">Catat kulakan/restock barang baru, barang rusak/kadaluarsa, kehilangan, atau hasil opname fisik.</p>
    </div>
    <a href="{{ route('owner.stock.index') }}" class="btn btn-sm btn-uk-outline rounded-pill px-3">
        <i class="fas fa-arrow-left me-1.5"></i>Kembali ke Stok
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="uk-card p-3 p-md-4">
            <form method="POST" action="{{ route('owner.stock.adjust.store') }}">
                @csrf

                <!-- Pilih Produk -->
                <div class="mb-4">
                    <label for="product_id" class="form-label fw-semibold small text-dark mb-1">
                        Pilih Produk <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('product_id') is-invalid @enderror"
                            id="product_id" name="product_id" required onchange="updateProductInfo()">
                        <option value="">-- Pilih Produk yang Disesuaikan --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}"
                                data-stock="{{ $product->stock }}"
                                data-unit="{{ $product->unit }}"
                                data-min="{{ $product->min_stock }}"
                                {{ (old('product_id', request('product_id')) == $product->id) ? 'selected' : '' }}>
                                {{ $product->name }} (Sisa Stok: {{ number_format($product->stock) }} {{ $product->unit }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Info Pod Produk Terpilih -->
                <div id="productInfoBox" class="p-3 bg-light rounded-3 mb-4 d-none border">
                    <div class="row text-center g-2">
                        <div class="col-sm-4 border-end">
                            <span class="text-muted small" style="font-size: 0.75rem;">Stok Fisik Saat Ini</span>
                            <div class="fw-bold fs-4 text-dark mt-1" id="displayCurrentStock">0</div>
                        </div>
                        <div class="col-sm-4 border-end">
                            <span class="text-muted small" style="font-size: 0.75rem;">Batas Minimum</span>
                            <div class="fw-semibold text-muted fs-5 mt-1" id="displayMinStock">0</div>
                        </div>
                        <div class="col-sm-4">
                            <span class="text-muted small" style="font-size: 0.75rem;">Estimasi Sisa Stok Akhir</span>
                            <div class="fw-bold fs-4 text-primary mt-1" id="displayProjectedStock">-</div>
                        </div>
                    </div>
                </div>

                <!-- Tipe Penyesuaian (Card Selector) -->
                <div class="mb-4">
                    <label class="form-label fw-semibold small text-dark mb-2">
                        Tipe Penyesuaian Mutasi <span class="text-danger">*</span>
                    </label>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="p-3 border rounded-3 d-block cursor-pointer h-100 position-relative uk-adjust-card" for="typeIn" style="background-color: var(--uk-surface);">
                                <div class="d-flex align-items-start gap-2">
                                    <input class="form-check-input mt-1" type="radio" name="type" id="typeIn" value="in"
                                           {{ old('type', 'in') === 'in' ? 'checked' : '' }} onchange="updateLabel()">
                                    <div>
                                        <span class="fw-bold text-dark d-block mb-1">
                                            <i class="fas fa-plus-circle text-primary me-1"></i>Penambahan Stok
                                        </span>
                                        <div class="text-muted small" style="font-size: 0.75rem; line-height: 1.4;">
                                            Kulakan belanja baru atau restock gudang barang dagangan.
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="p-3 border rounded-3 d-block cursor-pointer h-100 position-relative uk-adjust-card" for="typeDamaged" style="background-color: var(--uk-surface);">
                                <div class="d-flex align-items-start gap-2">
                                    <input class="form-check-input mt-1" type="radio" name="type" id="typeDamaged" value="damaged"
                                           {{ old('type') === 'damaged' ? 'checked' : '' }} onchange="updateLabel()">
                                    <div>
                                        <span class="fw-bold text-dark d-block mb-1">
                                            <i class="fas fa-heart-crack text-dark me-1"></i>Barang Rusak / Cacat
                                        </span>
                                        <div class="text-muted small" style="font-size: 0.75rem; line-height: 1.4;">
                                            Barang rusak, kadaluarsa, pecah, reject, atau cacat pabrik.
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="p-3 border rounded-3 d-block cursor-pointer h-100 position-relative uk-adjust-card" for="typeLost" style="background-color: var(--uk-surface);">
                                <div class="d-flex align-items-start gap-2">
                                    <input class="form-check-input mt-1" type="radio" name="type" id="typeLost" value="lost"
                                           {{ old('type') === 'lost' ? 'checked' : '' }} onchange="updateLabel()">
                                    <div>
                                        <span class="fw-bold text-dark d-block mb-1">
                                            <i class="fas fa-search-minus text-dark me-1"></i>Barang Hilang / Kurang
                                        </span>
                                        <div class="text-muted small" style="font-size: 0.75rem; line-height: 1.4;">
                                            Selisih fisik kurang saat penghitungan stock opname.
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="p-3 border rounded-3 d-block cursor-pointer h-100 position-relative uk-adjust-card" for="typeCorrection" style="background-color: var(--uk-surface);">
                                <div class="d-flex align-items-start gap-2">
                                    <input class="form-check-input mt-1" type="radio" name="type" id="typeCorrection" value="correction"
                                           {{ old('type') === 'correction' ? 'checked' : '' }} onchange="updateLabel()">
                                    <div>
                                        <span class="fw-bold text-dark d-block mb-1">
                                            <i class="fas fa-equals text-primary me-1"></i>Koreksi Fisik Riil
                                        </span>
                                        <div class="text-muted small" style="font-size: 0.75rem; line-height: 1.4;">
                                            Atur stok sistem langsung mengikuti jumlah fisik riil di toko.
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                    @error('type')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Input Jumlah -->
                <div class="mb-4">
                    <label for="amount" class="form-label fw-semibold small text-dark mb-1" id="amountLabel">
                        Jumlah Penambahan Stok <span class="text-danger">*</span>
                    </label>
                    <input type="number" class="form-control @error('amount') is-invalid @enderror"
                           id="amount" name="amount" value="{{ old('amount', 1) }}" min="0" required oninput="calculateProjectedStock()">
                    <div class="form-text small" id="amountHelp" style="font-size: 0.75rem;">
                        Masukkan jumlah barang yang ingin ditambahkan ke stok saat ini.
                    </div>
                    @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Catatan / Alasan -->
                <div class="mb-4">
                    <label for="notes" class="form-label fw-semibold small text-dark mb-1">
                        Alasan / Catatan Penyesuaian <span class="text-danger">*</span>
                    </label>
                    <textarea class="form-control @error('notes') is-invalid @enderror"
                              id="notes" name="notes" rows="3"
                              placeholder="Contoh: Belanja kulakan grosir dari distributor resmi, atau 2 botol pecah saat bongkar muat..." required>{{ old('notes') }}</textarea>
                    <div class="form-text small" style="font-size: 0.72rem;">Catatan ini akan tersimpan permanen di jurnal audit inventaris.</div>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="{{ route('owner.stock.index') }}" class="btn btn-sm btn-uk-secondary rounded-pill px-3">Batal</a>
                    <button type="submit" class="btn btn-sm btn-uk-primary rounded-pill px-4 shadow-xs fw-bold">
                        <i class="fas fa-save me-1.5"></i>Simpan Penyesuaian Stok
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Kolom Kanan: Audit & Panduan -->
    <div class="col-lg-4">
        <div class="uk-card p-3 p-md-4 mb-4">
            <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                <i class="fas fa-shield-halved text-primary"></i>
                <h6 class="fw-bold mb-0 text-dark">Audit Trail Inventaris</h6>
            </div>
            <p class="text-muted small mb-2" style="font-size: 0.8rem; line-height: 1.6;">
                Seluruh riwayat penyesuaian stok akan terdokumentasi otomatis ke sistem:
            </p>
            <ul class="text-muted small ps-3 mb-0" style="line-height: 1.8; font-size: 0.8rem;">
                <li>Waktu dan tanggal penyesuaian dicatat presisi</li>
                <li>Identitas staf/owner yang melakukan input</li>
                <li>Stok sebelum dan stok sesudah mutasi</li>
                <li>Pencatatan alasan untuk transparansi pembukuan</li>
            </ul>
        </div>

        <div class="uk-card p-3 p-md-4">
            <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                <i class="fas fa-lightbulb text-primary"></i>
                <h6 class="fw-bold mb-0 text-dark">Tips Stock Opname</h6>
            </div>
            <p class="text-muted small mb-0" style="line-height: 1.7; font-size: 0.8rem;">
                Lakukan perhitungan fisik secara berkala (misalnya setiap akhir pekan atau akhir bulan) untuk mencocokkan stok fisik barang di rak dengan angka pada sistem.
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentStockValue = 0;
    let currentUnitValue = '';

    function updateProductInfo() {
        const select = document.getElementById('product_id');
        const selected = select.options[select.selectedIndex];
        const infoBox = document.getElementById('productInfoBox');

        if (selected && selected.value) {
            const stock = parseFloat(selected.getAttribute('data-stock')) || 0;
            const unit = selected.getAttribute('data-unit') || '';
            const min = selected.getAttribute('data-min') || '0';

            currentStockValue = stock;
            currentUnitValue = unit;

            document.getElementById('displayCurrentStock').textContent = Number(stock).toLocaleString('id-ID') + ' ' + unit;
            document.getElementById('displayMinStock').textContent = Number(min).toLocaleString('id-ID') + ' ' + unit;
            infoBox.classList.remove('d-none');
            calculateProjectedStock();
        } else {
            infoBox.classList.add('d-none');
            currentStockValue = 0;
            currentUnitValue = '';
        }
    }

    function calculateProjectedStock() {
        const typeEl = document.querySelector('input[name="type"]:checked');
        if (!typeEl) return;
        const type = typeEl.value;
        const amount = parseFloat(document.getElementById('amount').value) || 0;
        let projected = currentStockValue;

        if (type === 'in') {
            projected = currentStockValue + amount;
        } else if (type === 'damaged' || type === 'lost') {
            projected = Math.max(0, currentStockValue - amount);
        } else if (type === 'correction') {
            projected = amount;
        }

        const projEl = document.getElementById('displayProjectedStock');
        if (projEl) {
            projEl.textContent = Number(projected).toLocaleString('id-ID') + ' ' + currentUnitValue;
        }
    }

    function updateLabel() {
        const typeEl = document.querySelector('input[name="type"]:checked');
        if (!typeEl) return;
        const type = typeEl.value;
        const label = document.getElementById('amountLabel');
        const help = document.getElementById('amountHelp');

        if (type === 'in') {
            label.innerHTML = 'Jumlah Penambahan Stok <span class="text-danger">*</span>';
            help.textContent = 'Masukkan jumlah barang yang ingin ditambahkan ke stok saat ini.';
        } else if (type === 'damaged') {
            label.innerHTML = 'Jumlah Barang Rusak / Kadaluarsa <span class="text-danger">*</span>';
            help.textContent = 'Jumlah barang cacat/rusak yang akan dipotong dari stok.';
        } else if (type === 'lost') {
            label.innerHTML = 'Jumlah Barang Hilang / Kurang <span class="text-danger">*</span>';
            help.textContent = 'Jumlah barang hilang/selisih yang akan dipotong dari stok.';
        } else if (type === 'correction') {
            label.innerHTML = 'Jumlah Stok Fisik Riil Sekarang <span class="text-danger">*</span>';
            help.textContent = 'Stok sistem akan disesuaikan menjadi persis sama dengan angka fisik ini.';
        }

        calculateProjectedStock();
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateProductInfo();
        updateLabel();
    });
</script>
@endpush
@endsection
