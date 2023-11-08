<?php

namespace App\Http\Controllers\Pengaduan;

use App\Http\Controllers\Controller;
use App\Models\DetailPengaduan;
use App\Models\Pengaduan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $detail_pengaduans = DetailPengaduan::where('pengaduan_id', $id)->orderBy('id', 'desc')->get();

        return view('pengaduan.proses.show', compact('pengaduan', 'detail_pengaduans'));
    }

    // pengaduan id
    public function add_detail(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'deskripsi' => 'required',
            'gambar' => 'required',
        ], [
            'deskripsi.required' => 'Deskripsi harus diisi!',
            'gambar.required' => 'Gambar harus ditambahkan!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();

            alert()->error('Error!', $error[0]);
            return back()->withInput();
        }

        $gambar = str_replace(' ', '', $request->gambar->getClientOriginalName());
        $gambar_nama = 'detail-pengaduan/' . date('mYdHs') . rand(1, 10) . '_' . $gambar;
        $request->gambar->storeAs('public/uploads/', $gambar_nama);

        DetailPengaduan::create([
            'pengaduan_id' => $id,
            'deskripsi' => $request->deskripsi,
            'gambar' => $gambar_nama,
        ]);

        alert()->success('Success!', 'Berhasil menambahkan Detail Pengaduan');
        return back();
    }

    public function selesai($id)
    {
        Pengaduan::where('id', $id)->update([
            'tanggal_selesai' => Carbon::now()->format('Y-m-d'),
            'status' => 'selesai'
        ]);

        alert()->success('Success', 'Berhasil memproses Pengaduan');
        return redirect('pengaduan/proses');
    }
}
