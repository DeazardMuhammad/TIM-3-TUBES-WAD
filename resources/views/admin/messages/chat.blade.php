@extends('layouts.app')

@section('title', 'Chat dengan ' . $user->nama)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-chat-dots"></i> Chat dengan {{ $user->nama }}
                    </h5>
                    <a href="{{ route('admin.messages.index') }}" class="btn btn-sm btn-light">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="card-body" style="height: 500px; overflow-y: auto;" id="chatMessages">
                    @foreach($messages as $message)
                    <div class="mb-3 {{ $message->sender_id == auth()->id() ? 'text-end' : '' }}" data-message-id="{{ $message->id }}">
                        <div class="d-inline-block {{ $message->sender_id == auth()->id() ? 'bg-primary text-white' : 'bg-light' }} p-3 rounded" style="max-width: 70%;">
                            <div class="mb-1">
                                <strong>{{ $message->sender->nama }}</strong>
                                @if($message->sender->isAdmin())
                                    <span class="badge bg-danger">Admin</span>
                                @endif
                            </div>
                            <div>{{ $message->message }}</div>
                            <small class="text-muted d-block mt-1">
                                {{ $message->created_at->format('H:i') }}
                            </small>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="card-footer">
                    <form id="sendMessageForm" class="d-flex gap-2">
                        <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                        <input type="text" name="message" id="messageInput" class="form-control" placeholder="Ketik pesan..." required>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> Kirim
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let lastMessageId = {{ $messages->last()->id ?? 0 }};
const renderedMessages = new Set();
document.querySelectorAll('#chatMessages [data-message-id]').forEach(el => {
    renderedMessages.add(parseInt(el.dataset.messageId, 10));
});
const userId = {{ $user->id }};

// Auto scroll to bottom
function scrollToBottom() {
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

scrollToBottom();

// Send message
document.getElementById('sendMessageForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const message = formData.get('message');
    
    if (!message.trim()) return;
    
    try {
        const response = await fetch('{{ route("messages.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                receiver_id: userId,
                message: message
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('messageInput').value = '';
            appendMessage(data.message, true);
            lastMessageId = parseInt(data.message.id, 10);
        }
    } catch (error) {
        console.error('Error:', error);
    }
});

// Append message to chat
function appendMessage(message, isSender = false) {
    if (renderedMessages.has(message.id)) {
        return;
    }
    const chatMessages = document.getElementById('chatMessages');
    const messageDiv = document.createElement('div');
    messageDiv.className = `mb-3 ${isSender ? 'text-end' : ''}`;
    messageDiv.dataset.messageId = message.id;
    
    const time = new Date(message.created_at).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'});
    
    messageDiv.innerHTML = `
        <div class="d-inline-block ${isSender ? 'bg-primary text-white' : 'bg-light'} p-3 rounded" style="max-width: 70%;">
            <div class="mb-1">
                <strong>${message.sender.nama}</strong>
                ${message.sender.role === 'admin' ? '<span class="badge bg-danger">Admin</span>' : ''}
            </div>
            <div>${message.message}</div>
            <small class="text-muted d-block mt-1">${time}</small>
        </div>
    `;
    
    chatMessages.appendChild(messageDiv);
    renderedMessages.add(message.id);
    scrollToBottom();
}

// Poll for new messages
setInterval(async function() {
    try {
        const response = await fetch(`{{ route("messages.get") }}?user_id=${userId}&last_message_id=${lastMessageId}`);
        const data = await response.json();
        
        if (data.success && data.messages.length > 0) {
            data.messages.forEach(message => {
                const isSender = message.sender_id == {{ auth()->id() }};
                appendMessage(message, isSender);
                lastMessageId = parseInt(message.id, 10);
            });
        }
    } catch (error) {
        console.error('Error polling messages:', error);
    }
}, 3000); // Poll every 3 seconds
</script>
@endpush

