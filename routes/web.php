<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebAuthController;

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

Route::resource('/admin/data-mahasiswa', UserController::class)
    ->names([
        'index' => 'admin.user',
        'create' => 'admin.user.create',
        'store' => 'admin.user.store',
        'show' => 'admin.user.show',
        'edit' => 'admin.user.edit',
        'update' => 'admin.user.update',
        'destroy' => 'admin.user.destroy',
    ]);

    Route::get('/admin/data-survey', function () {
        // Mengarahkan ke view: resources/views/admin/user.blade.php
        return view('admin.survey'); 
    })->name('admin.survey');

    Route::get('/admin/data-laporan', function () {
        // Mengarahkan ke view: resources/views/admin/user.blade.php
        return view('admin.laporan'); 
    })->name('admin.laporan');
    
    Route::get('/admin/data-hasil', function () {
        // Mengarahkan ke view: resources/views/admin/user.blade.php
        return view('admin.hasil'); 
    })->name('admin.hasil');
});


// Halaman Register
Route::get('/register', function () {
    return view('auth.register');
});
