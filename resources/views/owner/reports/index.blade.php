@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--uk-dark);">Laporan Keuangan</h4>
        <p class="text-muted small mb-0"><i class="fas fa-calendar-alt me-1 text-primary"></i>Periode: <strong>{{ $periodLabel }}</strong></p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('owner.export.pdf', array_merge(['period' => $period], $startDate && $endDate ? ['start_date' => $startDate, 'end_date' => $endDate] : [])) }}"
           class="btn btn-sm btn-uk-outline text-danger">
            <i class="fas fa-file-pdf me-1"></i> Export PDF
        </a>
        <a href="{{ route('owner.export.excel', array_merge(['period' => $period], $startDate && $endDate ? ['start_date' => $startDate, 'end_date' => $endDate] : [])) }}"
           class="btn btn-sm btn-uk-outline text-success">
            <i class="fas fa-file-excel me-1"></i> Export Excel
        </a>
    </div>
</div>

<!-- Navigasi Tab Laporan: Kas vs Penjualan -->
<div class="d-flex gap-2 mb-4 pb-1">
    <a class="btn btn-sm btn-uk-primary" href="{{ route('owner.reports.index', ['period' => $period]) }}">
        <i class="fas fa-book me-1"></i>Buku Kas & Laba Bersih
    </a>
    <a class="btn btn-sm btn-uk-outline" href="{{ route('owner.reports.sales', ['period' => $period]) }}">
        <i class="fas fa-shopping-bag me-1"></i>Penjualan & Produk Terlaris
    </a>
</div>

