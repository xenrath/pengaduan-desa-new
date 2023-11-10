<?php

namespace App\Http\Controllers\Pengaduan;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MenungguController extends Controller
{
    public function index()
    {
        $pengaduans = Pengaduan::where('status', 'menunggu')->with('user', 'kategori')->get();

        return view('pengaduan.menunggu.index', compact('pengaduans'));
    }

    public function show($id)
    {
        $pengaduan = Pengaduan::where('id', $id)->first();

        $result = [
            ['nama' => $pengaduan->kategori->nama],
            ['latitude' => $pengaduan->latitude],
            ['longitude' => $pengaduan->longitude],
        ];

        $result_lat_long = json_encode($result);

        return view('pengaduan.menunggu.show', compact('pengaduan', 'result_lat_long'));
    }

    public function proses($id)
    {
        Pengaduan::where('id', $id)->update([
            'tanggal_proses' => Carbon::now()->format('Y-m-d'),
            'status' => 'proses'
        ]);

        alert()->success('Success', 'Berhasil memproses Pengaduan');
        return back();
    }

    public function tolak($id)
    {
        Pengaduan::where('id', $id)->update([
            'status' => 'tolak'
        ]);

        alert()->success('Success', 'Berhasil menolak Pengaduan');
        return back();
    }
}
