<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\Response;
use App\Models\Question;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SurveyLaporanExport;

class LaporanController extends Controller
{
    /**
     * Menampilkan daftar laporan survei
     */
    public function index()
    {
        // Ambil semua survei beserta jumlah respon dan relasi pertanyaan
        $surveys = Survey::withCount('responses')
            ->with(['questions', 'responses'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.data-laporan.index', compact('surveys'));
    }

    /**
     * Menampilkan laporan detail untuk survei tertentu
     */
    public function show($uuid)
    {
        // Ambil survei berdasarkan UUID beserta relasi pertanyaan dan jawaban
        $survey = Survey::where('uuid', $uuid)
            ->with(['questions.options', 'responses.answers.option'])
            ->firstOrFail();

        // Hitung statistik per pertanyaan
        $statistics = $this->calculateStatistics($survey);

        return view('admin.data-laporan.show', compact('survey', 'statistics'));
    }

    /**
     * Menampilkan daftar responden dari survei tertentu
     */
    public function responses($uuid)
    {
        // Ambil survei dan data respon lengkap
        $survey = Survey::where('uuid', $uuid)
            ->with(['responses.user', 'responses.answers.question', 'responses.answers.option'])
            ->firstOrFail();

        // Paginasi daftar respon
        $responses = $survey->responses()->paginate(20);

        return view('admin.data-laporan.responses', compact('survey', 'responses'));
    }

    /**
     * Mengekspor laporan survei ke PDF
     */
    public function exportPDF($uuid)
    {
        $survey = Survey::where('uuid', $uuid)
            ->with(['questions.options', 'responses.answers.option'])
            ->firstOrFail();

        // Hitung statistik sebelum membuat PDF
        $statistics = $this->calculateStatistics($survey);

        // Generate dan unduh PDF laporan
        $pdf = Pdf::loadView('admin.data-laporan.pdf', compact('survey', 'statistics'));
        return $pdf->download('laporan-survey-' . $survey->uuid . '.pdf');
    }

    /**
     * Mengekspor laporan survei ke Excel
     */
    public function exportExcel($uuid)
    {
        $survey = Survey::where('uuid', $uuid)
            ->with(['questions', 'responses.answers'])
            ->firstOrFail();

        // Download file Excel laporan survei
        return Excel::download(new SurveyLaporanExport($survey), 'laporan-survey-' . $survey->uuid . '.xlsx');
    }

    /**
     * Menghitung statistik tiap pertanyaan dalam survei
     */
    private function calculateStatistics($survey)
    {
        $statistics = [];

        foreach ($survey->questions as $question) {
            $stats = [
                'question' => $question,
                'total_responses' => 0,
                'options_stats' => []
            ];

            // Jika tipe pertanyaan berupa pilihan ganda atau checkbox
            if ($question->tipe === 'pilihan_ganda' || $question->tipe === 'checkbox') {
                foreach ($question->options as $option) {
                    // Hitung jumlah respon untuk setiap opsi
                    $count = $survey->responses()
                        ->whereHas('answers', function ($q) use ($option) {
                            $q->where('question_option_id', $option->id);
                        })
                        ->count();

                    $stats['options_stats'][] = [
                        'option' => $option,
                        'count' => $count,
                        'percentage' => $survey->responses->count() > 0
                            ? round(($count / $survey->responses->count()) * 100, 2)
                            : 0
                    ];

                    $stats['total_responses'] += $count;
                }
            }

            // Jika tipe pertanyaan berupa teks (isian bebas)
            elseif ($question->tipe === 'teks' || $question->tipe === 'teks_panjang') {
                // Ambil semua jawaban teks untuk pertanyaan tersebut
                $textAnswers = $survey->responses()
                    ->with(['answers' => function ($q) use ($question) {
                        $q->where('question_id', $question->id);
                    }])
                    ->get()
                    ->pluck('answers')
                    ->flatten()
                    ->pluck('jawaban_teks')
                    ->filter();

                $stats['text_answers'] = $textAnswers;
                $stats['total_responses'] = $textAnswers->count();
            }

            $statistics[] = $stats;
        }

        return $statistics;
    }
}
