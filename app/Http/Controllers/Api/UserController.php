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
            $this->otp($user->id, $user->telp, false);
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

    public function otp($id, $telp, $resend = true)
    {
        $otp = Otp::where('telp', $telp)->first();
        $kode = rand(100000, 999999);

        if ($otp) {
            Otp::where('telp', $telp)->update([
                'kode' => $kode
            ]);
        } else {
            Otp::create([
                'telp' => $telp,
                'kode' => $kode
            ]);
        }

        $curl = curl_init();
        $data = [
            'target' => $telp,
            'message' => "Kode OTP Pengaduan Desa : " . $kode
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

        if ($resend) {
            $user = User::where('id', $id)->first();
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

    public function update_profile(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'telp' => 'required|unique:users,telp,' . $id,
        ], [
            'nama.required' => 'Nama tidak boleh kosong!',
            'telp.required' => 'Nomor telepon tidak boleh kosong!',
            'telp.unique' => 'Nomor telepon sudah digunakan!'
        ]);

        if ($validator->fails()) {
            return $this->response(false, $validator->errors()->all()[0]);
        }

        $user = User::where('id', $id)->update([
            'nama' => $request->nama,
            'telp' => $request->telp
        ]);

        if ($user) {
            return $this->response(true, 'Berhasil memperbarui user');
        } else {
            return $this->response(false, 'Gagal memperbarui user!');
        }
    }

    public function update_password(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed',
        ], [
            'password.required' => 'Password tidak boleh kosong!',
            'password.confirmed' => 'Konfirmasi password tidak sesuai!'
        ]);

        if ($validator->fails()) {
            return $this->response(false, $validator->errors()->all()[0]);
        }

        $user = User::where('id', $id)->update([
            'password' => bcrypt($request->password)
        ]);

        if ($user) {
            return $this->response(true, 'Berhasil memperbarui password');
        } else {
            return $this->response(false, 'Gagal memperbarui password!');
        }
    }

    public function verifikasi_profile(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required',
            'telp' => 'required',
        ], [
            'kode.required' => 'Kode tidak boleh kosong!',
            'telp.required' => 'Nomor telepon kosong!',
        ]);

        if ($validator->fails()) {
            return $this->response(false, $validator->errors()->all()[0]);
        }

        $kode = Otp::where('telp', $request->telp)->value('kode');

        if ($request->kode == $kode) {
            User::where('id', $id)->update([
                'telp' => $request->telp
            ]);
            return $this->response(true, 'Berhasil memperbarui profile');
        } else {
            return $this->response(false, 'Kode verifikasi salah!');
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
