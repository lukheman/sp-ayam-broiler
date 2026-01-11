<?php

namespace Database\Factories;

use App\Models\BasisPengetahuan;
use App\Models\Gejala;
use App\Models\Penyakit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BasisPengetahuan>
 */
class BasisPengetahuanFactory extends Factory
{
    protected $model = BasisPengetahuan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_penyakit' => Penyakit::factory(),
            'id_gejala' => Gejala::factory(),
        ];
    }
}
