<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Buat user admin jika belum ada
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'nama' => 'Admin',
                'password' => bcrypt('password123'),
            ]
        );

        $this->call([
            PenyakitSeeder::class,
            GejalaSeeder::class,
            BasisPengetahuanSeeder::class
        ]);
    }
}
