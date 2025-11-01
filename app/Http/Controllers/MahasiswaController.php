<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Response;
use Illuminate\Http\Request;
use App\Models\ResponseAnswer;
use Illuminate\Support\Facades\Auth;

class MahasiswaController extends Controller
{
    public function index()
    {
        $filledSurveyIds = Response::where('user_id', Auth::id())
            ->pluck('survey_id')
            ->toArray();

        $surveys = Survey::whereNotIn('id', $filledSurveyIds)->latest()->get();

        return view('mahasiswa.dashboard', compact('surveys'));
    }

    public function fill(Survey $survey)
    {
        $survey->load('questions.options');
        return view('mahasiswa.survey_fill', compact('survey'));
    }

    public function submit(Request $request, Survey $survey)
    {
        // Simpan response utama
        $response = Response::create([
            'user_id' => Auth::id(),
            'survey_id' => $survey->id,
        ]);

        // Simpan jawaban
        foreach ($request->answers as $questionId => $answerValue) {
            ResponseAnswer::create([
                'response_id' => $response->id,
                'question_id' => $questionId,
                'option_id' => is_numeric($answerValue) ? $answerValue : null,
                'jawaban_text' => !is_numeric($answerValue) ? $answerValue : null,
            ]);
        }

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Terima kasih, survey berhasil dikirim!');
    }
    public function results()
    {
        $responses = Auth::user()->responses()
            ->with(['survey', 'answers.question', 'answers'])
            ->latest()
            ->get();

        // Muat opsi secara manual karena foreign key-nya beda
        foreach ($responses as $response) {
            foreach ($response->answers as $answer) {
                $answer->option = \App\Models\QuestionOption::find($answer->question_option_id);
            }
        }

        return view('mahasiswa.survey_result', compact('responses'));
    }


    public function profile()
    {
        return view('mahasiswa.profile');
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            'tanggal_lahir' => 'required|date',
            'jurusan' => 'nullable|string|max:255',
        ]);

        Auth::user()->update($validated);
        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
