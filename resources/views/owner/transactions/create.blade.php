@extends('layouts.app')

@section('title', 'Tambah Transaksi')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--uk-dark);">Catat Transaksi Baru</h4>
        <p class="text-muted small mb-0">Catat transaksi penerimaan uang masuk atau pengeluaran operasional bisnis</p>
    </div>
    <a href="{{ route('owner.transactions.index') }}" class="btn btn-uk-outline">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card uk-card border-0">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('owner.transactions.store') }}">
                    @csrf

                    <!-- Tipe Transaksi (Segmented Radio) -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Tipe Transaksi <span class="text-danger">*</span></label>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="border rounded-3 p-3 d-flex align-items-center gap-3 w-100 cursor-pointer transition-all" for="typeMasuk" style="cursor: pointer;" id="labelTypeMasuk">
                                    <input class="form-check-input mt-0" type="radio" name="type" id="typeMasuk"
                                           value="masuk" {{ old('type', 'masuk') === 'masuk' ? 'checked' : '' }}>
                                    <div>
                                        <div class="fw-bold text-success"><i class="fas fa-arrow-down me-1"></i> Uang Masuk</div>
                                        <small class="text-muted" style="font-size: 0.8rem;">Penerimaan kas, penjualan non-stok, fee</small>
                                    </div>
                                </label>
                            </div>
                            <div class="col-sm-6">
                                <label class="border rounded-3 p-3 d-flex align-items-center gap-3 w-100 cursor-pointer transition-all" for="typeKeluar" style="cursor: pointer;" id="labelTypeKeluar">
                                    <input class="form-check-input mt-0" type="radio" name="type" id="typeKeluar"
                                           value="keluar" {{ old('type') === 'keluar' ? 'checked' : '' }}>
                                    <div>
                                        <div class="fw-bold text-danger"><i class="fas fa-arrow-up me-1"></i> Uang Keluar</div>
                                        <small class="text-muted" style="font-size: 0.8rem;">Pengeluaran, beban belanja, operasional</small>
                                    </div>
                                </label>
                            </div>
                        </div>
                        @error('type')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="amount" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Nominal Transaksi (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text fw-bold bg-white text-dark border-end-0">Rp</span>
                            <input type="text" class="form-control form-control-lg border-start-0 ps-0 fw-bold @error('amount') is-invalid @enderror"
                                   id="amount" name="amount" value="{{ old('amount') }}"
                                   placeholder="0" inputmode="numeric" required style="font-size: 1.5rem; color: var(--uk-dark);">
                        </div>
                        @error('amount')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="transaction_date" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Tanggal Transaksi <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('transaction_date') is-invalid @enderror"
                                   id="transaction_date" name="transaction_date"
                                   value="{{ old('transaction_date', date('Y-m-d')) }}" required>
                            @error('transaction_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="category_id" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Kategori Kas</label>
                            <select class="form-select @error('category_id') is-invalid @enderror"
                                    id="category_id" name="category_id">
                                <option value="">Pilih Kategori Transaksi</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}
                                        data-type="{{ $category->type }}">
                                        {{ $category->name }} ({{ ucfirst($category->type) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="source" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Sumber / Pihak Terkait</label>
                        <input type="text" class="form-control @error('source') is-invalid @enderror"
                               id="source" name="source" value="{{ old('source') }}"
                               placeholder="Contoh: Pembayaran Jasa Konsultasi, Toko Plastik Jaya, dsb.">
                        @error('source')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Keterangan Rinci</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="3"
                                  placeholder="Catatan tambahan mengenai transaksi ini (opsional)">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="payment_method" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Metode Pembayaran</label>
                        <select class="form-select @error('payment_method') is-invalid @enderror"
                                id="payment_method" name="payment_method">
                            <option value="">Pilih Metode Pembayaran</option>
                            <option value="tunai" {{ old('payment_method', 'tunai') === 'tunai' ? 'selected' : '' }}>Tunai / Cash</option>
                            <option value="transfer" {{ old('payment_method') === 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="ewallet" {{ old('payment_method') === 'ewallet' ? 'selected' : '' }}>E-Wallet (GoPay, OVO, ShopeePay)</option>
                            <option value="qris" {{ old('payment_method') === 'qris' ? 'selected' : '' }}>QRIS</option>
                            <option value="lainnya" {{ old('payment_method') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('payment_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex align-items-center gap-2 pt-2">
                        <button type="submit" class="btn btn-uk-primary px-4">
                            <i class="fas fa-check-circle me-1"></i>Simpan Transaksi
                        </button>
                        <a href="{{ route('owner.transactions.index') }}" class="btn btn-uk-outline">
                            <i class="fas fa-times me-1"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card uk-card border-0 mb-4">
            <div class="card-header bg-transparent py-3 border-bottom">
                <h6 class="fw-bold mb-0" style="color: var(--uk-dark);">
                    <i class="fas fa-lightbulb me-2 text-warning"></i>Panduan Buku Kas
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-4">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge badge-uk-success"><i class="fas fa-arrow-down me-1"></i>Uang Masuk</span>
                    </div>
                    <p class="text-muted small mb-0">
                        Catat pemasukan kas di luar transaksi kasir produk (misal: jasa servis, sewa aset, pendapatan komisi, atau uang muka).
                    </p>
                </div>

                <div class="mb-4">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge badge-uk-danger"><i class="fas fa-arrow-up me-1"></i>Uang Keluar</span>
                    </div>
                    <p class="text-muted small mb-0">
                        Catat seluruh pengeluaran kas usaha seperti pembelian perlengkapan, konsumsi harian, listrik, ongkos kirim, dan biaya tak terduga.
                    </p>
                </div>

                <div class="p-3 rounded-3" style="background: rgba(125, 226, 209, 0.15); border: 1px dashed var(--uk-primary);">
                    <h6 class="fw-bold small mb-1" style="color: var(--uk-dark);"><i class="fas fa-info-circle me-1 text-primary"></i> Otomatisasi Kasir</h6>
                    <p class="text-muted small mb-0" style="font-size: 0.8rem;">
                        Penjualan kasir yang berhasil otomatis dicatat ke dalam buku kas sebagai <strong>Uang Masuk</strong> dan stok barang langsung terpotong.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('amount').addEventListener('input', function(e) {
        var value = e.target.value.replace(/[^0-9]/g, '');
        if (value) {
            e.target.value = parseInt(value).toLocaleString('id-ID');
        } else {
            e.target.value = '';
        }
    });

    function syncTypeRadio() {
        var isMasuk = document.getElementById('typeMasuk').checked;
        var labelMasuk = document.getElementById('labelTypeMasuk');
        var labelKeluar = document.getElementById('labelTypeKeluar');
        
        if (isMasuk) {
            labelMasuk.style.borderColor = '#339989';
            labelMasuk.style.backgroundColor = 'rgba(51, 153, 137, 0.1)';
            labelKeluar.style.borderColor = 'var(--uk-border)';
            labelKeluar.style.backgroundColor = 'transparent';
        } else {
            labelKeluar.style.borderColor = '#2B2C28';
            labelKeluar.style.backgroundColor = 'rgba(43, 44, 40, 0.08)';
            labelMasuk.style.borderColor = 'var(--uk-border)';
            labelMasuk.style.backgroundColor = 'transparent';
        }

        var selectedType = isMasuk ? 'masuk' : 'keluar';
        var categorySelect = document.getElementById('category_id');
        var options = categorySelect.querySelectorAll('option[data-type]');

        options.forEach(function(option) {
            if (option.dataset.type === selectedType) {
                option.style.display = '';
            } else {
                option.style.display = 'none';
            }
        });
    }

    document.querySelectorAll('input[name="type"]').forEach(function(radio) {
        radio.addEventListener('change', syncTypeRadio);
    });

    syncTypeRadio();
</script>
@endpush
@endsection
