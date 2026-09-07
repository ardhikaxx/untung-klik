<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak - Untung Klik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; background: #f8f9fa; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .error-card { text-align: center; background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 48px; max-width: 420px; width: 100%; }
        .error-icon { width: 80px; height: 80px; background: #fef2f2; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 24px; }
        .error-icon i { font-size: 36px; color: #ef4444; }
        h2 { font-weight: 700; color: #1a1d23; margin-bottom: 8px; }
        p { color: #6c757d; font-size: 14px; margin-bottom: 24px; }
        .btn-home { background: #22c55e; color: #fff; border: none; padding: 10px 24px; border-radius: 6px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
        .btn-home:hover { background: #16a34a; color: #fff; }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-icon"><i class="fas fa-lock"></i></div>
        <h2>Akses Ditolak</h2>
        <p>Anda tidak memiliki izin untuk mengakses halaman ini.</p>
        <a href="{{ auth()->check() ? (auth()->user()->isOwner() ? route('owner.dashboard') : route('karyawan.dashboard')) : route('login') }}" class="btn-home">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</body>
</html>
