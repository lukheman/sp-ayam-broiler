<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GejalaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('gejala')->insert([
            ['kode_gejala' => 'G01',  'nama_gejala' => 'Nafsu makan berkurang'],
            ['kode_gejala' => 'G02',  'nama_gejala' => 'Nafas sesak/megap-megap'],
            ['kode_gejala' => 'G03',  'nama_gejala' => 'Nafas ngorok'],
            ['kode_gejala' => 'G04',  'nama_gejala' => 'Nafas cepat'],
            ['kode_gejala' => 'G05',  'nama_gejala' => 'Bersin-bersin'],
            ['kode_gejala' => 'G06',  'nama_gejala' => 'Batuk'],
            ['kode_gejala' => 'G07',  'nama_gejala' => 'Badan kurus'],
            ['kode_gejala' => 'G08',  'nama_gejala' => 'Lesu'],
            ['kode_gejala' => 'G09',  'nama_gejala' => 'Bulu kusam dan berkerut'],
            ['kode_gejala' => 'G10', 'nama_gejala' => 'Diare'],
            ['kode_gejala' => 'G11', 'nama_gejala' => 'Bulu berdiri'],
            ['kode_gejala' => 'G12', 'nama_gejala' => 'Kelihatan ngantuk'],
            ['kode_gejala' => 'G13', 'nama_gejala' => 'Kedinginan'],
            ['kode_gejala' => 'G14', 'nama_gejala' => 'Kaki dan perut tidak ditumbuhi bulu, berwarna biru keunguan'],
            ['kode_gejala' => 'G15', 'nama_gejala' => 'Tampak lesu'],
            ['kode_gejala' => 'G16', 'nama_gejala' => 'Feses kehijau-hijauan'],
            ['kode_gejala' => 'G17', 'nama_gejala' => 'Feses keputih-putihan'],
            ['kode_gejala' => 'G18', 'nama_gejala' => 'Feses bercampur darah'],
            ['kode_gejala' => 'G19', 'nama_gejala' => 'Banyak minum'],
            ['kode_gejala' => 'G20', 'nama_gejala' => 'Ayam pucat'],
            ['kode_gejala' => 'G21', 'nama_gejala' => 'Nampak membiru'],
            ['kode_gejala' => 'G22', 'nama_gejala' => 'Sempoyongan'],
            ['kode_gejala' => 'G23', 'nama_gejala' => 'Jengger membengkak merah'],
            ['kode_gejala' => 'G24', 'nama_gejala' => 'Jengger pucat'],
            ['kode_gejala' => 'G25', 'nama_gejala' => 'Kaki bengkak'],
            ['kode_gejala' => 'G26', 'nama_gejala' => 'Kaki meradang atau lumpuh'],
            ['kode_gejala' => 'G27', 'nama_gejala' => 'Kaki pincang'],
            ['kode_gejala' => 'G28', 'nama_gejala' => 'Kelopak mata kemerahan'],
            ['kode_gejala' => 'G29', 'nama_gejala' => 'Eksudat berbuih dari mata'],
            ['kode_gejala' => 'G30', 'nama_gejala' => 'Keluar cairan dari mata'],
            ['kode_gejala' => 'G31', 'nama_gejala' => 'Keluar cairan dari hidung'],
            ['kode_gejala' => 'G32', 'nama_gejala' => 'Keluar nanah dari mata dan bau'],
            ['kode_gejala' => 'G33', 'nama_gejala' => 'Kepala bengkak'],
            ['kode_gejala' => 'G34', 'nama_gejala' => 'Kepala terputar'],
            ['kode_gejala' => 'G35', 'nama_gejala' => 'Mata berair'],
            ['kode_gejala' => 'G36', 'nama_gejala' => 'Pembengkakan dari sinus dan mata'],
            ['kode_gejala' => 'G37', 'nama_gejala' => 'Perut membesar'],
            ['kode_gejala' => 'G38', 'nama_gejala' => 'Sayap mengkerut'],
            ['kode_gejala' => 'G39', 'nama_gejala' => 'Kotoran putih menempel di sekitar anus'],
            ['kode_gejala' => 'G40', 'nama_gejala' => 'Lendir bercampur darah pada rongga mulut'],
            ['kode_gejala' => 'G41', 'nama_gejala' => 'Tidur dengan paruh diletakkan di lantai'],
            ['kode_gejala' => 'G42', 'nama_gejala' => 'Duduk dengan sikap membungkuk'],
            ['kode_gejala' => 'G43', 'nama_gejala' => 'Pertumbuhan ayam lambat'],
            ['kode_gejala' => 'G44', 'nama_gejala' => 'Sendi bengkak atau radang'],
            ['kode_gejala' => 'G45', 'nama_gejala' => 'Cenderung meringkuk di dekat sumber panas'],
            ['kode_gejala' => 'G46', 'nama_gejala' => 'Kotoran encer bercampur butiran'],
            ['kode_gejala' => 'G47', 'nama_gejala' => 'Kotoran putih seperti kapur'],
            ['kode_gejala' => 'G48', 'nama_gejala' => 'Lemah gemetar'],
            ['kode_gejala' => 'G49', 'nama_gejala' => 'Mati secara mendadak / sayap menggantung'],
        ]);
    }
}
