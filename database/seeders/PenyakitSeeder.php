<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenyakitSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'kode_penyakit' => 'P01',
                'nama_penyakit' => 'Berak Kapur (Pullorum Disease)',
                'solusi' => 'Pisahkan ayam yang sakit, berikan antibiotik (sulfadimidin atau furazolidon), tingkatkan sanitasi kandang, dan musnahkan ayam yang terinfeksi parah untuk mencegah penularan.'
            ],
            [
                'kode_penyakit' => 'P02',
                'nama_penyakit' => 'Kolera Ayam (Fowl Cholera)',
                'solusi' => 'Berikan antibiotik seperti sulfonamida atau tetrasiklin, vaksinasi pencegahan, tingkatkan kebersihan kandang, dan isolasi ayam yang terinfeksi.'
            ],
            [
                'kode_penyakit' => 'P03',
                'nama_penyakit' => 'Flu Burung (Avian Influenza)',
                'solusi' => 'Segera laporkan ke dinas peternakan, lakukan karantina ketat, musnahkan ayam yang terinfeksi, desinfeksi kandang menyeluruh, dan terapkan biosekuriti yang ketat.'
            ],
            [
                'kode_penyakit' => 'P04',
                'nama_penyakit' => 'Tetelo (Newcastle Disease)',
                'solusi' => 'Vaksinasi pencegahan (ND), pisahkan ayam sakit, berikan vitamin untuk meningkatkan daya tahan tubuh, dan tingkatkan sanitasi kandang.'
            ],
            [
                'kode_penyakit' => 'P05',
                'nama_penyakit' => 'Tipus Ayam (Fowl Typhoid)',
                'solusi' => 'Berikan antibiotik (furazolidon atau sulfonamida), isolasi ayam yang terinfeksi, tingkatkan kebersihan pakan dan minum, serta desinfeksi kandang.'
            ],
            [
                'kode_penyakit' => 'P06',
                'nama_penyakit' => 'Berak Darah (Coccidosis)',
                'solusi' => 'Berikan obat coccidiostat (amprolium atau sulfonamida), jaga kelembaban litter tetap kering, berikan vitamin K untuk membantu pembekuan darah, dan tingkatkan sanitasi.'
            ],
            [
                'kode_penyakit' => 'P07',
                'nama_penyakit' => 'Gumboro (Gumboro Disease)',
                'solusi' => 'Vaksinasi pencegahan (IBD), berikan vitamin dan elektrolit untuk meningkatkan daya tahan tubuh, jaga suhu kandang optimal, dan isolasi ayam yang sakit.'
            ],
            [
                'kode_penyakit' => 'P08',
                'nama_penyakit' => 'Salesma Ayam (Infectious Coryza)',
                'solusi' => 'Berikan antibiotik (sulfonamida atau eritromisin), tingkatkan ventilasi kandang, isolasi ayam yang terinfeksi, dan vaksinasi pencegahan.'
            ],
            [
                'kode_penyakit' => 'P09',
                'nama_penyakit' => 'Batuk Ayam Menahun (Infectious Bronchitis)',
                'solusi' => 'Vaksinasi pencegahan (IB), berikan antibiotik untuk mencegah infeksi sekunder, tingkatkan ventilasi, dan berikan vitamin E dan C.'
            ],
            [
                'kode_penyakit' => 'P10',
                'nama_penyakit' => 'Busung Ayam (Lymphoid Leukosis)',
                'solusi' => 'Tidak ada pengobatan spesifik. Lakukan seleksi bibit yang bebas virus, musnahkan ayam yang terinfeksi, dan tingkatkan biosekuriti kandang.'
            ],
            [
                'kode_penyakit' => 'P11',
                'nama_penyakit' => 'Batuk Darah (Infectious Laryngotracheitis)',
                'solusi' => 'Vaksinasi pencegahan (ILT), berikan antibiotik untuk mencegah infeksi sekunder, tingkatkan ventilasi kandang, dan isolasi ayam yang terinfeksi.'
            ],
            [
                'kode_penyakit' => 'P12',
                'nama_penyakit' => 'Mareks (Mareks Disease)',
                'solusi' => 'Vaksinasi DOC (Day Old Chick), tidak ada pengobatan untuk ayam yang sudah terinfeksi, musnahkan ayam yang terinfeksi parah, dan tingkatkan biosekuriti.'
            ],
            [
                'kode_penyakit' => 'P13',
                'nama_penyakit' => 'Malaria Unggas',
                'solusi' => 'Berikan obat antimalaria (primakuin atau klorokuin), kontrol populasi nyamuk di sekitar kandang, dan pasang kawat nyamuk pada kandang.'
            ],
            [
                'kode_penyakit' => 'P14',
                'nama_penyakit' => 'Snot',
                'solusi' => 'Berikan antibiotik (streptomisin atau tetrasiklin), tingkatkan ventilasi kandang, jaga kebersihan tempat makan dan minum, serta isolasi ayam yang sakit.'
            ],
            [
                'kode_penyakit' => 'P15',
                'nama_penyakit' => 'Chronic Respiratory Disease',
                'solusi' => 'Berikan antibiotik (tylosin atau eritromisin), tingkatkan ventilasi kandang, kurangi kepadatan populasi, dan berikan vitamin untuk meningkatkan imunitas.'
            ],
        ];

        foreach ($data as $item) {
            DB::table('penyakit')->updateOrInsert(
                ['kode_penyakit' => $item['kode_penyakit']],
                $item
            );
        }
    }
}
