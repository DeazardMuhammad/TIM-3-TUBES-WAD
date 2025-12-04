@extends('layouts.app')

@section('title', 'Klaim Saya')

@section('content')
<div class="container py-4">
    <h2 class="mb-4"><i class="bi bi-clipboard-check"></i> Klaim Saya</h2>

    @if($claims->isEmpty())
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Anda belum mengajukan klaim apapun.
        </div>
    @else
        <div class="row">
            @foreach($claims as $claim)
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    @if($claim->foundItem->gambar)
                        <img src="{{ asset('storage/images/found/' . $claim->foundItem->gambar) }}" class="card-img-top" alt="{{ $claim->foundItem->nama }}" style="height: 200px; object-fit: cover;" onerror="this.style.display='none'">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $claim->foundItem->nama }}</h5>
                        <p class="card-text">
                            <small class="text-muted">
                                <i class="bi bi-tag"></i> {{ $claim->foundItem->kategori->nama }}<br>
                                <i class="bi bi-calendar"></i> Diklaim: {{ $claim->created_at->format('d M Y') }}
                            </small>
                        </p>
                        
                        <div class="mb-3">
                            <strong>Status:</strong>
                            @if($claim->status === 'pending')
                                <span class="badge bg-warning">Menunggu Verifikasi</span>
                            @elseif($claim->status === 'accepted')
                                <span class="badge bg-success">Disetujui</span>
                            @else
                                <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </div>

                        @if($claim->status === 'accepted')
                            <a href="{{ route('serah-terima.show', $claim->id) }}" class="btn btn-success w-100">
                                <i class="bi bi-arrow-left-right"></i> Serah Terima
                            </a>
                        @elseif($claim->status === 'rejected')
                            <div class="alert alert-danger mb-0">
                                <strong>Alasan:</strong> {{ $claim->alasan_reject }}
                            </div>
                        @else
                            <div class="alert alert-info mb-0">
                                <i class="bi bi-clock"></i> Menunggu verifikasi admin
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $claims->links() }}
        </div>
    @endif
</div>
@endsection

