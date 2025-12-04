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
                    @if($foundItem->gambar)
                        <img src="{{ asset('storage/images/found/' . $foundItem->gambar) }}" 
                             class="img-fluid rounded" 
                             alt="{{ $foundItem->nama }}"
                             style="max-height: 400px; width: auto;"
                             onerror="this.parentElement.innerHTML='<div class=\'bg-light p-5 rounded\'><i class=\'bi bi-image text-muted\' style=\'font-size: 5rem;\'></i><p class=\'text-muted mt-3\'>Gambar tidak dapat dimuat</p></div>'">
                    @else
                        <div class="bg-light p-5 rounded">
                            <i class="bi bi-image text-muted" style="font-size: 5rem;"></i>
                            <p class="text-muted mt-3">Tidak ada foto</p>
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

            <!-- Claim Button (if not owner and not admin) -->
            @if(!auth()->user()->isAdmin() && $foundItem->user_id != auth()->id() && $foundItem->status == 'belum diambil' && $foundItem->status_verifikasi == 'approved')
                <div class="card shadow mt-4 border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-hand-thumbs-up"></i> Ini Barang Anda?
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <p class="mb-3">Jika ini barang milik Anda yang hilang, silakan ajukan klaim untuk mengambilnya.</p>
                        <a href="{{ route('claims.create', $foundItem->id) }}" class="btn btn-success btn-lg">
                            <i class="bi bi-clipboard-check"></i> Klaim Barang Ini
                        </a>
                    </div>
                </div>
            @endif
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

    <!-- Admin Verification Section -->
    @if(auth()->user()->isAdmin() && $foundItem->status_verifikasi === 'pending')
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-exclamation-triangle-fill"></i> Verifikasi Laporan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning mb-4">
                        <i class="bi bi-info-circle"></i> <strong>Laporan ini menunggu verifikasi Anda.</strong> Periksa detail barang dan putuskan untuk menyetujui atau menolak laporan ini.
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Setujui Laporan</h6>
                            <p class="text-muted">Jika informasi lengkap dan valid, setujui laporan ini agar terlihat oleh semua pengguna.</p>
                            <form action="{{ route('admin.verifikasi.found-items.approve', $foundItem->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-lg w-100" onclick="return confirm('Yakin menyetujui laporan ini?')">
                                    <i class="bi bi-check-circle-fill"></i> Setujui Laporan
                                </button>
                            </form>
                        </div>

                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Tolak Laporan</h6>
                            <p class="text-muted">Jika informasi tidak lengkap atau tidak valid, tolak laporan ini dengan memberikan alasan.</p>
                            <button type="button" class="btn btn-danger btn-lg w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="bi bi-x-circle-fill"></i> Tolak Laporan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.verifikasi.found-items.reject', $foundItem->id) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title"><i class="bi bi-x-circle"></i> Tolak Laporan</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> User akan menerima notifikasi penolakan beserta alasan yang Anda berikan.
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alasan Penolakan *</label>
                            <textarea name="alasan_reject" class="form-control" rows="5" required placeholder="Jelaskan alasan penolakan laporan ini..."></textarea>
                            <small class="text-muted">Berikan alasan yang jelas agar user dapat memperbaiki laporannya.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-x-circle"></i> Tolak Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Verification Status Info -->
    @if($foundItem->status_verifikasi === 'rejected')
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-danger">
                <h5 class="alert-heading"><i class="bi bi-x-circle-fill"></i> Laporan Ditolak</h5>
                <p class="mb-0"><strong>Alasan:</strong> {{ $foundItem->alasan_reject }}</p>
                @if($foundItem->verified_at)
                    <small class="text-muted">Ditolak pada: {{ $foundItem->verified_at->format('d M Y, H:i') }}</small>
                @endif
            </div>
        </div>
    </div>
    @elseif($foundItem->status_verifikasi === 'approved')
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill"></i> <strong>Laporan Telah Diverifikasi</strong>
                @if($foundItem->verified_at)
                    <small class="d-block text-muted mt-1">Disetujui pada: {{ $foundItem->verified_at->format('d M Y, H:i') }}</small>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Admin Notes Section -->
    @if(auth()->user()->isAdmin())
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-journal-text"></i> Catatan Laporan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Left: Item Summary -->
                        <div class="col-md-4">
                            <div class="card border">
                                @if($foundItem->gambar)
                                    <img src="{{ asset('storage/images/found/' . $foundItem->gambar) }}" class="card-img-top" alt="{{ $foundItem->nama }}" style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <h6 class="card-title fw-bold">{{ $foundItem->nama }}</h6>
                                    <p class="card-text mb-1">
                                        <i class="bi bi-geo-alt text-danger"></i> {{ $foundItem->lokasi }}
                                    </p>
                                    <p class="card-text mb-1">
                                        <i class="bi bi-calendar text-warning"></i> {{ $foundItem->tanggal->format('d F Y') }}
                                    </p>
                                    <p class="card-text mb-0">
                                        <i class="bi bi-tag text-info"></i> {{ $foundItem->kategori->nama }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Notes -->
                        <div class="col-md-8">
                            <!-- Add Note Form -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3">Tambahkan catatan</h6>
                                <form action="{{ route('admin.notes.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="report_type" value="found">
                                    <input type="hidden" name="report_id" value="{{ $foundItem->id }}">
                                    
                                    <div class="mb-3">
                                        <textarea name="isi_catatan" class="form-control @error('isi_catatan') is-invalid @enderror" rows="4" placeholder="Masukkan catatan baru..." required></textarea>
                                        @error('isi_catatan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-save"></i> Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <hr>

                            <!-- Notes History -->
                            <h6 class="fw-bold mb-3">Riwayat catatan</h6>
                            <div class="notes-list" style="max-height: 400px; overflow-y: auto;">
                                @php
                                    $notes = \App\Models\Note::where('report_type', 'found')
                                        ->where('report_id', $foundItem->id)
                                        ->with('admin')
                                        ->latest()
                                        ->get();
                                @endphp

                                @forelse($notes as $note)
                                    <div class="card mb-3 border-start border-primary border-3">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <strong class="text-primary">{{ $note->admin->nama }}</strong>
                                                    <small class="text-muted ms-2">{{ $note->created_at->format('d F Y, H:i') }} WIB</small>
                                                </div>
                                                <form action="{{ route('admin.notes.destroy', $note->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus catatan ini?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                            <p class="mb-0">{{ $note->isi_catatan }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle"></i> Belum ada catatan untuk laporan ini.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

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