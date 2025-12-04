<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'nim',
        'email',
        'kontak',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke tabel lost_items
     * Seorang user bisa memiliki banyak laporan barang hilang
     */
    public function lostItems()
    {
        return $this->hasMany(LostItem::class);
    }

    /**
     * Relasi ke tabel found_items
     * Seorang user bisa memiliki banyak laporan barang ditemukan
     */
    public function foundItems()
    {
        return $this->hasMany(FoundItem::class);
    }

    /**
     * Check apakah user adalah admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check apakah user adalah mahasiswa
     */
    public function isMahasiswa()
    {
        return $this->role === 'mahasiswa';
    }

    /**
     * Relasi ke notifications
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Relasi ke claims
     */
    public function claims()
    {
        return $this->hasMany(Claim::class);
    }

    /**
     * Relasi ke notes (admin only)
     */
    public function notes()
    {
        return $this->hasMany(Note::class, 'admin_id');
    }

    /**
     * Relasi ke feedback
     */
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    /**
     * Relasi ke messages sent
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Relasi ke messages received
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Get unread notifications count
     */
    public function unreadNotificationsCount()
    {
        return $this->notifications()->where('read_status', false)->count();
    }

    /**
     * Get unread messages count
     */
    public function unreadMessagesCount()
    {
        return $this->receivedMessages()->where('read_status', false)->count();
    }
    
}
