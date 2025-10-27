<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SurveyController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\ResponseController;
use App\Http\Controllers\Api\AnalyticsController;
use Illuminate\Support\Facades\Route;

// Rute login admin dan mhsw
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Rute post atau menambah mhsw
    Route::post('/register', [AuthController::class, 'register']);

    Route::post('/logout', [AuthController::class, 'logout']);

    // Rute cek profile akun yang sedang login
    Route::get('/me', [AuthController::class, 'me']);

    // Mahasiswa Routes
    Route::prefix('mahasiswa')->group(function () {
        // Melihat survey yang ada
        Route::get('/surveys/available', [ResponseController::class, 'getAvailableSurveys']);
        // Mengirim respons survey
        Route::post('/surveys/{survey}/respond', [ResponseController::class, 'store']);
        // Melihat respons survey yang dikirim
        Route::get('/my-responses', [ResponseController::class, 'myResponses']);
    });

    Route::middleware('admin')->prefix('admin')->group(function () {
        // Survey Management
        Route::apiResource('surveys', SurveyController::class);

        // Question Management
        Route::post('surveys/{survey}/questions', [QuestionController::class, 'store']);
        Route::put('questions/{question}', [QuestionController::class, 'update']);
        Route::delete('questions/{question}', [QuestionController::class, 'destroy']);

        // Analytics
        Route::get('analytics/surveys/{survey}', [AnalyticsController::class, 'surveyAnalytics']);
        Route::get('analytics/overview', [AnalyticsController::class, 'overallStatistics']);
    });
});