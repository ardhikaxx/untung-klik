<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Untung Klik') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: #f0f2f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #1a1d23;
        }

        .auth-wrapper {
            width: 100%;
            max-width: 420px;
        }

        .auth-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 4px 16px rgba(0, 0, 0, 0.04);
            padding: 40px 36px;
        }

        .auth-brand {
            text-align: center;
            margin-bottom: 32px;
        }

        .auth-brand .brand-icon {
            width: 52px;
            height: 52px;
            background-color: #22c55e;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            color: #ffffff;
            font-size: 22px;
        }

        .auth-brand h1 {
            font-size: 22px;
            font-weight: 700;
            color: #1a1d23;
            margin-bottom: 4px;
            letter-spacing: -0.3px;
        }

        .auth-brand p {
            font-size: 13px;
            color: #6b7280;
            line-height: 1.5;
        }

        .auth-form .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }

        .input-group {
            border-radius: 10px;
            overflow: hidden;
            border: 1.5px solid #e5e7eb;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            background: #ffffff;
        }

        .input-group:focus-within {
            border-color: #22c55e;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
        }

        .input-group .input-icon {
            width: 46px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f9fafb;
            border-right: 1.5px solid #e5e7eb;
            color: #9ca3af;
            font-size: 15px;
        }

        .input-group:focus-within .input-icon {
            background: #f0fdf4;
            color: #22c55e;
            border-right-color: #bbf7d0;
        }

        .input-group .form-control {
            border: none;
            border-radius: 0;
            padding: 12px 14px;
            font-size: 14px;
            font-weight: 500;
            color: #1a1d23;
            background: transparent;
            height: 46px;
        }

        .input-group .form-control:focus {
            box-shadow: none;
            outline: none;
        }

        .input-group .form-control::placeholder {
            color: #c4c8cf;
            font-weight: 400;
        }

        .btn-toggle-pin {
            background: none;
            border: none;
            color: #9ca3af;
            padding: 0 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            font-size: 15px;
            transition: color 0.2s ease;
        }

        .btn-toggle-pin:hover {
            color: #22c55e;
        }

        .btn-primary-custom {
            width: 100%;
            height: 46px;
            border: none;
            border-radius: 10px;
            background-color: #22c55e;
            color: #ffffff;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.1s ease;
            letter-spacing: 0.2px;
        }

        .btn-primary-custom:hover {
            background-color: #16a34a;
        }

        .btn-primary-custom:active {
            transform: scale(0.985);
        }

        .auth-link {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #6b7280;
        }

        .auth-link a {
            color: #22c55e;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .auth-link a:hover {
            color: #16a34a;
            text-decoration: underline;
        }

        .text-danger-custom {
            font-size: 12px;
            color: #ef4444;
            margin-top: 6px;
            display: block;
        }

        @media (max-width: 480px) {
            .auth-card {
                padding: 28px 22px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        @yield('content')
        <footer class="text-center mt-3 text-muted" style="font-size: 0.8125rem;">
            <p class="mb-0">{{ config('services.copyright') }}</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: {!! json_encode(session('success')) !!},
            timer: 3500,
            timerProgressBar: true,
            confirmButtonColor: '#22c55e',
            confirmButtonText: 'Tutup'
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: {!! json_encode(session('error')) !!},
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Mengerti'
        });
    </script>
    @endif

    @if(session('warning'))
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian',
            text: {!! json_encode(session('warning')) !!},
            confirmButtonColor: '#f59e0b',
            confirmButtonText: 'Mengerti'
        });
    </script>
    @endif

    @stack('scripts')
</body>
</html>
