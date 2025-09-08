@extends('layouts.app')

@section('title', 'Detail Barang Ditemukan - Lost and Found')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('found-items.index') }}">Barang Ditemukan</a></li>
                    <li class="breadcrumb-item active">{{ $foundItem->nama }}</li>
                </ol>
            </nav>
            
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold text-success">
                        <i class="bi bi-check-circle"></i> Detail Barang Ditemukan
                    </h2>
                    <p class="text-muted">Informasi lengkap barang yang ditemukan</p>
                </div>
                <div>
                    <span class="badge bg-{{ $foundItem->status == 'belum diambil' ? 'warning' : 'success' }} fs-6">
                        {{ ucfirst($foundItem->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Image and Basic Info -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-image"></i> Foto Barang
                    </h5>
                </div>
                <div class="card-body text-center">
                    @if($foundItem->gambar && $foundItem->getImageUrl())
                        <img src="{{ $foundItem->getImageUrl() }}" 
                             class="img-fluid rounded" 
                             alt="{{ $foundItem->nama }}"
                             style="max-height: 400px; width: auto;"
                             onerror="this.parentElement.innerHTML='<div class=\'bg-light p-5 rounded\'><i class=\'bi bi-image text-muted\' style=\'font-size: 5rem;\'></i><p class=\'text-muted mt-3\'>Gambar tidak dapat dimuat</p></div>'">
                    @else
                        <div class="bg-light p-5 rounded">
                            <i class="bi bi-image text-muted" style="font-size: 5rem;"></i>
                            <p class="text-muted mt-3">
                                @if($foundItem->gambar)
                                    Gambar tidak dapat dimuat
                                @else
                                    Tidak ada foto
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Contact Info -->
            <div class="card shadow mt-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-person-lines-fill"></i> Informasi Kontak
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <strong>Penemu:</strong>
                            <p class="mb-2">{{ $foundItem->user->nama }}</p>
                        </div>
                        <div class="col-sm-6">
                            <strong>NIM:</strong>
                            <p class="mb-2">{{ $foundItem->user->nim }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <strong>Email:</strong>
                            <p class="mb-2">{{ $foundItem->user->email }}</p>
                        </div>
                        <div class="col-sm-6">
                            <strong>Kontak:</strong>
                            <p class="mb-2">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $foundItem->kontak) }}" 
                                   class="btn btn-success btn-sm" 
                                   target="_blank">
                                    <i class="bi bi-whatsapp"></i> {{ $foundItem->kontak }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Item Details -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle"></i> Detail Barang
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <strong>Nama Barang:</strong>
                        </div>
                        <div class="col-sm-8">
                            <h5 class="text-success">{{ $foundItem->nama }}</h5>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <strong>Kategori:</strong>
                        </div>
                        <div class="col-sm-8">
                            <span class="badge bg-secondary">{{ $foundItem->kategori->nama }}</span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <strong>Lokasi Ditemukan:</strong>
                        </div>
                        <div class="col-sm-8">
                            <i class="bi bi-geo-alt text-success"></i> {{ $foundItem->lokasi }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <strong>Tanggal Ditemukan:</strong>
                        </div>
                        <div class="col-sm-8">
                            <i class="bi bi-calendar text-warning"></i> {{ $foundItem->tanggal->format('d F Y') }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <strong>Status:</strong>
                        </div>
                        <div class="col-sm-8">
                            <span class="badge bg-{{ $foundItem->status == 'belum diambil' ? 'warning' : 'success' }} fs-6">
                                {{ ucfirst($foundItem->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <strong>Dilaporkan:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $foundItem->created_at->format('d F Y, H:i') }}
                        </div>
                    </div>

                    @if($foundItem->deskripsi)
                        <hr>
                        <div class="mb-3">
                            <strong>Deskripsi Detail:</strong>
                            <div class="mt-2 p-3 bg-light rounded">
                                {{ $foundItem->deskripsi }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Status Actions -->
            @if(auth()->user()->isAdmin() || $foundItem->user_id == auth()->id())
                <div class="card shadow mt-4">
                    <div class="card-header bg-{{ auth()->user()->isAdmin() ? 'warning' : 'success' }} text-{{ auth()->user()->isAdmin() ? 'dark' : 'white' }}">
                        <h5 class="mb-0">
                            <i class="bi bi-gear"></i> 
                            @if(auth()->user()->isAdmin())
                                Aksi Admin
                            @else
                                Ubah Status Barang
                            @endif
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($foundItem->status == 'belum diambil')
                            <form action="{{ route('found-items.update-status', $foundItem) }}" 
                                  method="POST" 
                                  class="d-inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="sudah diambil">
                                <button type="submit" 
                                        class="btn btn-success me-2"
                                        onclick="return confirm('Tandai barang ini sebagai sudah diambil?')">
                                    <i class="bi bi-check-circle"></i> Tandai Sudah Diambil
                                </button>
                            </form>
                        @else
                            <form action="{{ route('found-items.update-status', $foundItem) }}" 
                                  method="POST" 
                                  class="d-inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="belum diambil">
                                <button type="submit" 
                                        class="btn btn-warning me-2"
                                        onclick="return confirm('Kembalikan status ke belum diambil?')">
                                    <i class="bi bi-arrow-clockwise"></i> Reset Status
                                </button>
                            </form>
                        @endif
                        
                        @if(!auth()->user()->isAdmin() && $foundItem->user_id == auth()->id())
                            <p class="text-muted small mt-2 mb-0">
                                <i class="bi bi-info-circle"></i> 
                                Ubah status ini jika barang sudah diambil oleh pemiliknya
                            </p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('found-items.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
                        </a>
                        
                        @if(auth()->user()->isAdmin() || $foundItem->user_id == auth()->id())
                            <div>
                                <a href="{{ route('found-items.edit', $foundItem) }}" class="btn btn-warning me-2">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('found-items.destroy', $foundItem) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Yakin hapus laporan ini? Data tidak dapat dikembalikan!')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 