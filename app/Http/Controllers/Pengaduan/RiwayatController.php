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
        $pengaduans = Pengaduan::where('status', 'proses')->with('user', 'kategori')->get();

        return view('pengaduan.proses.index', compact('pengaduans'));
    }

    public function show($id)
    {
        $pengaduan = Pengaduan::where('id', $id)->first();
        $detail_pengaduans = DetailPengaduan::where('pengaduan_id', $id)->orderBy('id', 'desc')->get();

        return view('pengaduan.proses.show', compact('pengaduan', 'detail_pengaduans'));
    }
}
