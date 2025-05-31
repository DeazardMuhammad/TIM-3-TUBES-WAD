@extends('layouts.app')

@section('title', 'Edit User - Lost and Found')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Kelola Users</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users.show', $user) }}">{{ $user->nama }}</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
            <h2 class="fw-bold text-warning">
                <i class="bi bi-person-gear"></i> Edit User
            </h2>
            <p class="text-muted">Ubah informasi pengguna {{ $user->nama }}</p>
        </div>
    </div>

    <!-- Form -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-pencil"></i> Form Edit User
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Nama -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-person"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control @error('nama') is-invalid @enderror" 
                                               id="nama" 
                                               name="nama" 
                                               value="{{ old('nama', $user->nama) }}" 
                                               placeholder="Masukkan nama lengkap"
                                               required>
                                    </div>
                                    @error('nama')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- NIM -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nim" class="form-label">NIM <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-card-text"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control @error('nim') is-invalid @enderror" 
                                               id="nim" 
                                               name="nim" 
                                               value="{{ old('nim', $user->nim) }}" 
                                               placeholder="Contoh: 1234567890"
                                               required>
                                    </div>
                                    @error('nim')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Email -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-envelope"></i>
                                        </span>
                                        <input type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email', $user->email) }}" 
                                               placeholder="contoh@email.com"
                                               required>
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Kontak -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kontak" class="form-label">Kontak WhatsApp <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-phone"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control @error('kontak') is-invalid @enderror" 
                                               id="kontak" 
                                               name="kontak" 
                                               value="{{ old('kontak', $user->kontak) }}" 
                                               placeholder="08123456789"
                                               required>
                                    </div>
                                    @error('kontak')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Role -->
                        @if($user->id !== auth()->id() && ($user->role != 'admin' || \App\Models\User::where('role', 'admin')->count() > 1))
                            <div class="mb-3">
                                <label for="role" class="form-label">Role User <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-shield"></i>
                                    </span>
                                    <select class="form-select @error('role') is-invalid @enderror" 
                                            id="role" 
                                            name="role" 
                                            required>
                                        <option value="">Pilih Role</option>
                                        <option value="mahasiswa" {{ old('role', $user->role) == 'mahasiswa' ? 'selected' : '' }}>
                                            Mahasiswa
                                        </option>
                                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                            Admin
                                        </option>
                                    </select>
                                </div>
                                @error('role')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">
                                    <strong>Mahasiswa:</strong> Dapat membuat laporan dan melihat semua laporan<br>
                                    <strong>Admin:</strong> Dapat mengelola semua data sistem
                                </div>
                            </div>
                        @else
                            <div class="mb-3">
                                <label class="form-label">Role User</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-shield"></i>
                                    </span>
                                    <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" readonly>
                                    <input type="hidden" name="role" value="{{ $user->role }}">
                                </div>
                                <div class="form-text text-warning">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    @if($user->id === auth()->id())
                                        Anda tidak dapat mengubah role sendiri
                                    @else
                                        Tidak dapat mengubah role admin terakhir
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Password Section -->
                        <hr>
                        <h6 class="text-warning mb-3">
                            <i class="bi bi-key"></i> Ubah Password (Opsional)
                        </h6>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            Kosongkan jika tidak ingin mengubah password. Password minimal 8 karakter.
                        </div>

                        <div class="row">
                            <!-- New Password -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password Baru</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-lock"></i>
                                        </span>
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               placeholder="Kosongkan jika tidak diubah">
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-lock-fill"></i>
                                        </span>
                                        <input type="password" 
                                               class="form-control" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               placeholder="Ulangi password baru">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between">
                            <div>
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-secondary me-2">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </a>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-info">
                                    <i class="bi bi-list"></i> Ke Daftar
                                </a>
                            </div>
                            <div>
                                <button type="reset" class="btn btn-outline-warning me-2">
                                    <i class="bi bi-arrow-clockwise"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-check-lg"></i> Update User
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Info -->
    <div class="row mt-4 justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle"></i> Informasi Saat Ini
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <strong>Terdaftar:</strong>
                            <p class="mb-0 small">{{ $user->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-3">
                            <strong>Last Update:</strong>
                            <p class="mb-0 small">{{ $user->updated_at->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-3">
                            <strong>Total Laporan:</strong>
                            <p class="mb-0 small">{{ $user->lostItems->count() + $user->foundItems->count() }}</p>
                        </div>
                        <div class="col-md-3">
                            <strong>Status:</strong>
                            <span class="badge bg-{{ $user->role == 'admin' ? 'warning' : 'primary' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 