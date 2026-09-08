@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 col-xl-7">
        <!-- Page Header -->
        <div class="uk-page-header mb-4">
            <div>
                <h2 class="uk-page-title">Edit Pengguna: {{ $user->name }}</h2>
                <p class="uk-page-subtitle">Perbarui data profil staf, peran akses operasional, atau status keaktifan akun.</p>
            </div>
            <a href="{{ route('owner.users.index') }}" class="btn btn-sm btn-uk-outline rounded-pill px-3">
                <i class="fas fa-arrow-left me-1.5"></i>Kembali
            </a>
        </div>

        <div class="card uk-card border-0">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('owner.users.update', $user) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold small text-dark mb-1">Nama Lengkap Staf <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name', $user->name) }}"
                               placeholder="Nama lengkap staf"
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
                                   value="{{ old('username', $user->username) }}"
                                   placeholder="Username"
                                   required>
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
                                   value="{{ old('phone', $user->phone) }}"
                                   placeholder="08xxxxxxxxxx">
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
                            <option value="owner" {{ old('role', $user->role) === 'owner' ? 'selected' : '' }}>Owner (Pemilik Usaha Penuh)</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin (Pengelola Toko)</option>
                            <option value="karyawan" {{ old('role', $user->role) === 'karyawan' ? 'selected' : '' }}>Karyawan (Kasir POS & Stok)</option>
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
                                <span class="d-block text-muted fw-normal small">Pengguna aktif dapat melakukan login ke kasir menggunakan PIN.</span>
                            </label>
                            <input class="form-check-input ms-3"
                                   type="checkbox"
                                   role="switch"
                                   id="is_active"
                                   name="is_active"
                                   value="1"
                                   {{ old('is_active', $user->is_active ? '1' : '0') === '1' ? 'checked' : '' }}
                                   style="width: 2.75rem; height: 1.4rem;">
                        </div>
                        @error('is_active')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('owner.users.index') }}" class="btn btn-sm btn-uk-secondary rounded-pill px-3">Batal</a>
                        <button type="submit" class="btn btn-sm btn-uk-primary rounded-pill px-4 shadow-xs fw-bold">
                            <i class="fas fa-save me-1.5"></i>Perbarui Pengguna
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
