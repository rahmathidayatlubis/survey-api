<?php

namespace Database\Seeders;

use App\Models\Survey;
use App\Models\Question;
use Illuminate\Support\Str;
use App\Models\QuestionOption;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ==========================================
        // BUAT SURVEY
        // ==========================================
        $survey = Survey::create([
            'judul' => 'Evaluasi Kepuasan Mahasiswa Terhadap Layanan Akademik',
            'deskripsi' => 'Survey untuk mengevaluasi tingkat kepuasan mahasiswa terhadap layanan akademik kampus semester genap 2025',
            'tanggal_mulai' => now()->subDays(7),
            'tanggal_selesai' => now()->addDays(30),
            'is_active' => true,
        ]);

        $q1 = Question::create([
            'survey_id' => $survey->id,
            'pertanyaan' => 'Bagaimana penilaian Anda terhadap kualitas pengajaran dosen?',
            'tipe' => 'multiple_choice',
            'urutan' => 1,
        ]);

        QuestionOption::create(['question_id' => $q1->id, 'teks_pilihan' => 'Sangat Baik', 'nilai' => 5]);
        QuestionOption::create(['question_id' => $q1->id, 'teks_pilihan' => 'Baik', 'nilai' => 4]);
        QuestionOption::create(['question_id' => $q1->id, 'teks_pilihan' => 'Cukup', 'nilai' => 3]);
        QuestionOption::create(['question_id' => $q1->id, 'teks_pilihan' => 'Kurang', 'nilai' => 2]);
        QuestionOption::create(['question_id' => $q1->id, 'teks_pilihan' => 'Sangat Kurang', 'nilai' => 1]);

        $q2 = Question::create([
            'survey_id' => $survey->id,
            'pertanyaan' => 'Seberapa puas Anda dengan fasilitas perpustakaan?',
            'tipe' => 'multiple_choice',
            'urutan' => 2,
        ]);

        QuestionOption::create(['question_id' => $q2->id, 'teks_pilihan' => 'Sangat Puas', 'nilai' => 5]);
        QuestionOption::create(['question_id' => $q2->id, 'teks_pilihan' => 'Puas', 'nilai' => 4]);
        QuestionOption::create(['question_id' => $q2->id, 'teks_pilihan' => 'Cukup Puas', 'nilai' => 3]);
        QuestionOption::create(['question_id' => $q2->id, 'teks_pilihan' => 'Tidak Puas', 'nilai' => 2]);
        QuestionOption::create(['question_id' => $q2->id, 'teks_pilihan' => 'Sangat Tidak Puas', 'nilai' => 1]);

        $q3 = Question::create([
            'survey_id' => $survey->id,
            'pertanyaan' => 'Bagaimana pelayanan administrasi akademik?',
            'tipe' => 'multiple_choice',
            'urutan' => 3,
        ]);

        QuestionOption::create(['question_id' => $q3->id, 'teks_pilihan' => 'Sangat Memuaskan', 'nilai' => 5]);
        QuestionOption::create(['question_id' => $q3->id, 'teks_pilihan' => 'Memuaskan', 'nilai' => 4]);
        QuestionOption::create(['question_id' => $q3->id, 'teks_pilihan' => 'Cukup', 'nilai' => 3]);
        QuestionOption::create(['question_id' => $q3->id, 'teks_pilihan' => 'Kurang Memuaskan', 'nilai' => 2]);
        QuestionOption::create(['question_id' => $q3->id, 'teks_pilihan' => 'Tidak Memuaskan', 'nilai' => 1]);

        $q4 = Question::create([
            'survey_id' => $survey->id,
            'pertanyaan' => 'Bagaimana kondisi fasilitas laboratorium komputer?',
            'tipe' => 'multiple_choice',
            'urutan' => 4,
        ]);

        QuestionOption::create(['question_id' => $q4->id, 'teks_pilihan' => 'Sangat Baik', 'nilai' => 5]);
        QuestionOption::create(['question_id' => $q4->id, 'teks_pilihan' => 'Baik', 'nilai' => 4]);
        QuestionOption::create(['question_id' => $q4->id, 'teks_pilihan' => 'Cukup', 'nilai' => 3]);
        QuestionOption::create(['question_id' => $q4->id, 'teks_pilihan' => 'Buruk', 'nilai' => 2]);
        QuestionOption::create(['question_id' => $q4->id, 'teks_pilihan' => 'Sangat Buruk', 'nilai' => 1]);

        $q5 = Question::create([
            'survey_id' => $survey->id,
            'pertanyaan' => 'Bagaimana penilaian Anda terhadap kebersihan lingkungan kampus?',
            'tipe' => 'multiple_choice',
            'urutan' => 5,
        ]);

        QuestionOption::create(['question_id' => $q5->id, 'teks_pilihan' => 'Sangat Bersih', 'nilai' => 5]);
        QuestionOption::create(['question_id' => $q5->id, 'teks_pilihan' => 'Bersih', 'nilai' => 4]);
        QuestionOption::create(['question_id' => $q5->id, 'teks_pilihan' => 'Cukup Bersih', 'nilai' => 3]);
        QuestionOption::create(['question_id' => $q5->id, 'teks_pilihan' => 'Kotor', 'nilai' => 2]);
        QuestionOption::create(['question_id' => $q5->id, 'teks_pilihan' => 'Sangat Kotor', 'nilai' => 1]);

        // ==========================================
        // BUAT SURVEY KEDUA (TIDAK AKTIF)
        // ==========================================
        $survey2 = Survey::create([
            'judul' => 'Evaluasi Pembelajaran Online',
            'deskripsi' => 'Survey untuk mengevaluasi efektivitas pembelajaran online',
            'tanggal_mulai' => now()->addDays(5),
            'tanggal_selesai' => now()->addDays(35),
            'is_active' => false,
        ]);

        $q6 = Question::create([
            'survey_id' => $survey2->id,
            'pertanyaan' => 'Apakah platform pembelajaran online mudah digunakan?',
            'tipe' => 'multiple_choice',
            'urutan' => 1,
        ]);

        QuestionOption::create(['question_id' => $q6->id, 'teks_pilihan' => 'Sangat Mudah', 'nilai' => 5]);
        QuestionOption::create(['question_id' => $q6->id, 'teks_pilihan' => 'Mudah', 'nilai' => 4]);
        QuestionOption::create(['question_id' => $q6->id, 'teks_pilihan' => 'Cukup', 'nilai' => 3]);
        QuestionOption::create(['question_id' => $q6->id, 'teks_pilihan' => 'Sulit', 'nilai' => 2]);
        QuestionOption::create(['question_id' => $q6->id, 'teks_pilihan' => 'Sangat Sulit', 'nilai' => 1]);
    }
}