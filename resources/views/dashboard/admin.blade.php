@extends('layouts.app')

@section('title', 'Admin Dashboard - Lost and Found')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-primary">
                <i class="bi bi-speedometer2"></i> Admin Dashboard
            </h2>
            <p class="text-muted">Selamat datang, {{ auth()->user()->nama }}! Kelola sistem Lost & Found</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $totalUsers }}</h4>
                            <p class="card-text">Total Mahasiswa</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-people fs-1"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <small>Pengguna terdaftar</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
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
                    <small>{{ $lostItemsBelumDiambil }} belum ditemukan</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
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
                    <small>{{ $foundItemsBelumDiambil }} belum diambil</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $totalKategori }}</h4>
                            <p class="card-text">Kategori</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-tags fs-1"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.kategori.index') }}" class="text-white text-decoration-none">
                        Kelola <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Management Cards -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-gear"></i> Manajemen Sistem
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('lost-items.index') }}" class="btn btn-outline-danger btn-lg w-100 mb-2">
                                <i class="bi bi-exclamation-triangle"></i><br>
                                Kelola Barang Hilang
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('found-items.index') }}" class="btn btn-outline-success btn-lg w-100 mb-2">
                                <i class="bi bi-check-circle"></i><br>
                                Kelola Barang Ditemukan
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.kategori.index') }}" class="btn btn-outline-info btn-lg w-100 mb-2">
                                <i class="bi bi-tags"></i><br>
                                Kelola Kategori
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary btn-lg w-100 mb-2">
                                <i class="bi bi-people"></i><br>
                                Kelola Users
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
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
                                        <i class="bi bi-person"></i> {{ $item->user->nama }} | 
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
                                        <i class="bi bi-person"></i> {{ $item->user->nama }} | 
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

    <!-- Recent Users -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-people text-primary"></i> Mahasiswa Terbaru
                    </h5>
                </div>
                <div class="card-body">
                    @if($recentUsers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>NIM</th>
                                        <th>Email</th>
                                        <th>Kontak</th>
                                        <th>Terdaftar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentUsers as $user)
                                        <tr>
                                            <td>{{ $user->nama }}</td>
                                            <td>{{ $user->nim }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->kontak }}</td>
                                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">Belum ada mahasiswa yang terdaftar</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 