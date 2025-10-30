<?php

namespace App\Http\Controllers;

use App\Models\User; // Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Tambahkan ini
use Illuminate\Validation\ValidationException; // Tambahkan ini

class WebAuthController extends Controller
{
    // ... method showLoginForm() dan login(Request $request) yang sudah ada ...

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
    
    // --- Penambahan Method Registrasi Web ---

    /**
     * Menampilkan formulir registrasi.
     */
    public function showRegisterForm()
    {
        // Asumsi: Anda akan menyimpan file HTML Anda sebagai resources/views/auth/register.blade.php
        return view('auth.register'); 
    }

    /**
     * Memproses permintaan registrasi mahasiswa.
     */
    public function register(Request $request)
    {
        // Validasi, mirip dengan AuthController API tetapi dengan konfirmasi password
        $validated = $request->validate([
            'nim' => 'required|string|unique:users,nim',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'tanggal_lahir' => 'required|date',
            'jurusan' => 'nullable|string|max:255',
            // Memerlukan password dan konfirmasi untuk web
            'password' => 'required|string|min:8|confirmed', 
            // Tambahkan validasi untuk checkbox 'terms'
            'terms' => 'accepted', 
        ]);

        // Proses pembuatan user
        User::create([
            'nim' => $validated['nim'],
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'jurusan' => $validated['jurusan'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'mahasiswa',
        ]);

        // Opsional: Langsung login setelah registrasi
        // Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']]);
        // $request->session()->regenerate();
        // return redirect()->route('mahasiswa.dashboard')->with('status', 'Registrasi berhasil! Anda telah masuk.');


        // Redirect ke halaman login dengan pesan sukses
        return redirect()->route('login')
                         ->with('status', 'Registrasi berhasil. Silakan masuk menggunakan NIM/Email dan password Anda.');
    }

    // ... method logout() yang sudah ada ...
    
    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate(); // Invalidate session
        request()->session()->regenerateToken(); // Regenerate CSRF token

        return redirect()->route('login');
    }
}