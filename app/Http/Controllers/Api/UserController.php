<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'telp' => 'required',
            'password' => 'required',
        ], [
            'telp.required' => 'Nomor telepon tidak boleh kosong!',
            'password.required' => 'Password tidak boleh kosong!'
        ]);

        if ($validator->fails()) {
            return $this->response(false, $validator->errors()->all()[0]);
        }

        $telp = $request->telp;
        $password = $request->password;

        $user = User::where([
            ['telp', $telp],
            ['role', 'user']
        ])->first();
        if ($user) {
            if (password_verify($password, $user->password)) {
                if ($user->is_verif) {
                    return $this->response(true, 'Selamat Datang ' . $user->name, $user);
                } else {
                    return $this->response(false, 'Akun belum terverifikasi, kirim kode verifikasi?', $user);
                }
            } else {
                return $this->response(false, 'Nomor telepon dan password tidak sesuai!');
            }
        } else {
            return $this->response(false, 'Pengguna tidak ditemukan!');
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'telp' => 'required|unique:users',
            'password' => 'required',
        ], [
            'nama.required' => 'Nama tidak boleh kosong!',
            'telp.required' => 'Nomor telepon tidak boleh kosong!',
            'password.required' => 'Password tidak boleh kosong!',
        ]);

        if ($validator->fails()) {
            return $this->response(false, $validator->errors()->all()[0]);
        }

        $user = User::create([
            'nama' => $request->nama,
            'telp' => $request->telp,
            'password' => bcrypt($request->password),
            'role' => 'user'
        ]);

        if ($user) {
            $this->otp($user->id, false);
            return $this->response(true, 'Pendaftaran berhasil, silahkan lakukan verifikasi terlebih dahulu', $user);
        } else {
            return $this->response(false, 'Pendaftaran gagal, ' + $validator->errors()->all()[0]);
        }
    }

    public function verifikasi(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required',
        ], [
            'kode.required' => 'Kode tidak boleh kosong!',
        ]);

        if ($validator->fails()) {
            return $this->response(false, $validator->errors()->all()[0]);
        }

        $telp = User::where('id', $id)->value('telp');
        $kode = Otp::where('telp', $telp)->value('kode');

        if ($request->kode == $kode) {
            User::where('id', $id)->update([
                'is_verif' => true
            ]);
            return $this->response(true, 'Verifikasi berhasil, Anda sudah dapat login');
        } else {
            return $this->response(false, 'Kode verifikasi salah!');
        }
    }

    public function otp($id, $resend = true)
    {
        $user = User::where('id', $id)->first();
        $otp = Otp::where('telp', $user->telp)->first();
        $kode = rand(100000, 999999);

        if ($otp) {
            Otp::where('telp', $user->telp)->update([
                'kode' => $kode
            ]);
        } else {
            Otp::create([
                'telp' => $user->telp,
                'kode' => $kode
            ]);
        }

        $curl = curl_init();
        $data = [
            'target' => $user->telp,
            'message' => "Kode OTP Pengaduan Desa : " . $kode
        ];

        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            array(
                "Authorization: i0XBqAozh8uYyMpXe0#2",
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

        if ($resend) {
            return $this->response(true, 'Kode OTP terkirim', $user);
        }
    }

    public function show($id)
    {
        $user = User::where('id', $id)->first();

        if ($user) {
            return $this->response(true, 'Berhasil menampilkan user', $user);
        } else {
            return $this->response(false, 'Gagal menampilkan user!');
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
