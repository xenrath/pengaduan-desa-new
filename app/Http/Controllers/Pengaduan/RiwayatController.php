<?php

namespace App\Http\Controllers\Pengaduan;

use App\Http\Controllers\Controller;
use App\Models\DetailPengaduan;
use App\Models\Pengaduan;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index()
    {
        $pengaduans = Pengaduan::where('status', 'selesai')->orWhere('status', 'tolak')->with('user', 'kategori')->get();

        return view('pengaduan.riwayat.index', compact('pengaduans'));
    }

    public function show($id)
    {
        $pengaduan = Pengaduan::where('id', $id)->first();
        $detail_pengaduans = DetailPengaduan::where('pengaduan_id', $id)->orderBy('id', 'desc')->get();

        return view('pengaduan.riwayat.show', compact('pengaduan', 'detail_pengaduans'));
    }
}
