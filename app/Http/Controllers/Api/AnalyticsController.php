<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{Survey, Response, User};
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as HttpResponse;

class AnalyticsController extends Controller
{
    public function surveyAnalytics(Survey $survey): JsonResponse
    {
        try {
            $survey->load(['questions.options', 'responses.answers']);
            $totalResponses = $survey->responses()->count();

            $questions = $survey->questions->map(function ($q) use ($totalResponses) {
                $answers   = $q->answers()->with('option')->get();
                $nilaiTotal = $answers->sum('nilai');
                $rataRata   = $totalResponses > 0 ? $nilaiTotal / $totalResponses : 0;

                $optionStats = $q->options->map(function ($opt) use ($answers, $totalResponses) {
                    $count      = $answers->where('question_option_id', $opt->id)->count();
                    $percentage = $totalResponses > 0 ? ($count / $totalResponses) * 100 : 0;

                    return [
                        'option_id'      => $opt->id,
                        'teks_pilihan'   => $opt->teks_pilihan,
                        'nilai'          => $opt->nilai,
                        'jumlah_dipilih' => $count,
                        'persentase'     => round($percentage, 2),
                    ];
                });

                return [
                    'question_id'      => $q->id,
                    'pertanyaan'       => $q->pertanyaan,
                    'tipe'             => $q->tipe,
                    'total_jawaban'    => $answers->count(),
                    'nilai_rata_rata'  => round($rataRata, 2),
                    'option_statistics' => $optionStats,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Analytics survey berhasil diambil.',
                'data' => [
                    'survey' => [
                        'id'              => $survey->id,
                        'judul'           => $survey->judul,
                        'total_responses' => $totalResponses,
                    ],
                    'analytics' => $questions,
                ],
            ], HttpResponse::HTTP_OK);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil analytics survey.',
                'error'   => $e->getMessage(),
            ], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function overallStatistics(): JsonResponse
    {
        try {
            $totalSurveys   = Survey::count();
            $activeSurveys  = Survey::where('is_active', true)->count();
            $totalResponses = Response::count();
            $totalUsers     = User::where('role', 'mahasiswa')->count();

            $topSurveys = Survey::withCount('responses')
                ->orderByDesc('responses_count')
                ->limit(5)
                ->get(['id', 'judul'])
                ->map(fn($s) => [
                    'id'              => $s->id,
                    'judul'           => $s->judul,
                    'responses_count' => $s->responses_count,
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Statistik keseluruhan berhasil diambil.',
                'data' => [
                    'statistics' => [
                        'total_surveys'   => $totalSurveys,
                        'active_surveys'  => $activeSurveys,
                        'total_responses' => $totalResponses,
                        'total_mahasiswa' => $totalUsers,
                    ],
                    'top_surveys' => $topSurveys,
                ],
            ], HttpResponse::HTTP_OK);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil statistik keseluruhan.',
                'error'   => $e->getMessage(),
            ], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}