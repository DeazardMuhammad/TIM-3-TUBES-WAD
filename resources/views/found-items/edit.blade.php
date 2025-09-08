@extends('layouts.app')

@section('title', 'Edit Barang Ditemukan')

@section('content')
<div class="container py-4">
    <h4 class="mb-3 text-warning"><i class="bi bi-pencil-square"></i> Edit Barang Ditemukan</h4>

    <form action="{{ route('found-items.update', $foundItem) }}" method="POST" enctype="multipart/form-data" class="card shadow-sm p-4">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nama Barang</label>
                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $foundItem->nama) }}" required>
                @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Kategori</label>
                <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror" required>
                    <option value="">Pilih Kategori</option>
                    @foreach($kategoriList as $kategori)
                        <option value="{{ $kategori->id }}" {{ old('kategori_id', $foundItem->kategori_id) == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama }}
                        </option>
                    @endforeach
                </select>
                @error('kategori_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Lokasi Ditemukan</label>
                <input type="text" name="lokasi" class="form-control @error('lokasi') is-invalid @enderror" value="{{ old('lokasi', $foundItem->lokasi) }}" required>
                @error('lokasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Tanggal Ditemukan</label>
                <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', $foundItem->tanggal->format('Y-m-d')) }}" required>
                @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Kontak</label>
                <input type="text" name="kontak" class="form-control @error('kontak') is-invalid @enderror" value="{{ old('kontak', $foundItem->kontak) }}" required>
                @error('kontak') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            @if(auth()->user()->isAdmin())
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="belum diambil" {{ old('status', $foundItem->status) == 'belum diambil' ? 'selected' : '' }}>Belum Diambil</option>
                    <option value="sudah diambil" {{ old('status', $foundItem->status) == 'sudah diambil' ? 'selected' : '' }}>Sudah Diambil</option>
                </select>
                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            @endif

            <div class="col-12">
                <label class="form-label">Deskripsi (Opsional)</label>
                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="3">{{ old('deskripsi', $foundItem->deskripsi) }}</textarea>
                @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            @if($foundItem->gambar)
            <div class="col-md-6">
                <label class="form-label">Foto Saat Ini</label>
                <div><img src="{{ $foundItem->getImageUrl() }}" class="img-thumbnail" style="max-height:150px;"></div>
            </div>
            @endif

            <div class="col-md-6">
                <label class="form-label">Upload Foto Baru (Opsional)</label>
                <input type="file" name="gambar" class="form-control @error('gambar') is-invalid @enderror" accept="image/*">
                @error('gambar') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <div>
                <a href="{{ route('found-items.show', $foundItem) }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>
            <div>
                <button type="reset" class="btn btn-outline-warning me-2"><i class="bi bi-arrow-clockwise"></i> Reset</button>
                <button type="submit" class="btn btn-warning"><i class="bi bi-check-lg"></i> Update</button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.querySelector('input[name="gambar"]').addEventListener('change', function (e) {
        const preview = document.getElementById('preview');
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                if (!preview) {
                    const img = document.createElement('img');
                    img.id = 'preview';
                    img.classList.add('img-thumbnail', 'mt-2');
                    img.style.maxHeight = '150px';
                    e.target.closest('.col-md-6').appendChild(img);
                }
                document.getElementById('preview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection
