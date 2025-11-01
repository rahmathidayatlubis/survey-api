<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebAuthController extends Controller
{
    public function showLoginForm()
    {
        // Validasi sudah login/ belum
        if (!Auth::check()) {
            return view('auth.index'); // Gunakan return view()
        }

        // Cek role user yang sedang login
        $user = Auth::user();

        if ($user->role === 'admin') {
            // Redirect ke dashboard admin
            return redirect('/admin/dashboard');
        } elseif ($user->role === 'mahasiswa') {
            // Redirect ke dashboard mahasiswa
            return redirect('/mahasiswa/dashboard');
        }

        // Paksa logout jika user yang login tidak mahasiswa atau admin
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return view('auth.index');
    }

    public function login(Request $request)
    {
        // Validasi identifier login dan password
        $request->validate([
            'identifier' => 'required|string',
            'password' => 'required'
        ]);

        $identifier = $request->input('identifier');
        $password = $request->input('password');

        // Cek identifier yang digunakan
        $field = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'nim';

        $credentials = [
            $field => $identifier,
            'password' => $password
        ];

        // Ambil status 'remember' dari form (checkbox 'Ingat saya')
        $remember = $request->filled('remember');

        // Coba otentikasi menggunakan credentials yang dinamis dan state 'remember'
        if (!Auth::attempt($credentials, $remember)) {
            // Beri pesan error yang lebih umum untuk keamanan
            return back()->withErrors([
                'login' => 'Kredensial yang Anda masukkan tidak cocok dengan catatan kami.',
            ])->withInput();
        }

        // Regenerasi session untuk keamanan
        $request->session()->regenerate();
        $user = Auth::user();

        // Lanjutkan dengan logika redirect berdasarkan role
        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        }

        if ($user->role === 'mahasiswa') {
            return redirect()->route('mahasiswa.dashboard');
        }

        return back()->withErrors([
            'role' => 'Role pengguna tidak dikenali!'
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
