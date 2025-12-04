@extends('layouts.app')

@section('title', 'Verifikasi Laporan')

@section('content')
<div class="container py-4">
    <h2 class="mb-4"><i class="bi bi-check-square"></i> Verifikasi Laporan</h2>

    <!-- Filter tabs -->
    <ul class="nav nav-tabs mb-4" id="verifikasiTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="barang-hilang-tab" data-bs-toggle="tab" data-bs-target="#barang-hilang" type="button">
                <i class="bi bi-exclamation-triangle"></i> Barang Hilang ({{ $pendingLostItems->count() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="barang-ditemukan-tab" data-bs-toggle="tab" data-bs-target="#barang-ditemukan" type="button">
                <i class="bi bi-check-circle"></i> Barang Ditemukan ({{ $pendingFoundItems->count() }})
            </button>
        </li>
    </ul>

    <div class="tab-content" id="verifikasiTabContent">
        <!-- Barang Hilang Tab -->
        <div class="tab-pane fade show active" id="barang-hilang" role="tabpanel">
            @if($pendingLostItems->isEmpty())
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Tidak ada laporan barang hilang yang perlu diverifikasi.
                </div>
            @else
                <div class="row">
                    @foreach($pendingLostItems as $item)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            @if($item->gambar)
                                <img src="{{ asset('storage/images/lost/' . $item->gambar) }}" class="card-img-top" alt="{{ $item->nama }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="bi bi-image" style="font-size: 3rem;"></i>
                                </div>
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $item->nama }}</h5>
                                <p class="card-text">
                                    <small class="text-muted">
                                        <i class="bi bi-tag"></i> {{ $item->kategori->nama }}<br>
                                        <i class="bi bi-geo-alt"></i> {{ $item->lokasi }}<br>
                                        <i class="bi bi-calendar"></i> {{ $item->tanggal->format('d M Y') }}<br>
                                        <i class="bi bi-person"></i> {{ $item->user->nama }}
                                    </small>
                                </p>
                                <p class="card-text">{{ Str::limit($item->deskripsi, 100) }}</p>
                                
                                <div class="mt-3">
                                    <a href="{{ route('lost-items.show', $item->id) }}" class="btn btn-primary w-100">
                                        <i class="bi bi-eye"></i> Lihat Detail & Verifikasi
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Barang Ditemukan Tab -->
        <div class="tab-pane fade" id="barang-ditemukan" role="tabpanel">
            @if($pendingFoundItems->isEmpty())
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Tidak ada laporan barang ditemukan yang perlu diverifikasi.
                </div>
            @else
                <div class="row">
                    @foreach($pendingFoundItems as $item)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            @if($item->gambar)
                                <img src="{{ asset('storage/images/found/' . $item->gambar) }}" class="card-img-top" alt="{{ $item->nama }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="bi bi-image" style="font-size: 3rem;"></i>
                                </div>
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $item->nama }}</h5>
                                <p class="card-text">
                                    <small class="text-muted">
                                        <i class="bi bi-tag"></i> {{ $item->kategori->nama }}<br>
                                        <i class="bi bi-geo-alt"></i> {{ $item->lokasi }}<br>
                                        <i class="bi bi-calendar"></i> {{ $item->tanggal->format('d M Y') }}<br>
                                        <i class="bi bi-person"></i> {{ $item->user->nama }}
                                    </small>
                                </p>
                                <p class="card-text">{{ Str::limit($item->deskripsi, 100) }}</p>
                                
                                <div class="mt-3">
                                    <a href="{{ route('found-items.show', $item->id) }}" class="btn btn-primary w-100">
                                        <i class="bi bi-eye"></i> Lihat Detail & Verifikasi
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

