<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebAuthController;

// --- 1. RUTE UNTUK PENGGUNA TAMU (GUEST) ---
// Rute ini hanya bisa diakses oleh pengguna yang BELUM login.
Route::get('/', [WebAuthController::class, 'showLoginForm'])->name('login'); 
// Memproses data login (URL: /process-login)
Route::post('/process-login', [WebAuthController::class, 'login'])->name('process.login'); 

// REGISTRASI
// Menampilkan form registrasi (URL: /register)
Route::get('/register', [WebAuthController::class, 'showRegisterForm'])->name('register'); 
// Memproses data registrasi (URL: /register POST)
Route::post('/register', [WebAuthController::class, 'register'])->name('register.post');

// --- 2. RUTE UNTUK PENGGUNA TEROTENTIKASI (AUTH) ---
// Rute ini hanya bisa diakses oleh pengguna yang SUDAH login.
Route::middleware('auth')->group(function () {
    
    // LOGOUT
    Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

    // DASHBOARD & FITUR MAHASISWA
    // Ini adalah rute yang akan dituju setelah login mahasiswa berhasil.
    Route::get('/mahasiswa/dashboard', function () {
        return view('mahasiswa.dashboard'); 
    })->name('mahasiswa.dashboard');

    Route::get('/data-survey', function () {
        return view('mahasiswa.survey'); 
    })->name('mahasiswa.survey');

    Route::get('/data-riwayat', function () {
        return view('mahasiswa.riwayat'); 
    })->name('mahasiswa.riwayat');
    
    // DASHBOARD & FITUR ADMIN
    Route::prefix('admin')->name('admin.')->group(function () {
        
        // Dashboard
        // Ini adalah rute yang akan dituju setelah login admin berhasil.
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Data Management
        Route::get('/data-mahasiswa', function () {
            return view('admin.user'); 
        })->name('user');

        Route::get('/data-survey', function () {
            return view('admin.survey'); 
        })->name('survey');

        Route::get('/data-laporan', function () {
            return view('admin.laporan'); 
        })->name('laporan');
        
        Route::get('/data-hasil', function () {
            return view('admin.hasil'); 
        })->name('hasil');
    });

});