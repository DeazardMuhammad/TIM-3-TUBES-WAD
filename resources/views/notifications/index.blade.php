@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-bell"></i> Notifikasi</h2>
        @if($notifications->where('read_status', false)->count() > 0)
            <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-check-all"></i> Tandai Semua Dibaca
                </button>
            </form>
        @endif
    </div>

    @if($notifications->isEmpty())
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Tidak ada notifikasi.
        </div>
    @else
        <div class="list-group">
            @foreach($notifications as $notification)
            <div class="list-group-item list-group-item-action {{ $notification->read_status ? '' : 'bg-light' }}">
                <div class="d-flex w-100 justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center mb-1">
                            @if($notification->type === 'success')
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                            @elseif($notification->type === 'danger')
                                <i class="bi bi-x-circle-fill text-danger me-2"></i>
                            @elseif($notification->type === 'warning')
                                <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                            @else
                                <i class="bi bi-info-circle-fill text-info me-2"></i>
                            @endif
                            <h6 class="mb-0">{{ $notification->title }}</h6>
                        </div>
                        <p class="mb-1">{{ $notification->message }}</p>
                        <small class="text-muted">
                            <i class="bi bi-clock"></i> {{ $notification->created_at->diffForHumans() }}
                        </small>
                    </div>
                    <div class="ms-3">
                        @if(!$notification->read_status)
                            <span class="badge bg-primary">Baru</span>
                        @endif
                    </div>
                </div>
                <div class="mt-2 d-flex gap-2">
                    @if($notification->link)
                        <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="bi bi-eye"></i> Lihat
                            </button>
                        </form>
                    @else
                        @if(!$notification->read_status)
                            <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-check"></i> Tandai Dibaca
                                </button>
                            </form>
                        @endif
                    @endif
                    <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus notifikasi ini?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection

