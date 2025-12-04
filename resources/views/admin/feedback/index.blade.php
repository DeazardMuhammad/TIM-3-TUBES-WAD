@extends('layouts.app')

@section('title', 'Feedback & Rating')

@section('content')
<div class="container py-4">
    <h2 class="mb-4"><i class="bi bi-star"></i> Feedback & Rating</h2>

    @if($feedbacks->isEmpty())
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Belum ada feedback yang masuk.
        </div>
    @else
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Barang</th>
                                <th>Rating</th>
                                <th>Komentar</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($feedbacks as $feedback)
                            <tr>
                                <td>
                                    {{ $feedback->user->nama }}<br>
                                    <small class="text-muted">{{ $feedback->user->nim }}</small>
                                </td>
                                <td>
                                    {{ $feedback->claim->foundItem->nama }}<br>
                                    <small class="text-muted">{{ $feedback->claim->foundItem->kategori->nama }}</small>
                                </td>
                                <td>
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $feedback->rating)
                                            <i class="bi bi-star-fill text-warning"></i>
                                        @else
                                            <i class="bi bi-star text-muted"></i>
                                        @endif
                                    @endfor
                                    <br>
                                    <small class="text-muted">({{ $feedback->rating }}/5)</small>
                                </td>
                                <td>{{ Str::limit($feedback->komentar, 50) }}</td>
                                <td>{{ $feedback->created_at->format('d M Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $feedbacks->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

