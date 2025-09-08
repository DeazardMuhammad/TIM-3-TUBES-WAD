@extends('layouts.app')

@section('title', 'Lapor Barang Ditemukan')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Lapor Barang Ditemukan</h2>

    <form action="{{ route('found-items.store') }}" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
        @csrf

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Barang *</label>
            <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
                   value="{{ old('nama') }}" required>
            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="kategori_id" class="form-label">Kategori *</label>
            <select name="kategori_id" id="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategoriList as $kategori)
                    <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                        {{ $kategori->nama }}
                    </option>
                @endforeach
            </select>
            @error('kategori_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="lokasi" class="form-label">Lokasi Ditemukan *</label>
            <input type="text" name="lokasi" id="lokasi" class="form-control @error('lokasi') is-invalid @enderror"
                   value="{{ old('lokasi') }}" required>
            @error('lokasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal Ditemukan *</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                   value="{{ old('tanggal', date('Y-m-d')) }}" required>
            @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="kontak" class="form-label">Kontak *</label>
            <input type="text" name="kontak" id="kontak" class="form-control @error('kontak') is-invalid @enderror"
                   value="{{ old('kontak', auth()->user()->kontak) }}" required>
            @error('kontak') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="4"
                      placeholder="Detail ciri-ciri barang, warna, merek, dsb.">{{ old('deskripsi') }}</textarea>
            @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="gambar" class="form-label">Foto Barang</label>
            <input type="file" name="gambar" id="gambar" class="form-control @error('gambar') is-invalid @enderror"
                   accept="image/jpeg,image/png,image/jpg">
            @error('gambar') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <div id="imagePreview" class="mt-2" style="display: none;">
                <img id="preview" src="#" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">Lapor</button>
            <a href="{{ route('found-items.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
document.getElementById('gambar').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        document.getElementById('imagePreview').style.display = 'none';
    }
});
</script>
@endsection
