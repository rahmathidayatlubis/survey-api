<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WebSurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $surveys = Survey::with(['questions.options'])
            ->withCount('responses')
            ->latest()
            ->get();

        $data = [
            'surveys' => $surveys
        ];

        return view('admin.survey', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'is_active' => 'boolean',
        ]);

        Survey::create($validated);

        return redirect('/admin/surveys')->with(['success' => 'Survey berhasil ditambahkan!']);
    }
    /**
     * Display the specified resource.
     */
    public function show(Survey $survey)
    {
        $data = [
            'apa' => 'itu'
        ];

        return view('admin.question', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Survey $survey)
    {
        $surveys = Survey::with(['questions.options'])
            ->withCount('responses')
            ->latest()
            ->get();

        $survey->load(['questions.options', 'responses.user']);

        $data = [
            'surveys' => $surveys,
            'survey' => $survey,
        ];

        return view('admin.question', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Survey $survey)
    {
        $validated = $request->validate([
            'judul' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('surveys', 'judul')->ignore($survey->uuid, 'uuid')
            ],
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'sometimes|date',
            'tanggal_selesai' => 'sometimes|date|after:tanggal_mulai',
            'is_active' => 'boolean',
        ]);

        $survey->update($validated);

        return redirect('/admin/surveys')->with('success', 'Survey berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Survey $survey)
    {
        $survey->delete();

        return response()->json([
            'success' => true,
            'message' => 'Survey berhasil dihapus.'
        ], 200);
    }
}