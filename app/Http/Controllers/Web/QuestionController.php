<?php

namespace App\Http\Controllers\Api;

use App\Models\Survey;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class QuestionController extends Controller
{
    public function store(Request $request, Survey $survey)
    {
        $validated = $request->validate([
            'pertanyaan' => 'required|string',
            'tipe' => 'required|in:multiple_choice,rating,text',
            'urutan' => 'integer',
            'options' => 'required_if:tipe,multiple_choice,rating|array',
            'options.*.teks_pilihan' => 'required|string',
            'options.*.nilai' => 'required|integer',
        ]);

        $question = $survey->questions()->create([
            'pertanyaan' => $validated['pertanyaan'],
            'tipe' => $validated['tipe'],
            'urutan' => $validated['urutan'] ?? 0,
        ]);

        if (isset($validated['options'])) {
            foreach ($validated['options'] as $option) {
                $question->options()->create($option);
            }
        }

        $question->load('options');

        return response()->json([
            'success' => true,
            'message' => 'Pertanyaan berhasil ditambahkan.',
            'data' => [
                'question' => $question
            ]
        ], Response::HTTP_CREATED);
    }

    public function update(Request $request, Question $question)
    {
        $validated = $request->validate([
            'pertanyaan' => 'sometimes|string',
            'tipe' => 'sometimes|in:multiple_choice,rating,text',
            'urutan' => 'integer',
            'options' => 'sometimes|array',
            'options.*.id' => 'sometimes|exists:question_options,id',
            'options.*.teks_pilihan' => 'required|string',
            'options.*.nilai' => 'required|integer',
        ]);

        $question->update([
            'pertanyaan' => $validated['pertanyaan'] ?? $question->pertanyaan,
            'tipe' => $validated['tipe'] ?? $question->tipe,
            'urutan' => $validated['urutan'] ?? $question->urutan,
        ]);

        if (isset($validated['options'])) {
            $question->options()->delete();

            foreach ($validated['options'] as $option) {
                $question->options()->create([
                    'teks_pilihan' => $option['teks_pilihan'],
                    'nilai' => $option['nilai'],
                ]);
            }
        }

        $question->load('options');

        return response()->json([
            'success' => true,
            'message' => 'Pertanyaan berhasil diupdate.',
            'data' => [
                'question' => $question
            ]
        ], Response::HTTP_OK);
    }

    public function destroy(Question $question)
    {
        $question->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pertanyaan berhasil dihapus.'
        ], Response::HTTP_OK);
    }
}