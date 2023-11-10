<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'user')->get();

        return view('user.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::where('id', $id)->first();

        return view('user.show', compact('user'));
    }

    public function hubungi($id)
    {
        $telp = User::where('id', $id)->value('telp');

        return redirect()->away('https://web.whatsapp.com/send?phone=+62' . $telp);
    }
}
