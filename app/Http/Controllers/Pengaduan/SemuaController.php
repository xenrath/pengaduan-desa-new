<?php

namespace App\Http\Controllers\Pengaduan;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Pengaduan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SemuaController extends Controller
{
    public function index()
    {
        $pengaduans = Pengaduan::orderByDesc('id')->get();

        return view('pengaduan.semua.index', compact('pengaduans'));
    }

    public function edit($id)
    {
        $pengaduan = Pengaduan::where('id', $id)->first();
        $users = User::where('role', 'user')->get();
        $kategoris = Kategori::get();

        return view('pengaduan.semua.edit', compact('pengaduan', 'users', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'kategori_id' => 'required',
            'deskripsi' => 'required',
            'lokasi' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'user_id.required' => 'Pengadu harus dipilih!',
            'kategori_id.required' => 'Kategori harus dipilih!',
            'deskripsi.required' => 'Deskripsi harus diisi!',
            'lokasi.required' => 'Lokasi harus diisi!',
            'gambar.image' => 'Gambar yang dimasukan salah!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        $pengaduan = Pengaduan::findOrFail($id);

        if ($request->gambar) {
            Storage::disk('local')->delete('public/uploads/' . $pengaduan->gambar);
            $gambar = str_replace(' ', '', $request->gambar->getClientOriginalName());
            $namagambar = 'pengaduan/' . date('mYdHs') . rand(1, 10) . '_' . $gambar;
            $request->gambar->storeAs('public/uploads/', $namagambar);
        } else {
            $namagambar = $pengaduan->gambar;
        }

        Pengaduan::where('id', $id)->update([
            'user_id' => $request->user_id,
            'kategori_id' => $request->kategori_id,
            'deskripsi' => $request->deskripsi,
            'lokasi' => $request->lokasi,
            'gambar' => $namagambar,
        ]);

        alert()->success('Success', 'Berhasil memperbarui Pengaduan');
        return redirect('pengaduan/semua');
    }

    public function destroy($id)
    {
        $pengaduan = Pengaduan::findOrFail($id);
        $pengaduan->delete();

        alert()->success('Success', 'Berhasil menghapus Pengaduan');
        return redirect('pengaduan/semua');
    }
}
