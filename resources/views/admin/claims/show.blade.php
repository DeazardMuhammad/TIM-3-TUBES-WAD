@extends('layouts.app')

@section('title', 'Detail Klaim')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-clipboard-check"></i> Detail Klaim</h4>
                </div>
                <div class="card-body">
                    <h5>Informasi Pengklaim</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th width="150">Nama:</th>
                            <td>{{ $claim->user->nama }}</td>
                        </tr>
                        <tr>
                            <th>NIM:</th>
                            <td>{{ $claim->user->nim }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $claim->user->email }}</td>
                        </tr>
                        <tr>
                            <th>Kontak:</th>
                            <td>{{ $claim->user->kontak }}</td>
                        </tr>
                    </table>

                    <hr>

                    <h5>Barang yang Diklaim</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th width="150">Nama Barang:</th>
                            <td>{{ $claim->foundItem->nama }}</td>
                        </tr>
                        <tr>
                            <th>Kategori:</th>
                            <td>{{ $claim->foundItem->kategori->nama }}</td>
                        </tr>
                        <tr>
                            <th>Lokasi Ditemukan:</th>
                            <td>{{ $claim->foundItem->lokasi }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal:</th>
                            <td>{{ $claim->foundItem->tanggal->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>Penemu:</th>
                            <td>{{ $claim->foundItem->user->nama }}</td>
                        </tr>
                    </table>

                    @if($claim->foundItem->gambar)
                        <div class="mb-3">
                            <img src="{{ asset('storage/images/found/' . $claim->foundItem->gambar) }}" alt="{{ $claim->foundItem->nama }}" class="img-fluid rounded" style="max-height: 300px;" onerror="this.style.display='none'">
                        </div>
                    @endif

                    <hr>

                    <h5>Deskripsi Klaim</h5>
                    <p>{{ $claim->deskripsi_klaim }}</p>

                    @if($claim->bukti)
                        <h5>Bukti Kepemilikan</h5>
                        <img src="{{ $claim->getBuktiUrl() }}" alt="Bukti" class="img-fluid rounded" style="max-height: 300px;">
                    @endif

                    <hr>

                    <table class="table table-borderless">
                        <tr>
                            <th width="150">Status:</th>
                            <td>
                                @if($claim->status === 'pending')
                                    <span class="badge bg-warning">Menunggu Verifikasi</span>
                                @elseif($claim->status === 'accepted')
                                    <span class="badge bg-success">Disetujui</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                        </tr>
                        @if($claim->status !== 'pending')
                            <tr>
                                <th>Diverifikasi oleh:</th>
                                <td>{{ $claim->reviewer->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Verifikasi:</th>
                                <td>{{ $claim->reviewed_at ? $claim->reviewed_at->format('d M Y H:i') : '-' }}</td>
                            </tr>
                        @endif
                        @if($claim->status === 'rejected' && $claim->alasan_reject)
                            <tr>
                                <th>Alasan Penolakan:</th>
                                <td>{{ $claim->alasan_reject }}</td>
                            </tr>
                        @endif
                    </table>

                    @if($claim->status === 'pending')
                        <div class="d-flex gap-2 mt-4">
                            <form action="{{ route('admin.claims.accept', $claim->id) }}" method="POST" class="flex-fill">
                                @csrf
                                <button type="submit" class="btn btn-success w-100" onclick="return confirm('Setujui klaim ini?')">
                                    <i class="bi bi-check-circle"></i> Setujui Klaim
                                </button>
                            </form>

                            <button type="button" class="btn btn-danger flex-fill" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="bi bi-x-circle"></i> Tolak Klaim
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.claims.index') }}" class="btn btn-secondary w-100 mb-2">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <a href="{{ route('found-items.show', $claim->foundItem->id) }}" class="btn btn-info w-100 mb-2">
                        <i class="bi bi-box"></i> Lihat Barang
                    </a>
                    @if($claim->status === 'accepted')
                        <a href="{{ route('admin.serah-terima.show', $claim->id) }}" class="btn btn-primary w-100">
                            <i class="bi bi-arrow-left-right"></i> Serah Terima
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.claims.reject', $claim->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tolak Klaim</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Alasan Penolakan</label>
                            <textarea name="alasan_reject" class="form-control" rows="4" required placeholder="Masukkan alasan penolakan..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Tolak Klaim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

