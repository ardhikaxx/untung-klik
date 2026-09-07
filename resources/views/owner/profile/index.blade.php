@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-1">Profil Saya</h4>
    <p class="text-muted mb-0">Kelola informasi profil dan PIN Anda</p>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom">
                <h6 class="fw-bold mb-0"><i class="fas fa-user-circle me-2"></i>Informasi Profil</h6>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ auth()->user()->isOwner() ? route('owner.profile.update') : route('karyawan.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name', $user->name) }}"
                               placeholder="Masukkan nama lengkap"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label fw-semibold">Username</label>
                        <input type="text"
                               class="form-control"
                               id="username"
                               value="{{ $user->username }}"
                               readonly
                               disabled>
                        <div class="form-text">Username tidak dapat diubah.</div>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label fw-semibold">Nomor Telepon</label>
                        <input type="text"
                               class="form-control @error('phone') is-invalid @enderror"
                               id="phone"
                               name="phone"
                               value="{{ old('phone', $user->phone) }}"
                               placeholder="Masukkan nomor telepon">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Role</label>
                        <input type="text"
                               class="form-control"
                               value="{{ ucfirst($user->role) }}"
                               readonly
                               disabled>
                        <div class="form-text">Role ditentukan oleh owner.</div>
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h6 class="fw-bold mb-0"><i class="fas fa-lock me-2"></i>Ganti PIN</h6>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ auth()->user()->isOwner() ? route('owner.profile.pin') : route('karyawan.profile.pin') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="current_pin" class="form-label fw-semibold">PIN Saat Ini <span class="text-danger">*</span></label>
                        <input type="password"
                               class="form-control @error('current_pin') is-invalid @enderror"
                               id="current_pin"
                               name="current_pin"
                               placeholder="4 digit PIN"
                               maxlength="4"
                               inputmode="numeric"
                               pattern="[0-9]*"
                               required>
                        @error('current_pin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="new_pin" class="form-label fw-semibold">PIN Baru <span class="text-danger">*</span></label>
                        <input type="password"
                               class="form-control @error('new_pin') is-invalid @enderror"
                               id="new_pin"
                               name="new_pin"
                               placeholder="4 digit PIN baru"
                               maxlength="4"
                               inputmode="numeric"
                               pattern="[0-9]*"
                               required>
                        @error('new_pin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="new_pin_confirmation" class="form-label fw-semibold">Konfirmasi PIN Baru <span class="text-danger">*</span></label>
                        <input type="password"
                               class="form-control @error('new_pin_confirmation') is-invalid @enderror"
                               id="new_pin_confirmation"
                               name="new_pin_confirmation"
                               placeholder="Ulangi PIN baru"
                               maxlength="4"
                               inputmode="numeric"
                               pattern="[0-9]*"
                               required>
                        @error('new_pin_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-key me-1"></i> Perbarui PIN
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: '{{ $errors->first() }}',
            confirmButtonColor: '#22c55e',
            confirmButtonText: 'OK'
        });
    @endif
</script>
@endpush
