<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function index()
    {
        // Mengambil semua survey dengan menghitung jumlah questions dan responses
        // menggunakan withCount() untuk performa yang optimal (menghindari N+1 problem).
        $surveys = Survey::withCount(['questions', 'responses'])
                         ->orderBy('created_at', 'desc')
                         ->paginate(10); // Paginate 10 data per halaman

        // View untuk Admin biasanya berada di folder 'admin'
        return view('admin.survey', compact('surveys'));
    }
}