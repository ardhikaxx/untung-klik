@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
<div class="mb-4">
    <a href="{{ route('owner.categories.index') }}" class="text-decoration-none text-muted">
        <i class="fas fa-arrow-left me-1"></i>Kembali ke Daftar Kategori
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                <h5 class="fw-bold mb-0">Tambah Kategori Baru</h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('owner.categories.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            class="form-control @error('name') is-invalid @enderror"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Masukkan nama kategori"
                            required
                            autofocus
                        >
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="type" class="form-label">Tipe <span class="text-danger">*</span></label>
                        <select
                            class="form-select @error('type') is-invalid @enderror"
                            id="type"
                            name="type"
                            required
                        >
                            <option value="">-- Pilih Tipe --</option>
                            <option value="masuk" {{ old('type') === 'masuk' ? 'selected' : '' }}>Masuk</option>
                            <option value="keluar" {{ old('type') === 'keluar' ? 'selected' : '' }}>Keluar</option>
                            <option value="operasional" {{ old('type') === 'operasional' ? 'selected' : '' }}>Operasional</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success px-4">
                            <i class="fas fa-save me-2"></i>Simpan
                        </button>
                        <a href="{{ route('owner.categories.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
