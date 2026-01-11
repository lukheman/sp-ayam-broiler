<?php

namespace Database\Factories;

use App\Models\Gejala;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Gejala>
 */
class GejalaFactory extends Factory
{
    protected $model = Gejala::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $counter = 1;

        return [
            'kode_gejala' => 'G' . str_pad($counter++, 3, '0', STR_PAD_LEFT),
            'nama_gejala' => fake()->sentence(4),
        ];
    }
}
