@extends('layouts.app')

@section('title', 'Grafik Keuangan')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--uk-dark);">Grafik Arus Kas & Tren Keuangan</h4>
        <p class="text-muted small mb-0">Visualisasi data tren pemasukan, pengeluaran, dan laba bersih usaha Anda</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-sm btn-uk-primary btn-period active" data-period="30days">
            <i class="fas fa-calendar-day me-1.5"></i>30 Hari Terakhir
        </button>
        <button class="btn btn-sm btn-uk-outline btn-period" data-period="6months">
            <i class="fas fa-calendar-week me-1.5"></i>6 Bulan
        </button>
        <button class="btn btn-sm btn-uk-outline btn-period" data-period="12months">
            <i class="fas fa-calendar me-1.5"></i>12 Bulan (1 Tahun)
        </button>
    </div>
</div>

<div class="row g-4">
    <div class="col-12">
        <div class="card uk-card border-0">
            <div class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0" style="color: var(--uk-dark);">
                    <i class="fas fa-chart-area me-2 text-primary"></i>Tren Arus Kas Usaha
                </h6>
                <div class="d-flex align-items-center gap-3 small">
                    <span class="d-inline-flex align-items-center gap-1"><span style="width: 10px; height: 10px; border-radius: 50%; background: #95C623; display: inline-block;"></span> Pemasukan</span>
                    <span class="d-inline-flex align-items-center gap-1"><span style="width: 10px; height: 10px; border-radius: 50%; background: #E55812; display: inline-block;"></span> Pengeluaran</span>
                    <span class="d-inline-flex align-items-center gap-1"><span style="width: 10px; height: 10px; border-radius: 50%; background: #0E4749; display: inline-block;"></span> Laba Bersih</span>
                </div>
            </div>
            <div class="card-body p-4">
                <div style="position: relative; height: 420px; width: 100%;">
                    <canvas id="financeChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    let financeChart = null;

    function formatRupiah(value) {
        return 'Rp ' + value.toLocaleString('id-ID');
    }

    function loadChart(period) {
        const url = '{{ route("owner.charts.data") }}?period=' + period;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (financeChart) {
                    financeChart.destroy();
                }

                const ctx = document.getElementById('financeChart').getContext('2d');
                financeChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [
                            {
                                label: 'Pemasukan',
                                data: data.income,
                                borderColor: '#95C623',
                                backgroundColor: 'rgba(149, 198, 35, 0.12)',
                                borderWidth: 2.5,
                                fill: true,
                                tension: 0.35,
                                pointRadius: 3,
                                pointHoverRadius: 6,
                                pointBackgroundColor: '#95C623',
                                pointBorderColor: '#FFFFFF',
                                pointBorderWidth: 2,
                            },
                            {
                                label: 'Pengeluaran',
                                data: data.expense,
                                borderColor: '#E55812',
                                backgroundColor: 'rgba(229, 88, 18, 0.12)',
                                borderWidth: 2.5,
                                fill: true,
                                tension: 0.35,
                                pointRadius: 3,
                                pointHoverRadius: 6,
                                pointBackgroundColor: '#E55812',
                                pointBorderColor: '#FFFFFF',
                                pointBorderWidth: 2,
                            },
                            {
                                label: 'Laba Bersih',
                                data: data.profit,
                                borderColor: '#0E4749',
                                backgroundColor: 'rgba(14, 71, 73, 0.08)',
                                borderWidth: 2.5,
                                fill: true,
                                tension: 0.35,
                                pointRadius: 3,
                                pointHoverRadius: 6,
                                pointBackgroundColor: '#002626',
                                pointBorderColor: '#FFFFFF',
                                pointBorderWidth: 2,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    padding: 20,
                                    font: {
                                        size: 12,
                                        family: "'Plus Jakarta Sans', sans-serif",
                                        weight: '600'
                                    },
                                    color: '#002626'
                                }
                            },
                            tooltip: {
                                backgroundColor: '#002626',
                                titleFont: { size: 12, family: "'Plus Jakarta Sans', sans-serif", weight: '600' },
                                bodyFont: { size: 11, family: "'Plus Jakarta Sans', sans-serif" },
                                padding: 12,
                                cornerRadius: 8,
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': ' + formatRupiah(context.parsed.y);
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        size: 11,
                                        family: "'Plus Jakarta Sans', sans-serif"
                                    },
                                    color: '#002626',
                                    maxRotation: 45,
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 38, 38, 0.06)',
                                    drawBorder: false,
                                },
                                ticks: {
                                    font: {
                                        size: 11,
                                        family: "'Plus Jakarta Sans', sans-serif"
                                    },
                                    color: '#002626',
                                    callback: function(value) {
                                        if (value >= 1000000) {
                                            return 'Rp ' + (value / 1000000).toFixed(0) + 'jt';
                                        } else if (value >= 1000) {
                                            return 'Rp ' + (value / 1000).toFixed(0) + 'rb';
                                        }
                                        return 'Rp ' + value;
                                    }
                                }
                            }
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Error loading chart data:', error);
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        loadChart('30days');

        document.querySelectorAll('.btn-period').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.btn-period').forEach(function(b) {
                    b.classList.remove('active', 'btn-uk-primary');
                    b.classList.add('btn-uk-outline');
                });
                this.classList.add('active', 'btn-uk-primary');
                this.classList.remove('btn-uk-outline');

                loadChart(this.dataset.period);
            });
        });
    });
</script>
@endpush
