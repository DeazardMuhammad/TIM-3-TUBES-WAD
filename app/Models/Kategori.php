<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
    ];

    /**
     * Relasi ke tabel lost_items
     * Satu kategori bisa memiliki banyak barang hilang
     */
    public function lostItems()
    {
        return $this->hasMany(LostItem::class);
    }

    /**
     * Relasi ke tabel found_items
     * Satu kategori bisa memiliki banyak barang ditemukan
     */
    public function foundItems()
    {
        return $this->hasMany(FoundItem::class);
    }
}
