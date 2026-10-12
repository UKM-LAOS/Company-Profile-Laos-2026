<?php

namespace Database\Factories;

use App\Models\Divisi;
use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Program>
 */
class ProgramFactory extends Factory
{
    protected $model = Program::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $judul = fake()->unique()->words(3, true);

        return [
            'divisi_id' => Divisi::factory(),
            'judul_program' => ucfirst($judul),
            'slug' => Str::slug($judul),
            'location_name' => 'Gedung Fasilkom UNEJ',
            'foto' => null,
            'open_regis_panitia' => '2026-04-01',
            'close_regis_panitia' => '2026-04-10',
            'gform_panitia' => 'https://forms.gle/panitia-sample',
            'open_regis_peserta' => '2026-04-15',
            'close_regis_peserta' => '2026-04-30',
            'gform_peserta' => 'https://forms.gle/peserta-sample',
        ];
    }
}
