<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Question;
use Illuminate\Http\Request;

class WebQuestionController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Survey $survey)
    {
        $validated = $request->validate([
            'pertanyaan' => 'required|string',
            'tipe' => 'required|in:multiple_choice,rating,text',
            // 'urutan' => 'integer',
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

        return redirect()->route('admin.surveys.edit', $survey)
            ->with('success', 'Pertanyaan berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        $validated = $request->validate([
            'pertanyaan' => 'sometimes|string',
            'tipe' => 'sometimes|in:multiple_choice,rating,text',
            'options' => 'sometimes|array',
            'options.*.id' => 'sometimes|exists:question_options,id',
            'options.*.teks_pilihan' => 'required|string',
            'options.*.nilai' => 'required|integer',
        ]);

        $question->update([
            'pertanyaan' => $validated['pertanyaan'] ?? $question->pertanyaan,
            'tipe' => $validated['tipe'] ?? $question->tipe,
        ]);

        if (isset($validated['options'])) {
            $existingOptionIds = $question->options()->pluck('id')->toArray();
            $submittedOptionIds = collect($validated['options'])
                ->pluck('id')
                ->filter()
                ->toArray();

            $toDelete = array_diff($existingOptionIds, $submittedOptionIds);
            if (!empty($toDelete)) {
                $question->options()->whereIn('id', $toDelete)->delete();
            }

            foreach ($validated['options'] as $option) {
                if (isset($option['id'])) {
                    $question->options()->where('id', $option['id'])->update([
                        'teks_pilihan' => $option['teks_pilihan'],
                        'nilai' => $option['nilai'],
                    ]);
                } else {
                    $question->options()->create([
                        'teks_pilihan' => $option['teks_pilihan'],
                        'nilai' => $option['nilai'],
                    ]);
                }
            }
        }

        $question->load('options');

        return redirect()->back()->with('success', 'Pertanyaan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        $question->delete();

        return redirect()->back()->with('success', 'Pertanyaan berhasil dihapus!');
    }
}
