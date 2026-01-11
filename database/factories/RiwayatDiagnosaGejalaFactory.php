<?php

namespace Database\Factories;

use App\Models\Gejala;
use App\Models\RiwayatDiagnosa;
use App\Models\RiwayatDiagnosaGejala;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RiwayatDiagnosaGejala>
 */
class RiwayatDiagnosaGejalaFactory extends Factory
{
    protected $model = RiwayatDiagnosaGejala::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_riwayat_diagnosa' => RiwayatDiagnosa::factory(),
            'id_gejala' => Gejala::factory(),
        ];
    }
}
