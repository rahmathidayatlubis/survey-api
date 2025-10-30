<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // $this->call(UserSeeder::class);
        $this->call([SurveySeeder::class, UserSeeder::class]);

        // ==========================================
        // RINGKASAN DATA
        // ==========================================
        echo "\n===========================================\n";
        echo "📊 DATABASE SEEDING COMPLETED\n";
        echo "===========================================\n";
        echo "👤 Admin: 1 user\n";
        echo "👨‍🎓 Mahasiswa: 2 users\n";
        echo "📋 Surveys: 2 surveys\n";
        echo "❓ Questions: " . Question::count() . " questions\n";
        echo "✅ Options: " . QuestionOption::count() . " options\n";
        echo "===========================================\n";
    }
}