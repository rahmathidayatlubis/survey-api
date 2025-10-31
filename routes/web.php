<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebAuthController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\DataHasilSurveyController;
use App\Http\Controllers\Admin\LaporanController;

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
        // Rute Admin Dashboard & User
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/dashboard/activity-data', [AdminDashboardController::class, 'getActivityData'])->name('dashboard.activity.data'); 
        Route::get('/data-mahasiswa', [UserController::class, 'index'])->name('user');
        Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
        Route::get('/user/show/{id}', [UserController::class, 'show'])->name('user.show');
        Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::put('/user/{id}/update', [UserController::class, 'update'])->name('user.update');
        Route::delete('/user/{id}/destroy', [UserController::class, 'destroy'])->name('user.destroy');

        // --- RUTE LENGKAP CRUD UNTUK SURVEY ---
        Route::get('/data-survey', [SurveyController::class, 'index'])->name('survey');
        Route::get('/data-survey/create', [SurveyController::class, 'create'])->name('survey.create');
        Route::post('/data-survey/store', [SurveyController::class, 'store'])->name('survey.store');
        Route::get('/data-survey/{uuid}', [SurveyController::class, 'show'])->name('survey.show');
        Route::get('/data-survey/{uuid}/edit', [SurveyController::class, 'edit'])->name('survey.edit');
        Route::put('/data-survey/{uuid}/update', [SurveyController::class, 'update'])->name('survey.update');
        Route::delete('/data-survey/{uuid}/destroy', [SurveyController::class, 'destroy'])->name('survey.destroy');
        

        // ===== PERBAIKAN ROUTE LAPORAN =====
        // URL Path: /admin/data-laporan/*
        // Route Name: laporan.*
        Route::prefix('data-laporan')->name('laporan.')->group(function () {
            // Rute ini menjadi 'laporan.index'
            Route::get('/', [LaporanController::class, 'index'])->name('index'); 
            
            // Rute ini menjadi 'laporan.show'
            Route::get('/{uuid}', [LaporanController::class, 'show'])->name('show');
            
            // Rute ini menjadi 'laporan.responses'
            Route::get('/{uuid}/responses', [LaporanController::class, 'responses'])->name('responses');
            
            // PERBAIKAN UTAMA: Hapus prefix URL ganda dan nama route yang berlebihan
            // Rute ini menjadi 'laporan.responseDetail'
            Route::get('/response-detail/{id}', [LaporanController::class, 'getResponseDetail'])->name('responseDetail');
            
            // Rute ini menjadi 'laporan.pdf'
            Route::get('/{uuid}/pdf', [LaporanController::class, 'exportPDF'])->name('pdf');
            
            // Rute ini menjadi 'laporan.excel'
            Route::get('/{uuid}/excel', [LaporanController::class, 'exportExcel'])->name('excel');
        });

        // Rute Data Hasil
        Route::get('/data-hasil', [DataHasilSurveyController::class, 'index'])->name('hasil'); 
        Route::get('/data-hasil/data', [DataHasilSurveyController::class, 'data'])->name('hasil.data'); 
    });
});
