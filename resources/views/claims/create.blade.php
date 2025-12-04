@extends('layouts.app')

@section('title', 'Form Klaim Barang')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-clipboard-check"></i> Form Klaim Barang</h4>
                </div>
                <div class="card-body">
                    <!-- Item Info -->
                    <div class="alert alert-info mb-4">
                        <h5>Barang yang Diklaim:</h5>
                        <p class="mb-1"><strong>{{ $foundItem->nama }}</strong></p>
                        <p class="mb-1"><small><i class="bi bi-tag"></i> {{ $foundItem->kategori->nama }}</small></p>
                        <p class="mb-1"><small><i class="bi bi-geo-alt"></i> {{ $foundItem->lokasi }}</small></p>
                        <p class="mb-0"><small><i class="bi bi-calendar"></i> {{ $foundItem->tanggal->format('d M Y') }}</small></p>
                    </div>

                    <form action="{{ route('claims.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="found_item_id" value="{{ $foundItem->id }}">

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap *</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->nama }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">NIM *</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->nim }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" class="form-control" value="{{ auth()->user()->email }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kategori Barang *</label>
                            <input type="text" class="form-control" value="{{ $foundItem->kategori->nama }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi_klaim" class="form-label">Deskripsi Klaim *</label>
                            <textarea name="deskripsi_klaim" id="deskripsi_klaim" class="form-control @error('deskripsi_klaim') is-invalid @enderror" rows="5" required placeholder="Jelaskan mengapa barang ini milik Anda (ciri-ciri khusus, lokasi kehilangan, dll.)">{{ old('deskripsi_klaim') }}</textarea>
                            @error('deskripsi_klaim')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Jelaskan secara detail mengapa barang ini adalah milik Anda</small>
                        </div>

                        <div class="mb-3">
                            <label for="bukti" class="form-label">Bukti Kepemilikan (Opsional)</label>
                            <input type="file" name="bukti" id="bukti" class="form-control @error('bukti') is-invalid @enderror" accept="image/*">
                            @error('bukti')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Upload foto bukti kepemilikan (struk pembelian, foto barang saat masih dimiliki, dll.)</small>
                        </div>

                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> <strong>Catatan:</strong> Klaim Anda akan diverifikasi oleh admin. Pastikan informasi yang Anda berikan akurat.
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('found-items.show', $foundItem->id) }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Kirim Klaim
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

