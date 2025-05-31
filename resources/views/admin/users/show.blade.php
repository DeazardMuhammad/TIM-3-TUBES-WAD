@extends('layouts.app')

@section('title', 'Detail User - Lost and Found')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Kelola Users</a></li>
                    <li class="breadcrumb-item active">{{ $user->nama }}</li>
                </ol>
            </nav>
            
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold text-primary">
                        <i class="bi bi-person-circle"></i> Detail User
                    </h2>
                    <p class="text-muted">Informasi lengkap pengguna sistem</p>
                </div>
                <div>
                    <span class="badge bg-{{ $user->role == 'admin' ? 'warning' : 'primary' }} fs-6">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- User Info -->
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle"></i> Informasi User
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Nama Lengkap:</strong>
                        </div>
                        <div class="col-sm-9">
                            <h5 class="text-primary">{{ $user->nama }}</h5>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>NIM:</strong>
                        </div>
                        <div class="col-sm-9">
                            <span class="badge bg-secondary">{{ $user->nim }}</span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Email:</strong>
                        </div>
                        <div class="col-sm-9">
                            <i class="bi bi-envelope text-info"></i> {{ $user->email }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Kontak:</strong>
                        </div>
                        <div class="col-sm-9">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->kontak) }}" 
                               class="btn btn-success btn-sm" 
                               target="_blank">
                                <i class="bi bi-whatsapp"></i> {{ $user->kontak }}
                            </a>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Role:</strong>
                        </div>
                        <div class="col-sm-9">
                            <span class="badge bg-{{ $user->role == 'admin' ? 'warning' : 'primary' }} fs-6">
                                {{ ucfirst($user->role) }}
                            </span>
                            @if($user->role == 'admin')
                                <small class="text-muted ms-2">Akses penuh ke sistem</small>
                            @else
                                <small class="text-muted ms-2">Dapat membuat laporan</small>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Terdaftar:</strong>
                        </div>
                        <div class="col-sm-9">
                            <i class="bi bi-calendar text-warning"></i> {{ $user->created_at->format('d F Y, H:i') }}
                            <small class="text-muted">
                                ({{ $user->created_at->diffForHumans() }})
                            </small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Terakhir Update:</strong>
                        </div>
                        <div class="col-sm-9">
                            {{ $user->updated_at->format('d F Y, H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up"></i> Statistik Aktivitas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h3 class="text-danger">{{ $userLostItems }}</h3>
                        <p class="mb-0 small">Laporan Barang Hilang</p>
                    </div>
                    
                    <div class="text-center mb-3">
                        <h3 class="text-success">{{ $userFoundItems }}</h3>
                        <p class="mb-0 small">Laporan Barang Ditemukan</p>
                    </div>
                    
                    <div class="text-center">
                        <h3 class="text-primary">{{ $userLostItems + $userFoundItems }}</h3>
                        <p class="mb-0 small">Total Laporan</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            @if($user->id !== auth()->id())
                <div class="card shadow mt-3">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="bi bi-gear"></i> Aksi Admin
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Edit User
                            </a>
                            
                            @if($user->role != 'admin' || \App\Models\User::where('role', 'admin')->count() > 1)
                                <form action="{{ route('admin.users.destroy', $user) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Yakin hapus user ini? Data tidak dapat dikembalikan!')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="bi bi-trash"></i> Hapus User
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-danger w-100" disabled title="Tidak dapat menghapus admin terakhir">
                                    <i class="bi bi-shield-x"></i> Admin Terakhir
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- User Reports -->
    @if($userLostItems > 0 || $userFoundItems > 0)
        <div class="row mt-4">
            <!-- Lost Items -->
            @if($userLostItems > 0)
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-exclamation-triangle text-danger"></i> Barang Hilang
                            </h5>
                            <span class="badge bg-danger">{{ $userLostItems }}</span>
                        </div>
                        <div class="card-body">
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
                            @if($userLostItems > 5)
                                <div class="text-center mt-2">
                                    <a href="{{ route('lost-items.index', ['user_id' => $user->id]) }}" 
                                       class="btn btn-sm btn-outline-danger">
                                        Lihat Semua ({{ $userLostItems }})
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Found Items -->
            @if($userFoundItems > 0)
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-check-circle text-success"></i> Barang Ditemukan
                            </h5>
                            <span class="badge bg-success">{{ $userFoundItems }}</span>
                        </div>
                        <div class="card-body">
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
                            @if($userFoundItems > 5)
                                <div class="text-center mt-2">
                                    <a href="{{ route('found-items.index', ['user_id' => $user->id]) }}" 
                                       class="btn btn-sm btn-outline-success">
                                        Lihat Semua ({{ $userFoundItems }})
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
                        </a>
                        
                        @if($user->id !== auth()->id())
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Edit User
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 