@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
<div class="container mt-4">
    <h3>Tambah Kategori</h3>
    <a href="{{ route('admin.kategori.index') }}" class="btn btn-sm btn-secondary mb-3">← Kembali</a>

    <form action="{{ route('admin.kategori.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Kategori</label>
            <input type="text" 
                   id="nama" 
                   name="nama" 
                   class="form-control @error('nama') is-invalid @enderror" 
                   placeholder="Contoh: Elektronik, Pakaian"
                   value="{{ old('nama') }}" required>
            @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.kategori.index') }}" class="btn btn-outline-secondary">Batal</a>
    </form>

    <div class="mt-4">
        <h6>Tips Membuat Kategori</h6>
        <ul>
            <li>Gunakan nama yang spesifik dan mudah dipahami</li>
            <li>Pastikan belum ada kategori dengan nama yang sama</li>
            <li>Hindari kategori yang terlalu umum seperti "Lainnya"</li>
        </ul>
    </div>
</div>
@endsection
