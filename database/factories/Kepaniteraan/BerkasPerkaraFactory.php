<?php

namespace Database\Factories\Kepaniteraan;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kepaniteraan\BerkasPerkara>
 */
class BerkasPerkaraFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nomor_perkara' => $this->faker->unique()->numerify('PP-#####'),
            'penggugat' => $this->faker->name(),
            'tergugat' => $this->faker->name(),
            'tanggal_masuk' => $this->faker->date(),
            'status' => $this->faker->randomElement(['tersedia', 'dipinjam']),
            'lokasi' => $this->faker->optional()->word(),
        ];
    }
}
