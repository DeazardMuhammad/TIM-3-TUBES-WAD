@extends('layouts.app')

@section('title', 'Daftar Barang Ditemukan - Lost and Found')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold text-success">
                <i class="bi bi-check-circle"></i> Barang Ditemukan
                @if(request('my_reports') == 'true')
                    <span class="badge bg-primary ms-2">Laporan Saya</span>
                @endif
            </h2>
            <p class="text-muted">
                @if(request('my_reports') == 'true')
                    Daftar barang ditemukan yang saya laporkan
                @else
                    Kelola laporan barang ditemukan
                @endif
            </p>
        </div>
        <div class="col-md-4 text-end">
            @if(request('my_reports') == 'true')
                <a href="{{ route('found-items.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-left"></i> Lihat Semua
                </a>
            @endif
            <a href="{{ route('found-items.create') }}" class="btn btn-success btn-lg">
                <i class="bi bi-plus-circle"></i> Lapor Barang Ditemukan
            </a>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('found-items.index') }}">
                @if(request('my_reports'))
                    <input type="hidden" name="my_reports" value="{{ request('my_reports') }}">
                @endif
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Pencarian</label>
                        <input type="text" 
                               class="form-control" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari nama barang, lokasi, atau deskripsi...">
                    </div>
                    <div class="col-md-2">
                        <label for="kategori_id" class="form-label">Kategori</label>
                        <select class="form-select" id="kategori_id" name="kategori_id">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoriList as $kategori)
                                <option value="{{ $kategori->id }}" 
                                        {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="belum diambil" {{ request('status') == 'belum diambil' ? 'selected' : '' }}>
                                Belum Diambil
                            </option>
                            <option value="sudah diambil" {{ request('status') == 'sudah diambil' ? 'selected' : '' }}>
                                Sudah Diambil
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="tanggal_dari" class="form-label">Dari Tanggal</label>
                        <input type="date" 
                               class="form-control" 
                               id="tanggal_dari" 
                               name="tanggal_dari" 
                               value="{{ request('tanggal_dari') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="tanggal_sampai" class="form-label">Sampai Tanggal</label>
                        <input type="date" 
                               class="form-control" 
                               id="tanggal_sampai" 
                               name="tanggal_sampai" 
                               value="{{ request('tanggal_sampai') }}">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Cari
                        </button>
                        <a href="{{ route('found-items.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Items Grid -->
    <div class="row">
        @if($foundItems->count() > 0)
            @foreach($foundItems as $item)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        @if($item->gambar)
                            <img src="{{ $item->getImageUrl() }}" 
                                 class="card-img-top" 
                                 style="height: 200px; object-fit: cover;" 
                                 alt="{{ $item->nama }}">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                 style="height: 200px;">
                                <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title">{{ $item->nama }}</h5>
                                <span class="badge bg-{{ $item->status == 'belum diambil' ? 'warning' : 'success' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </div>
                            
                            <p class="card-text text-muted small mb-2">
                                <i class="bi bi-geo-alt"></i> {{ $item->lokasi }}
                            </p>
                            <p class="card-text text-muted small mb-2">
                                <i class="bi bi-calendar"></i> {{ $item->tanggal->format('d F Y') }}
                            </p>
                            <p class="card-text text-muted small mb-2">
                                <i class="bi bi-tag"></i> {{ $item->kategori->nama }}
                            </p>
                            
                            @if($item->deskripsi)
                                <p class="card-text">{{ Str::limit($item->deskripsi, 100) }}</p>
                            @endif
                            
                            <div class="mt-auto">
                                <div class="btn-group w-100" role="group">
                                    <a href="{{ route('found-items.show', $item) }}" 
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                    
                                    @if(auth()->user()->isAdmin() || $item->user_id == auth()->id())
                                        <a href="{{ route('found-items.edit', $item) }}" 
                                           class="btn btn-outline-warning btn-sm">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('found-items.destroy', $item) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Yakin hapus laporan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                
                                @if((auth()->user()->isAdmin() || $item->user_id == auth()->id()) && $item->status == 'belum diambil')
                                    <form action="{{ route('found-items.update-status', $item) }}" 
                                          method="POST" 
                                          class="mt-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="sudah diambil">
                                        <button type="submit" 
                                                class="btn btn-success btn-sm w-100"
                                                onclick="return confirm('Tandai sebagai sudah diambil?')">
                                            <i class="bi bi-check-circle"></i> Tandai Sudah Diambil
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        
                        <div class="card-footer text-muted small">
                            <i class="bi bi-person"></i> {{ $item->user->nama }} | 
                            <i class="bi bi-phone"></i> {{ $item->kontak }}
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-search text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3">Tidak ada data ditemukan</h4>
                        <p class="text-muted">Belum ada laporan barang ditemukan atau coba ubah filter pencarian.</p>
                        <a href="{{ route('found-items.create') }}" class="btn btn-success">
                            <i class="bi bi-plus-circle"></i> Buat Laporan Pertama
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    
    @if($foundItems->hasPages())
        <div class="row mt-4">
            <div class="col-12 d-flex justify-content-center">
                {{ $foundItems->withQueryString()->links() }}
            </div>
        </div>
    @endif

    <!-- Footer -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h6 class="text-muted">Lost and Found System</h6>
                    <p class="text-muted small mb-0">
                        Sistem pelaporan barang hilang dan ditemukan untuk membantu mahasiswa mengeliminasi masalah barang hilang
                    </p>
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="bi bi-phone"></i> Kontak Admin: admin@lostandfound.com | 
                            <i class="bi bi-clock"></i> Layanan 24/7
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 