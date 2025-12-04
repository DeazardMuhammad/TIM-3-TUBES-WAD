@extends('layouts.app')

@section('title', 'Statistik Dashboard')

@section('content')
<div class="container-fluid py-4">
    <h2 class="mb-4"><i class="bi bi-graph-up"></i> Statistik Dashboard</h2>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Total Pengguna</h6>
                            <h2 class="mb-0">{{ $totalUsers }}</h2>
                        </div>
                        <i class="bi bi-people" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Barang Hilang</h6>
                            <h2 class="mb-0">{{ $totalLostItems }}</h2>
                        </div>
                        <i class="bi bi-exclamation-triangle" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Barang Ditemukan</h6>
                            <h2 class="mb-0">{{ $totalFoundItems }}</h2>
                        </div>
                        <i class="bi bi-check-circle" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Total Klaim</h6>
                            <h2 class="mb-0">{{ $totalClaims }}</h2>
                        </div>
                        <i class="bi bi-clipboard-check" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Verification & Claims Stats -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Status Verifikasi Laporan</h5>
                </div>
                <div class="card-body">
                    <canvas id="verificationChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Status Klaim</h5>
                </div>
                <div class="card-body">
                    <canvas id="claimsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Trend -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Tren Laporan (6 Bulan Terakhir)</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Items by Category -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Laporan per Kategori</h5>
                </div>
                <div class="card-body">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Tambahan</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Pending Verifikasi</h6>
                        <h3 class="text-warning">{{ $pendingVerification }}</h3>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <h6>Rata-rata Waktu Penyelesaian</h6>
                        <h3 class="text-info">{{ $avgCompletionTime }} jam</h3>
                    </div>
                    <hr>
                    <div class="mb-0">
                        <h6>Approved Items</h6>
                        <h3 class="text-success">{{ $approvedItems }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ratings by Category -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Rating Rata-rata per Kategori</h5>
                </div>
                <div class="card-body">
                    <canvas id="ratingChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Verification Chart
const verificationCtx = document.getElementById('verificationChart').getContext('2d');
new Chart(verificationCtx, {
    type: 'doughnut',
    data: {
        labels: ['Pending', 'Approved', 'Rejected'],
        datasets: [{
            data: [{{ $pendingVerification }}, {{ $approvedItems }}, {{ $rejectedItems }}],
            backgroundColor: ['#ffc107', '#28a745', '#dc3545']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Claims Chart
const claimsCtx = document.getElementById('claimsChart').getContext('2d');
new Chart(claimsCtx, {
    type: 'doughnut',
    data: {
        labels: ['Pending', 'Accepted', 'Rejected'],
        datasets: [{
            data: [{{ $pendingClaims }}, {{ $acceptedClaims }}, {{ $rejectedClaims }}],
            backgroundColor: ['#ffc107', '#28a745', '#dc3545']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Monthly Trend Chart
const monthlyCtx = document.getElementById('monthlyTrendChart').getContext('2d');
new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode(array_column($monthlyData, 'month')) !!},
        datasets: [{
            label: 'Barang Hilang',
            data: {!! json_encode(array_column($monthlyData, 'lost')) !!},
            borderColor: '#dc3545',
            backgroundColor: 'rgba(220, 53, 69, 0.1)',
            fill: true,
            tension: 0.4
        }, {
            label: 'Barang Ditemukan',
            data: {!! json_encode(array_column($monthlyData, 'found')) !!},
            borderColor: '#28a745',
            backgroundColor: 'rgba(40, 167, 69, 0.1)',
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Category Chart
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
new Chart(categoryCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_column($itemsByCategory, 'name')) !!},
        datasets: [{
            label: 'Barang Hilang',
            data: {!! json_encode(array_column($itemsByCategory, 'lost')) !!},
            backgroundColor: '#dc3545'
        }, {
            label: 'Barang Ditemukan',
            data: {!! json_encode(array_column($itemsByCategory, 'found')) !!},
            backgroundColor: '#28a745'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Rating Chart
const ratingCtx = document.getElementById('ratingChart').getContext('2d');
new Chart(ratingCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_column($ratingsByCategory, 'category')) !!},
        datasets: [{
            label: 'Rating Rata-rata',
            data: {!! json_encode(array_column($ratingsByCategory, 'rating')) !!},
            backgroundColor: '#ffc107',
            borderColor: '#ffc107',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 5,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
</script>
@endpush

