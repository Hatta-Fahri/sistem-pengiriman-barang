<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // Menampilkan halaman utama untuk masuk (login)
    public function index()
    {
        return view('auth.login');
    }

    // Proses verifikasi kredensial pengguna
    public function authenticate(Request $request)
    {
        // 1. Pastikan input email dan password diisi dengan format yang benar
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // 2. Lakukan pengecekan kecocokan data dengan yang ada di database
        if (Auth::attempt($credentials)) {

            // 3. Buat sesi baru untuk mengamankan status login pengguna
            $request->session()->regenerate();

            $user = Auth::user();

            // 4. Arahkan pengguna ke halaman dashboard yang sesuai dengan jabatannya
            if ($user->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }

            if ($user->role === 'kurir') {
                return redirect()->intended('/courier/dashboard');
            }
        }

        // 5. Kembalikan pengguna ke halaman login dengan pesan error jika kredensial salah
        return back()->withErrors([
            'email' => 'Email atau Password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // Proses keluar dari sistem
    public function logout(Request $request)
    {
        // 1. Akhiri sesi login pengguna saat ini
        Auth::logout();

        // 2. Hapus seluruh data sesi di browser demi keamanan
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // 3. Arahkan kembali ke halaman login
        return redirect('/login');
    }
}
