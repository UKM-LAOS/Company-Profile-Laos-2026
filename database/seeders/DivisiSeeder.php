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
                'nama' => 'Software Development',
                'slug' => 'software-development',
                'deskripsi' => 'Divisi teknis yang berfokus pada riset, perancangan, dan pengembangan sistem aplikasi web, mobile, serta perangkat lunak open-source.',
                'logo' => null,
            ],
            [
                'nama' => 'Cyber Security',
                'slug' => 'cyber-security',
                'deskripsi' => 'Divisi yang berfokus pada eksplorasi keamanan siber, penetration testing, pertahanan jaringan, dan keamanan sistem informasi.',
                'logo' => null,
            ],
            [
                'nama' => 'Human Resource Management',
                'slug' => 'human-resource-management',
                'deskripsi' => 'Divisi yang bertanggung jawab dalam pengembangan potensi anggota, regenerasi, keakraban internal, dan manajemen sumber daya manusia.',
                'logo' => null,
            ],
            [
                'nama' => 'Humas',
                'slug' => 'humas',
                'deskripsi' => 'Divisi penghubung komunikasi eksternal, branding organisasi, kemitraan, dan relasi antar lembaga maupun komunitas teknologi.',
                'logo' => null,
            ],
            [
                'nama' => 'Multimedia',
                'slug' => 'multimedia',
                'deskripsi' => 'Divisi kreatif yang berfokus pada desain visual, UI/UX, videografi, fotografi, dan konten multimedia organisasi.',
                'logo' => null,
            ],
        ];

        foreach ($divisis as $divisi) {
            Divisi::updateOrCreate(
                ['slug' => $divisi['slug']],
                $divisi
            );
        }
    }
}
