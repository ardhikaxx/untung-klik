@extends('layouts.app')

@section('title', 'Grafik Keuangan')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1">Grafik Keuangan</h4>
        <p class="text-muted mb-0">Visualisasi data keuangan usaha Anda</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-sm btn-outline-secondary btn-period active" data-period="30days">
            30 Hari
        </button>
        <button class="btn btn-sm btn-outline-secondary btn-period" data-period="6months">
            6 Bulan
        </button>
        <button class="btn btn-sm btn-outline-secondary btn-period" data-period="12months">
            12 Bulan
        </button>
    </div>
</div>

<div class="row g-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <canvas id="financeChart" style="width: 100%; height: 400px;"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
@endpush

@push('scripts')
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
                                borderColor: '#22c55e',
                                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.3,
                                pointRadius: 3,
                                pointHoverRadius: 6,
                                pointBackgroundColor: '#22c55e',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                            },
                            {
                                label: 'Pengeluaran',
                                data: data.expense,
                                borderColor: '#ef4444',
                                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.3,
                                pointRadius: 3,
                                pointHoverRadius: 6,
                                pointBackgroundColor: '#ef4444',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                            },
                            {
                                label: 'Laba Bersih',
                                data: data.profit,
                                borderColor: '#3b82f6',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.3,
                                pointRadius: 3,
                                pointHoverRadius: 6,
                                pointBackgroundColor: '#3b82f6',
                                pointBorderColor: '#fff',
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
                                        family: "'Inter', sans-serif"
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: '#1a1d23',
                                titleFont: { size: 12 },
                                bodyFont: { size: 11 },
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
                                        family: "'Inter', sans-serif"
                                    },
                                    color: '#9ca3af',
                                    maxRotation: 45,
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#f3f4f6',
                                    drawBorder: false,
                                },
                                ticks: {
                                    font: {
                                        size: 11,
                                        family: "'Inter', sans-serif"
                                    },
                                    color: '#9ca3af',
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
                    b.classList.remove('active', 'btn-success');
                    b.classList.add('btn-outline-secondary');
                });
                this.classList.add('active', 'btn-success');
                this.classList.remove('btn-outline-secondary');

                loadChart(this.dataset.period);
            });
        });
    });
</script>
@endpush
