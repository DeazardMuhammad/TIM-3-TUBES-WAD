<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class LostItem extends Model
{
    use HasFactory;

    protected $table = 'lost_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'lokasi',
        'tanggal',
        'kontak',
        'deskripsi',
        'gambar',
        'status',
        'kategori_id',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Relasi ke tabel users
     * Setiap barang hilang milik satu user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke tabel kategori
     * Setiap barang hilang termasuk dalam satu kategori
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeBelumDiambil($query)
    {
        return $query->where('status', 'belum diambil');
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeSudahDiambil($query)
    {
        return $query->where('status', 'sudah diambil');
    }

    /**
     * Check apakah barang sudah diambil
     */
    public function isSudahDiambil()
    {
        return $this->status === 'sudah diambil';
    }

    /**
     * Get full image URL
     */
    public function getImageUrl()
    {
        if ($this->gambar && Storage::disk('public')->exists('images/lost/' . $this->gambar)) {
            return asset('storage/images/lost/' . $this->gambar);
        }
        return null; // Mengembalikan null jika tidak ada gambar atau file tidak ditemukan
    }
}
