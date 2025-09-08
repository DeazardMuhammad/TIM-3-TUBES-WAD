@extends('layouts.app')

@section('title', 'Detail Kategori')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <div>
            <h4 class="mb-1">Detail Kategori</h4>
            <small class="text-muted">Informasi kategori "{{ $kategori->nama }}"</small>
        </div>
        <div>
            <a href="{{ route('admin.kategori.edit', $kategori) }}" class="btn btn-warning btn-sm">Edit</a>
            <a href="{{ route('admin.kategori.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-light fw-bold">Informasi Kategori</div>
                <div class="card-body">
                    <p><strong>Nama:</strong><br>{{ $kategori->nama }}</p>
                    <p><strong>Dibuat:</strong><br>{{ $kategori->created_at->format('d F Y, H:i') }}</p>
                    <p><strong>Diperbarui:</strong><br>{{ $kategori->updated_at->format('d F Y, H:i') }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-light fw-bold">Statistik</div>
                <div class="card-body row text-center">
                    <div class="col">
                        <h5 class="text-danger">{{ $kategori->lost_items_count ?? 0 }}</h5>
                        <small>Hilang</small>
                    </div>
                    <div class="col">
                        <h5 class="text-success">{{ $kategori->found_items_count ?? 0 }}</h5>
                        <small>Ditemukan</small>
                    </div>
                    <div class="col">
                        <h5 class="text-primary">{{ ($kategori->lost_items_count ?? 0) + ($kategori->found_items_count ?? 0) }}</h5>
                        <small>Total</small>
                    </div>
                    <div class="col">
                        @php
                            $activeReports = $lostItems->where('status', 'belum diambil')->count() + 
                                             $foundItems->where('status', 'belum diambil')->count();
                        @endphp
                        <h5 class="text-warning">{{ $activeReports }}</h5>
                        <small>Belum Diambil</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-danger text-white fw-bold">Barang Hilang Terbaru</div>
                <div class="card-body">
                    @forelse($lostItems as $item)
                        <div class="border-bottom py-2">
                            <strong>{{ $item->nama }}</strong><br>
                            <small class="text-muted">{{ $item->user->nama }} - {{ $item->lokasi }} - {{ $item->tanggal->format('d/m/Y') }}</small>
                            <span class="badge bg-{{ $item->status == 'belum diambil' ? 'danger' : 'success' }} float-end">
                                {{ ucfirst($item->status) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-muted text-center">Tidak ada barang hilang.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white fw-bold">Barang Ditemukan Terbaru</div>
                <div class="card-body">
                    @forelse($foundItems as $item)
                        <div class="border-bottom py-2">
                            <strong>{{ $item->nama }}</strong><br>
                            <small class="text-muted">{{ $item->user->nama }} - {{ $item->lokasi }} - {{ $item->tanggal->format('d/m/Y') }}</small>
                            <span class="badge bg-{{ $item->status == 'belum diambil' ? 'danger' : 'success' }} float-end">
                                {{ ucfirst($item->status) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-muted text-center">Tidak ada barang ditemukan.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

