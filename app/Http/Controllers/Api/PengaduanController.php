<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DetailPengaduan;
use App\Models\Kategori;
use App\Models\Komentar;
use App\Models\Pengaduan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PengaduanController extends Controller
{
    public function list_all()
    {
        $pengaduans = Pengaduan::where('status', 'proses')->orWhere('status', 'selesai')->with('kategori')->get();

        if ($pengaduans) {
            return $this->response(true, 'Pengaduan berhasil ditampilkan', $pengaduans);
        } else {
            return $this->response(false, 'Pengaduan kosong!');
        }
    }

    public function list($user_id)
    {
        $pengaduans = Pengaduan::where('user_id', $user_id)->with('kategori')->get();

        if ($pengaduans) {
            return $this->response(true, 'Pengaduan berhasil ditampilkan', $pengaduans);
        } else {
            return $this->response(false, 'Pengaduan kosong!');
        }
    }

    public function search(Request $request)
    {
        $keyword = $request->keyword;

        $pengaduans = Pengaduan::where(function ($query) {
            $query->where('status', 'proses');
            $query->orWhere('status', 'selesai');
        })->where(function ($query) use ($keyword) {
            $query->where('deskripsi', 'like', "%$keyword%");
            $query->orWhere('lokasi', 'like', "%$keyword%");
            $query->orWhere('patokan', 'like', "%$keyword%");
        })->with('kategori')->get();

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
            'lokasi' => 'required',
            'patokan' => 'required',
            'gambar' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ], [
            'user_id.required' => 'User tidak ditemukan!',
            'kategori_id.required' => 'Kategori harus dipilih!',
            'deskripsi.required' => 'Deskripsi tidak boleh kosong!',
            'lokasi.required' => 'Lokasi tidak boleh kosong!',
            'patokan.required' => 'Patokan tidak boleh kosong!',
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
            'lokasi' => $request->lokasi,
            'patokan' => $request->patokan,
            'gambar' => $gambar_nama,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'jam_aduan' => Carbon::now()->format('H:i'),
            'tanggal_aduan' => Carbon::now()->format('Y-m-d'),
            'status' => 'menunggu',
        ]);

        if ($pengaduan) {
            $this->send_notification($request->user_id, "Pengaduan baru!");
            return $this->response(true, 'Pengaduan berhasil ditambahkan');
        } else {
            return $this->response(false, 'Pengaduan gagal ditambahkan!');
        }
    }

    public function show($id)
    {
        $pengaduan = Pengaduan::where('id', $id)->with('user', 'kategori', 'detail_pengaduans', 'komentars.user')->first();

        if ($pengaduan) {
            return $this->response(true, 'Detail Pengaduan berhasil ditampilkan', $pengaduan);
        } else {
            return $this->response(false, 'Detail Pengaduan kosong!');
        }
    }

    public function list_proses($id)
    {
        $detail_pengaduans = DetailPengaduan::where('pengaduan_id', $id)->get();

        if ($detail_pengaduans) {
            return $this->response(true, 'List Proses berhasil ditampilkan', $detail_pengaduans);
        } else {
            return $this->response(false, 'List Proses kosong!');
        }
    }

    public function list_komentar($id)
    {
        $komentars = Komentar::where('pengaduan_id', $id)->with('user')->get();

        if ($komentars) {
            return $this->response(true, 'Komentar berhasil ditampilkan', $komentars);
        } else {
            return $this->response(false, 'Komentar kosong!');
        }
    }

    public function get_kategori()
    {
        $kategoris = Kategori::get();

        if ($kategoris) {
            return $this->response(true, 'Berhasil menampilkan kategori', $kategoris);
        } else {
            return $this->response(false, 'Gagal menampilkan kategori!');
        }
    }

    public function send_notification($id, $message)
    {
        $telp = User::where('id', '1')->value('telp');

        $curl = curl_init();
        $data = [
            'target' => $telp,
            'message' => $message
        ];

        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            array(
                "Authorization: NMW3zyUNYAwudny96K_@",
            )
        );

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_URL, "https://api.fonnte.com/send");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        $result = curl_exec($curl);

        curl_close($curl);
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
