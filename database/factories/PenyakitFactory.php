<?php

namespace Database\Factories;

use App\Models\Penyakit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Penyakit>
 */
class PenyakitFactory extends Factory
{
    protected $model = Penyakit::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $counter = 1;

        return [
            'kode_penyakit' => 'P' . str_pad($counter++, 3, '0', STR_PAD_LEFT),
            'nama_penyakit' => fake()->sentence(3),
            'solusi' => fake()->optional(0.8)->paragraph(2),
        ];
    }
}
