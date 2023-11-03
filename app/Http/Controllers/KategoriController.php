<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::get();

        return view('kategori.index', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required'
        ], [
            'nama.required' => 'Nama kategori harus diisi!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();

            alert()->error('Error!', $error[0]);
            return back()->withInput();
        }

        Kategori::create([
            'nama' => $request->nama
        ]);

        alert()->success('Success', 'Berhasil menambahkan Kategori');
        return back();
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required'
        ], [
            'nama.required' => 'Nama kategori harus diisi!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();

            alert()->error('Error!', $error[0]);
            return back()->withInput();
        }

        Kategori::where('id', $id)->update([
            'nama' => $request->nama
        ]);

        alert()->success('Success', 'Berhasil memperbarui Kategori');
        return back();
    }
    
    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();
        
        alert()->success('Success', 'Berhasil menghapus Kategori');
        return back();
    }
}
