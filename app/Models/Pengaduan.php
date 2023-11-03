<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kategori_id',
        'daskripsi',
        'gambar'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}
