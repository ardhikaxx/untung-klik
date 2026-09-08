@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')
<div class="auth-card">
    <div class="auth-brand">
        <div class="brand-icon">
            <i class="fas fa-wallet"></i>
        </div>
        <h1>Untung Klik</h1>
        <p>Buku kas digital untuk usaha yang lebih tertata</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success d-flex align-items-center mb-3 py-2 px-3 small border-0 shadow-xs" style="background-color: rgba(51, 153, 137, 0.1); color: var(--uk-primary); border-left: 4px solid var(--uk-primary) !important; border-radius: 8px;">
            <i class="fas fa-check-circle me-2" style="color: var(--uk-primary);"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger d-flex align-items-center mb-3 py-2 px-3 small border-0 shadow-xs" style="background-color: rgba(43, 44, 40, 0.08); color: var(--uk-dark-secondary); border-left: 4px solid var(--uk-dark-secondary) !important; border-radius: 8px;">
            <i class="fas fa-exclamation-circle me-2" style="color: var(--uk-dark-secondary);"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

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
                <button type="button" class="btn-toggle-pin" id="togglePin" tabindex="-1" aria-label="Toggle PIN Visibility">
                    <i class="fas fa-eye" id="pinIcon"></i>
                </button>
            </div>
            @error('pin')
                <span class="text-danger-custom">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn-primary-custom">
            <i class="fas fa-right-to-bracket me-1.5"></i>Masuk
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
            html: '<ul class="text-start mb-0 ps-3">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>',
            confirmButtonColor: '#339989',
            confirmButtonText: 'Periksa Kembali'
        });
    @endif
</script>
@endpush
@endsection
