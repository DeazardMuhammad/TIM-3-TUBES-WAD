@extends('layouts.app')

@section('title', 'Kelola User - Lost and Found')

@section('content')
<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="text-primary mb-2">
                            <i class="bi bi-people"></i> Kelola User
                        </h2>
                        <p class="text-muted mb-0">Daftar semua pengguna sistem Lost & Found</p>
                    </div>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-plus"></i> Tambah User
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-search"></i> Cari User</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.users.index') }}">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama / NIM / Email</label>
                            <input type="text" 
                                   class="form-control" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Cari berdasarkan nama, NIM, atau email...">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select">
                                <option value="">Semua Role</option>
                                <option value="mahasiswa" {{ request('role') == 'mahasiswa' ? 'selected' : '' }}>
                                    Mahasiswa
                                </option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>
                                    Admin
                                </option>
                            </select>
                        </div>
                        
                        <div class="col-md-2 mb-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i> Cari
                                </button>
                                @if(request()->hasAny(['search', 'role']))
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-clockwise"></i> Reset
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-list"></i> Daftar User
                    <span class="badge bg-primary ms-2">{{ $users->total() }} total</span>
                </h5>
            </div>
            <div class="card-body">
                @if($users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Foto</th>
                                    <th>Nama & NIM</th>
                                    <th>Email & Kontak</th>
                                    <th>Role</th>
                                    <th>Laporan</th>
                                    <th>Terdaftar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 50px; height: 50px;">
                                                <i class="bi bi-person text-muted"></i>
                                            </div>
                                        </td>
                                        <td>
                                            <h6 class="mb-1">{{ $user->nama }}</h6>
                                            <small class="text-muted">{{ $user->nim }}</small>
                                        </td>
                                        <td>
                                            <div>{{ $user->email }}</div>
                                            <small class="text-muted">{{ $user->kontak }}</small>
                                        </td>
                                        <td>
                                            @if($user->role == 'admin')
                                                <span class="badge bg-danger">Admin</span>
                                            @else
                                                <span class="badge bg-primary">Mahasiswa</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-danger">{{ $user->lost_items_count }} Hilang</small><br>
                                            <small class="text-success">{{ $user->found_items_count }} Ditemukan</small>
                                        </td>
                                        <td>
                                            {{ $user->created_at->format('d/m/Y') }}
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.users.show', $user) }}" 
                                                   class="btn btn-sm btn-outline-info">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.users.edit', $user) }}" 
                                                   class="btn btn-sm btn-outline-warning">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                @if($user->id !== auth()->id())
                                                    <form action="{{ route('admin.users.destroy', $user) }}" 
                                                          method="POST" 
                                                          class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-outline-danger"
                                                                onclick="return confirm('Yakin ingin menghapus user ini?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <button class="btn btn-sm btn-outline-danger" disabled>
                                                        <i class="bi bi-shield"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($users->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $users->appends(request()->query())->links() }}
                        </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="text-center p-5">
                        <i class="bi bi-people text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3">Tidak Ada User Ditemukan</h4>
                        <p class="text-muted">
                            @if(request()->hasAny(['search', 'role']))
                                Tidak ada user yang sesuai dengan pencarian Anda.
                                <a href="{{ route('admin.users.index') }}">Lihat semua user</a>
                            @else
                                Belum ada user terdaftar.
                                <a href="{{ route('admin.users.create') }}">Tambah user pertama</a>
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Summary -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center">
                <p class="mb-0 text-muted">
                    Menampilkan {{ $users->count() }} dari {{ $users->total() }} user
                    @if(request()->hasAny(['search', 'role']))
                        berdasarkan filter yang dipilih
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
@endsection 