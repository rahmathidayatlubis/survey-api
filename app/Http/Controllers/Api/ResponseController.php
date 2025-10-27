<?php

namespace App\Http\Controllers\Api;

use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

// Model Response dilakukan aliases supaya
// menghindari bentrok dengan class Http Response
use App\Models\Response as ResponseModel;

class ResponseController extends Controller
{
    public function getAvailableSurveys(Request $request)
    {
        $user = $request->user();

        $surveys = Survey::where('is_active', true)
            ->whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_selesai', '>=', now())
            ->whereDoesntHave('responses', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with('questions.options')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil survey tersedia.',
            'data' => [
                'surveys' => $surveys
            ]
        ], Response::HTTP_OK);
    }

    public function store(Request $request, Survey $survey)
    {
        $user = $request->user();

        if ($survey->responses()->where('user_id', $user->id)->exists()) {
            return response()->json([
                'message' => 'Anda sudah mengisi survey ini',
            ], 422);
        }

        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.question_option_id' => 'nullable|exists:question_options,id',
            'answers.*.jawaban_teks' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $response = ResponseModel::create([
                'survey_id' => $survey->id,
                'user_id' => $user->id,
                'submitted_at' => now(),
            ]);

            foreach ($validated['answers'] as $answer) {
                $nilai = 0;

                if (isset($answer['question_option_id'])) {
                    $option = \App\Models\QuestionOption::find($answer['question_option_id']);
                    $nilai = $option->nilai;
                }

                $response->answers()->create([
                    'question_id' => $answer['question_id'],
                    'question_option_id' => $answer['question_option_id'] ?? null,
                    'jawaban_teks' => $answer['jawaban_teks'] ?? null,
                    'nilai' => $nilai,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Terima kasih! Jawaban Anda telah tersimpan.',
                'data' => [
                    'response' => $response->load('answers')
                ]
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan jawaban.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function myResponses(Request $request)
    {
        $user = $request->user();

        $responses = ResponseModel::where('user_id', $user->id)
            ->with(['survey', 'answers.question', 'answers.option'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil data respons survey.',
            'data' => [
                'responses' => $responses
            ]
        ], Response::HTTP_OK);
    }
}