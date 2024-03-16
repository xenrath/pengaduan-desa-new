<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $menunggu = Pengaduan::where('status', 'menunggu')->count();
        $proses = Pengaduan::where('status', 'proses')->count();
        $selesai = Pengaduan::where('status', 'selesai')->count();
        $tolak = Pengaduan::where('status', 'tolak')->count();

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

        $status = $request->status;

        foreach ($bulan as $b) {
            if ($status != "") {
                $jumlah_pengaduan = Pengaduan::where('status', $status)
                    ->whereMonth('updated_at', $b['month'])
                    ->whereYear('updated_at', $b['year'])
                    ->count();
            } else {
                $jumlah_pengaduan = Pengaduan::whereMonth('updated_at', $b['month'])
                    ->whereYear('updated_at', $b['year'])
                    ->count();
            }
            $label[] = $b['label'];
            $data[] = $jumlah_pengaduan;
        }

        return view('home', compact('menunggu', 'proses', 'selesai', 'tolak', 'data', 'label'));
    }
}
