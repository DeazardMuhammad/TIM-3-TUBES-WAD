<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'found_item_id',
        'deskripsi_klaim',
        'bukti',
        'status',
        'alasan_reject',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    /**
     * Relasi ke user (claimant)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke found item
     */
    public function foundItem()
    {
        return $this->belongsTo(FoundItem::class);
    }

    /**
     * Relasi ke admin yang mereview
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Relasi ke serah terima
     */
    public function serahTerima()
    {
        return $this->hasOne(SerahTerima::class);
    }

    /**
     * Relasi ke feedback
     */
    public function feedback()
    {
        return $this->hasOne(Feedback::class);
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Get bukti image URL
     */
    public function getBuktiUrl()
    {
        if ($this->bukti && Storage::disk('public')->exists('images/claims/' . $this->bukti)) {
            return asset('storage/images/claims/' . $this->bukti);
        }
        return null;
    }
}

