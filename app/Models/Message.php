<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'read_status',
    ];

    protected $casts = [
        'read_status' => 'boolean',
    ];

    /**
     * Relasi ke sender
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Relasi ke receiver
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Scope untuk unread messages
     */
    public function scopeUnread($query)
    {
        return $query->where('read_status', false);
    }

    /**
     * Mark as read
     */
    public function markAsRead()
    {
        $this->update(['read_status' => true]);
    }
}

