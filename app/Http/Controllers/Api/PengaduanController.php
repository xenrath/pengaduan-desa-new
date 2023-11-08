<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PengaduanController extends Controller
{
    public function list_all()
    {
        $pengaduans = Pengaduan::where('status', 'proses')->orWhere('status', 'selesai')->get();

        if ($pengaduans) {
            return $this->response(true, 'Pengaduan berhasil ditampilkan', $pengaduans);
        } else {
            return $this->response(false, 'Pengaduan kosong!');
        }
    }

    public function list($user_id)
    {
        $pengaduans = Pengaduan::where('user_id', $user_id)->get();

        if ($pengaduans) {
            return $this->response(true, 'Pengaduan berhasil ditampilkan', $pengaduans);
        } else {
            return $this->response(false, 'Pengaduan kosong!');
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'kategori_id' => 'required',
            'deskripsi' => 'required',
            'gambar' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ], [
            'user_id.required' => 'User tidak ditemukan!',
            'kategori_id.required' => 'Kategori harus dipilih!',
            'deskripsi.required' => 'Deskripsi tidak boleh kosong!',
            'gambar.required' => 'Gambar harus diisi!',
            'latitude.required' => 'Latitude kosong!',
            'longitude.required' => 'Longitude kosong!'
        ]);

        if ($validator->fails()) {
            return $this->response(false, $validator->errors()->all()[0]);
        }

        $gambar = str_replace(' ', '', $request->gambar->getClientOriginalName());
        $gambar_nama = 'pengaduan/' . date('mYdHs') . rand(1, 10) . '_' . $gambar;
        $request->gambar->storeAs('public/uploads/', $gambar_nama);

        $pengaduan = Pengaduan::create([
            'user_id' => $request->user_id,
            'kategori_id' => $request->kategori_id,
            'deskripsi' => $request->deskripsi,
            'gambar' => $gambar_nama,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'jam_aduan' => Carbon::now()->format('H:i'),
            'tanggal_aduan' => Carbon::now()->format('Y-m-d'),
            'status' => 'menunggu',
        ]);

        if ($pengaduan) {
            return $this->response(true, 'Pengaduan berhasil ditambahkan');
        } else {
            return $this->response(false, 'Pengaduan gagal ditambahkan!');
        }
    }

    public function show($id)
    {
        $pengaduan = Pengaduan::where('id', $id)->with('user', 'kategori', 'detail_pengaduans')->first();

        if ($pengaduan) {
            return $this->response(true, 'Detail Pengaduan berhasil ditampilkan', $pengaduan);
        } else {
            return $this->response(false, 'Detail Pengaduan kosong!');
        }
    }

    public function response($status, $message, $data = null)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ]);
    }
}
