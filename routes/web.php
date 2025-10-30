<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebAuthController;
use App\Http\Controllers\Admin\DataHasilSurveyController;

Route::get('/', [WebAuthController::class, 'showLoginForm'])->name('login');
Route::post('/process-login', [WebAuthController::class, 'login'])->name('process.login');
Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/mahasiswa/dashboard', function () {
        return "Dashboard Mahasiswa";
    })->name('mahasiswa.dashboard');

    Route::get('/admin/data-mahasiswa', function () {
        return view('admin.user'); 
    })->name('admin.user');

    Route::get('/admin/data-survey', function () {
        return view('admin.survey'); 
    })->name('admin.survey');

    Route::get('/admin/data-laporan', function () {
        return view('admin.laporan'); 
    })->name('admin.laporan');

    /*
    |--------------------------------------------------------------------------
    | ✨ BAGIAN DASHBOARD HASIL SURVEI
    |--------------------------------------------------------------------------
    */

    // ✅ halaman visualisasi hasil survei (pakai controller baru)
    Route::get('/admin/data-hasil', [DataHasilSurveyController::class, 'index'])
        ->name('admin.hasil');

    // ✅ endpoint JSON untuk bar chart, pie chart, dan summary
    Route::get('/admin/data-hasil/data', [DataHasilSurveyController::class, 'data'])
        ->name('admin.hasil.data');
});


// Halaman Register
Route::get('/register', function () {
    return view('auth.register');
});
