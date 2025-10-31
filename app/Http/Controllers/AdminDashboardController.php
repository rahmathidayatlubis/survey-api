<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\User;
use App\Models\Response;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // --- 1. Ambil Data Statistik Utama ---
        $totalSurveys = Survey::count();
        $totalRespondents = Response::distinct('user_id')->count();
        $activeSurveys = Survey::where('is_active', true)
                                ->where('tanggal_selesai', '>=', Carbon::today())
                                ->count();
        
        // --- 2. Ambil Data Tabel Survey Terbaru ---
        $recentSurveys = Survey::withCount('responses')
                               ->orderBy('created_at', 'desc')
                               ->take(4)
                               ->get();
                               
        // --- 3. Ambil Data Ranking Survey ---
        $topSurveys = Survey::withCount('responses')
                            ->orderByDesc('responses_count')
                            ->take(3)
                            ->get();

        // --- 4. Hitung Total User Mahasiswa (Untuk Response Rate, asumsi) ---
        $totalMahasiswa = User::where('role', 'mahasiswa')->count();
        $responseRate = ($totalMahasiswa > 0) ? round(($totalRespondents / $totalMahasiswa) * 100) : 0;
        
        return view('admin.dashboard', compact(
            'totalSurveys', 
            'totalRespondents', 
            'activeSurveys',
            'recentSurveys',
            'topSurveys',
            'responseRate'
        ));
    }

    /**
     * Mengambil data aktivitas responden per hari untuk Chart.js (7 hari terakhir).
     * Endpoint ini dipanggil melalui AJAX di view dashboard.
     */
    public function getActivityData()
    {
        // Set locale ke Bahasa Indonesia (jika Carbon mendukungnya, atau kita mapping manual)
        Carbon::setLocale('id');

        $sevenDaysAgo = Carbon::now()->subDays(6)->startOfDay();

        // Query untuk menghitung jumlah Response unik (berdasarkan user_id) per hari
        $activity = Response::select(
            DB::raw('DATE(created_at) as date'), 
            DB::raw('COUNT(id) as count') // Menghitung total response per hari
        )
        ->where('created_at', '>=', $sevenDaysAgo)
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // Siapkan array kosong untuk 7 hari
        $labels = [];
        $data = [];
        $dayMapping = [
            'Mon' => 'Sen', 'Tue' => 'Sel', 'Wed' => 'Rab', 
            'Thu' => 'Kam', 'Fri' => 'Jum', 'Sat' => 'Sab', 'Sun' => 'Min'
        ];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateKey = $date->format('Y-m-d');
            
            // Ambil nama hari dalam bahasa Inggris dan map ke Indonesia
            $dayName = $date->format('D'); 
            $labels[] = $dayMapping[$dayName] ?? $dayName;

            // Cari data response untuk tanggal ini
            $dayData = $activity->firstWhere('date', $dateKey);
            $data[] = $dayData ? $dayData->count : 0;
        }

        return response()->json(['labels' => $labels, 'data' => $data]);
    }
}