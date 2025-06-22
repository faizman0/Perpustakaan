<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Memproses login
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Coba melakukan login
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            // Jika berhasil, regenerate session dan redirect ke halaman yang dimaksud
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        // Jika gagal, kembali ke halaman login dengan pesan error
        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }

    // Memproses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}