<?php

namespace Database\Seeders;

use App\Models\Divisi;
use App\Models\Program;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bph = Divisi::where('slug', 'badan-pengurus-harian')->first();
        $web = Divisi::where('slug', 'web-development')->first();
        $uiux = Divisi::where('slug', 'multimedia-ui-ux-design')->first();
        $keorg = Divisi::where('slug', 'keorganisasian-humas')->first();

        $defaultDivisi = Divisi::first();
        $defaultDivisiId = $bph ? $bph->id : ($defaultDivisi ? $defaultDivisi->id : 1);

        $programs = [
            [
                'divisi_id' => $web ? $web->id : $defaultDivisiId,
                'judul_program' => 'LAOS Web & API Intensive Bootcamp',
                'slug' => 'laos-web-api-intensive-bootcamp',
                'location_name' => 'Lab Komputer Gd. Fasilkom UNEJ',
                'open_regis_panitia' => '2026-03-01',
                'close_regis_panitia' => '2026-03-10',
                'gform_panitia' => 'https://forms.gle/laos-panitia-bootcamp',
                'open_regis_peserta' => '2026-03-15',
                'close_regis_peserta' => '2026-03-30',
                'gform_peserta' => 'https://forms.gle/laos-peserta-bootcamp',
            ],
            [
                'divisi_id' => $uiux ? $uiux->id : $defaultDivisiId,
                'judul_program' => 'Design Sprint & Figma Masterclass',
                'slug' => 'design-sprint-figma-masterclass',
                'location_name' => 'Auditorium Fasilkom UNEJ / Hybrid Zoom',
                'open_regis_panitia' => '2026-04-01',
                'close_regis_panitia' => '2026-04-08',
                'gform_panitia' => 'https://forms.gle/laos-panitia-design',
                'open_regis_peserta' => '2026-04-10',
                'close_regis_peserta' => '2026-04-25',
                'gform_peserta' => 'https://forms.gle/laos-peserta-design',
            ],
            [
                'divisi_id' => $keorg ? $keorg->id : $defaultDivisiId,
                'judul_program' => 'Open Recruitment Pengurus & Anggota LAOS',
                'slug' => 'open-recruitment-pengurus-anggota-laos',
                'location_name' => 'Ruang UKM Gedung PKM UNEJ',
                'open_regis_panitia' => '2026-08-01',
                'close_regis_panitia' => '2026-08-10',
                'gform_panitia' => 'https://forms.gle/laos-panitia-oprec',
                'open_regis_peserta' => '2026-08-15',
                'close_regis_peserta' => '2026-08-31',
                'gform_peserta' => 'https://forms.gle/laos-peserta-oprec',
            ],
        ];

        foreach ($programs as $prog) {
            Program::firstOrCreate(
                ['slug' => $prog['slug']],
                $prog
            );
        }
    }
}
