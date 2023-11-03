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
                if ($user->status) {
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
            $this->otp($user->telp);
            return $this->response(true, 'Pendaftaran berhasil, silahkan lakukan verifikasi terlebih dahulu', $user);
        } else {
            return $this->response(false, 'Pendaftaran gagal, ' + $validator->errors()->all()[0]);
        }
    }

    public function otp($telp)
    {
        $user = Otp::where('telp', $telp)->exists();
        $otp = rand(100000, 999999);

        if ($user) {
            Otp::where('telp', $telp)->update([
                'kode' => $otp
            ]);
        } else {
            Otp::create([
                'telp' => $telp,
                'kode' => $otp
            ]);
        }

        $curl = curl_init();
        $data = [
            'target' => $telp,
            'message' => "Kode OTP Pengaduan Desa : " . $otp
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
