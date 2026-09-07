@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1">Laporan Keuangan</h4>
        <p class="text-muted mb-0">{{ $periodLabel }}</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('owner.export.pdf', array_merge(['period' => $period], $startDate && $endDate ? ['start_date' => $startDate, 'end_date' => $endDate] : [])) }}"
           class="btn btn-danger btn-sm">
            <i class="fas fa-file-pdf me-1"></i> Export PDF
        </a>
        <a href="{{ route('owner.export.excel', array_merge(['period' => $period], $startDate && $endDate ? ['start_date' => $startDate, 'end_date' => $endDate] : [])) }}"
           class="btn btn-success btn-sm">
            <i class="fas fa-file-excel me-1"></i> Export Excel
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('owner.reports.index') }}" id="periodForm">
            <div class="d-flex flex-wrap gap-2 align-items-end">
                <div class="d-flex gap-1 flex-wrap">
                    <a href="{{ route('owner.reports.index', ['period' => 'today']) }}"
                       class="btn btn-sm {{ $period === 'today' ? 'btn-success' : 'btn-outline-secondary' }}">
                        Hari Ini
                    </a>
                    <a href="{{ route('owner.reports.index', ['period' => 'week']) }}"
                       class="btn btn-sm {{ $period === 'week' ? 'btn-success' : 'btn-outline-secondary' }}">
                        Minggu Ini
                    </a>
                    <a href="{{ route('owner.reports.index', ['period' => 'month']) }}"
                       class="btn btn-sm {{ $period === 'month' ? 'btn-success' : 'btn-outline-secondary' }}">
                        Bulan Ini
                    </a>
                    <a href="{{ route('owner.reports.index', ['period' => 'year']) }}"
                       class="btn btn-sm {{ $period === 'year' ? 'btn-success' : 'btn-outline-secondary' }}">
                        Tahun Ini
                    </a>
                    <button type="button"
                            class="btn btn-sm {{ $period === 'custom' ? 'btn-success' : 'btn-outline-secondary' }}"
                            id="btnCustomPeriod">
                        Custom
                    </button>
                </div>

                <div class="d-flex gap-2 flex-wrap ms-md-auto" id="customDateRange"
                     style="{{ $period === 'custom' ? '' : 'display: none;' }}">
                    <input type="date" name="start_date" id="startDate"
                           class="form-control form-control-sm"
                           value="{{ $startDate ?? '' }}"
                           style="width: 160px;">
                    <span class="align-self-center">-</span>
                    <input type="date" name="end_date" id="endDate"
                           class="form-control form-control-sm"
                           value="{{ $endDate ?? '' }}"
                           style="width: 160px;">
                    <input type="hidden" name="period" value="custom">
                    <button type="submit" class="btn btn-sm btn-success">
                        <i class="fas fa-search me-1"></i>Terapkan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-4 col-xl-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2"
                     style="width: 44px; height: 44px;">
                    <i class="fas fa-arrow-down text-success"></i>
                </div>
                <h6 class="text-muted mb-1" style="font-size: 0.75rem;">Total Pemasukan</h6>
                <h5 class="fw-bold text-success mb-0">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h5>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-4 col-xl-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="rounded-circle bg-danger bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2"
                     style="width: 44px; height: 44px;">
                    <i class="fas fa-arrow-up text-danger"></i>
                </div>
                <h6 class="text-muted mb-1" style="font-size: 0.75rem;">Total Pengeluaran</h6>
                <h5 class="fw-bold text-danger mb-0">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h5>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-4 col-xl-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2"
                     style="width: 44px; height: 44px;">
                    <i class="fas fa-coins text-primary"></i>
                </div>
                <h6 class="text-muted mb-1" style="font-size: 0.75rem;">Total Modal</h6>
                <h5 class="fw-bold text-primary mb-0">Rp {{ number_format($totalCapital, 0, ',', '.') }}</h5>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-4 col-xl-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2"
                     style="width: 44px; height: 44px;">
                    <i class="fas fa-receipt text-warning"></i>
                </div>
                <h6 class="text-muted mb-1" style="font-size: 0.75rem;">Pengeluaran Operasional</h6>
                <h5 class="fw-bold text-warning mb-0">Rp {{ number_format($totalOperational, 0, ',', '.') }}</h5>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-4 col-xl-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="rounded-circle bg-info bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2"
                     style="width: 44px; height: 44px;">
                    <i class="fas fa-chart-line text-info"></i>
                </div>
                <h6 class="text-muted mb-1" style="font-size: 0.75rem;">Laba Bersih</h6>
                <h5 class="fw-bold {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }} mb-0">
                    Rp {{ number_format($netProfit, 0, ',', '.') }}
                </h5>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-4 col-xl-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="rounded-circle bg-secondary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2"
                     style="width: 44px; height: 44px;">
                    <i class="fas fa-list-ol text-secondary"></i>
                </div>
                <h6 class="text-muted mb-1" style="font-size: 0.75rem;">Jumlah Transaksi</h6>
                <h5 class="fw-bold text-secondary mb-0">{{ $transactionCount }}</h5>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom">
        <h6 class="fw-bold mb-0"><i class="fas fa-table me-2"></i>Detail Transaksi</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="width: 50px;">No</th>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Kategori</th>
                        <th>Sumber/Keterangan</th>
                        <th class="text-end pe-3">Jumlah</th>
                        <th>User</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $index => $transaction)
                        <tr>
                            <td class="ps-3">{{ $index + 1 }}</td>
                            <td>{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                            <td>
                                @if ($transaction->type === 'masuk')
                                    <span class="badge bg-success-subtle text-success">Pemasukan</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger">Pengeluaran</span>
                                @endif
                            </td>
                            <td>{{ $transaction->category->name ?? '-' }}</td>
                            <td>
                                @if ($transaction->type === 'masuk')
                                    {{ $transaction->source ?? '-' }}
                                @else
                                    {{ $transaction->description ?? '-' }}
                                @endif
                            </td>
                            <td class="text-end pe-3 fw-semibold {{ $transaction->type === 'masuk' ? 'text-success' : 'text-danger' }}">
                                {{ $transaction->type === 'masuk' ? '+' : '-' }} Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                            </td>
                            <td>{{ $transaction->user->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                Tidak ada data transaksi untuk periode ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .bg-success-subtle { background-color: rgba(25, 135, 84, 0.1) !important; }
    .bg-danger-subtle { background-color: rgba(220, 53, 69, 0.1) !important; }
</style>
@endpush

@push('scripts')
<script>
    document.getElementById('btnCustomPeriod').addEventListener('click', function () {
        const customRange = document.getElementById('customDateRange');
        const isHidden = customRange.style.display === 'none';
        customRange.style.display = isHidden ? 'flex' : 'none';
    });

    const startDate = document.getElementById('startDate');
    const endDate = document.getElementById('endDate');
    if (startDate && endDate) {
        startDate.addEventListener('change', function () {
            if (this.value) {
                endDate.min = this.value;
            }
        });
        endDate.addEventListener('change', function () {
            if (this.value) {
                startDate.max = this.value;
            }
        });
    }
</script>
@endpush
