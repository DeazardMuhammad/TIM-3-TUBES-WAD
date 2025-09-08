@extends('layouts.app')

@section('title', 'Kelola Kategori')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <div>
            <h4 class="mb-1">Kelola Kategori</h4>
            <small class="text-muted">Manajemen kategori barang Lost & Found</small>
        </div>
        <a href="{{ route('admin.kategori.create') }}" class="btn btn-primary">
            + Tambah Kategori
        </a>
    </div>

    @if($kategoris->count())
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Hilang</th>
                        <th>Ditemukan</th>
                        <th>Total</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kategoris as $kategori)
                        <tr>
                            <td>{{ $loop->iteration + ($kategoris->currentPage() - 1) * $kategoris->perPage() }}</td>
                            <td>{{ $kategori->nama }}</td>
                            <td><span class="badge bg-danger">{{ $kategori->lost_items_count }}</span></td>
                            <td><span class="badge bg-success">{{ $kategori->found_items_count }}</span></td>
                            <td><span class="badge bg-secondary">{{ $kategori->lost_items_count + $kategori->found_items_count }}</span></td>
                            <td>{{ $kategori->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.kategori.show', $kategori) }}" class="btn btn-sm btn-outline-info">Lihat</a>
                                <a href="{{ route('admin.kategori.edit', $kategori) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                <form action="{{ route('admin.kategori.destroy', $kategori) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus kategori {{ $kategori->nama }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" {{ ($kategori->lost_items_count + $kategori->found_items_count) > 0 ? 'disabled' : '' }}>Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-center">
            {{ $kategoris->links() }}
        </div>

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="alert alert-primary text-center">
                    <strong>{{ $kategoris->total() }}</strong><br>Total Kategori
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-danger text-center">
                    <strong>{{ $kategoris->sum('lost_items_count') }}</strong><br>Total Barang Hilang
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-success text-center">
                    <strong>{{ $kategoris->sum('found_items_count') }}</strong><br>Total Barang Ditemukan
                </div>
            </div>
        </div>
    @else
        <div class="text-center my-5">
            <h5>Belum Ada Kategori</h5>
            <p class="text-muted">Silakan tambah kategori untuk mulai mengelola barang.</p>
            <a href="{{ route('admin.kategori.create') }}" class="btn btn-primary">Tambah Kategori</a>
        </div>
    @endif
</div>
@endsection
