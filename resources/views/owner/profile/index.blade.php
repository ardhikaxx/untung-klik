@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<!-- Page Header -->
<div class="uk-page-header">
    <div>
        <h2 class="uk-page-title">Profil & Keamanan Akun</h2>
        <p class="uk-page-subtitle">Kelola informasi identitas profil usaha dan perbarui PIN keamanan akun Anda.</p>
    </div>
</div>

<!-- Header User Hero Pod -->
<div class="card uk-card border-0 mb-4">
    <div class="card-body p-4 d-flex align-items-center flex-wrap gap-3">
        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold fs-4" style="width: 58px; height: 58px; background-color: var(--uk-dark); color: #FFFAFB;">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div>
            <h4 class="fw-bold mb-1 text-dark">{{ $user->name }}</h4>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="badge badge-uk-primary">{{ ucfirst($user->role) }}</span>
                <span class="text-muted small font-monospace"><i class="fas fa-at me-1"></i>{{ $user->username }}</span>
                @if(isset($business) && $business)
                    <span class="badge badge-uk-accent text-dark fw-medium"><i class="fas fa-store me-1"></i>{{ $business->name }}</span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Informasi Profil -->
    <div class="col-lg-7">
        <div class="card uk-card border-0 h-100">
            <div class="card-header bg-transparent py-3 border-bottom d-flex align-items-center gap-2">
                <i class="fas fa-id-card text-primary"></i>
                <h6 class="fw-bold mb-0 text-dark">Informasi Profil Pengguna</h6>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ auth()->user()->isOwner() ? route('owner.profile.update') : route('karyawan.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold small text-dark mb-1">Nama Lengkap <span class="text-danger">*</span></label>
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
                        <label for="username" class="form-label fw-semibold small text-dark mb-1">Username Login</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-lock"></i></span>
                            <input type="text"
                                   class="form-control border-start-0 bg-light"
                                   id="username"
                                   value="{{ $user->username }}"
                                   readonly
                                   disabled>
                        </div>
                        <div class="form-text small" style="font-size: 0.72rem;">Username bersifat permanen untuk login akun.</div>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label fw-semibold small text-dark mb-1">Nomor WhatsApp / Telepon</label>
                        <input type="text"
                               class="form-control @error('phone') is-invalid @enderror"
                               id="phone"
                               name="phone"
                               value="{{ old('phone', $user->phone) }}"
                               placeholder="Contoh: 081234567890">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-dark mb-1">Hak Akses (Role)</label>
                        <input type="text"
                               class="form-control bg-light"
                               value="{{ ucfirst($user->role) }} (Akses Penuh Pemilik Usaha)"
                               readonly
                               disabled>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="btn btn-sm btn-uk-primary rounded-pill px-4 shadow-xs fw-bold">
                            <i class="fas fa-save me-1.5"></i>Simpan Perubahan Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Ganti PIN 4-Digit -->
    <div class="col-lg-5">
        <div class="card uk-card border-0 h-100">
            <div class="card-header bg-transparent py-3 border-bottom d-flex align-items-center gap-2">
                <i class="fas fa-shield-halved text-primary"></i>
                <h6 class="fw-bold mb-0 text-dark">Ganti 4-Digit PIN Masuk</h6>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ auth()->user()->isOwner() ? route('owner.profile.pin') : route('karyawan.profile.pin') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="current_pin" class="form-label fw-semibold small text-dark mb-1">PIN Saat Ini <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password"
                                   class="form-control @error('current_pin') is-invalid @enderror"
                                   id="current_pin"
                                   name="current_pin"
                                   placeholder="4 digit angka lama"
                                   maxlength="4"
                                   inputmode="numeric"
                                   pattern="[0-9]*"
                                   required>
                            <button type="button" class="btn btn-uk-outline" onclick="togglePinVisibility('current_pin', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('current_pin')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="new_pin" class="form-label fw-semibold small text-dark mb-1">PIN Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password"
                                   class="form-control @error('new_pin') is-invalid @enderror"
                                   id="new_pin"
                                   name="new_pin"
                                   placeholder="4 digit angka baru"
                                   maxlength="4"
                                   inputmode="numeric"
                                   pattern="[0-9]*"
                                   required>
                            <button type="button" class="btn btn-uk-outline" onclick="togglePinVisibility('new_pin', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('new_pin')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="new_pin_confirmation" class="form-label fw-semibold small text-dark mb-1">Konfirmasi PIN Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password"
                                   class="form-control @error('new_pin_confirmation') is-invalid @enderror"
                                   id="new_pin_confirmation"
                                   name="new_pin_confirmation"
                                   placeholder="Ketik ulang 4 digit PIN baru"
                                   maxlength="4"
                                   inputmode="numeric"
                                   pattern="[0-9]*"
                                   required>
                            <button type="button" class="btn btn-uk-outline" onclick="togglePinVisibility('new_pin_confirmation', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('new_pin_confirmation')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="btn btn-sm btn-uk-dark w-100 rounded-pill py-2.5 fw-bold shadow-xs">
                            <i class="fas fa-key me-1.5"></i>Perbarui PIN Keamanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePinVisibility(inputId, btn) {
        var input = document.getElementById(inputId);
        var icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Gagal Memperbarui',
            text: '{{ $errors->first() }}',
            confirmButtonColor: '#339989',
            confirmButtonText: 'Tutup',
            background: '#FFFAFB'
        });
    @endif
</script>
@endpush
