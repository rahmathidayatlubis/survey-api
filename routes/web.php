<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});






// rute sementara untuk cek desain
Route::get('/dashboard', function () {
    return view('survey.dashboard');
});
Route::get('/surveys', function () {
    return view('survey.surveys');
});
Route::get('/survey/create', function () {
    return view('survey.create');
});
Route::get('/respondents', function () {
    return view('survey.responden');
});
Route::get('/reports', function () {
    return view('survey.laporan');
});


// 