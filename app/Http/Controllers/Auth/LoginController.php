<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Menampilkan halaman login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses Login
    public function login(Request $request)
    {
        // 1. Validasi Input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            // PENTING: Key input name HTML harus 'password', meski di DB 'password_hash'
            'password' => ['required'], 
        ]);

        // 2. Coba Login
        // Auth::attempt otomatis pakai getAuthPassword() di Model User buat ngecek hash
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // 3. Cek Role (Case Insensitive biar aman)
            if (strtolower(Auth::user()->role) === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }

            // Kalau login sukses tapi bukan admin
            Auth::logout();
            return back()->with('error', 'Akun Anda tidak memiliki akses Admin.');
        }

        // 4. Jika Gagal
        return back()->with('error', 'Email atau password salah.');
    }

    // Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}