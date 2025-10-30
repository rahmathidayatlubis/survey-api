<?php

namespace App\Http\Controllers\Api;

use App\Models\Survey;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class SurveyController extends Controller
{
    public function index()
    {
        $surveys = Survey::with(['questions.options'])
            ->withCount('responses')
            ->latest()
            ->get();


        return response()->json([
            'success' => true,
            'message' => 'Data semua survey berhasil diambil.',
            'data' => [
                'surveys' => $surveys,
            ]
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'is_active' => 'boolean',
        ]);

        $survey = Survey::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Survey berhasil dibuat.',
            'data' => [
                'survey' => $survey,
            ]
        ], Response::HTTP_CREATED);
    }

    public function show(Survey $survey)
    {
        $survey->load(['questions.options', 'responses.user']);

        return response()->json([
            'success' => true,
            'message' => 'Detail survey berhasil diambil.',
            'data' => [
                'survey' => $survey,
            ]
        ], Response::HTTP_OK);
    }

    public function update(Request $request, Survey $survey)
    {
        $validated = $request->validate([
            'judul' => 'sometimes|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'sometimes|date',
            'tanggal_selesai' => 'sometimes|date|after:tanggal_mulai',
            'is_active' => 'boolean',
        ]);

        $survey->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Survey berhasil diupdate.',
            'data' => [
                'survey' => $survey,
            ]
        ], Response::HTTP_OK);
    }

    public function destroy(Survey $survey)
    {
        $survey->delete();

        return response()->json([
            'success' => true,
            'message' => 'Survey berhasil dihapus.'
        ], Response::HTTP_OK);
    }
}