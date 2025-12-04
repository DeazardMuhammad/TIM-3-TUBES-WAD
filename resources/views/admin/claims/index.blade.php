@extends('layouts.app')

@section('title', 'Kelola Klaim')

@section('content')
<div class="container py-4">
    <h2 class="mb-4"><i class="bi bi-clipboard-check"></i> Kelola Klaim Barang</h2>

    <!-- Filter Tabs -->
    <ul class="nav nav-pills mb-4">
        <li class="nav-item">
            <a class="nav-link active" href="#">Semua ({{ $claims->total() }})</a>
        </li>
    </ul>

    <div class="card">
        <div class="card-body">
            @if($claims->isEmpty())
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Belum ada klaim yang masuk.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Barang</th>
                                <th>Pengklaim</th>
                                <th>Tanggal Klaim</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($claims as $claim)
                            <tr>
                                <td>
                                    <strong>{{ $claim->foundItem->nama }}</strong><br>
                                    <small class="text-muted">{{ $claim->foundItem->kategori->nama }}</small>
                                </td>
                                <td>
                                    {{ $claim->user->nama }}<br>
                                    <small class="text-muted">{{ $claim->user->nim }}</small>
                                </td>
                                <td>{{ $claim->created_at->format('d M Y') }}</td>
                                <td>
                                    @if($claim->status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($claim->status === 'accepted')
                                        <span class="badge bg-success">Disetujui</span>
                                    @else
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.claims.show', $claim->id) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $claims->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

