<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Response;
use App\Models\ResponseAnswer;
use Illuminate\Support\Facades\DB;

class DataHasilSurveyController extends Controller
{
    public function index()
    {
        // ambil semua survey untuk dropdown
        $surveys = Survey::orderBy('created_at', 'desc')->get(['id','judul']);
        return view('admin.hasil', compact('surveys'));
    }

    /**
     * Endpoint JSON untuk data chart dan summary
     * Query params:
     *  - survey_id (required)
     *  - question_id (optional) => jika dikirim, akan sertakan distribusi jawaban untuk question_id tersebut
     */
    public function data(Request $req)
    {
        $surveyId = $req->query('survey_id');
        if (!$surveyId) {
            return response()->json(['error' => 'survey_id required'], 422);
        }

        // Ambil semua responses id untuk survey
        $responseIds = Response::where('survey_id', $surveyId)->pluck('id')->toArray();

        // Total responden
        $totalRespondents = count($responseIds);

        // Ambil questions untuk survey
        $questions = Question::where('survey_id', $surveyId)
                    ->orderBy('urutan','asc')
                    ->get(['id','pertanyaan']);

        // Rata-rata nilai per question + jumlah jawaban
        $questionSummary = [];
        foreach ($questions as $q) {
            $row = ResponseAnswer::where('question_id', $q->id)
                    ->whereIn('response_id', $responseIds);

            $avg = $row->avg('nilai') ?? 0;
            $count = $row->count();
            $questionSummary[] = [
                'question_id' => $q->id,
                'pertanyaan' => $q->pertanyaan,
                'avg' => round((float)$avg, 2),
                'responses_count' => $count,
                // label pendek untuk chart (limit length)
                'short_label' => strlen($q->pertanyaan) > 40 ? substr($q->pertanyaan,0,40).'...' : $q->pertanyaan,
            ];
        }

        // Overall average (rata-rata dari semua nilai jawaban)
        $overallAverage = 0;
        if ($totalRespondents > 0) {
            $allAvg = ResponseAnswer::whereIn('response_id', $responseIds)->avg('nilai');
            $overallAverage = $allAvg ? round((float)$allAvg, 2) : 0;
        }

        // IKM = overallAverage / 5 * 100
        $ikm = $overallAverage ? round(($overallAverage / 5) * 100, 2) : 0;

        // Siapkan pertanyaan + option distribution
        $questionsData = [];
        $optionsByQuestion = [];
        foreach ($questions as $q) {
            $questionsData[] = [
                'id' => $q->id,
                'pertanyaan' => $q->pertanyaan,
            ];

            // ambil opsi dan jumlah jawaban per opsi
            $options = QuestionOption::where('question_id', $q->id)
                        ->orderBy('nilai','desc')
                        ->get(['id','teks_pilihan','nilai'])
                        ->map(function($opt) use ($responseIds, $q) {
                            $count = ResponseAnswer::whereIn('response_id', $responseIds)
                                        ->where('question_id', $q->id)
                                        ->where('question_option_id', $opt->id)
                                        ->count();
                            return [
                                'id' => $opt->id,
                                'teks_pilihan' => $opt->teks_pilihan,
                                'nilai' => $opt->nilai,
                                'count' => $count
                            ];
                        })->toArray();

            $optionsByQuestion[$q->id] = $options;
        }

        // jika request menyertakan question_id, kembalikan juga distribusi untuk question_id itu
        $questionId = $req->query('question_id');
        $selectedQuestionOptions = [];
        if ($questionId) {
            $selectedQuestionOptions = $optionsByQuestion[$questionId] ?? [];
        }

        return response()->json([
            'total_respondents' => $totalRespondents,
            'overall_average' => $overallAverage,
            'ikm' => $ikm,
            'questions' => $questionsData,
            'question_summary' => $questionSummary,
            'options_by_question' => $optionsByQuestion,
            'question_options' => $selectedQuestionOptions,
        ]);
    }
}
