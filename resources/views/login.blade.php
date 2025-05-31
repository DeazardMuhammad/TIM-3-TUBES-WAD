@extends('layouts.app')

@section('title', 'Login - Lost and Found')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card">
            <div class="card-header text-center">
                <h3 class="text-primary">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </h3>
                <p class="text-muted mb-0">Masuk ke akun Anda</p>
            </div>
            <div class="card-body">`
        
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

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope"></i> Email
                        </label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               placeholder="Masukkan email Anda"
                               required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock"></i> Password
                        </label>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               placeholder="Masukkan password Anda"
                               required>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </button>
                    </div>
                </form>
                
                <div class="text-center mt-3">
                    <p class="mb-0">
                        Belum punya akun? 
                        <a href="{{ route('register') }}" class="text-decoration-none">Daftar disini</a>
                    </p>
                </div>
            </div>
            
            <!-- Test Accounts -->
            <div class="card-footer">
                <div class="row">
                    <div class="col-6">
                        <small class="text-muted">
                            <strong>Admin:</strong><br>
                            admin@lostandfound.com<br>
                            admin123
                        </small>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">
                            <strong>Mahasiswa:</strong><br>
                            mahasiswa@test.com<br>
                            password
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 