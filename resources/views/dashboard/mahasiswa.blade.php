@extends('layouts.app')

@section('title', 'Dashboard - Lost and Found')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-primary">
                <i class="bi bi-house"></i> Dashboard
            </h2>
            <p class="text-muted">Selamat datang, {{ auth()->user()->nama }}!</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $totalLostItems }}</h4>
                            <p class="card-text">Barang Hilang</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-exclamation-triangle fs-1"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('lost-items.index') }}" class="text-white text-decoration-none">
                        Lihat Detail <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $totalFoundItems }}</h4>
                            <p class="card-text">Barang Ditemukan</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-check-circle fs-1"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('found-items.index') }}" class="text-white text-decoration-none">
                        Lihat Detail <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $lostItemsBelumDiambil }}</h4>
                            <p class="card-text">Hilang Belum Ditemukan</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-clock fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $foundItemsBelumDiambil }}</h4>
                            <p class="card-text">Ditemukan Belum Diambil</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-hourglass fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-lightning"></i> Aksi Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('lost-items.create') }}" class="btn btn-danger btn-lg w-100 mb-2">
                                <i class="bi bi-plus-circle"></i> Laporkan Barang Hilang
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('found-items.create') }}" class="btn btn-success btn-lg w-100 mb-2">
                                <i class="bi bi-plus-circle"></i> Laporkan Barang Ditemukan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Reports -->
    <div class="row">
        <!-- Recent Lost Items -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-exclamation-triangle text-danger"></i> Barang Hilang Terbaru
                    </h5>
                    <a href="{{ route('lost-items.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    @if($recentLostItems->count() > 0)
                        @foreach($recentLostItems as $item)
                            <div class="d-flex align-items-center border-bottom py-2">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $item->nama }}</h6>
                                    <small class="text-muted">
                                        <i class="bi bi-geo-alt"></i> {{ $item->lokasi }} | 
                                        <i class="bi bi-calendar"></i> {{ $item->tanggal->format('d/m/Y') }}
                                    </small>
                                </div>
                                <span class="badge bg-{{ $item->status == 'belum diambil' ? 'danger' : 'success' }}">
                                    {{ $item->status == 'belum diambil' ? 'Belum Ditemukan' : 'Sudah Ditemukan' }}
                                </span>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center mb-0">Belum ada laporan barang hilang</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Found Items -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-check-circle text-success"></i> Barang Ditemukan Terbaru
                    </h5>
                    <a href="{{ route('found-items.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    @if($recentFoundItems->count() > 0)
                        @foreach($recentFoundItems as $item)
                            <div class="d-flex align-items-center border-bottom py-2">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $item->nama }}</h6>
                                    <small class="text-muted">
                                        <i class="bi bi-geo-alt"></i> {{ $item->lokasi }} | 
                                        <i class="bi bi-calendar"></i> {{ $item->tanggal->format('d/m/Y') }}
                                    </small>
                                </div>
                                <span class="badge bg-{{ $item->status == 'belum diambil' ? 'warning' : 'success' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center mb-0">Belum ada laporan barang ditemukan</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 