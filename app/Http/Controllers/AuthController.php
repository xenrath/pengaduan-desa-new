<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'telp' => 'required',
            'password' => 'required',
        ], [
            'telp.required' => 'Username tidak boleh kosong!',
            'password.required' => 'Password tidak boleh kosong!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            alert()->error('Error!', $error);
            return back()->onlyInput('telp');
        }

        $user = User::where('telp', $request->telp)->first();

        if ($user) {
            if ($user->role != 'admin') {
                alert()->error('Error!', 'Username atau Password salah!');
                return back()->onlyInput('telp');
            }
        } else {
            alert()->error('Error!', 'Username atau Password salah!');
            return back()->onlyInput('telp');
        }

        if (Auth::attempt(['telp' => $request->telp, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }
    }

    public function logout()
    {
        auth()->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }
}
