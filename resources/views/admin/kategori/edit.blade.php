@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
<div class="container mt-4">
    <h3>Edit Kategori</h3>
    <a href="{{ route('admin.kategori.index') }}" class="btn btn-sm btn-secondary mb-3">← Kembali</a>

    <form action="{{ route('admin.kategori.update', $kategori) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Kategori</label>
            <input type="text" 
                   id="nama" 
                   name="nama" 
                   class="form-control @error('nama') is-invalid @enderror" 
                   value="{{ old('nama', $kategori->nama) }}"
                   placeholder="Contoh: Elektronik, Pakaian" required>
            @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-warning">Update</button>
        <a href="{{ route('admin.kategori.index') }}" class="btn btn-outline-secondary">Batal</a>
    </form>

</div>
@endsection
