<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebSurveyAnalyticsController extends Controller
{
    public function index()
    {
        // Jumlah survei aktif
        $totalSurveiAktif = DB::table('surveys')->where('is_active', 1)->count();

        // Total responden (jumlah response)
        $totalResponden = DB::table('responses')->count();

        // Rata-rata nilai keseluruhan survei
        $rataKeseluruhan = DB::table('response_answers')->avg('nilai') ?? 0;

        // Jumlah responden per survei
        $responPerSurvei = DB::table('surveys as s')
            ->leftJoin('responses as r', 's.id', '=', 'r.survey_id')
            ->select('s.id', 's.judul', DB::raw('COUNT(DISTINCT r.id) as total_responden'))
            ->groupBy('s.id', 's.judul')
            ->orderBy('s.id')
            ->get();

        // Skor rata-rata per pertanyaan
        $rataPerPertanyaan = DB::table('response_answers as ra')
            ->join('questions as q', 'ra.question_id', '=', 'q.id')
            ->select('q.id', 'q.pertanyaan', DB::raw('ROUND(AVG(ra.nilai), 2) as rata_nilai'))
            ->where('ra.nilai', '>', 0)
            ->groupBy('q.id', 'q.pertanyaan')
            ->orderBy('q.id')
            ->get();

        // Jika tidak ada data, ambil semua pertanyaan dengan nilai default 0
        if ($rataPerPertanyaan->isEmpty()) {
            $rataPerPertanyaan = DB::table('questions')
                ->select('id', 'pertanyaan', DB::raw('0 as rata_nilai'))
                ->orderBy('id')
                ->get();
        }

        // Distribusi jawaban per opsi
        $distribusiOpsi = DB::table('response_answers as ra')
            ->join('question_options as qo', 'ra.question_option_id', '=', 'qo.id')
            ->join('questions as q', 'qo.question_id', '=', 'q.id')
            ->select('q.pertanyaan', 'qo.teks_pilihan', DB::raw('COUNT(ra.id) as jumlah_pilihan'))
            ->where('ra.nilai', '>', 0)
            ->groupBy('qo.id', 'q.id', 'q.pertanyaan', 'qo.teks_pilihan')
            ->orderBy('q.id')
            ->get();

        // Nilai rata-rata per survei
        $rataPerSurvei = DB::table('response_answers as ra')
            ->join('responses as r', 'ra.response_id', '=', 'r.id')
            ->join('surveys as s', 'r.survey_id', '=', 's.id')
            ->select('s.id', 's.judul', DB::raw('ROUND(AVG(ra.nilai), 2) as rata_survey'))
            ->where('ra.nilai', '>', 0)
            ->groupBy('s.id', 's.judul')
            ->orderBy('s.id')
            ->get();

        // Jika tidak ada data, ambil semua survei dengan nilai default 0
        if ($rataPerSurvei->isEmpty()) {
            $rataPerSurvei = DB::table('surveys')
                ->select('id', 'judul', DB::raw('0 as rata_survey'))
                ->orderBy('id')
                ->get();
        }

        // Nilai rata-rata per jurusan
        $rataPerJurusan = DB::table('response_answers as ra')
            ->join('responses as r', 'ra.response_id', '=', 'r.id')
            ->join('users as u', 'r.user_id', '=', 'u.id')
            ->select('u.jurusan', DB::raw('ROUND(AVG(ra.nilai), 2) as rata_nilai'))
            ->where('ra.nilai', '>', 0)
            ->whereNotNull('u.jurusan')
            ->groupBy('u.jurusan')
            ->orderBy('u.jurusan')
            ->get();

        // Jika tidak ada data, ambil semua jurusan dari users dengan nilai default 0
        if ($rataPerJurusan->isEmpty()) {
            $rataPerJurusan = DB::table('users')
                ->select('jurusan', DB::raw('0 as rata_nilai'))
                ->whereNotNull('jurusan')
                ->where('role', 'mahasiswa')
                ->distinct()
                ->orderBy('jurusan')
                ->get();
        }


        // Kirim ke view
        return view('admin.dashboard', compact(
            'totalSurveiAktif',
            'totalResponden',
            'rataKeseluruhan',
            'responPerSurvei',
            'rataPerPertanyaan',
            'distribusiOpsi',
            'rataPerSurvei',
            'rataPerJurusan'
        ));
    }
}
