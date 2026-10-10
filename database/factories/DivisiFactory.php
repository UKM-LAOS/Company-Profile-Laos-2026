<?php

namespace Database\Factories;

use App\Models\Divisi;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Divisi>
 */
class DivisiFactory extends Factory
{
    protected $model = Divisi::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nama = fake()->unique()->words(2, true);

        return [
            'nama' => ucfirst($nama),
            'slug' => Str::slug($nama),
            'deskripsi' => fake()->paragraph(),
            'logo' => null,
        ];
    }
}
