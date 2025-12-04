<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class SerahTerima extends Model
{
    use HasFactory;

    protected $table = 'serah_terima';

    protected $fillable = [
        'claim_id',
        'bukti_serah_terima_user',
        'bukti_serah_terima_admin',
        'user_uploaded_at',
        'admin_uploaded_at',
        'completed_at',
        'status',
    ];

    protected $casts = [
        'user_uploaded_at' => 'datetime',
        'admin_uploaded_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Relasi ke claim
     */
    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }

    /**
     * Get user bukti image URL
     */
    public function getUserBuktiUrl()
    {
        if ($this->bukti_serah_terima_user && Storage::disk('public')->exists('images/serah-terima/' . $this->bukti_serah_terima_user)) {
            return asset('storage/images/serah-terima/' . $this->bukti_serah_terima_user);
        }
        return null;
    }

    /**
     * Get admin bukti image URL
     */
    public function getAdminBuktiUrl()
    {
        if ($this->bukti_serah_terima_admin && Storage::disk('public')->exists('images/serah-terima/' . $this->bukti_serah_terima_admin)) {
            return asset('storage/images/serah-terima/' . $this->bukti_serah_terima_admin);
        }
        return null;
    }

    /**
     * Check if completed
     */
    public function isCompleted()
    {
        return $this->status === 'completed' && $this->completed_at !== null;
    }
}

