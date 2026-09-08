@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('owner.categories.index') }}" class="btn btn-uk-outline btn-sm me-3">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h4 class="fw-bold mb-1" style="color: var(--uk-dark);">Tambah Kategori Baru</h4>
                <p class="text-muted small mb-0">Klasifikasikan akun buku kas masuk, keluar, atau operasional</p>
            </div>
        </div>

        <div class="card uk-card border-0">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('owner.categories.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Nama Kategori <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            class="form-control form-control-lg @error('name') is-invalid @enderror"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Contoh: Pendapatan Jasa, Listrik & Air, dsb"
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
                            <option value="masuk" {{ old('type') === 'masuk' ? 'selected' : '' }}>Masuk (Pemasukan Kas)</option>
                            <option value="keluar" {{ old('type') === 'keluar' ? 'selected' : '' }}>Keluar (Pengeluaran Kas)</option>
                            <option value="operasional" {{ old('type') === 'operasional' ? 'selected' : '' }}>Operasional (Beban Usaha)</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex align-items-center gap-2 pt-2">
                        <button type="submit" class="btn btn-uk-primary px-4">
                            <i class="fas fa-check-circle me-1"></i>Simpan Kategori
                        </button>
                        <a href="{{ route('owner.categories.index') }}" class="btn btn-uk-outline">
                            <i class="fas fa-times me-1"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
