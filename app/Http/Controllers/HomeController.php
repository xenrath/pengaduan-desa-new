<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $menunggu = Pengaduan::where('status', 'menunggu')->count();
        $proses = Pengaduan::where('status', 'proses')->count();
        $riwayat = Pengaduan::where('status', 'selesai')->count();

        $bulan = array();
        for ($i = 1; $i <= 12; $i++) {
            $jumlah = $i - 12;
            $bulan_data['label'] = Carbon::now()->addMonth($jumlah)->format('M Y');
            $bulan_data['month'] = Carbon::now()->addMonth($jumlah)->format('m');
            $bulan_data['year'] = Carbon::now()->addMonth($jumlah)->format('Y');

            $bulan[] = $bulan_data;
        }

        $label = array();
        $data = array();
        foreach ($bulan as $b) {
            $jumlah_pengaduan = Pengaduan::where('status', 'selesai')
                ->whereMonth('tanggal_selesai', $b['month'])
                ->whereYear('tanggal_selesai', $b['year'])
                ->count();
            $label[] = $b['label'];
            $data[] = $jumlah_pengaduan;
        }

        return view('home', compact('menunggu', 'proses', 'riwayat', 'data', 'label'));
    }
}
