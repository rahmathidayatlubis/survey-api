<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebAuthController;
use App\Http\Controllers\Admin\LaporanController;


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

    // ===== ROUTE LAPORAN (UPDATED) =====
    Route::prefix('admin/data-laporan')->name('laporan.')->group(function () {
    Route::get('/', [LaporanController::class, 'index'])->name('index');
    Route::get('/{uuid}', [LaporanController::class, 'show'])->name('show');
    Route::get('/{uuid}/responses', [LaporanController::class, 'responses'])->name('responses');
    Route::get('/{uuid}/pdf', [LaporanController::class, 'exportPDF'])->name('pdf');
    Route::get('/{uuid}/excel', [LaporanController::class, 'exportExcel'])->name('excel');
});
    
    Route::get('/admin/data-hasil', function () {
        return view('admin.hasil'); 
    })->name('admin.hasil');
});

// Halaman Register
Route::get('/register', function () {
    return view('auth.register');
});