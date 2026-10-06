<?php

namespace Database\Factories;

use App\Models\Pengurus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pengurus>
 */
class PengurusFactory extends Factory
{
    protected $model = Pengurus::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'jabatan' => fake()->randomElement(['Ketua Umum', 'Sekretaris Umum', 'Bendahara Umum', 'Koordinator Divisi']),
            'periode' => '2025/2026',
            'foto' => null,
            'sosmed' => null,
            'urutan' => fake()->numberBetween(0, 50),
            'aktif' => true,
        ];
    }
}
