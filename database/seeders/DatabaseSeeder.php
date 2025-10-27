<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Survey;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ==========================================
        // BUAT DATA ADMIN
        // ==========================================
        $admin = User::create([
            'nim' => 'ADMIN001',
            'nama' => 'Admin System',
            'email' => 'admin@survey.com',
            'tanggal_lahir' => '1990-01-01',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        echo "✅ Admin created: {$admin->nama} (Email: {$admin->email})\n";

        // ==========================================
        // BUAT DATA MAHASISWA
        // ==========================================
        $mahasiswa1 = User::create([
            'nim' => '2021001',
            'nama' => 'Budi Santoso',
            'email' => 'budi@mahasiswa.com',
            'tanggal_lahir' => '2000-10-18',
            'jurusan' => 'Teknik Informatika',
            'password' => Hash::make('18102000'),
            'role' => 'mahasiswa',
        ]);

        echo "✅ Mahasiswa created: {$mahasiswa1->nama} (NIM: {$mahasiswa1->nim}, Password: 18102000)\n";

        $mahasiswa2 = User::create([
            'nim' => '2021002',
            'nama' => 'Siti Nurhaliza',
            'email' => 'siti@mahasiswa.com',
            'tanggal_lahir' => '2001-05-25',
            'jurusan' => 'Sistem Informasi',
            'password' => Hash::make('25052001'), // default password = tanggal lahir
            'role' => 'mahasiswa',
        ]);

        echo "✅ Mahasiswa created: {$mahasiswa2->nama} (NIM: {$mahasiswa2->nim}, Password: 25052001)\n";

        $mahasiswa3 = User::create([
            'nim' => '2021003',
            'nama' => 'Ahmad Rizki',
            'email' => 'ahmad@mahasiswa.com',
            'tanggal_lahir' => '2000-12-15',
            'jurusan' => 'Teknik Elektro',
            'password' => Hash::make('15122000'),
            'role' => 'mahasiswa',
        ]);

        echo "✅ Mahasiswa created: {$mahasiswa3->nama} (NIM: {$mahasiswa3->nim}, Password: 15122000)\n";

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

        echo "\n✅ Survey created: {$survey->judul}\n";

        // ==========================================
        // PERTANYAAN 1: Kualitas Pengajaran Dosen
        // ==========================================
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

        echo "  ✅ Question 1 created with 5 options\n";

        // ==========================================
        // PERTANYAAN 2: Fasilitas Perpustakaan
        // ==========================================
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

        echo "  ✅ Question 2 created with 5 options\n";

        // ==========================================
        // PERTANYAAN 3: Pelayanan Administrasi
        // ==========================================
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

        echo "  ✅ Question 3 created with 5 options\n";

        // ==========================================
        // PERTANYAAN 4: Fasilitas Laboratorium
        // ==========================================
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

        echo "  ✅ Question 4 created with 5 options\n";

        // ==========================================
        // PERTANYAAN 5: Kebersihan Kampus
        // ==========================================
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

        echo "  ✅ Question 5 created with 5 options\n";

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

        echo "\n✅ Survey 2 created: {$survey2->judul} (Inactive)\n";

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

        echo "  ✅ Question created for Survey 2\n";

        // ==========================================
        // RINGKASAN DATA
        // ==========================================
        echo "\n===========================================\n";
        echo "📊 DATABASE SEEDING COMPLETED\n";
        echo "===========================================\n";
        echo "👤 Admin: 1 user\n";
        echo "👨‍🎓 Mahasiswa: 3 users\n";
        echo "📋 Surveys: 2 surveys\n";
        echo "❓ Questions: " . Question::count() . " questions\n";
        echo "✅ Options: " . QuestionOption::count() . " options\n";
        echo "===========================================\n";
    }
}