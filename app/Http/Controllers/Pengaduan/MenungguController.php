<?php

namespace App\Http\Controllers\Pengaduan;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use App\Models\User;
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
        // Pengaduan::where('id', $id)->update([
        //     'tanggal_proses' => Carbon::now()->format('Y-m-d'),
        //     'status' => 'proses'
        // ]);

        $pengaduan = Pengaduan::where('id', $id)->first();

        $this->send_notification($pengaduan->user->telp, "Pengaduan Anda Di Proses");

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
