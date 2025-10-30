<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SurveyController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\ResponseController;
use App\Http\Controllers\Api\AnalyticsController;
use Illuminate\Support\Facades\Route;

// ✅ Login & Register boleh diakses tanpa auth
Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/register', [AuthController::class, 'register'])->name('api.register');

// ✅ Semua route di bawah ini wajib login
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
    Route::get('/me', [AuthController::class, 'me'])->name('api.me');

    // Mahasiswa only
    Route::prefix('mahasiswa')->group(function () {
        Route::get('/surveys/available', [ResponseController::class, 'getAvailableSurveys']);
        Route::post('/surveys/{survey}/respond', [ResponseController::class, 'store']);
        Route::get('/my-responses', [ResponseController::class, 'myResponses']);
    });

    // Admin only
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::apiResource('surveys', SurveyController::class);

        Route::post('surveys/{survey}/questions', [QuestionController::class, 'store']);
        Route::put('questions/{question}', [QuestionController::class, 'update']);
        Route::delete('questions/{question}', [QuestionController::class, 'destroy']);

        Route::get('analytics/surveys/{survey}', [AnalyticsController::class, 'surveyAnalytics']);
        Route::get('analytics/overview', [AnalyticsController::class, 'overallStatistics']);
    });
});
