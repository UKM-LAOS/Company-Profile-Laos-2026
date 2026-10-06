<?php

namespace Database\Seeders;

use App\Models\Pengurus;
use Illuminate\Database\Seeder;

class PengurusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $penguruses = [
            [
                'nama' => 'Ahmad Fathoni',
                'foto' => null,
                'jabatan' => 'Ketua Umum',
                'periode' => '2025/2026',
                'sosmed' => [
                    'instagram' => 'https://instagram.com/ahmadfathoni',
                    'linkedin' => 'https://linkedin.com/in/ahmadfathoni',
                    'github' => 'https://github.com/ahmadfathoni',
                ],
                'urutan' => 1,
                'aktif' => true,
            ],
            [
                'nama' => 'Siti Nurhaliza',
                'foto' => null,
                'jabatan' => 'Sekretaris Umum',
                'periode' => '2025/2026',
                'sosmed' => [
                    'instagram' => 'https://instagram.com/sitinurhaliza',
                    'linkedin' => 'https://linkedin.com/in/sitinurhaliza',
                ],
                'urutan' => 2,
                'aktif' => true,
            ],
            [
                'nama' => 'Dewi Anggraini',
                'foto' => null,
                'jabatan' => 'Bendahara Umum',
                'periode' => '2025/2026',
                'sosmed' => [
                    'instagram' => 'https://instagram.com/dewianggraini',
                    'linkedin' => 'https://linkedin.com/in/dewianggraini',
                ],
                'urutan' => 3,
                'aktif' => true,
            ],
            [
                'nama' => 'Budi Prasetyo',
                'foto' => null,
                'jabatan' => 'Koordinator Divisi Web Development',
                'periode' => '2025/2026',
                'sosmed' => [
                    'instagram' => 'https://instagram.com/budipras',
                    'github' => 'https://github.com/budipras',
                ],
                'urutan' => 4,
                'aktif' => true,
            ],
            [
                'nama' => 'Rian Ardiansyah',
                'foto' => null,
                'jabatan' => 'Koordinator Divisi Mobile & IoT',
                'periode' => '2025/2026',
                'sosmed' => [
                    'github' => 'https://github.com/rianard',
                    'linkedin' => 'https://linkedin.com/in/rianard',
                ],
                'urutan' => 5,
                'aktif' => true,
            ],
            [
                'nama' => 'Maya Kartika',
                'foto' => null,
                'jabatan' => 'Koordinator Divisi Multimedia & UI/UX',
                'periode' => '2025/2026',
                'sosmed' => [
                    'instagram' => 'https://instagram.com/mayakartika',
                    'linkedin' => 'https://linkedin.com/in/mayakartika',
                ],
                'urutan' => 6,
                'aktif' => true,
            ],
            [
                'nama' => 'Dimas Maulana',
                'foto' => null,
                'jabatan' => 'Koordinator Keorganisasian & Humas',
                'periode' => '2025/2026',
                'sosmed' => [
                    'instagram' => 'https://instagram.com/dimasmaul',
                ],
                'urutan' => 7,
                'aktif' => true,
            ],
        ];

        foreach ($penguruses as $pengurus) {
            Pengurus::firstOrCreate(
                [
                    'nama' => $pengurus['nama'],
                    'periode' => $pengurus['periode'],
                ],
                $pengurus
            );
        }
    }
}
