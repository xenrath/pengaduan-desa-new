<?php

namespace App\Http\Controllers;

use App\Models\Komentar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KomentarController extends Controller
{
    public function index()
    {
        $komentars = Komentar::get();

        return view('komentar.index', compact('komentars'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'komentar' => 'required',
        ], [
            'komentar.required' => 'Pengadu harus dipilih!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        Komentar::where('id', $id)->update([
            'komentar' => $request->komentar
        ]);

        alert()->success('Success', 'Berhasil memperbarui Komentar');
        return redirect('komentar');
    }
}
