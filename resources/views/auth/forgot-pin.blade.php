@extends('layouts.auth')

@section('content')
<div class="auth-card">
    <div class="auth-brand">
        <div class="brand-icon">
            <i class="fas fa-key"></i>
        </div>
        <h1>Lupa PIN</h1>
        <p>Masukkan nomor telepon yang terdaftar untuk mereset PIN Anda</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success d-flex align-items-center mb-3 py-2 px-3 small border-0 shadow-sm" style="background-color: #ecfdf5; color: #065f46; border-left: 4px solid #10b981 !important; border-radius: 8px;">
            <i class="fas fa-check-circle me-2 text-success"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger d-flex align-items-center mb-3 py-2 px-3 small border-0 shadow-sm" style="background-color: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444 !important; border-radius: 8px;">
            <i class="fas fa-exclamation-circle me-2 text-danger"></i>
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
                    placeholder="Masukkan nomor telepon"
                    required
                    autofocus
                >
            </div>
            @error('phone')
                <span class="text-danger-custom">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn-primary-custom">
            Kirim
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
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Periksa Kembali'
        });
    @endif
</script>
@endpush
@endsection
