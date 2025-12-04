@extends('layouts.app')

@section('title', 'Serah Terima Barang')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="bi bi-arrow-left-right"></i> Serah Terima Barang</h4>
                </div>
                <div class="card-body">
                    <!-- Klaim Info -->
                    <div class="alert alert-success">
                        <h5><i class="bi bi-check-circle"></i> Klaim Anda Disetujui!</h5>
                        <p class="mb-0">Silakan koordinasi untuk serah terima barang dan upload bukti serah terima.</p>
                    </div>

                    <!-- Item Info -->
                    <h5>Informasi Barang</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th width="180">Nama Barang:</th>
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
                    </table>

                    <hr>

                    <!-- Contact Info -->
                    <h5>Kontak Penemu</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th width="180">Nama:</th>
                            <td>{{ $claim->foundItem->user->nama }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $claim->foundItem->user->email }}</td>
                        </tr>
                        <tr>
                            <th>Kontak:</th>
                            <td>{{ $claim->foundItem->user->kontak }}</td>
                        </tr>
                    </table>

                    <hr>

                    <!-- Upload Bukti User -->
                    <h5>Bukti Serah Terima (Anda)</h5>
                    @if($claim->serahTerima && $claim->serahTerima->bukti_serah_terima_user)
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i> Bukti sudah diupload
                        </div>
                        <img src="{{ $claim->serahTerima->getUserBuktiUrl() }}" class="img-fluid rounded mb-3" style="max-height: 300px;">
                        <p><small class="text-muted">Diupload pada: {{ $claim->serahTerima->user_uploaded_at->format('d M Y H:i') }}</small></p>
                    @else
                        <form action="{{ route('serah-terima.upload-user', $claim->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Upload Foto Bukti Serah Terima *</label>
                                <input type="file" name="bukti_serah_terima_user" class="form-control" accept="image/*" required>
                                <small class="form-text text-muted">Upload foto saat menerima barang dari penemu</small>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-upload"></i> Upload Bukti
                            </button>
                        </form>
                    @endif

                    <hr>

                    <!-- Admin Bukti Status -->
                    <h5>Bukti Serah Terima (Admin)</h5>
                    @if($claim->serahTerima && $claim->serahTerima->bukti_serah_terima_admin)
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i> Admin telah mengupload bukti
                        </div>
                        <img src="{{ $claim->serahTerima->getAdminBuktiUrl() }}" class="img-fluid rounded mb-3" style="max-height: 300px;">
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-clock"></i> Menunggu admin mengupload bukti serah terima
                        </div>
                    @endif

                    <!-- Completion Status -->
                    @if($claim->serahTerima && $claim->serahTerima->isCompleted())
                        <div class="alert alert-success mt-4">
                            <h5><i class="bi bi-check-circle"></i> Serah Terima Selesai!</h5>
                            <p class="mb-0">Proses serah terima telah selesai. Silakan berikan feedback.</p>
                            <a href="{{ route('feedback.create', $claim->id) }}" class="btn btn-success mt-2">
                                <i class="bi bi-star"></i> Berikan Rating & Feedback
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

