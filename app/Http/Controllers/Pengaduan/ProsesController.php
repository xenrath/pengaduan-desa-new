<?php

namespace App\Http\Controllers\Pengaduan;

use App\Http\Controllers\Controller;
use App\Models\DetailPengaduan;
use App\Models\Komentar;
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
        $komentars = Komentar::where('pengaduan_id', $id)->orderBy('id', 'desc')->get();

        return view('pengaduan.proses.show', compact('pengaduan', 'detail_pengaduans', 'komentars'));
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

        $pengaduan = Pengaduan::where('id', $id)->first();
        $jumlah = DetailPengaduan::where('pengaduan_id', $id)->count();

        $this->send_notification($pengaduan->user->telp, "Pengaduan Anda Di Proses Tahap " . $jumlah);

        alert()->success('Success!', 'Berhasil menambahkan Detail Pengaduan');
        return back();
    }

    public function selesai($id)
    {
        Pengaduan::where('id', $id)->update([
            'tanggal_selesai' => Carbon::now()->format('Y-m-d'),
            'status' => 'selesai'
        ]);

        $pengaduan = Pengaduan::where('id', $id)->first();

        $this->send_notification($pengaduan->user->telp, "Pengaduan Anda Telah Selesai");

        alert()->success('Success', 'Berhasil memproses Pengaduan');
        return redirect('pengaduan/proses');
    }

    public function send_notification($telp, $message)
    {
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
}
