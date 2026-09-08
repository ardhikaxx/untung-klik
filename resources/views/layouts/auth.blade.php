<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Masuk ke Akun') - {{ config('app.name', 'Untung Klik') }}</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CDN & Font Awesome 6 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Untung Klik Global CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        body {
            background-color: #FFFAFB;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            color: #131515;
        }

        .auth-wrapper {
            width: 100%;
            max-width: 420px;
        }

        .auth-card {
            background: #FFFAFB;
            border-radius: 16px;
            border: 1px solid rgba(43, 44, 40, 0.12);
            box-shadow: 0 4px 20px -2px rgba(19, 21, 21, 0.06), 0 2px 6px -1px rgba(19, 21, 21, 0.03);
            padding: 38px 34px;
        }

        .auth-brand {
            text-align: center;
            margin-bottom: 28px;
        }

        .auth-brand .brand-icon {
            width: 52px;
            height: 52px;
            background-color: #339989;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
            color: #FFFAFB;
            font-size: 22px;
        }

        .auth-brand h1 {
            font-size: 22px;
            font-weight: 800;
            color: #131515;
            margin-bottom: 4px;
            letter-spacing: -0.025em;
        }

        .auth-brand p {
            font-size: 13px;
            color: #2B2C28;
            opacity: 0.75;
            line-height: 1.5;
            margin: 0;
        }

        .auth-form .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #131515;
            margin-bottom: 6px;
        }

        .input-group {
            border-radius: 10px;
            overflow: hidden;
            border: 1.5px solid rgba(43, 44, 40, 0.15);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            background: #FFFAFB;
        }

        .input-group:focus-within {
            border-color: #339989;
            box-shadow: 0 0 0 3px rgba(51, 153, 137, 0.15);
        }

        .input-group .input-icon {
            width: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #FFFAFB;
            border-right: 1.5px solid rgba(43, 44, 40, 0.15);
            color: #2B2C28;
            font-size: 14px;
        }

        .input-group:focus-within .input-icon {
            background: rgba(125, 226, 209, 0.15);
            color: #339989;
            border-right-color: #339989;
        }

        .input-group .form-control {
            border: none;
            border-radius: 0;
            padding: 11px 14px;
            font-size: 14px;
            font-weight: 500;
            color: #131515;
            background: transparent;
            height: 46px;
        }

        .input-group .form-control:focus {
            box-shadow: none;
            outline: none;
        }

        .input-group .form-control::placeholder {
            color: rgba(43, 44, 40, 0.45);
            font-weight: 400;
        }

        .btn-toggle-pin {
            background: none;
            border: none;
            color: #2B2C28;
            padding: 0 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            font-size: 15px;
            transition: color 0.2s ease;
        }

        .btn-toggle-pin:hover {
            color: #339989;
        }

        .btn-primary-custom {
            width: 100%;
            height: 46px;
            border: none;
            border-radius: 10px;
            background-color: #339989;
            color: #FFFAFB;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.1s ease;
            letter-spacing: 0.02em;
        }

        .btn-primary-custom:hover {
            background-color: #2B2C28;
            color: #FFFAFB;
        }

        .btn-primary-custom:active {
            transform: scale(0.99);
        }

        .auth-link {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #2B2C28;
            opacity: 0.8;
        }

        .auth-link a {
            color: #339989;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .auth-link a:hover {
            color: #2B2C28;
            text-decoration: underline;
        }

        .text-danger-custom {
            font-size: 12px;
            color: #2B2C28;
            margin-top: 6px;
            display: block;
        }

        @media (max-width: 480px) {
            .auth-card {
                padding: 28px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        @yield('content')
        <footer class="text-center mt-3 text-muted" style="font-size: 0.78rem;">
            <p class="mb-0">{{ config('services.copyright', '© ' . date('Y') . ' Untung Klik. Hak Cipta Dilindungi.') }}</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: {!! json_encode(session('success')) !!},
            timer: 3000,
            timerProgressBar: true,
            confirmButtonColor: '#339989',
            confirmButtonText: 'Tutup',
            background: '#FFFAFB'
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: {!! json_encode(session('error')) !!},
            confirmButtonColor: '#339989',
            confirmButtonText: 'Mengerti',
            background: '#FFFAFB'
        });
    </script>
    @endif

    @if(session('warning'))
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian',
            text: {!! json_encode(session('warning')) !!},
            confirmButtonColor: '#339989',
            confirmButtonText: 'Mengerti',
            background: '#FFFAFB'
        });
    </script>
    @endif

    @stack('scripts')
</body>
</html>
