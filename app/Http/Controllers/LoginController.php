<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;



class LoginController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }



    // Menampilkan halaman login
    public function showLoginForm()
    {
        $user = User::latest()->get();

        return view('auth.login', compact('user')); // Pastikan ada file resources/views/auth/login.blade.php
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Coba autentikasi dengan username & password
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            return redirect()->intended('/admin/home'); // Redirect ke halaman admin jika sukses
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }

    // Logout user
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect('/'); // Redirect kembali ke halaman login
    }
}
