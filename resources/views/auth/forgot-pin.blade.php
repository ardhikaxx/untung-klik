@extends('layouts.auth')

@section('title', 'Lupa PIN')

@section('content')
<div class="auth-card">
    <div class="auth-brand">
        <div class="brand-icon">
            <i class="fas fa-key"></i>
        </div>
        <h1>Lupa PIN</h1>
        <p>Masukkan nomor telepon terdaftar untuk mengatur ulang PIN Anda</p>
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

    <form method="POST" action="{{ route('forgot-pin.post') }}" class="auth-form">
        @csrf

        <div class="mb-4">
            <label for="phone" class="form-label">Nomor Telepon</label>
            <div class="input-group">
                <span class="input-icon">
                    <i class="fas fa-phone"></i>
                </span>
                <input
                    type="tel"
                    class="form-control @error('phone') is-invalid @enderror"
                    id="phone"
                    name="phone"
                    value="{{ old('phone') }}"
                    placeholder="Contoh: 08123456789"
                    required
                    autofocus
                >
            </div>
            @error('phone')
                <span class="text-danger-custom">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn-primary-custom">
            <i class="fas fa-paper-plane me-1.5"></i>Kirim Link Reset PIN
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
    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Gagal Memproses',
            html: '<ul class="text-start mb-0 ps-3">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>',
            confirmButtonColor: '#0E4749',
            confirmButtonText: 'Periksa Kembali'
        });
    @endif
</script>
@endpush
@endsection
