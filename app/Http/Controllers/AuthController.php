<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // Menampilkan Halaman Form Login
    public function index()
    {
        return view('auth.login');
    }

    // Proses Autentikasi (Sama seperti public function login di API-mu)
    public function authenticate(Request $request)
    {
        // 1. Validasi Input (Kita pakai email, bukan NIK)
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // 2. Cek User & Password (Auth::attempt sudah mengecek Hash otomatis)
        if (Auth::attempt($credentials)) {

            // 3. Buat Session Baru (Pengganti createToken di API)
            $request->session()->regenerate();

            $user = Auth::user();

            // 4. Redirect berdasarkan Role (Pengganti return JSON)
            if ($user->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }

            if ($user->role === 'kurir') {
                return redirect()->intended('/courier/dashboard');
            }
        }

        // 5. Jika Gagal Login (Pengganti response()->json status error)
        return back()->withErrors([
            'email' => 'Email atau Password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // Proses Logout
    public function logout(Request $request)
    {
        // 1. Hapus Session Auth (Pengganti currentAccessToken()->delete())
        Auth::logout();

        // 2. Bersihkan sisa session browser
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // 3. Redirect ke halaman Login
        return redirect('/login');
    }
}
