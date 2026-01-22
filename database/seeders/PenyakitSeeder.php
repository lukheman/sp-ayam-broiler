<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenyakitSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kode_penyakit' => 'P01', 'nama_penyakit' => 'Berak Kapur (Pullorum Disease)'],
            ['kode_penyakit' => 'P02', 'nama_penyakit' => 'Kolera Ayam (Fowl Cholera)'],
            ['kode_penyakit' => 'P03', 'nama_penyakit' => 'Flu Burung (Avian Influenza)'],
            ['kode_penyakit' => 'P04', 'nama_penyakit' => 'Tetelo (Newcastle Disease)'],
            ['kode_penyakit' => 'P05', 'nama_penyakit' => 'Tipus Ayam (Fowl Typhoid)'],
            ['kode_penyakit' => 'P06', 'nama_penyakit' => 'Berak Darah (Coccidosis)'],
            ['kode_penyakit' => 'P07', 'nama_penyakit' => 'Gumboro (Gumboro Disease)'],
            ['kode_penyakit' => 'P08', 'nama_penyakit' => 'Salesma Ayam (Infectious Coryza)'],
            ['kode_penyakit' => 'P09', 'nama_penyakit' => 'Batuk Ayam Menahun (Infectious Bronchitis)'],
            ['kode_penyakit' => 'P10', 'nama_penyakit' => 'Busung Ayam (Lymphoid Leukosis)'],
            ['kode_penyakit' => 'P11', 'nama_penyakit' => 'Batuk Darah (Infectious Laryngotracheitis)'],
            ['kode_penyakit' => 'P12', 'nama_penyakit' => 'Mareks (Mareks Disease)'],
            ['kode_penyakit' => 'P13', 'nama_penyakit' => 'Malaria Unggas'],
            ['kode_penyakit' => 'P14', 'nama_penyakit' => 'Snot'],
            ['kode_penyakit' => 'P15', 'nama_penyakit' => 'Chronic Respiratory Disease'],
        ];

        foreach ($data as $item) {
            DB::table('penyakit')->updateOrInsert(
                ['kode_penyakit' => $item['kode_penyakit']],
                $item
            );
        }
    }
}
