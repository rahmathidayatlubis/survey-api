<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Question; // <<< WAJIB DITAMBAHKAN
use App\Models\QuestionOption; // <<< WAJIB DITAMBAHKAN

class SurveyController extends Controller
{
    /**
     * Menampilkan daftar semua survey (READ - Index).
     */
    public function index()
    {
        // Mengambil semua survey dengan menghitung jumlah questions dan responses
        $surveys = Survey::withCount(['questions', 'responses'])
                             ->orderBy('created_at', 'desc')
                             ->paginate(10); 

        return view('admin.survey', compact('surveys'));
    }

    /**
     * Menampilkan formulir untuk membuat survey baru (CREATE - Form).
     */
    public function create()
    {
        return view('admin.survey.create');
    }

    /**
     * Menyimpan data survey yang baru dibuat ke database (CREATE - Store).
     */
    public function store(Request $request)
    {
        // 1. Validasi Data Survey Dasar
        $validatedSurveyData = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'is_active' => 'required|boolean',
            // Validasi array pertanyaan (min. 1 pertanyaan)
            'questions' => 'required|array|min:1', 
        ], [
            'tanggal_selesai.after_or_equal' => 'Tanggal Selesai harus setelah atau sama dengan Tanggal Mulai.',
            'questions.required' => 'Survey harus memiliki minimal satu pertanyaan.',
            'questions.min' => 'Survey harus memiliki minimal satu pertanyaan.',
        ]);
        
        // 2. Validasi Data Pertanyaan (dilakukan terpisah atau di dalam loop untuk feedback yang lebih baik)
        
        DB::beginTransaction(); // Mulai Transaksi Database

        try {
            // 2.1. Simpan Data Survey
            $survey = Survey::create([
                'uuid' => (string) Str::uuid(),
                'judul' => $validatedSurveyData['judul'],
                'deskripsi' => $validatedSurveyData['deskripsi'],
                'tanggal_mulai' => $validatedSurveyData['tanggal_mulai'],
                'tanggal_selesai' => $validatedSurveyData['tanggal_selesai'],
                'is_active' => $validatedSurveyData['is_active'],
            ]);

            // 2.2. Simpan Pertanyaan dan Opsi
            foreach ($request->questions as $questionData) {
                // Validasi data setiap pertanyaan di dalam loop
                $validatedQuestion = validator($questionData, [
                    'pertanyaan' => 'required|string',
                    'tipe' => 'required|in:multiple_choice,rating,text',
                    'urutan' => 'required|integer|min:1',
                    'options' => 'nullable|array', // Opsi bisa kosong untuk tipe 'text'
                ])->validate();
                
                // Simpan Pertanyaan
                $question = Question::create([
                    'survey_id' => $survey->id,
                    'pertanyaan' => $validatedQuestion['pertanyaan'],
                    'tipe' => $validatedQuestion['tipe'],
                    'urutan' => $validatedQuestion['urutan'],
                ]);

                // Simpan Opsi Jawaban (Hanya untuk tipe multiple_choice dan rating)
                if (in_array($validatedQuestion['tipe'], ['multiple_choice', 'rating']) && isset($validatedQuestion['options'])) {
                    foreach ($validatedQuestion['options'] as $optionData) {
                        // Validasi data setiap opsi
                        $validatedOption = validator($optionData, [
                            'teks_pilihan' => 'required|string|max:255',
                            'nilai' => 'required|integer|min:1',
                        ])->validate();

                        QuestionOption::create([
                            'question_id' => $question->id,
                            'teks_pilihan' => $validatedOption['teks_pilihan'],
                            'nilai' => $validatedOption['nilai'],
                        ]);
                    }
                }
            }

            DB::commit(); // Komit transaksi jika semua berhasil
            
            // Redirect kembali dengan pesan sukses
            return redirect()->route('admin.survey')
                             ->with('success', 'Survey "' . $survey->judul . '" beserta ' . count($request->questions) . ' pertanyaan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan transaksi jika ada error
            // Log error
            \Log::error('Gagal menyimpan survey: ' . $e->getMessage()); 
            
            // Redirect kembali dengan pesan error
            return redirect()->back()
                             ->withInput()
                             ->withErrors(['error' => 'Gagal menyimpan survey dan pertanyaan. Pastikan semua kolom terisi dengan benar dan log diperiksa.']);
        }
    }

    /**
     * Menampilkan detail survey spesifik (READ - Show).
     * Kami menggunakan UUID sebagai kunci rute.
     */
    public function show($uuid)
    {
        // Cari survey berdasarkan UUID, gagal jika tidak ditemukan
        $survey = Survey::where('uuid', $uuid)
                        ->withCount(['questions', 'responses'])
                        ->firstOrFail();

        // Kita akan menampilkan detail, serta daftar pertanyaan dan jawaban terkait
        // Untuk tujuan ini, kita asumsikan Anda ingin melihat detail survey
        return view('admin.survey.show', compact('survey'));
    }

    /**
     * Menampilkan formulir untuk mengedit survey (UPDATE - Form).
     */
    public function edit($uuid)
    {
        // Eager load questions dan options-nya
        $survey = Survey::where('uuid', $uuid)->with('questions.options')->firstOrFail();
        
        // Konversi data survey ke format yang mudah dibaca di form
        $survey->tanggal_mulai = Carbon::parse($survey->tanggal_mulai)->format('Y-m-d');
        $survey->tanggal_selesai = Carbon::parse($survey->tanggal_selesai)->format('Y-m-d');

        return view('admin.survey.edit', compact('survey'));
    }

    /**
     * Memperbarui data survey, pertanyaan, dan opsi di database (UPDATE - Store).
     */
    public function update(Request $request, $uuid)
    {
        $survey = Survey::where('uuid', $uuid)->firstOrFail();

        // 1. Validasi Data Survey Dasar
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'is_active' => 'required|boolean',
            
            // Validasi untuk relasi (Bisa null jika admin menghapus semua pertanyaan)
            'questions' => 'nullable|array', 
        ], [
            'tanggal_selesai.after_or_equal' => 'Tanggal Selesai harus setelah atau sama dengan Tanggal Mulai.',
        ]);

        DB::beginTransaction();

        try {
            // A. Update Data Survey Dasar
            $survey->update($validatedData);

            // B. Proses Pertanyaan dan Opsi
            $submittedQuestionIds = [];
            
            if (isset($validatedData['questions'])) {
                foreach ($request->questions as $questionData) {
                    $questionId = $questionData['id'] ?? null;
                    $submittedOptionIds = [];

                    // 1. Validasi Pertanyaan
                    $validatedQuestion = validator($questionData, [
                        'id' => 'nullable|exists:questions,id',
                        'pertanyaan' => 'required|string',
                        'tipe' => 'required|in:multiple_choice,rating,text',
                        'urutan' => 'required|integer|min:1',
                        'options' => 'nullable|array',
                    ])->validate();

                    // 2. CREATE atau UPDATE Pertanyaan
                    $question = Question::updateOrCreate(
                        [
                            'id' => $questionId, 
                            'survey_id' => $survey->id // Penting: pastikan question milik survey ini
                        ],
                        [
                            'pertanyaan' => $validatedQuestion['pertanyaan'],
                            'tipe' => $validatedQuestion['tipe'],
                            'urutan' => $validatedQuestion['urutan'],
                        ]
                    );

                    $submittedQuestionIds[] = $question->id;

                    // 3. Proses Opsi Jawaban (CREATE/UPDATE)
                    if (in_array($question->tipe, ['multiple_choice', 'rating']) && isset($validatedQuestion['options'])) {
                        foreach ($validatedQuestion['options'] as $optionData) {
                            $optionId = $optionData['id'] ?? null;
                            
                            $validatedOption = validator($optionData, [
                                'id' => 'nullable|exists:question_options,id',
                                'teks_pilihan' => 'required|string|max:255',
                                'nilai' => 'required|integer|min:1',
                            ])->validate();

                            $option = QuestionOption::updateOrCreate(
                                [
                                    'id' => $optionId, 
                                    'question_id' => $question->id // Penting: pastikan opsi milik pertanyaan ini
                                ],
                                [
                                    'teks_pilihan' => $validatedOption['teks_pilihan'],
                                    'nilai' => $validatedOption['nilai'],
                                ]
                            );
                            $submittedOptionIds[] = $option->id;
                        }
                    }
                    
                    // 4. Hapus Opsi yang Dihilangkan (DELETE)
                    // Hapus opsi lama yang tidak ada dalam submisi (hanya untuk pertanyaan yang di-update)
                    if ($questionId) {
                         $question->options()->whereNotIn('id', $submittedOptionIds)->delete();
                    }
                }
            }
            
            // C. Hapus Pertanyaan yang Dihilangkan (DELETE)
            // Hapus pertanyaan lama yang tidak ada dalam submisi (hanya untuk survey ini)
            $survey->questions()->whereNotIn('id', $submittedQuestionIds)->delete();

            DB::commit(); 

            return redirect()->route('admin.survey.edit', $survey->uuid)
                             ->with('success', 'Survey dan pertanyaan berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal memperbarui survey: ' . $e->getMessage()); 
            
            return redirect()->back()
                             ->withInput()
                             ->withErrors(['error' => 'Gagal menyimpan perubahan. Pastikan data valid.']);
        }
    }


    /**
     * Menghapus survey dari database (DELETE).
     */
    public function destroy($uuid)
    {
        $survey = Survey::where('uuid', $uuid)->firstOrFail();
        $surveyTitle = $survey->judul;
        
        // Hapus survey (relasi seperti questions dan responses akan terhapus berkat ON DELETE CASCADE)
        $survey->delete();

        return redirect()->route('admin.survey')
                         ->with('success', 'Survey "' . $surveyTitle . '" dan semua data terkait berhasil dihapus.');
    }
}
