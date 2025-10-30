<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

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
    $faker = Faker::create('id_ID'); // gunakan locale Indonesia

        for ($i = 1; $i <= 20; $i++) {
            User::create([
                'nim' => '2021' . str_pad($i, 3, '0', STR_PAD_LEFT), // contoh: 2021001, 2021002, dst
                'nama' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'tanggal_lahir' => $faker->date('Y-m-d', '2003-12-31'),
                'jurusan' => $faker->randomElement([
                    'Teknik Informatika',
                    'Sistem Informasi',
                    'Teknik Komputer',
                    'Manajemen Informatika'
                ]),
                'password' => Hash::make('123456'), // default password
                'role' => 'mahasiswa',
                'uuid' => Str::uuid(),
            ]);
        }
    }
}