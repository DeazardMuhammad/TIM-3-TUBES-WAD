@extends('layouts.app')

@section('title', 'Chat Pengguna')

@section('content')
<div class="container py-4">
    <h2 class="mb-4"><i class="bi bi-chat-dots"></i> Chat Pengguna</h2>

    @if($users->isEmpty())
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Belum ada percakapan.
        </div>
    @else
        <div class="list-group">
            @foreach($users as $user)
            <a href="{{ route('admin.messages.chat', $user->id) }}" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">{{ $user->nama }}</h5>
                        <p class="mb-1 text-muted">
                            {{ $user->last_message ? Str::limit($user->last_message->message, 50) : 'Belum ada pesan' }}
                        </p>
                        <small class="text-muted">
                            {{ $user->last_message ? $user->last_message->created_at->diffForHumans() : '' }}
                        </small>
                    </div>
                    <div>
                        @if($user->unread_count > 0)
                            <span class="badge bg-danger rounded-pill">{{ $user->unread_count }}</span>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    @endif
</div>
@endsection

