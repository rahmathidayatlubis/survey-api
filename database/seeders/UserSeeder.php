<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ==========================================
        // BUAT DATA ADMIN
        // ==========================================
        User::create([
            'nama' => 'Admin System',
            'email' => 'admin@survey.com',
            'tanggal_lahir' => '1990-01-01',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // ==========================================
        // BUAT DATA MAHASISWA
        // ==========================================
        User::create([
            'nim' => '2021001',
            'nama' => 'Budi Santoso',
            'email' => 'budi@mahasiswa.com',
            'tanggal_lahir' => '2000-10-18',
            'jurusan' => 'Teknik Informatika',
            'password' => Hash::make('18102000'),
            'role' => 'mahasiswa',
        ]);

        User::create([
            'nim' => '2021002',
            'nama' => 'Siti Nurhaliza',
            'email' => 'siti@mahasiswa.com',
            'tanggal_lahir' => '2001-05-25',
            'jurusan' => 'Sistem Informasi',
            'password' => Hash::make('25052001'),
            'role' => 'mahasiswa',
        ]);
    }
}