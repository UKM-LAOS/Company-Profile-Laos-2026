<?php

namespace Database\Seeders;

use App\Models\Divisi;
use Illuminate\Database\Seeder;

class DivisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisis = [
            [
                'nama' => 'Badan Pengurus Harian',
                'slug' => 'badan-pengurus-harian',
                'deskripsi' => 'Pengarah utama arah gerak, koordinasi internal, dan pengambil kebijakan tertinggi organisasi UKM LAOS.',
                'logo' => null,
            ],
            [
                'nama' => 'Web Development',
                'slug' => 'web-development',
                'deskripsi' => 'Divisi teknis yang berfokus pada riset, perancangan, dan pengembangan sistem aplikasi web modern open-source.',
                'logo' => null,
            ],
            [
                'nama' => 'Mobile & IoT Development',
                'slug' => 'mobile-iot-development',
                'deskripsi' => 'Divisi yang berfokus pada eksplorasi ekosistem mobile application lintas platform dan integrasi hardware IoT.',
                'logo' => null,
            ],
            [
                'nama' => 'Multimedia & UI/UX Design',
                'slug' => 'multimedia-ui-ux-design',
                'deskripsi' => 'Divisi kreatif yang berfokus pada user experience, antarmuka visual, branding identitas, dan media visual organisasi.',
                'logo' => null,
            ],
            [
                'nama' => 'Keorganisasian & Humas',
                'slug' => 'keorganisasian-humas',
                'deskripsi' => 'Divisi penghubung komunikasi eksternal, kemitraan sponsor, dan pengembangan sumber daya kader organisasi.',
                'logo' => null,
            ],
        ];

        foreach ($divisis as $divisi) {
            Divisi::firstOrCreate(
                ['slug' => $divisi['slug']],
                $divisi
            );
        }
    }
}
