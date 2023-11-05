<?php

namespace App\Http\Controllers\Pengaduan;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProsesController extends Controller
{
    public function index()
    {
        $pengaduans = Pengaduan::where('status', 'proses')->with('user', 'kategori')->get();

        return view('pengaduan.proses.index', compact('pengaduans'));
    }

    public function show($id)
    {
        $pengaduan = Pengaduan::where('id', $id)->first();

        return view('pengaduan.proses.show', compact('pengaduan'));
    }

    public function selesai($id)
    {
        Pengaduan::where('id', $id)->update([
            'tanggal_akhir' => Carbon::now()->format('Y-m-d'),
            'status' => 'selesai'
        ]);

        alert()->success('Success', 'Berhasil memproses Pengaduan');
        return back();
    }
}
