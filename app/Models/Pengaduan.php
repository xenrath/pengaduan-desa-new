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
        'deskripsi',
        'gambar',
        'alamat',
        'latitude',
        'longitude',
        'jam_aduan',
        'tanggal_aduan',
        'tanggal_proses',
        'tanggal_selesai',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function detail_pengaduans()
    {
        return $this->hasMany(DetailPengaduan::class);
    }
}