<!-- Filter Periode -->
<div class="card uk-card border-0 mb-4">
    <div class="card-body p-3 p-md-4">
        <form method="GET" action="{{ route('owner.reports.index') }}" id="periodForm">
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <div class="d-flex gap-1 flex-wrap">
                    <a href="{{ route('owner.reports.index', ['period' => 'today']) }}"
                       class="btn btn-sm {{ $period === 'today' ? 'btn-uk-primary' : 'btn-uk-outline' }}">
                        Hari Ini
                    </a>
                    <a href="{{ route('owner.reports.index', ['period' => 'week']) }}"
                       class="btn btn-sm {{ $period === 'week' ? 'btn-uk-primary' : 'btn-uk-outline' }}">
                        Minggu Ini
                    </a>
                    <a href="{{ route('owner.reports.index', ['period' => 'month']) }}"
                       class="btn btn-sm {{ $period === 'month' ? 'btn-uk-primary' : 'btn-uk-outline' }}">
                        Bulan Ini
                    </a>
                    <a href="{{ route('owner.reports.index', ['period' => 'year']) }}"
                       class="btn btn-sm {{ $period === 'year' ? 'btn-uk-primary' : 'btn-uk-outline' }}">
                        Tahun Ini
                    </a>
                    <button type="button"
                            class="btn btn-sm {{ $period === 'custom' ? 'btn-uk-primary' : 'btn-uk-outline' }}"
                            id="btnCustomPeriod">
                        <i class="fas fa-calendar-alt me-1.5"></i>Custom
                    </button>
                </div>

                <div class="d-flex gap-2 flex-wrap ms-md-auto" id="customDateRange"
                     style="{{ $period === 'custom' ? '' : 'display: none;' }}">
                    <input type="date" name="start_date" id="startDate"
                           class="form-control form-control-sm"
                           value="{{ $startDate ?? '' }}"
                           style="width: 150px;">
                    <span class="align-self-center text-muted">-</span>
                    <input type="date" name="end_date" id="endDate"
                           class="form-control form-control-sm"
                           value="{{ $endDate ?? '' }}"
                           style="width: 150px;">
                    <input type="hidden" name="period" value="custom">
                    <button type="submit" class="btn btn-sm btn-uk-primary">
                        <i class="fas fa-search me-1"></i>Terapkan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-4 col-xl-2">
        <div class="card uk-stat-card border-0 h-100">
            <div class="card-body text-center p-3 d-flex flex-column justify-content-between">
                <div>
                    <div class="uk-stat-icon mx-auto mb-2" style="background: rgba(51, 153, 137, 0.12); color: #339989;">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                    <div class="uk-stat-label mb-1" style="font-size: 0.72rem;">Total Pemasukan</div>
                    <div class="uk-stat-value text-success" style="font-size: 1.15rem;">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-4 col-xl-2">
        <div class="card uk-stat-card border-0 h-100">
            <div class="card-body text-center p-3 d-flex flex-column justify-content-between">
                <div>
                    <div class="uk-stat-icon mx-auto mb-2" style="background: rgba(43, 44, 40, 0.1); color: #2B2C28;">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                    <div class="uk-stat-label mb-1" style="font-size: 0.72rem;">Total Pengeluaran</div>
                    <div class="uk-stat-value text-danger" style="font-size: 1.15rem;">Rp {{ number_format($totalExpense, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-4 col-xl-2">
        <div class="card uk-stat-card border-0 h-100">
            <div class="card-body text-center p-3 d-flex flex-column justify-content-between">
                <div>
                    <div class="uk-stat-icon mx-auto mb-2" style="background: rgba(51, 153, 137, 0.15); color: var(--uk-primary);">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="uk-stat-label mb-1" style="font-size: 0.72rem;">Total Modal</div>
                    <div class="uk-stat-value" style="font-size: 1.15rem; color: var(--uk-primary);">Rp {{ number_format($totalCapital, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-4 col-xl-2">
        <div class="card uk-stat-card border-0 h-100">
            <div class="card-body text-center p-3 d-flex flex-column justify-content-between">
                <div>
                    <div class="uk-stat-icon mx-auto mb-2" style="background: rgba(125, 226, 209, 0.25); color: #131515;">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div class="uk-stat-label mb-1" style="font-size: 0.72rem;">Beban Operasional</div>
                    <div class="uk-stat-value text-warning" style="font-size: 1.15rem;">Rp {{ number_format($totalOperational, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-4 col-xl-2">
        <div class="card uk-stat-card border-0 h-100">
            <div class="card-body text-center p-3 d-flex flex-column justify-content-between">
                <div>
                    <div class="uk-stat-icon mx-auto mb-2" style="background: {{ $netProfit >= 0 ? 'rgba(51, 153, 137, 0.15)' : 'rgba(43, 44, 40, 0.1)' }}; color: {{ $netProfit >= 0 ? '#339989' : '#2B2C28' }};">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="uk-stat-label mb-1" style="font-size: 0.72rem;">Laba Bersih</div>
                    <div class="uk-stat-value {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}" style="font-size: 1.15rem;">
                        Rp {{ number_format($netProfit, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-4 col-xl-2">
        <div class="card uk-stat-card border-0 h-100">
            <div class="card-body text-center p-3 d-flex flex-column justify-content-between">
                <div>
                    <div class="uk-stat-icon mx-auto mb-2" style="background: rgba(19, 21, 21, 0.1); color: var(--uk-dark);">
                        <i class="fas fa-list-ol"></i>
                    </div>
                    <div class="uk-stat-label mb-1" style="font-size: 0.72rem;">Jumlah Transaksi</div>
                    <div class="uk-stat-value" style="font-size: 1.15rem; color: var(--uk-dark);">{{ $transactionCount }} <span class="fs-6 fw-normal text-muted">Data</span></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detail Table -->
<div class="card uk-card border-0">
    <div class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0" style="color: var(--uk-dark);"><i class="fas fa-table me-2 text-primary"></i>Detail Transaksi Periode Ini</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table uk-table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 60px;">No</th>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Kategori</th>
                        <th>Sumber/Keterangan</th>
                        <th class="text-end">Jumlah</th>
                        <th class="pe-4">Pencatat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $index => $transaction)
                        <tr>
                            <td class="ps-4 text-muted small">{{ ($transactions->firstItem() ?? 1) + $index }}</td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $transaction->transaction_date->format('d/m/Y') }}</div>
                            </td>
                            <td>
                                @if ($transaction->type === 'masuk')
                                    <span class="badge badge-uk-success"><i class="fas fa-arrow-down me-1"></i>Pemasukan</span>
                                @else
                                    <span class="badge badge-uk-danger"><i class="fas fa-arrow-up me-1"></i>Pengeluaran</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-uk-accent text-dark fw-medium">{{ $transaction->category->name ?? '-' }}</span>
                            </td>
                            <td>
                                @if ($transaction->type === 'masuk')
                                    <span class="fw-medium text-dark">{{ $transaction->source ?? '-' }}</span>
                                @else
                                    <span class="text-dark">{{ $transaction->description ?? '-' }}</span>
                                @endif
                            </td>
                            <td class="text-end fw-bold {{ $transaction->type === 'masuk' ? 'text-success' : 'text-danger' }}">
                                {{ $transaction->type === 'masuk' ? '+' : '-' }} Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                            </td>
                            <td class="pe-4">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white small" style="width: 26px; height: 26px; background-color: var(--uk-dark-secondary); font-size: 0.7rem;">
                                        {{ strtoupper(substr($transaction->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <span class="small text-muted">{{ $transaction->user->name ?? '-' }}</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="mb-3">
                                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background: rgba(51, 153, 137, 0.1); color: var(--uk-primary);">
                                        <i class="fas fa-inbox fa-lg"></i>
                                    </div>
                                </div>
                                <h6 class="fw-bold mb-1" style="color: var(--uk-dark);">Tidak Ada Data Transaksi</h6>
                                <p class="text-muted small mb-0">Belum ada aktivitas transaksi yang tercatat pada periode ini</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($transactions->hasPages())
        <div class="card-footer bg-transparent border-top d-flex justify-content-between align-items-center flex-wrap gap-2 px-4 py-3">
            <small class="text-muted">
                Menampilkan {{ $transactions->firstItem() }} - {{ $transactions->lastItem() }} dari {{ $transactions->total() }} transaksi
            </small>
            <div>
                {{ $transactions->withQueryString()->links() }}
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('btnCustomPeriod')?.addEventListener('click', function () {
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
