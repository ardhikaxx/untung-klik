@extends('layouts.auth')

@section('content')
<div class="auth-card">
    <div class="auth-brand">
        <div class="brand-icon">
            <i class="fas fa-wallet"></i>
        </div>
        <h1>Untung Klik</h1>
        <p>Buku kas digital untuk usaha yang lebih tertata</p>
    </div>

    <form method="POST" action="{{ route('login.post') }}" class="auth-form">
        @csrf

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <div class="input-group">
                <span class="input-icon">
                    <i class="fas fa-user"></i>
                </span>
                <input
                    type="text"
                    class="form-control @error('username') is-invalid @enderror"
                    id="username"
                    name="username"
                    value="{{ old('username') }}"
                    placeholder="Masukkan username"
                    required
                    autofocus
                >
            </div>
            @error('username')
                <span class="text-danger-custom">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label for="pin" class="form-label">PIN</label>
            <div class="input-group">
                <span class="input-icon">
                    <i class="fas fa-lock"></i>
                </span>
                <input
                    type="password"
                    class="form-control @error('pin') is-invalid @enderror"
                    id="pin"
                    name="pin"
                    value="{{ old('pin') }}"
                    placeholder="4 digit PIN"
                    maxlength="4"
                    inputmode="numeric"
                    pattern="[0-9]*"
                    required
                >
                <button type="button" class="btn-toggle-pin" id="togglePin" tabindex="-1">
                    <i class="fas fa-eye" id="pinIcon"></i>
                </button>
            </div>
            @error('pin')
                <span class="text-danger-custom">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn-primary-custom">
            Masuk
        </button>
    </form>

    <div class="auth-link">
        <a href="{{ route('forgot-pin') }}">Lupa PIN?</a>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('togglePin').addEventListener('click', function () {
        const pinInput = document.getElementById('pin');
        const pinIcon = document.getElementById('pinIcon');

        if (pinInput.type === 'password') {
            pinInput.type = 'text';
            pinIcon.classList.remove('fa-eye');
            pinIcon.classList.add('fa-eye-slash');
        } else {
            pinInput.type = 'password';
            pinIcon.classList.remove('fa-eye-slash');
            pinIcon.classList.add('fa-eye');
        }
    });

    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Gagal Masuk',
            text: '{{ $errors->first() }}',
            confirmButtonColor: '#22c55e',
            confirmButtonText: 'OK',
            customClass: { popup: 'swal2-popup-custom' }
        });
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal Masuk',
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
        });
    @endif
</script>
@endpush
@endsection
