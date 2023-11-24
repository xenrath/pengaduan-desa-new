<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Komentar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KomentarController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'pengaduan_id' => 'required',
            'komentar' => 'required',
        ], [
            'user_id.required' => 'User tidak ditemukan!',
            'pengaduan_id.required' => 'Pengaduan tidak ditemukan!',
            'komentar.required' => 'Komentar tidak boleh kosong!',
        ]);

        if ($validator->fails()) {
            return $this->response(false, $validator->errors()->all()[0]);
        }

        $komentar = Komentar::create([
            'user_id' => $request->user_id,
            'pengaduan_id' => $request->pengaduan_id,
            'komentar' => $request->komentar,
        ]);

        if ($komentar) {
            return $this->response(true, 'Komentar berhasil ditambahkan');
        } else {
            return $this->response(false, 'Komentar gagal ditambahkan!');
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
