@extends('layouts.app')

@section('title', 'Beranda - Lost and Found')

@section('content')
@php
    $activities = collect($recentLostItems)->map(function ($item) {
        return [
            'title' => $item->nama ?? 'Barang Hilang',
            'location' => $item->lokasi ?? '-',
            'date' => optional($item->tanggal),
            'type' => 'lost',
            'status' => 'Belum ditemukan',
        ];
    })->merge($recentFoundItems->map(function ($item) {
        return [
            'title' => $item->nama ?? 'Barang Ditemukan',
            'location' => $item->lokasi ?? '-',
            'date' => optional($item->tanggal),
            'type' => 'found',
            'status' => 'Siap diverifikasi',
        ];
    }))->sortByDesc(function ($activity) {
        return optional($activity['date'])->timestamp ?? 0;
    })->take(5);
@endphp

<style>
    .dashboard-hero {
        background: linear-gradient(120deg, #6a5acd, #8c6bff);
        border-radius: 24px;
        padding: 32px;
        color: #fff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 25px 60px rgba(106, 90, 205, 0.25);
    }
    .dashboard-hero::after {
        content: '';
        position: absolute;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: rgba(255,255,255,0.08);
        top: -60px;
        right: -20px;
    }
    .stat-pill {
        border-radius: 18px;
        padding: 20px;
        background: #f4f5ff;
        border: 1px solid rgba(106, 90, 205, 0.15);
        min-height: 120px;
        box-shadow: 0 10px 30px rgba(84, 85, 108, 0.08);
    }
    .quick-card {
        border-radius: 20px;
        border: none;
        padding: 22px;
        height: 100%;
        box-shadow: 0 20px 35px rgba(20,22,37,0.12);
        transition: transform .3s ease, box-shadow .3s ease;
    }
    .quick-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 30px 45px rgba(20,22,37,0.18);
    }
    .activity-item {
        border-radius: 16px;
        padding: 16px 20px;
        border: 1px solid rgba(229, 231, 235, 0.9);
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
        align-items: center;
    }
    .activity-icon {
        width: 50px;
        height: 50px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }
    .tip-card {
        border-radius: 18px;
        padding: 22px;
        background: #fff7ec;
        border: 1px solid rgba(255, 195, 113, 0.4);
    }
</style>

<div class="dashboard-hero mt-4">
    <div class="row">
        <div class="col-md-8">
            <h2 class="fw-bold mb-3">Halo, {{ auth()->user()->nama ?? 'Mahasiswa' }} 👋</h2>
            <p class="mb-4" style="max-width: 520px;">Kelola laporan kehilangan dan temuan barang kamu secara lebih teratur. Pantau statistik pribadi, aktivitas terbaru, dan akses tindakan cepat dalam satu halaman.</p>
            <div class="d-flex flex-wrap gap-3">
                <a href="{{ route('lost-items.create') }}" class="btn btn-light text-primary fw-semibold">
                    <i class="bi bi-plus-circle"></i> Lapor Barang Hilang
                </a>
                <a href="{{ route('found-items.create') }}" class="btn btn-outline-light text-white fw-semibold">
                    <i class="bi bi-plus"></i> Lapor Barang Ditemukan
                </a>
            </div>
        </div>
        <div class="col-md-4 text-md-end mt-4 mt-md-0">
            <div class="stat-pill text-white" style="background: rgba(255,255,255,0.15);">
                <small>Jumlah laporan saya</small>
                <h1 class="fw-bold mt-2 mb-0">{{ $myLostItems + $myFoundItems }}</h1>
                <small class="text-white-50">{{ $myLostItems }} kehilangan • {{ $myFoundItems }} temuan</small>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4 g-3">
    <div class="col-md-3">
        <div class="stat-pill">
            <small>Total barang hilang</small>
            <h3 class="text-danger fw-bold mt-2 mb-0">{{ $totalLostItems }}</h3>
            <span class="text-muted">Tersimpan di sistem</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-pill">
            <small>Total barang ditemukan</small>
            <h3 class="text-success fw-bold mt-2 mb-0">{{ $totalFoundItems }}</h3>
            <span class="text-muted">Siap diverifikasi</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-pill">
            <small>Laporan hilang saya</small>
            <h3 class="text-primary fw-bold mt-2 mb-0">{{ $myLostItems }}</h3>
            <span class="text-muted">Sedang dipantau</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-pill">
            <small>Laporan temuan saya</small>
            <h3 class="text-info fw-bold mt-2 mb-0">{{ $myFoundItems }}</h3>
            <span class="text-muted">Menunggu klaim</span>
        </div>
    </div>
</div>

<div class="row mt-4 g-4">
    <div class="col-lg-8">
        <div class="card quick-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="mb-1 fw-bold">Aktivitas & Notifikasi</h5>
                    <small class="text-muted">Ringkasan progres laporan kamu</small>
                </div>
                <span class="badge bg-light text-dark">{{ $activities->count() }} aktivitas terbaru</span>
            </div>
            @if($activities->count())
                @foreach($activities as $activity)
                    <div class="activity-item">
                        <div class="activity-icon {{ $activity['type'] === 'lost' ? 'bg-danger bg-opacity-10 text-danger' : 'bg-success bg-opacity-10 text-success' }}">
                            <i class="bi {{ $activity['type'] === 'lost' ? 'bi-exclamation-triangle' : 'bi-check-circle' }}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $activity['title'] }}</strong>
                                <small class="text-muted">{{ optional($activity['date'])->format('d M Y') ?? 'Tanggal belum ada' }}</small>
                            </div>
                            <small class="text-muted">{{ $activity['location'] }}</small>
                            <div>
                                <span class="badge rounded-pill {{ $activity['type'] === 'lost' ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }}">
                                    {{ $activity['status'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-muted text-center mb-0 py-3">Belum ada aktivitas terbaru. Yuk buat laporan pertama kamu!</p>
            @endif
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card quick-card mb-4">
            <h6 class="fw-bold mb-3">Akses Cepat</h6>
            <div class="d-grid gap-2">
                <a href="{{ route('claims.my-claims') }}" class="btn btn-outline-primary">
                    <i class="bi bi-bell"></i> Lihat status klaim saya
                </a>
                <a href="{{ route('lost-items.index', ['my_reports' => 'true']) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-journal-text"></i> Semua laporan yang saya buat
                </a>
                <a href="{{ route('messages.index') }}" class="btn btn-outline-dark">
                    <i class="bi bi-chat-dots"></i> Butuh bantuan admin
                </a>
            </div>
        </div>
        <div class="tip-card">
            <h6 class="fw-bold mb-2"><i class="bi bi-lightbulb"></i> Tips menjaga barang</h6>
            <ul class="ps-3 mb-3">
                <li>Simpan kontak admin jika butuh verifikasi cepat.</li>
                <li>Foto detail barang dan lokasi terakhir terlihat.</li>
                <li>Segera lapor jika menemukan barang orang lain.</li>
            </ul>
            <div class="bg-white rounded-3 p-3">
                <small class="text-muted">Kontak Admin</small>
                <div class="fw-bold">admin@lostandfound.com</div>
                <small class="text-muted">Layanan cepat 08:00 - 21:00 WIB</small>
            </div>
        </div>
    </div>
</div>
@endsection