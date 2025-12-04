<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'report_type',
        'report_id',
        'isi_catatan',
    ];

    /**
     * Relasi ke admin
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get the related report (polymorphic-like)
     */
    public function getReportAttribute()
    {
        if ($this->report_type === 'lost') {
            return LostItem::find($this->report_id);
        } elseif ($this->report_type === 'found') {
            return FoundItem::find($this->report_id);
        }
        return null;
    }
}

