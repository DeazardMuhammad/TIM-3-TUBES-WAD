@extends('layouts.app')

@section('title', 'Detail Barang Hilang - Lost and Found')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold text-danger">
                        <i class="bi bi-exclamation-triangle"></i> Detail Barang Hilang
                    </h2>
                    <p class="text-muted">Informasi lengkap barang yang hilang</p>
                </div>
                <div>
                    <span class="badge bg-{{ $lostItem->status == 'belum diambil' ? 'danger' : 'success' }} fs-6">
                        {{ $lostItem->status == 'belum diambil' ? 'Belum Ditemukan' : 'Sudah Ditemukan' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Image and Basic Info -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-image"></i> Foto Barang
                    </h5>
                </div>
                <div class="card-body text-center">
                    @if($lostItem->gambar && $lostItem->getImageUrl())
                        <img src="{{ $lostItem->getImageUrl() }}" 
                             class="img-fluid rounded" 
                             alt="{{ $lostItem->nama }}"
                             style="max-height: 400px; width: auto;"
                             onerror="this.parentElement.innerHTML='<div class=\'bg-light p-5 rounded\'><i class=\'bi bi-image text-muted\' style=\'font-size: 5rem;\'></i><p class=\'text-muted mt-3\'>Gambar tidak dapat dimuat</p></div>'">
                    @else
                        <div class="bg-light p-5 rounded">
                            <i class="bi bi-image text-muted" style="font-size: 5rem;"></i>
                            <p class="text-muted mt-3">
                                @if($lostItem->gambar)
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
                            <strong>Pelapor:</strong>
                            <p class="mb-2">{{ $lostItem->user->nama }}</p>
                        </div>
                        <div class="col-sm-6">
                            <strong>NIM:</strong>
                            <p class="mb-2">{{ $lostItem->user->nim }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <strong>Email:</strong>
                            <p class="mb-2">{{ $lostItem->user->email }}</p>
                        </div>
                        <div class="col-sm-6">
                            <strong>Kontak:</strong>
                            <p class="mb-2">
                                <a >
                                     {{ $lostItem->kontak }}
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
                            <h5 class="text-danger">{{ $lostItem->nama }}</h5>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <strong>Kategori:</strong>
                        </div>
                        <div class="col-sm-8">
                            <span class="badge bg-secondary">{{ $lostItem->kategori->nama }}</span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <strong>Lokasi Hilang:</strong>
                        </div>
                        <div class="col-sm-8">
                            <i class="bi bi-geo-alt text-danger"></i> {{ $lostItem->lokasi }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <strong>Tanggal Hilang:</strong>
                        </div>
                        <div class="col-sm-8">
                            <i class="bi bi-calendar text-warning"></i> {{ $lostItem->tanggal->format('d F Y') }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <strong>Status:</strong>
                        </div>
                        <div class="col-sm-8">
                            <span class="badge bg-{{ $lostItem->status == 'belum diambil' ? 'danger' : 'success' }} fs-6">
                                {{ $lostItem->status == 'belum diambil' ? 'Belum Ditemukan' : 'Sudah Ditemukan' }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <strong>Dilaporkan:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $lostItem->created_at->format('d F Y, H:i') }}
                        </div>
                    </div>

                    @if($lostItem->deskripsi)
                        <hr>
                        <div class="mb-3">
                            <strong>Deskripsi Detail:</strong>
                            <div class="mt-2 p-3 bg-light rounded">
                                {{ $lostItem->deskripsi }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Status Actions -->
            @if(auth()->user()->isAdmin() || $lostItem->user_id == auth()->id())
                <div class="card shadow mt-4">
                    <div class="card-header bg-{{ auth()->user()->isAdmin() ? 'warning' : 'primary' }} text-{{ auth()->user()->isAdmin() ? 'dark' : 'white' }}">
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
                        @if($lostItem->status == 'belum diambil')
                            <form action="{{ route('lost-items.update-status', $lostItem) }}" 
                                  method="POST" 
                                  class="d-inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="sudah diambil">
                                <button type="submit" 
                                        class="btn btn-success me-2"
                                        onclick="return confirm('Tandai barang ini sebagai sudah ditemukan?')">
                                    <i class="bi bi-check-circle"></i> Tandai Sudah Ditemukan
                                </button>
                            </form>
                        @else
                            <form action="{{ route('lost-items.update-status', $lostItem) }}" 
                                  method="POST" 
                                  class="d-inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="belum diambil">
                                <button type="submit" 
                                        class="btn btn-warning me-2"
                                        onclick="return confirm('Kembalikan status ke belum ditemukan?')">
                                    <i class="bi bi-arrow-clockwise"></i> Reset Status
                                </button>
                            </form>
                        @endif
                        
                        @if(!auth()->user()->isAdmin() && $lostItem->user_id == auth()->id())
                            <p class="text-muted small mt-2 mb-0">
                                <i class="bi bi-info-circle"></i> 
                                Ubah status ini jika barang sudah ditemukan
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
                        <a href="{{ route('lost-items.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
                        </a>
                        
                        @if(auth()->user()->isAdmin() || $lostItem->user_id == auth()->id())
                            <div>
                                <a href="{{ route('lost-items.edit', $lostItem) }}" class="btn btn-warning me-2">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('lost-items.destroy', $lostItem) }}" 
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