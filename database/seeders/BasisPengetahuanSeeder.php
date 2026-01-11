<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BasisPengetahuanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [

            // ===== G1 =====
            ['id_penyakit' => 1,  'id_gejala' => 1], // P01 - G1
            ['id_penyakit' => 2,  'id_gejala' => 1],
            ['id_penyakit' => 3,  'id_gejala' => 1],
            ['id_penyakit' => 4,  'id_gejala' => 1],
            ['id_penyakit' => 5,  'id_gejala' => 1],
            ['id_penyakit' => 6,  'id_gejala' => 1],
            ['id_penyakit' => 7,  'id_gejala' => 1],
            ['id_penyakit' => 8,  'id_gejala' => 1],
            ['id_penyakit' => 9,  'id_gejala' => 1],
            ['id_penyakit' => 10, 'id_gejala' => 1],
            ['id_penyakit' => 11, 'id_gejala' => 1],
            ['id_penyakit' => 13, 'id_gejala' => 1],
            ['id_penyakit' => 15, 'id_gejala' => 1],

            // ===== G2 =====
            ['id_penyakit' => 1,  'id_gejala' => 2],
            ['id_penyakit' => 2,  'id_gejala' => 2],
            ['id_penyakit' => 3,  'id_gejala' => 2],
            ['id_penyakit' => 4,  'id_gejala' => 2],
            ['id_penyakit' => 7,  'id_gejala' => 2],
            ['id_penyakit' => 9,  'id_gejala' => 2],
            ['id_penyakit' => 15, 'id_gejala' => 2],

            // ===== G3 =====
            ['id_penyakit' => 2,  'id_gejala' => 3],
            ['id_penyakit' => 3,  'id_gejala' => 3],
            ['id_penyakit' => 4,  'id_gejala' => 3],
            ['id_penyakit' => 9,  'id_gejala' => 3],
            ['id_penyakit' => 11, 'id_gejala' => 3],
            ['id_penyakit' => 15, 'id_gejala' => 3],

            // ===== G4 =====
            ['id_penyakit' => 1,  'id_gejala' => 4],
            ['id_penyakit' => 12, 'id_gejala' => 4],
            ['id_penyakit' => 14, 'id_gejala' => 4],

            // ===== LANJUTKAN SESUAI TANDA √ PADA TABEL =====

        ];

        DB::table('basis_pengetahuan')->insert($data);
    }
}
