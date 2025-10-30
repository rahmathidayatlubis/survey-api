<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Response; 
use App\Models\ResponseAnswer; 
use App\Models\Question; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SurveyController extends Controller
{
    /**
     * Menampilkan Dashboard Mahasiswa dengan metrik dan daftar survei pending.
     * Menggunakan view: resources/views/mahasiswa/dashboard.blade.php
     */
    public function dashboard()
    {
        $userId = Auth::id();
        $today = Carbon::today();

        // ** 1. Ambil Metrik Statistik **

        // Total Survey Tersedia (Total yang aktif dalam periode)
        $totalAvailable = Survey::where('is_active', 1)
            ->where('tanggal_mulai', '<=', $today)
            ->where('tanggal_selesai', '>=', $today)
            ->count();
            
        // Total Survei Selesai (Sudah Diisi)
        $totalCompleted = Response::where('user_id', $userId)->count();

        // Total Survey Menunggu (Pending)
        $totalPending = $totalAvailable - $totalCompleted;
        
        // Total Poin Kontribusi
        $totalPoints = ResponseAnswer::whereHas('response', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })->sum('nilai');


        // ** 2. Ambil Daftar Survei yang BELUM DIISI (Pending Surveys) **
        $pendingSurveys = Survey::where('is_active', 1)
            ->where('tanggal_mulai', '<=', $today)
            ->where('tanggal_selesai', '>=', $today)
            // Hanya ambil yang BELUM memiliki respons dari user ini
            ->whereDoesntHave('responses', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->orderBy('tanggal_selesai', 'asc') // Urutkan yang batas waktunya terdekat
            ->limit(5)
            ->get();
        
        // ** 3. Pass semua variabel ke view **
        return view('mahasiswa.dashboard', compact(
            'totalAvailable',
            'totalCompleted',
            'totalPending',
            'totalPoints',
            'pendingSurveys'
        ));
    }
    
    //---------------------------------------------------------
    // Fungsi index, show, submit, dan riwayat di bawah ini
    //---------------------------------------------------------
    
    /**
     * Menampilkan daftar survei yang tersedia.
     */
    public function index()
    {
        $today = Carbon::today();
        $userId = Auth::id();

        $availableSurveys = Survey::where('is_active', 1)
            ->where('tanggal_mulai', '<=', $today)
            ->where('tanggal_selesai', '>=', $today)
            ->whereDoesntHave('responses', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->get();

        return view('mahasiswa.survey', compact('availableSurveys')); 
    }

    /**
     * Menampilkan formulir survei spesifik.
     */
    public function show($uuid)
    {
        $survey = Survey::where('uuid', $uuid)
                        ->where('is_active', 1)
                        ->firstOrFail();

        if ($survey->responses()->where('user_id', Auth::id())->exists()) {
             return redirect()->route('mahasiswa.survey')
                              ->with('error', 'Anda sudah mengisi survei: ' . $survey->judul);
        }

        $questions = $survey->questions()->with('options')->orderBy('urutan')->get();
        $totalQuestions = $questions->count();

        return view('mahasiswa.survey_form', compact('survey', 'questions', 'totalQuestions'));
    }

    /**
     * Menyimpan jawaban survei.
     */
    public function submit(Request $request, $uuid)
    {
        $request->validate([
            'survey_id' => 'required|exists:surveys,id',
            'answers' => 'required|array',
            'answers.*' => 'required', 
        ]);

        $survey = Survey::where('uuid', $uuid)->firstOrFail();
        $userId = Auth::id();

        if ($survey->responses()->where('user_id', $userId)->exists()) {
            return redirect()->route('mahasiswa.survey')
                             ->with('error', 'Anda sudah mengisi survei ini sebelumnya.');
        }

        try {
            DB::beginTransaction(); 

            $response = Response::create([
                'survey_id' => $survey->id,
                'user_id' => $userId,
                'submitted_at' => Carbon::now(),
            ]);

            foreach ($request->answers as $questionId => $answer) {
                $question = Question::find($questionId);
                $optionId = null;
                $textAnswer = null;
                $value = 0;

                if ($question->tipe == 'multiple_choice' || $question->tipe == 'rating') {
                    $optionId = $answer;
                    $option = $question->options()->find($optionId);
                    if ($option) {
                        $value = $option->nilai;
                        $textAnswer = $option->teks_pilihan;
                    }
                } elseif ($question->tipe == 'text') {
                    $textAnswer = is_array($answer) ? $answer['text'] : $answer;
                    $value = 0; 
                }

                ResponseAnswer::create([
                    'response_id' => $response->id,
                    'question_id' => $questionId,
                    'question_option_id' => $optionId,
                    'jawaban_teks' => $textAnswer,
                    'nilai' => $value,
                ]);
            }

            DB::commit(); 
            return redirect()->route('mahasiswa.survey')
                             ->with('success', 'Terima kasih! Survei telah berhasil dikirim.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Survey submission failed: ' . $e->getMessage()); 
            
            return redirect()->back()
                             ->with('error', 'Terjadi kesalahan saat menyimpan jawaban. Silakan coba lagi.')
                             ->withInput();
        }
    }

    /**
     * Menampilkan riwayat partisipasi.
     */
    public function showResponse($id)
    {
        $userId = Auth::id();

        // Cari Response berdasarkan ID dan pastikan itu milik user yang sedang login
        $response = Response::with([
            'survey',
            // Ambil semua jawaban dan relasikan dengan pertanyaan
            'answers.question' 
        ])
        ->where('id', $id)
        ->where('user_id', $userId)
        ->first();

        if (!$response) {
            return response()->json(['error' => 'Data riwayat tidak ditemukan atau tidak memiliki akses.'], 404);
        }

        // Format data untuk memudahkan tampilan di JavaScript
        $details = $response->answers->map(function ($answer) {
            return [
                'pertanyaan' => $answer->question->pertanyaan,
                'tipe' => $answer->question->tipe,
                'jawaban' => $answer->jawaban_teks,
                'nilai' => $answer->nilai
            ];
        });

        return response()->json([
            'survey_judul' => $response->survey->judul,
            'submitted_at' => $response->submitted_at->translatedFormat('d F Y, H:i'),
            'details' => $details
        ]);
    }
    
    public function riwayat()
    {
        $userId = Auth::id();
        $now = Carbon::now();

        $history = Response::with('survey')
            ->where('user_id', $userId)
            ->orderBy('submitted_at', 'desc')
            ->get();

        $totalSurveysCompleted = $history->count();
        $surveysThisMonth = $history->filter(function ($response) use ($now) {
            return $response->submitted_at->month === $now->month && $response->submitted_at->year === $now->year;
        })->count();

        $totalPoints = ResponseAnswer::whereHas('response', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })->sum('nilai');


        return view('mahasiswa.riwayat', compact(
            'history', 
            'totalSurveysCompleted', 
            'surveysThisMonth', 
            'totalPoints'
        ));
    }
}
