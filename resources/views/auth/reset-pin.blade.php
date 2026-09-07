@extends('layouts.auth')

@section('content')
<div class="auth-card">
    <div class="auth-brand">
        <div class="brand-icon">
            <i class="fas fa-shield-halved"></i>
        </div>
        <h1>Atur Ulang PIN</h1>
        <p>Buat PIN baru untuk akun Anda</p>
    </div>

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
                <button type="button" class="btn-toggle-pin" id="togglePin" tabindex="-1">
                    <i class="fas fa-eye" id="pinIcon"></i>
                </button>
            </div>
            @error('pin')
                <span class="text-danger-custom">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label for="pin_confirmation" class="form-label">Konfirmasi PIN</label>
            <div class="input-group">
                <span class="input-icon">
                    <i class="fas fa-lock"></i>
                </span>
                <input
                    type="password"
                    class="form-control @error('pin_confirmation') is-invalid @enderror"
                    id="pin_confirmation"
                    name="pin_confirmation"
                    placeholder="Ulangi 4 digit PIN"
                    maxlength="4"
                    inputmode="numeric"
                    pattern="[0-9]*"
                    required
                >
                <button type="button" class="btn-toggle-pin" id="togglePinConfirm" tabindex="-1">
                    <i class="fas fa-eye" id="pinConfirmIcon"></i>
                </button>
            </div>
            @error('pin_confirmation')
                <span class="text-danger-custom">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn-primary-custom">
            Perbarui PIN
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
            title: 'Gagal Memperbarui',
            text: '{{ $errors->first() }}',
            confirmButtonColor: '#22c55e',
            confirmButtonText: 'OK'
        });
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal Memperbarui',
            text: '{{ session('error') }}',
            confirmButtonColor: '#22c55e',
            confirmButtonText: 'OK'
        });
    @endif

    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session('success') }}',
            confirmButtonColor: '#22c55e',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = '{{ route("login") }}';
        });
    @endif
</script>
@endpush
@endsection
