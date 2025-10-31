<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $surveys = Survey::when($search, function ($query) use ($search) {
            return $query->where('judul', 'like', '%' . $search . '%')
                ->orWhere('deskripsi', 'like', '%' . $search . '%');
        })
            ->paginate(10)
            ->appends(['search' => $search]);

        return view('admin.survey', compact('surveys'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.survey_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    //    dd($request);
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'is_active' => 'required|boolean',
        ]);

        Survey::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'is_active' => $request->is_active,
        ]);

        $surveys = Survey::paginate(10);
        return view('admin.survey', [
            'surveys' => $surveys,
            'success' => 'Data survei berhasil disimpan.'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'is_active' => 'required|boolean',
        ]);

        $survey = Survey::findOrFail($id);
        $survey->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'is_active' => $request->is_active,
        ]);

        $surveys = Survey::paginate(10);
        return view('admin.survey', [
            'surveys' => $surveys,
            'success' => 'Data survei berhasil disimpan.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $survey = Survey::findOrFail($id);
        $survey->delete();

        $surveys = Survey::paginate(10);
        return view('admin.survey', [
            'surveys' => $surveys,
            'success' => 'Data survei berhasil disimpan.'
        ]);
    }
}
