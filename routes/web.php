<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebAuthController;
use App\Http\Controllers\MahasiswaController; // Wajib diimpor
use App\Http\Controllers\SurveyController; // Wajib diimpor
use App\Http\Controllers\AdminDashboardController;

// --- 1. RUTE UNTUK PENGGUNA TAMU (GUEST) ---
Route::get('/', [WebAuthController::class, 'showLoginForm'])->name('login');
Route::post('/process-login', [WebAuthController::class, 'login'])->name('process.login');

// REGISTRASI
Route::get('/register', [WebAuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [WebAuthController::class, 'register'])->name('register.post');

// --- 2. RUTE UNTUK PENGGUNA TEROTENTIKASI (AUTH) ---
Route::middleware('auth')->group(function () {
    // LOGOUT
    Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

    // DASHBOARD & FITUR MAHASISWA (Dibungkus dalam grup prefix dan name: 'mahasiswa.')
    Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [MahasiswaController::class, 'dashboard'])->name('dashboard');

        // [DAFTAR SURVEY] - Menuju MahasiswaController@index
        Route::get('/data-survey', [MahasiswaController::class, 'index'])->name('survey'); 

        // [FORMULIR SURVEY SPESIFIK]
        Route::get('/survey-form/{uuid}', [MahasiswaController::class, 'show'])->name('survey.show');
        
        // [SUBMIT JAWABAN]
        Route::post('/survey-form/{uuid}/submit', [MahasiswaController::class, 'submit'])->name('survey.submit');

        // [RIWAYAT - FIX]
        Route::get('/data-riwayat', [MahasiswaController::class, 'riwayat'])->name('riwayat');

    });


    // DASHBOARD & FITUR ADMIN
    Route::prefix('admin')->name('admin.')->group(function () {
        // Rute Admin Pengguna (sudah ada)
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/dashboard/activity-data', [AdminDashboardController::class, 'getActivityData'])->name('dashboard.activity.data'); // <<< INI YANG HILANG
        Route::get('/data-mahasiswa', [UserController::class, 'index'])->name('user');
        Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
        Route::get('/user/show/{id}', [UserController::class, 'show'])->name('user.show');
        Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::put('/user/{id}/update', [UserController::class, 'update'])->name('user.update');
        Route::delete('/user/{id}/destroy', [UserController::class, 'destroy'])->name('user.destroy');

        // --- RUTE LENGKAP CRUD UNTUK SURVEY ---
        
        // 1. Index: Daftar Survey
        Route::get('/data-survey', [SurveyController::class, 'index'])->name('survey');
        
        // 2. Create: Form Tambah (admin.survey.create)
        Route::get('/data-survey/create', [SurveyController::class, 'create'])->name('survey.create');
        
        // 3. Store: Simpan Data Baru (admin.survey.store)
        Route::post('/data-survey/store', [SurveyController::class, 'store'])->name('survey.store');
        
        // 4. Show: Detail Survey (admin.survey.show)
        Route::get('/data-survey/{uuid}', [SurveyController::class, 'show'])->name('survey.show');
        
        // 5. Edit: Form Edit (admin.survey.edit)
        Route::get('/data-survey/{uuid}/edit', [SurveyController::class, 'edit'])->name('survey.edit');
        
        // 6. Update: Proses Update Data (admin.survey.update)
        Route::put('/data-survey/{uuid}/update', [SurveyController::class, 'update'])->name('survey.update');
        
        // 7. Destroy: Hapus Data (admin.survey.destroy)
        Route::delete('/data-survey/{uuid}/destroy', [SurveyController::class, 'destroy'])->name('survey.destroy');
        

        // Rute Admin lainnya
        Route::get('/data-laporan', function () { return view('admin.laporan'); })->name('laporan');
        Route::get('/data-hasil', function () { return view('admin.hasil'); })->name('hasil');
    });
});
