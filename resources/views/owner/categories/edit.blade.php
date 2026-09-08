@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('owner.categories.index') }}" class="btn btn-uk-outline btn-sm me-3">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h4 class="fw-bold mb-1" style="color: var(--uk-dark);">Edit Kategori</h4>
                <p class="text-muted small mb-0">Perbarui rincian kategori arus kas usaha</p>
            </div>
        </div>

        <div class="card uk-card border-0">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('owner.categories.update', $category) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="name" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Nama Kategori <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            class="form-control form-control-lg @error('name') is-invalid @enderror"
                            id="name"
                            name="name"
                            value="{{ old('name', $category->name) }}"
                            placeholder="Masukkan nama kategori"
                            required
                            autofocus
                        >
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="type" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Tipe Arus Kas <span class="text-danger">*</span></label>
                        <select
                            class="form-select @error('type') is-invalid @enderror"
                            id="type"
                            name="type"
                            required
                        >
                            <option value="">Pilih Tipe Kategori</option>
                            <option value="masuk" {{ old('type', $category->type) === 'masuk' ? 'selected' : '' }}>Masuk (Pemasukan Kas)</option>
                            <option value="keluar" {{ old('type', $category->type) === 'keluar' ? 'selected' : '' }}>Keluar (Pengeluaran Kas)</option>
                            <option value="operasional" {{ old('type', $category->type) === 'operasional' ? 'selected' : '' }}>Operasional (Beban Usaha)</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <input type="hidden" name="is_active" value="0">
                        <div class="form-check form-switch p-3 rounded-3 border bg-white d-flex align-items-center justify-content-between">
                            <label class="form-check-label fw-semibold text-dark mb-0 cursor-pointer" for="is_active">
                                Status Aktif Kategori
                                <span class="d-block text-muted fw-normal small">Kategori aktif dapat dipilih saat mencatat transaksi baru</span>
                            </label>
                            <input class="form-check-input ms-3"
                                   type="checkbox"
                                   role="switch"
                                   id="is_active"
                                   name="is_active"
                                   value="1"
                                   {{ old('is_active', $category->is_active ? '1' : '0') === '1' ? 'checked' : '' }}
                                   style="width: 2.75rem; height: 1.4rem;">
                        </div>
                        @error('is_active')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex align-items-center gap-2 pt-2">
                        <button type="submit" class="btn btn-uk-primary px-4">
                            <i class="fas fa-save me-1"></i>Perbarui Kategori
                        </button>
                        <a href="{{ route('owner.categories.index') }}" class="btn btn-uk-outline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
