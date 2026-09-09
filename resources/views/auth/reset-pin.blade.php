@extends('layouts.auth')

@section('title', 'Atur Ulang PIN')

@section('content')
<div class="auth-card">
    <div class="auth-brand">
        <div class="brand-icon">
            <i class="fas fa-shield-halved"></i>
        </div>
        <h1>Atur Ulang PIN</h1>
        <p>Buat 4 digit PIN baru untuk akun Anda</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success d-flex align-items-center mb-3 py-2 px-3 small border-0 shadow-xs" style="background-color: rgba(149, 198, 35, 0.15); color: #2e4206; border-left: 4px solid var(--uk-accent) !important; border-radius: 8px;">
            <i class="fas fa-check-circle me-2" style="color: var(--uk-accent);"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger d-flex align-items-center mb-3 py-2 px-3 small border-0 shadow-xs" style="background-color: rgba(229, 88, 18, 0.12); color: var(--uk-orange); border-left: 4px solid var(--uk-orange) !important; border-radius: 8px;">
            <i class="fas fa-exclamation-circle me-2" style="color: var(--uk-orange);"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    <form method="POST" action="{{ route('reset-pin.post') }}" class="auth-form">
        @csrf
        <input type="hidden" name="token" value="{{ $token ?? '' }}">
        <input type="hidden" name="phone" value="{{ $phone ?? '' }}">

        <div class="mb-3">
            <label for="pin" class="form-label">PIN Baru</label>
            <div class="input-group">
                <span class="input-icon">
                    <i class="fas fa-lock"></i>
                </span>
                <input
                    type="password"
                    class="form-control @error('pin') is-invalid @enderror"
                    id="pin"
                    name="pin"
                    placeholder="4 digit PIN baru"
                    maxlength="4"
                    inputmode="numeric"
                    pattern="[0-9]*"
                    required
                    autofocus
                >
                <button type="button" class="btn-toggle-pin" id="togglePin" tabindex="-1" aria-label="Toggle PIN Visibility">
                    <i class="fas fa-eye" id="pinIcon"></i>
                </button>
            </div>
            @error('pin')
                <span class="text-danger-custom">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label for="pin_confirmation" class="form-label">Konfirmasi PIN Baru</label>
            <div class="input-group">
                <span class="input-icon">
                    <i class="fas fa-lock"></i>
                </span>
                <input
                    type="password"
                    class="form-control @error('pin_confirmation') is-invalid @enderror"
                    id="pin_confirmation"
                    name="pin_confirmation"
                    placeholder="Ulangi 4 digit PIN baru"
                    maxlength="4"
                    inputmode="numeric"
                    pattern="[0-9]*"
                    required
                >
                <button type="button" class="btn-toggle-pin" id="togglePinConfirm" tabindex="-1" aria-label="Toggle Confirm PIN Visibility">
                    <i class="fas fa-eye" id="pinConfirmIcon"></i>
                </button>
            </div>
            @error('pin_confirmation')
                <span class="text-danger-custom">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn-primary-custom">
            <i class="fas fa-key me-1.5"></i>Simpan PIN Baru
        </button>
    </form>

    <div class="auth-link">
        <a href="{{ route('login') }}">
            <i class="fas fa-arrow-left me-1" style="font-size: 11px;"></i> Kembali ke Login
        </a>
    </div>
</div>

@push('scripts')
<script>
    function setupToggle(buttonId, inputId, iconId) {
        document.getElementById(buttonId).addEventListener('click', function () {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    }

    setupToggle('togglePin', 'pin', 'pinIcon');
    setupToggle('togglePinConfirm', 'pin_confirmation', 'pinConfirmIcon');

    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Gagal Memperbarui PIN',
            html: '<ul class="text-start mb-0 ps-3">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>',
            confirmButtonColor: '#0E4749',
            confirmButtonText: 'Periksa Kembali'
        });
    @endif
</script>
@endpush
@endsection
