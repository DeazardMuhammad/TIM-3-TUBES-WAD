@extends('layouts.app')

@section('title', 'Beranda - Lost and Found')

@section('content')


<!--- Menu Utama --->
<div class="row mt-4">
    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-body text-center p-4">
                <div class="mb-3">
                    <i class="bi bi-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                </div>
                <h4>Laporkan Barang Hilang</h4>
                <p class="text-muted">Kehilangan barang? Laporkan disini agar orang lain bisa membantu!</p>
                <a href="{{ route('lost-items.create') }}" class="btn btn-danger btn-lg">
                    <i class="bi bi-plus"></i> Lapor Sekarang
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-body text-center p-4">
                <div class="mb-3">
                    <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                </div>
                <h4>Laporkan Barang Ditemukan</h4>
                <p class="text-muted">Menemukan barang orang lain? Laporkan agar pemiliknya tahu</p>
                <a href="{{ route('found-items.create') }}" class="btn btn-success btn-lg">
                    <i class="bi bi-plus"></i> Lapor Sekarang
                </a>
            </div>
        </div>
    </div>
</div>

<!--- Riwayat Laporan Saya --->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> History Laporan Saya</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <a href="{{ route('lost-items.index', ['my_reports' => 'true']) }}" class="btn btn-outline-danger w-100">
                            <i class="bi bi-exclamation-triangle"></i> Barang Hilang Saya ({{ $myLostItems }})
                        </a>
                    </div>
                    <div class="col-md-6 mb-2">
                        <a href="{{ route('found-items.index', ['my_reports' => 'true']) }}" class="btn btn-outline-success w-100">
                            <i class="bi bi-check-circle"></i> Barang Ditemukan Saya ({{ $myFoundItems }})
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<<<<<<< HEAD
<!-- Statistik Sederhana -->

<!-- Total Barang  -->

<div class="row mt-4">
    <div class="col-md-3 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-danger">{{ $totalLostItems }}</h3>
                <p class="mb-0">Total Barang Hilang</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-success">{{ $totalFoundItems }}</h3>
                <p class="mb-0">Total Barang Ditemukan</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-primary">{{ $myLostItems }}</h3>
                <p class="mb-0">Laporan Saya (Hilang)</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-info">{{ $myFoundItems }}</h3>
                <p class="mb-0">Laporan Saya (Ditemukan)</p>
            </div>
        </div>
    </div>
</div>



<!-- Barang Hilang dan Ditemukan Terbaru -->

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Barang Hilang Terbaru</h5>
            </div>
            <div class="card-body">
                @if($recentLostItems->count() > 0)
                    @foreach($recentLostItems as $item)
                        <div class="d-flex align-items-center mb-3 p-2 border rounded">
                            <div class="me-3">
                                @if($item->gambar && $item->getImageUrl())
                                    <img src="{{ $item->getImageUrl() }}" 
                                         class="rounded" 
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $item->nama }}</h6>
                                <small class="text-muted">{{ $item->lokasi }} • {{ $item->tanggal->format('d/m/Y') }}</small>
                            </div>
                            <a href="{{ route('lost-items.show', $item) }}" class="btn btn-sm btn-outline-primary">
                                Lihat
                            </a>
                        </div>
                    @endforeach
                    <div class="text-center">
                        <a href="{{ route('lost-items.index') }}" class="btn btn-outline-danger">
                            Lihat Semua <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                @else
                    <p class="text-muted text-center">Belum ada laporan barang hilang</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-check-circle"></i> Barang Ditemukan Terbaru</h5>
            </div>
            <div class="card-body">
                @if($recentFoundItems->count() > 0)
                    @foreach($recentFoundItems as $item)
                        <div class="d-flex align-items-center mb-3 p-2 border rounded">
                            <div class="me-3">
                                @if($item->gambar && $item->getImageUrl())
                                    <img src="{{ $item->getImageUrl() }}" 
                                         class="rounded" 
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $item->nama }}</h6>
                                <small class="text-muted">{{ $item->lokasi }} • {{ $item->tanggal->format('d/m/Y') }}</small>
                            </div>
                            <a href="{{ route('found-items.show', $item) }}" class="btn btn-sm btn-outline-primary">
                                Lihat
                            </a>
                        </div>
                    @endforeach
                    <div class="text-center">
                        <a href="{{ route('found-items.index') }}" class="btn btn-outline-success">
                            Lihat Semua <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                @else
                    <p class="text-muted text-center">Belum ada laporan barang ditemukan</p>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection 