<?php

namespace Database\Seeders;

use App\Models\Divisi;
use App\Models\Pengurus;
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

        $webPengurus = Pengurus::where('nama', 'like', '%Budi%')->orWhere('jabatan', 'like', '%Web%')->first();
        $uiuxPengurus = Pengurus::where('nama', 'like', '%Maya%')->orWhere('jabatan', 'like', '%Multimedia%')->first();
        $keorgPengurus = Pengurus::where('nama', 'like', '%Nadia%')->orWhere('jabatan', 'like', '%Humas%')->first();

        $programs = [
            [
                'divisi_id' => $web ? $web->id : $defaultDivisiId,
                'pengurus_id' => $webPengurus ? $webPengurus->id : null,
                'judul_program' => 'LAOS Web & API Intensive Bootcamp',
                'slug' => 'laos-web-api-intensive-bootcamp',
                'location_name' => 'Lab Komputer Gd. Fasilkom UNEJ',
                'deskripsi' => 'Pelatihan intensif selama 2 minggu mengenai dasar pengembangan web (HTML, CSS, JS) hingga pembuatan REST API menggunakan Laravel.',
                'open_regis_panitia' => '2026-03-01',
                'close_regis_panitia' => '2026-03-10',
                'gform_panitia' => 'https://forms.gle/laos-panitia-bootcamp',
                'open_regis_peserta' => '2026-03-15',
                'close_regis_peserta' => '2026-03-30',
                'gform_peserta' => 'https://forms.gle/laos-peserta-bootcamp',
            ],
            [
                'divisi_id' => $uiux ? $uiux->id : $defaultDivisiId,
                'pengurus_id' => $uiuxPengurus ? $uiuxPengurus->id : null,
                'judul_program' => 'Design Sprint & Figma Masterclass',
                'slug' => 'design-sprint-figma-masterclass',
                'location_name' => 'Auditorium Fasilkom UNEJ / Hybrid Zoom',
                'deskripsi' => 'Workshop kolaboratif merancang antarmuka pengguna (UI/UX) modern menggunakan metodologi Design Sprint dan alat bantu Figma.',
                'open_regis_panitia' => '2026-04-01',
                'close_regis_panitia' => '2026-04-08',
                'gform_panitia' => 'https://forms.gle/laos-panitia-design',
                'open_regis_peserta' => '2026-04-10',
                'close_regis_peserta' => '2026-04-25',
                'gform_peserta' => 'https://forms.gle/laos-peserta-design',
            ],
            [
                'divisi_id' => $keorg ? $keorg->id : $defaultDivisiId,
                'pengurus_id' => $keorgPengurus ? $keorgPengurus->id : null,
                'judul_program' => 'Open Recruitment Pengurus & Anggota LAOS',
                'slug' => 'open-recruitment-pengurus-anggota-laos',
                'location_name' => 'Ruang UKM Gedung PKM UNEJ',
                'deskripsi' => 'Penerimaan anggota dan pengurus baru UKM LAOS periode 2026. Jadilah bagian dari komunitas IT terbesar di Universitas Jember.',
                'open_regis_panitia' => '2026-08-01',
                'close_regis_panitia' => '2026-08-10',
                'gform_panitia' => 'https://forms.gle/laos-panitia-oprec',
                'open_regis_peserta' => '2026-08-15',
                'close_regis_peserta' => '2026-08-31',
                'gform_peserta' => 'https://forms.gle/laos-peserta-oprec',
            ],
        ];

        foreach ($programs as $prog) {
            Program::updateOrCreate(
                ['slug' => $prog['slug']],
                $prog
            );
        }
    }
}
