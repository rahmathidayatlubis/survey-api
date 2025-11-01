<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\WebAuthController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\WebSurveyController;
use App\Http\Controllers\WebQuestionController;
use App\Http\Controllers\WebSurveyAnalyticsController;

Route::get('/', [WebAuthController::class, 'showLoginForm'])->name('login');
Route::post('/process-login', [WebAuthController::class, 'login'])->name('process.login');
Route::get('/logout', [WebAuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/dashboard', [MahasiswaController::class, 'index'])->name('dashboard');
        Route::get('/surveys/{survey}/isi', [MahasiswaController::class, 'fill'])->name('survey.fill');
        Route::post('/surveys/{survey}/submit', [MahasiswaController::class, 'submit'])->name('survey.submit');
        Route::get('/hasil-survey', [MahasiswaController::class, 'results'])->name('survey.results');
        Route::get('/profile', [MahasiswaController::class, 'profile'])->name('profile');
        Route::put('/profile', [MahasiswaController::class, 'updateProfile'])->name('profile.update');
    });


    // =======================================================
    Route::middleware('role:admin')->prefix('admin')->as('admin.')->group(function () {
        Route::resource('surveys', WebSurveyController::class);

        Route::post('surveys/{survey}/questions', [WebQuestionController::class, 'store']);
        Route::put('questions/{question}', [WebQuestionController::class, 'update']);
        Route::delete('questions/{question}', [WebQuestionController::class, 'destroy']);

        Route::get('dashboard', [WebSurveyAnalyticsController::class, 'index']);

        Route::resource('students', StudentController::class);
    });
    // =======================================================
});

// Halaman Register
Route::get('/register', function () {
    return view('auth.register');
});
