@extends('layouts.app')

@section('title', 'Catatan Laporan')

@section('content')
<div class="container py-4">
    <div class="card shadow">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">
                <i class="bi bi-journal-text"></i> Catatan Laporan
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Left: Item Summary -->
                <div class="col-md-4">
                    <div class="card border">
                        @if($item->gambar)
                            <img src="{{ asset('storage/images/' . $reportType . '/' . $item->gambar) }}" class="card-img-top" alt="{{ $item->nama }}" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h6 class="card-title fw-bold">{{ $item->nama }}</h6>
                            <p class="card-text mb-1">
                                <i class="bi bi-geo-alt text-danger"></i> {{ $item->lokasi }}
                            </p>
                            <p class="card-text mb-1">
                                <i class="bi bi-calendar text-warning"></i> {{ $item->tanggal->format('d F Y') }}
                            </p>
                            <p class="card-text mb-0">
                                <i class="bi bi-tag text-info"></i> {{ $item->kategori->nama }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ $reportType == 'lost' ? route('lost-items.show', $item->id) : route('found-items.show', $item->id) }}" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-arrow-left"></i> Kembali ke Detail
                        </a>
                    </div>
                </div>

                <!-- Right: Notes -->
                <div class="col-md-8">
                    <!-- Add Note Form -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Tambahkan catatan</h6>
                        <form action="{{ route('admin.notes.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="report_type" value="{{ $reportType }}">
                            <input type="hidden" name="report_id" value="{{ $item->id }}">
                            
                            <div class="mb-3">
                                <textarea name="isi_catatan" class="form-control @error('isi_catatan') is-invalid @enderror" rows="4" placeholder="Masukkan catatan baru..." required></textarea>
                                @error('isi_catatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Simpan
                                </button>
                            </div>
                        </form>
                    </div>

                    <hr>

                    <!-- Notes History -->
                    <h6 class="fw-bold mb-3">Riwayat catatan</h6>
                    <div class="notes-list" style="max-height: 500px; overflow-y: auto;">
                        @forelse($notes as $note)
                            <div class="card mb-3 border-start border-primary border-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <strong class="text-primary">{{ $note->admin->nama }}</strong>
                                            <small class="text-muted ms-2">{{ $note->created_at->format('d F Y, H:i') }} WIB</small>
                                        </div>
                                        <form action="{{ route('admin.notes.destroy', $note->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus catatan ini?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <p class="mb-0" style="white-space: pre-wrap;">{{ $note->isi_catatan }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> Belum ada catatan untuk laporan ini.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

