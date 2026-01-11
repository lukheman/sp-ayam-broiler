<?php

namespace Database\Factories;

use App\Models\Penyakit;
use App\Models\RiwayatDiagnosa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RiwayatDiagnosa>
 */
class RiwayatDiagnosaFactory extends Factory
{
    protected $model = RiwayatDiagnosa::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tanggal' => fake()->date(),
            'nama' => fake()->name(),
            'id_penyakit' => Penyakit::factory(),
        ];
    }
}
