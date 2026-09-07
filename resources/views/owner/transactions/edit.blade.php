@extends('layouts.app')

@section('title', 'Edit Transaksi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Edit Transaksi</h4>
        <p class="text-muted mb-0">Perbarui data transaksi</p>
    </div>
    <a href="{{ route('owner.transactions.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('owner.transactions.update', $transaction) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Tipe Transaksi <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="typeMasuk"
                                       value="masuk" {{ old('type', $transaction->type) === 'masuk' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold text-success" for="typeMasuk">
                                    <i class="fas fa-arrow-up me-1"></i>Uang Masuk
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="typeKeluar"
                                       value="keluar" {{ old('type', $transaction->type) === 'keluar' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold text-danger" for="typeKeluar">
                                    <i class="fas fa-arrow-down me-1"></i>Uang Keluar
                                </label>
                            </div>
                        </div>
                        @error('type')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="amount" class="form-label fw-semibold">Jumlah (Rp) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg @error('amount') is-invalid @enderror"
                               id="amount" name="amount"
                               value="{{ old('amount', number_format($transaction->amount, 0, ',', '.')) }}"
                               placeholder="0" inputmode="numeric" required>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="transaction_date" class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('transaction_date') is-invalid @enderror"
                               id="transaction_date" name="transaction_date"
                               value="{{ old('transaction_date', $transaction->transaction_date->format('Y-m-d')) }}" required>
                        @error('transaction_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="category_id" class="form-label fw-semibold">Kategori</label>
                        <select class="form-select @error('category_id') is-invalid @enderror"
                                id="category_id" name="category_id">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $transaction->category_id) == $category->id ? 'selected' : '' }}
                                    data-type="{{ $category->type }}">
                                    {{ $category->name }} ({{ ucfirst($category->type) }})
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="source" class="form-label fw-semibold">Sumber</label>
                        <input type="text" class="form-control @error('source') is-invalid @enderror"
                               id="source" name="source"
                               value="{{ old('source', $transaction->source) }}"
                               placeholder="Contoh: Penjualan produk A, Sewa tempat">
                        @error('source')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold">Keterangan</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="3"
                                  placeholder="Keterangan tambahan (opsional)">{{ old('description', $transaction->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="payment_method" class="form-label fw-semibold">Metode Pembayaran</label>
                        <select class="form-select @error('payment_method') is-invalid @enderror"
                                id="payment_method" name="payment_method">
                            <option value="">Pilih Metode</option>
                            <option value="tunai" {{ old('payment_method', $transaction->payment_method) === 'tunai' ? 'selected' : '' }}>Tunai</option>
                            <option value="transfer" {{ old('payment_method', $transaction->payment_method) === 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="ewallet" {{ old('payment_method', $transaction->payment_method) === 'ewallet' ? 'selected' : '' }}>E-Wallet</option>
                            <option value="qris" {{ old('payment_method', $transaction->payment_method) === 'qris' ? 'selected' : '' }}>QRIS</option>
                            <option value="lainnya" {{ old('payment_method', $transaction->payment_method) === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('payment_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success px-4">
                            <i class="fas fa-save me-1"></i>Perbarui Transaksi
                        </button>
                        <a href="{{ route('owner.transactions.index') }}" class="btn btn-outline-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border shadow-sm">
            <div class="card-header bg-white">
                <h6 class="fw-bold mb-0">
                    <i class="fas fa-info-circle me-2"></i>Informasi Transaksi
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted d-block">Dicatat oleh</small>
                    <span class="fw-semibold">{{ $transaction->user->name }}</span>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Tanggal pencatatan</small>
                    <span class="fw-semibold">{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div>
                    <small class="text-muted d-block">Terakhir diperbarui</small>
                    <span class="fw-semibold">{{ $transaction->updated_at->format('d/m/Y H:i') }}</span>
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

    document.querySelectorAll('input[name="type"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            var selectedType = this.value;
            var categorySelect = document.getElementById('category_id');
            var options = categorySelect.querySelectorAll('option[data-type]');

            options.forEach(function(option) {
                if (option.dataset.type === selectedType) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                    if (option.selected) {
                        categorySelect.value = '';
                    }
                }
            });
        });
    });

    document.querySelector('input[name="type"]:checked').dispatchEvent(new Event('change'));
</script>
@endpush
@endsection
