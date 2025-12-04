<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedback';

    protected $fillable = [
        'claim_id',
        'user_id',
        'rating',
        'komentar',
    ];

    /**
     * Relasi ke claim
     */
    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }

    /**
     * Relasi ke user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

