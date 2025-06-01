@extends('layouts.app')

@section('title', 'Register - Lost and Found')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card">
            <div class="card-header text-center">
                <h3 class="text-primary">
                    <i class="bi bi-person-plus"></i> Daftar Akun
                </h3>
                <p class="text-muted mb-0">Buat akun baru untuk menggunakan Lost & Found</p>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <strong><i class="bi bi-exclamation-triangle"></i> Error!</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('register.post') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama" class="form-label">
                                    <i class="bi bi-person"></i> Nama Lengkap *
                                </label>
                                <input type="text" 
                                       class="form-control @error('nama') is-invalid @enderror" 
                                       id="nama" 
                                       name="nama" 
                                       value="{{ old('nama') }}" 
                                       placeholder="Nama lengkap Anda"
                                       required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nim" class="form-label">
                                    <i class="bi bi-card-text"></i> NIM *
                                </label>
                                <input type="text" 
                                       class="form-control @error('nim') is-invalid @enderror" 
                                       id="nim" 
                                       name="nim" 
                                       value="{{ old('nim') }}" 
                                       placeholder="Nomor Induk Mahasiswa"
                                       required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope"></i> Email *
                        </label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               placeholder="Email aktif Anda"
                               required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="kontak" class="form-label">
                            <i class="bi bi-whatsapp"></i> Nomor WhatsApp *
                        </label>
                        <input type="text" 
                               class="form-control @error('kontak') is-invalid @enderror" 
                               id="kontak" 
                               name="kontak" 
                               value="{{ old('kontak') }}" 
                               placeholder="08123456789"
                               required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock"></i> Password *
                                </label>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Minimal 6 karakter"
                                       required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">
                                    <i class="bi bi-lock-fill"></i> Konfirmasi Password *
                                </label>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       placeholder="Ketik ulang password"
                                       required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-person-plus"></i> Daftar Sekarang
                        </button>
                    </div>
                </form>
                
                <div class="text-center mt-3">
                    <p class="mb-0">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="text-decoration-none">Login disini</a>
                    </p>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection 