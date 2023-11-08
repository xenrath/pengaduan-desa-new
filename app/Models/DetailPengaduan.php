<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPengaduan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengaduan_id',
        'deskripsi',
        'gambar'
    ];

    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class);
    }
}
