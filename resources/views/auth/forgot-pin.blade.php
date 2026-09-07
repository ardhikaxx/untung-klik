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
            title: 'Gagal Mengirim',
            text: '{{ $errors->first() }}',
            confirmButtonColor: '#22c55e',
            confirmButtonText: 'OK'
        });
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal Mengirim',
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
