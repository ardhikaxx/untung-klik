@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex align-items-center mb-4">
            <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 56px; height: 56px; background-color: #22c55e;">
                <span class="text-white fw-bold" style="font-size: 1.25rem;">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
            </div>
            <div>
                <h5 class="fw-bold mb-0">{{ $user->name }}</h5>
                <span class="text-muted small">{{ ucfirst($user->role) }}</span>
            </div>
        </div>

        <!-- Informasi Profil -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-user-circle me-2 text-success"></i>Informasi Profil</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('karyawan.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label small fw-semibold">Nama Lengkap</label>
                            <input
                                type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                id="name"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                required
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="username" class="form-label small fw-semibold">Username</label>
                            <input
                                type="text"
                                class="form-control"
                                id="username"
                                value="{{ $user->username }}"
                                readonly
                                disabled
                            >
                            <div class="form-text">Username tidak dapat diubah.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label small fw-semibold">Telepon</label>
                            <input
                                type="text"
                                class="form-control @error('phone') is-invalid @enderror"
                                id="phone"
                                name="phone"
                                value="{{ old('phone', $user->phone) }}"
                            >
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Ganti PIN -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-lock me-2 text-warning"></i>Ganti PIN</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('karyawan.profile.pin') }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="current_pin" class="form-label small fw-semibold">PIN Saat Ini</label>
                            <input
                                type="password"
                                class="form-control @error('current_pin') is-invalid @enderror"
                                id="current_pin"
                                name="current_pin"
                                maxlength="4"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                required
                            >
                            @error('current_pin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="new_pin" class="form-label small fw-semibold">PIN Baru</label>
                            <input
                                type="password"
                                class="form-control @error('new_pin') is-invalid @enderror"
                                id="new_pin"
                                name="new_pin"
                                maxlength="4"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                required
                            >
                            @error('new_pin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="new_pin_confirmation" class="form-label small fw-semibold">Konfirmasi PIN Baru</label>
                            <input
                                type="password"
                                class="form-control"
                                id="new_pin_confirmation"
                                name="new_pin_confirmation"
                                maxlength="4"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                required
                            >
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-key me-2"></i>Ganti PIN
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
