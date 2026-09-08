<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 - Terjadi Kendala Sistem - Untung Klik</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        body {
            background-color: #FFFAFB;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #131515;
        }
        .error-card {
            max-width: 480px;
            width: 100%;
            background: #FFFAFB;
            border-radius: 16px;
            padding: 40px 32px;
            text-align: center;
            box-shadow: 0 4px 20px -2px rgba(19, 21, 21, 0.06);
            border: 1px solid rgba(43, 44, 40, 0.12);
        }
        .error-icon {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background-color: rgba(43, 44, 40, 0.1);
            color: #2B2C28;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-icon">
            <i class="fas fa-triangle-exclamation"></i>
        </div>
        <h3 class="fw-bold mb-2" style="color: #131515;">Terjadi Kendala Sistem (500)</h3>
        <p class="text-muted mb-4" style="font-size: 0.9rem;">
            Mohon maaf, sistem sedang mengalami kendala internal sementara. Silakan coba muat ulang atau kembali ke dashboard.
        </p>
        <div class="d-flex gap-2 justify-content-center">
            <a href="{{ url()->previous() }}" class="btn btn-uk-secondary px-3">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
            <a href="{{ auth()->check() && auth()->user()->isOwner() ? route('owner.dashboard') : (auth()->check() ? route('karyawan.dashboard') : route('login')) }}" class="btn btn-uk-primary px-4">
                <i class="fas fa-home me-1"></i>Ke Dashboard
            </a>
        </div>
        <div class="mt-4 pt-3 border-top text-muted small">
            Untung Klik &bull; {{ config('services.copyright', '© ' . date('Y') . ' Untung Klik.') }}
        </div>
    </div>
</body>
</html>
