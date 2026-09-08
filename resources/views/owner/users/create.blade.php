@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 col-xl-7">
        <!-- Page Header -->
        <div class="uk-page-header mb-4">
            <div>
                <h2 class="uk-page-title">Tambah Pengguna Baru</h2>
                <p class="uk-page-subtitle">Buat akun untuk kasir toko atau staf operasional usaha Anda.</p>
            </div>
            <a href="{{ route('owner.users.index') }}" class="btn btn-sm btn-uk-outline rounded-pill px-3">
                <i class="fas fa-arrow-left me-1.5"></i>Kembali
            </a>
        </div>

        <div class="card uk-card border-0">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('owner.users.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold small text-dark mb-1">Nama Lengkap Staf <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="Contoh: Siti Rahma"
                               required
                               autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="username" class="form-label fw-semibold small text-dark mb-1">Username Login <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('username') is-invalid @enderror"
                                   id="username"
                                   name="username"
                                   value="{{ old('username') }}"
                                   placeholder="Contoh: kasir1"
                                   required>
                            <div class="form-text small" style="font-size: 0.72rem;">Digunakan staf saat memasukkan identitas login.</div>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-semibold small text-dark mb-1">Nomor WhatsApp / Telepon</label>
                            <input type="text"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   id="phone"
                                   name="phone"
                                   value="{{ old('phone') }}"
                                   placeholder="Contoh: 081234567890">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label fw-semibold small text-dark mb-1">Peran Akses (Role) <span class="text-danger">*</span></label>
                        <select class="form-select @error('role') is-invalid @enderror"
                                id="role"
                                name="role"
                                required>
                            <option value="">-- Pilih Peran Akses --</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin (Pengelola Toko)</option>
                            <option value="karyawan" {{ old('role', 'karyawan') === 'karyawan' ? 'selected' : '' }}>Karyawan (Kasir POS & Kelola Stok)</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <input type="hidden" name="is_active" value="0">
                        <div class="form-check form-switch p-3 rounded-3 border bg-light d-flex align-items-center justify-content-between">
                            <label class="form-check-label fw-semibold text-dark mb-0 cursor-pointer" for="is_active">
                                Status Akun Aktif
                                <span class="d-block text-muted fw-normal small">Staf dapat login ke kasir saat status akun aktif.</span>
                            </label>
                            <input class="form-check-input ms-3"
                                   type="checkbox"
                                   role="switch"
                                   id="is_active"
                                   name="is_active"
                                   value="1"
                                   {{ old('is_active', '1') === '1' ? 'checked' : '' }}
                                   style="width: 2.75rem; height: 1.4rem;">
                        </div>
                        @error('is_active')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Info PIN Default -->
                    <div class="p-3 rounded-3 mb-4" style="background: rgba(125, 226, 209, 0.15); border: 1px solid rgba(51, 153, 137, 0.3);">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-key text-primary"></i>
                            <div class="small text-dark">
                                <strong>PIN Bawaan Awal:</strong> Akun baru otomatis dibuatkan PIN awal <span class="badge badge-uk-primary">2222</span> dan staf dapat langsung mengubahnya saat pertama login.
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('owner.users.index') }}" class="btn btn-sm btn-uk-secondary rounded-pill px-3">Batal</a>
                        <button type="submit" class="btn btn-sm btn-uk-primary rounded-pill px-4 shadow-xs fw-bold">
                            <i class="fas fa-save me-1.5"></i>Simpan Pengguna
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
