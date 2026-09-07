@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Dashboard</h4>
        <p class="text-muted mb-0">Ringkasan keuangan usaha Anda</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('owner.dashboard', ['period' => 'today']) }}"
           class="btn btn-sm {{ $period === 'today' ? 'btn-success' : 'btn-outline-secondary' }}">
            Hari Ini
        </a>
        <a href="{{ route('owner.dashboard', ['period' => 'week']) }}"
           class="btn btn-sm {{ $period === 'week' ? 'btn-success' : 'btn-outline-secondary' }}">
            Minggu Ini
        </a>
        <a href="{{ route('owner.dashboard', ['period' => 'month']) }}"
           class="btn btn-sm {{ $period === 'month' ? 'btn-success' : 'btn-outline-secondary' }}">
            Bulan Ini
        </a>
        <a href="{{ route('owner.dashboard', ['period' => 'year']) }}"
           class="btn btn-sm {{ $period === 'year' ? 'btn-success' : 'btn-outline-secondary' }}">
            Tahun Ini
        </a>
        <a href="{{ route('owner.dashboard', ['period' => 'all']) }}"
           class="btn btn-sm {{ $period === 'all' ? 'btn-success' : 'btn-outline-secondary' }}">
            Semua
        </a>
        <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#customDateRange">
            <i class="fas fa-calendar-alt me-1"></i>Custom
        </button>
    </div>
</div>

<div class="collapse mb-4" id="customDateRange">
    <div class="card border shadow-sm">
        <div class="card-body">
            <form method="GET" action="{{ route('owner.dashboard') }}" class="row g-3 align-items-end">
                <input type="hidden" name="period" value="custom">
                <div class="col-md-4">
                    <label for="start_date" class="form-label fw-semibold">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="start_date" name="start_date"
                           value="{{ request('start_date') }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label fw-semibold">Tanggal Akhir</label>
                    <input type="date" class="form-control" id="end_date" name="end_date"
                           value="{{ request('end_date') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i>Terapkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-lg">
        <div class="card border shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small fw-semibold">Total Uang Masuk</p>
                        <h4 class="fw-bold mb-0 text-success">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h4>
                    </div>
                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="fas fa-arrow-up text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg">
        <div class="card border shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small fw-semibold">Total Uang Keluar</p>
                        <h4 class="fw-bold mb-0 text-danger">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h4>
                    </div>
                    <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                        <i class="fas fa-arrow-down text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg">
        <div class="card border shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small fw-semibold">Total Modal</p>
                        <h4 class="fw-bold mb-0 text-primary">Rp {{ number_format($totalCapital, 0, ',', '.') }}</h4>
                    </div>
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                        <i class="fas fa-coins text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg">
        <div class="card border shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small fw-semibold">Pengeluaran Operasional</p>
                        <h4 class="fw-bold mb-0 text-warning">Rp {{ number_format($totalOperational, 0, ',', '.') }}</h4>
                    </div>
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                        <i class="fas fa-receipt text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg">
        <div class="card border shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small fw-semibold">Laba Bersih</p>
                        <h4 class="fw-bold mb-0 {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}">
                            Rp {{ number_format($netProfit, 0, ',', '.') }}
                        </h4>
                    </div>
                    <div class="rounded-circle {{ $netProfit >= 0 ? 'bg-success' : 'bg-danger' }} bg-opacity-10 p-3">
                        <i class="fas fa-chart-line {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mb-3">
    <span class="text-muted">
        <i class="fas fa-file-alt me-1"></i>
        Total <strong>{{ $transactionCount }}</strong> transaksi tercatat pada periode ini
    </span>
</div>

<div class="card border shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0">
            <i class="fas fa-list me-2"></i>Transaksi Terbaru
        </h6>
        <a href="{{ route('owner.transactions.index') }}" class="btn btn-sm btn-outline-primary">
            Lihat Semua
        </a>
    </div>
    <div class="card-body p-0">
        @if($recentTransactions->isEmpty())
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-inbox fa-3x text-muted"></i>
                </div>
                <h6 class="text-muted">Belum ada transaksi</h6>
                <p class="text-muted small mb-3">Mulai catat transaksi keuangan usaha Anda</p>
                <a href="{{ route('owner.transactions.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i>Tambah Transaksi
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Tanggal</th>
                            <th>Tipe</th>
                            <th>Sumber</th>
                            <th class="text-end">Jumlah</th>
                            <th class="pe-3">Dicatat Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentTransactions as $transaction)
                            <tr>
                                <td class="ps-3">
                                    {{ $transaction->transaction_date->format('d/m/Y') }}
                                </td>
                                <td>
                                    @if($transaction->type === 'masuk')
                                        <span class="badge bg-success">Masuk</span>
                                    @else
                                        <span class="badge bg-danger">Keluar</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $transaction->source ?: '-' }}
                                </td>
                                <td class="text-end fw-semibold {{ $transaction->type === 'masuk' ? 'text-success' : 'text-danger' }}">
                                    {{ $transaction->type === 'masuk' ? '+' : '-' }} Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                </td>
                                <td class="pe-3 text-muted small">
                                    {{ $transaction->user->name }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
