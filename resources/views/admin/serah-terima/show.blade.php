@extends('layouts.app')

@section('title', 'Serah Terima Barang - Admin')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="bi bi-arrow-left-right"></i> Serah Terima Barang</h4>
                </div>
                <div class="card-body">
                    <!-- Item & Claim Info -->
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Informasi Barang</h5>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="150">Barang:</th>
                                    <td>{{ $claim->foundItem->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Kategori:</th>
                                    <td>{{ $claim->foundItem->kategori->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Penemu:</th>
                                    <td>{{ $claim->foundItem->user->nama }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Informasi Pengklaim</h5>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="150">Nama:</th>
                                    <td>{{ $claim->user->nama }}</td>
                                </tr>
                                <tr>
                                    <th>NIM:</th>
                                    <td>{{ $claim->user->nim }}</td>
                                </tr>
                                <tr>
                                    <th>Kontak:</th>
                                    <td>{{ $claim->user->kontak }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <!-- User Bukti -->
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Bukti User</h5>
                            @if($claim->serahTerima && $claim->serahTerima->bukti_serah_terima_user)
                                <img src="{{ $claim->serahTerima->getUserBuktiUrl() }}" class="img-fluid rounded mb-2" style="max-height: 300px;">
                                <p><small class="text-muted">Diupload: {{ $claim->serahTerima->user_uploaded_at->format('d M Y H:i') }}</small></p>
                            @else
                                <div class="alert alert-warning">
                                    <i class="bi bi-clock"></i> User belum upload bukti
                                </div>
                            @endif
                        </div>

                        <!-- Admin Bukti -->
                        <div class="col-md-6">
                            <h5>Bukti Admin</h5>
                            @if($claim->serahTerima && $claim->serahTerima->bukti_serah_terima_admin)
                                <img src="{{ $claim->serahTerima->getAdminBuktiUrl() }}" class="img-fluid rounded mb-2" style="max-height: 300px;">
                                <p><small class="text-muted">Diupload: {{ $claim->serahTerima->admin_uploaded_at->format('d M Y H:i') }}</small></p>
                            @else
                                @if($claim->serahTerima && $claim->serahTerima->bukti_serah_terima_user)
                                    <form action="{{ route('admin.serah-terima.upload-admin', $claim->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Upload Foto Bukti *</label>
                                            <input type="file" name="bukti_serah_terima_admin" class="form-control" accept="image/*" required>
                                        </div>
                                        <button type="submit" class="btn btn-success">
                                            <i class="bi bi-upload"></i> Upload Bukti
                                        </button>
                                    </form>
                                @else
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle"></i> Menunggu user upload bukti terlebih dahulu
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                    <!-- Completion Status -->
                    @if($claim->serahTerima && $claim->serahTerima->isCompleted())
                        <div class="alert alert-success mt-4">
                            <h5><i class="bi bi-check-circle"></i> Serah Terima Selesai!</h5>
                            <p class="mb-0">Diselesaikan pada: {{ $claim->serahTerima->completed_at->format('d M Y H:i') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

