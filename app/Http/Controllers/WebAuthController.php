<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.index');
    }

    public function login(Request $request)
    {
        // 1. Ubah validasi dari 'email' menjadi 'identifier'
        $request->validate([
            // Tidak menggunakan '|email' karena bisa berupa NIM
            'identifier' => 'required|string', 
            'password' => 'required'
        ]);

        $identifier = $request->input('identifier');
        $password = $request->input('password');
        
        // 2. Tentukan field otentikasi (email atau nim)
        // Asumsi: Jika input tidak valid email, maka itu adalah NIM. 
        // Anda harus memastikan kolom 'nim' ada di tabel 'users'.
        $field = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'nim'; 

        $credentials = [
            $field => $identifier,
            'password' => $password
        ];
        
        // Ambil status 'remember' dari form (checkbox 'Ingat saya')
        $remember = $request->filled('remember');

        // 3. Coba otentikasi menggunakan credentials yang dinamis dan state 'remember'
        if (!Auth::attempt($credentials, $remember)) {
            // Beri pesan error yang lebih umum untuk keamanan
            return back()->withErrors([
                'login' => 'Kredensial yang Anda masukkan tidak cocok dengan catatan kami.', 
            ])->withInput();
        }

        // Regenerasi session untuk keamanan
        $request->session()->regenerate(); 
        $user = Auth::user();

        // 4. Lanjutkan dengan logika redirect berdasarkan role
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
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