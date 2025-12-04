@extends('layouts.app')

@section('title', 'Edit Barang Hilang')

@section('content')
<div class="container">
    <div class="mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('lost-items.index') }}">Barang Hilang</a></li>
                <li class="breadcrumb-item"><a href="{{ route('lost-items.show', $lostItem) }}">{{ $lostItem->nama }}</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
        <h4 class="text-warning">Edit Barang Hilang</h4>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('lost-items.update', $lostItem) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama', $lostItem->nama) }}" class="form-control @error('nama') is-invalid @enderror" required>
                        @error('nama')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoriList as $kategori)
                                <option value="{{ $kategori->id }}" {{ old('kategori_id', $lostItem->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_id')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Lokasi Hilang <span class="text-danger">*</span></label>
                        <input type="text" name="lokasi" value="{{ old('lokasi', $lostItem->lokasi) }}" class="form-control @error('lokasi') is-invalid @enderror" required>
                        @error('lokasi')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Hilang <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', $lostItem->tanggal->format('Y-m-d')) }}" class="form-control @error('tanggal') is-invalid @enderror" required>
                        @error('tanggal')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kontak <span class="text-danger">*</span></label>
                    <input type="text" name="kontak" value="{{ old('kontak', $lostItem->kontak) }}" class="form-control @error('kontak') is-invalid @enderror" required>
                    @error('kontak')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>

                @if(auth()->user()->isAdmin())
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="belum diambil" {{ old('status', $lostItem->status) == 'belum diambil' ? 'selected' : '' }}>Belum Ditemukan</option>
                            <option value="sudah diambil" {{ old('status', $lostItem->status) == 'sudah diambil' ? 'selected' : '' }}>Sudah Ditemukan</option>
                        </select>
                        @error('status')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" rows="4" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $lostItem->deskripsi) }}</textarea>
                    @error('deskripsi')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>

                @if($lostItem->gambar)
                    <div class="mb-3">
                        <label class="form-label">Foto Saat Ini</label><br>
                        <img src="{{ asset('storage/images/lost/' . $lostItem->gambar) }}" class="img-thumbnail" style="max-width: 180px;">
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label">Foto Baru (Opsional)</label>
                    <input type="file" name="gambar" class="form-control @error('gambar') is-invalid @enderror" accept="image/jpeg,image/png">
                    @error('gambar')<div class="text-danger small">{{ $message }}</div>@enderror
                    <small class="text-muted">Kosongkan jika tidak ingin mengubah foto.</small>
                    <div id="imagePreview" class="mt-2" style="display: none;">
                        <img id="preview" src="#" alt="Preview" class="img-thumbnail" style="max-width: 180px;">
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <div>
                        <a href="{{ route('lost-items.show', $lostItem) }}" class="btn btn-secondary">Kembali</a>
                        <a href="{{ route('lost-items.index') }}" class="btn btn-outline-secondary">Daftar</a>
                    </div>
                    <div>
                        <button type="reset" class="btn btn-outline-warning">Reset</button>
                        <button type="submit" class="btn btn-warning">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('gambar').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('preview');
        const container = document.getElementById('imagePreview');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            container.style.display = 'none';
        }
    });
</script>
@endpush
@endsection
