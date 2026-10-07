<?php

namespace Database\Seeders;

use App\Models\Pengurus;
use Illuminate\Database\Seeder;

class PengurusSeeder extends Seeder
{
    /**
     * Tentukan urutan hierarki kepengurusan berdasarkan jabatan:
     * 1: Ketua Umum
     * 2: Wakil Ketua Umum
     * 3: Sekertaris 1
     * 4: Sekertaris 2
     * 5: Bendahara 1
     * 6: Bendahara 2
     * 7: Kepala Divisi / Kepala Sub Divisi
     * 8: Anggota
     */
    public static function getUrutan(string $jabatan): int
    {
        $j = mb_strtolower($jabatan);

        if (str_contains($j, 'wakil') || str_contains($j, 'waketum')) {
            return 2;
        }

        if (str_contains($j, 'ketua umum') || str_contains($j, 'ketum')) {
            return 1;
        }

        // Sekre 2 dicek terlebih dahulu karena string 'sekertaris ii' mengandung 'sekertaris i'
        if (
            str_contains($j, 'sekertaris ii') ||
            str_contains($j, 'sekretaris ii') ||
            str_contains($j, 'sekretaris 2') ||
            str_contains($j, 'sekertaris 2') ||
            str_contains($j, 'sekre 2') ||
            str_contains($j, 'sekre ii')
        ) {
            return 4;
        }

        // Sekre 1 / Sekertaris I
        if (
            str_contains($j, 'sekertaris') ||
            str_contains($j, 'sekretaris') ||
            str_contains($j, 'sekre')
        ) {
            return 3;
        }

        // Bendahara 2 dicek sebelum Bendahara 1
        if (
            str_contains($j, 'bendahara ii') ||
            str_contains($j, 'bendahara 2')
        ) {
            return 6;
        }

        // Bendahara 1 / Bendahara
        if (str_contains($j, 'bendahara')) {
            return 5;
        }

        // Kepala Divisi / Kepala Sub Divisi
        if (str_contains($j, 'kepala')) {
            return 7;
        }

        // Anggota
        return 8;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bersihkan data lama
        Pengurus::query()->forceDelete();

        $penguruses = [
            [
                'nama' => 'Nandana Aji Nugroho Romadhoni',
                'jabatan' => 'Ketua Umum',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/skiestimez',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Aditya Sakti Prananda',
                'jabatan' => 'Wakil Ketua Umum',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/_nanda.anan',
                    'linkedin' => 'https://www.linkedin.com/in/aditya-sp-sundays04/',
                    'github' => 'https://github.com/CIELDAWN',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Khalyana Kartika Dewi',
                'jabatan' => 'Bendahara',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/_dlyna',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Ifran Raffi Gunawan',
                'jabatan' => 'Sekertaris I',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/raffiefh',
                    'linkedin' => 'https://www.linkedin.com/in/ifran-raffi-gunawan/',
                    'github' => 'https://github.com/raffiefh',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Muhammad Fuadi Kamil',
                'jabatan' => 'Sekertaris II',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/adictive____',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Syavana Salsabila',
                'jabatan' => 'Kepala Divisi Humas',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/_vanvl',
                    'linkedin' => 'https://www.linkedin.com/in/syavanasalsabila/',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Sharifah Nuraini',
                'jabatan' => 'Anggota Divisi Humas',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/shrshasa',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Rafael Ababil Ainur Hadi',
                'jabatan' => 'Anggota Divisi Humas',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/rfael.hd',
                    'linkedin' => 'https://www.linkedin.com/in/rafael-ababil-ainur-hadi-634270387',
                    'github' => 'https://github.com/rafaelhdi',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Shinta Bella Nurrohmah',
                'jabatan' => 'Anggota Divisi Humas',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/nrs4____',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Moch. Farhan Syahbanna',
                'jabatan' => 'Anggota Divisi Humas',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/farhan_syah007',
                    'linkedin' => 'https://www.linkedin.com/in/moch-farhan-syahbanna/',
                    'github' => 'https://github.com/farhansyahbanna',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Neva Maritza Andini',
                'jabatan' => 'Anggota Divisi Humas',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/nvaandnii',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Jamilatuz Zahra',
                'jabatan' => 'Kepala Divisi Human Resource Management (HRM)',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => null,
                'aktif' => true,
            ],
            [
                'nama' => 'Muhammad Habib Ar-Rasyid Ashar',
                'jabatan' => 'Anggota Divisi HRM',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/habibarrasyid17',
                    'linkedin' => 'https://www.linkedin.com/in/muhammad-habib-ar-rasyid-ashar-94447a330/',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Danang Rizki Ramadhan',
                'jabatan' => 'Anggota Divisi HRM',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/a.danang_r',
                    'github' => 'https://github.com/Danang705',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Gerald Jepedro Sitorus',
                'jabatan' => 'Anggota Divisi HRM',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/grld.33',
                    'linkedin' => 'https://www.linkedin.com/in/gerald-sitorus/',
                    'github' => 'https://github.com/g3raldatsc',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Aurellya Kusuma Wardhani',
                'jabatan' => 'Anggota Divisi HRM',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/aureeelw',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Audri Dwi Lestari',
                'jabatan' => 'Anggota Divisi HRM',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/audrilestari',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Syifa Islami Auliya Qolbi',
                'jabatan' => 'Anggota Divisi HRM',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/syaqlbii_',
                    'linkedin' => 'https://www.linkedin.com/in/syifa-islami-auliya-qolbi-026b69330',
                    'github' => 'https://github.com/oOwbiie',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Tunggul Abdul Majid',
                'jabatan' => 'Kepala Divisi Software Development',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/t.abdlmajd_',
                    'linkedin' => 'https://www.linkedin.com/in/tunggulabdulmajid/',
                    'github' => 'https://github.com/tunggulalmajid',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Tantri Okta Puspita Sari',
                'jabatan' => 'Kepala Sub Divisi Perancangan',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/tantrioktaa',
                    'linkedin' => 'https://www.linkedin.com/in/tantrioktaps',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Kamelia Rizkiana',
                'jabatan' => 'Anggota Sub Divisi Perancangan',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/liaorzk',
                    'linkedin' => 'https://www.linkedin.com/in/kamelia-rizkiana/',
                    'github' => 'https://github.com/kameliarz',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Alya Nadilla Arosma',
                'jabatan' => 'Anggota Sub Divisi Perancangan',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/alyanadillaa',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Dina Lu\'luul Karimah',
                'jabatan' => 'Anggota Sub Divisi Perancangan',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/hn.dinaull',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Muchammad Nur Kholish',
                'jabatan' => 'Kepala Sub Divisi Pengembangan',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/kholish_d',
                    'linkedin' => 'https://www.linkedin.com/in/mnurkholish/',
                    'github' => 'https://github.com/mnurkholish',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Taja Trang Alta Gemilang',
                'jabatan' => 'Anggota Sub Divisi Pengembangan',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/taltagem',
                    'linkedin' => 'https://www.linkedin.com/in/altagemilang',
                    'github' => 'https://github.com/HarmlessValve',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Aditya Dwi Ferdiansyah',
                'jabatan' => 'Anggota Sub Divisi Pengembangan',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/adtydwf',
                    'linkedin' => 'https://www.linkedin.com/in/adtydwf/',
                    'github' => 'https://github.com/delissesu',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Agung Kurniawan',
                'jabatan' => 'Anggota Sub Divisi Pengembangan',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/agungkurrrrrr',
                    'linkedin' => 'https://www.linkedin.com/in/agung-kurniawan-363781330/',
                    'github' => 'https://github.com/AKZeqw',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Aldo Rifki Firmansyah',
                'jabatan' => 'Anggota Sub Divisi Pengembangan',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/firmansyah.09_',
                    'linkedin' => 'https://www.linkedin.com/in/aldo-rifki-firmansyah-217290287/',
                    'github' => 'https://github.com/aldorifkifirmansyah',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Khosyatullah Ahmad',
                'jabatan' => 'Anggota Sub Divisi Pengembangan',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/ahmdshnaa',
                    'linkedin' => 'https://www.linkedin.com/in/khosyatullah-ahmad-40b150248/',
                    'github' => 'https://github.com/Dzox13524',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Ahmad Zafarell Zouvan Dhani',
                'jabatan' => 'Kepala Divisi Cyber Security',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://www.instagram.com/akupengenmenanglomba/',
                    'linkedin' => 'https://www.linkedin.com/in/ahmadzafarell',
                    'github' => 'https://github.com/Farewellez',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Arie Akbarull Ridho',
                'jabatan' => 'Anggota Sub Divisi OS dan Jaringan',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://www.instagram.com/ariear__',
                    'linkedin' => 'https://www.linkedin.com/in/ariear/',
                    'github' => 'https://github.com/ariear',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Excelyno Magenta',
                'jabatan' => 'Anggota Sub Divisi OS dan Jaringan',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/excelyno5',
                    'linkedin' => 'https://www.linkedin.com/in/excelyno-magenta/',
                    'github' => 'https://github.com/excelyno',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Dennise Surya Anggara',
                'jabatan' => 'Anggota Sub Divisi OS dan Jaringan',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/anggara.dennise',
                    'linkedin' => 'https://www.linkedin.com/in/dennise-anggara/',
                    'github' => 'https://github.com/angganyobaIT',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Risma Ayuning Tiyas',
                'jabatan' => 'Anggota Sub Divisi Keamanan Siber',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/risma_reez',
                    'linkedin' => 'https://www.linkedin.com/in/rismatiyas2317',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Andro Mahesha Aprian',
                'jabatan' => 'Anggota Sub Divisi Keamanan Siber',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/androf.23',
                    'linkedin' => 'https://www.linkedin.com/in/andro-mahesha-aprian',
                    'github' => 'https://github.com/Loidk',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Muhammad Fajrul Falah',
                'jabatan' => 'Anggota Sub Divisi Keamanan Siber',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/falah.faj',
                    'github' => 'https://github.com/FalahFaj',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Rafi Hadianto Aribowo',
                'jabatan' => 'Kepala Divisi Multimedia',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/rafii.ill',
                    'linkedin' => 'https://www.linkedin.com/in/rafihadianto/',
                    'github' => 'https://github.com/Azaryn',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Vincent Alexander',
                'jabatan' => 'Anggota Divisi Multimedia',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/vincentalxndr7',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Elsa Alifatul Mu\'Azaroh',
                'jabatan' => 'Anggota Divisi Multimedia',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/somtingels_',
                    'linkedin' => 'https://linkedin.com/in/elsaamuazaroh',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Hafidlul Muffid Hidayat',
                'jabatan' => 'Anggota Divisi Multimedia',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/hafidlul._',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Cindy Aulia Andriani',
                'jabatan' => 'Anggota Divisi Multimedia',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/hambagur',
                    'linkedin' => 'https://www.linkedin.com/in/cindy-aulia-andriani',
                    'github' => 'https://github.com/seraphien',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Afif Muhammad Darmawan',
                'jabatan' => 'Anggota Divisi Multimedia',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/afifmagomed',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Ahmad Rayhaan Fauzi',
                'jabatan' => 'Anggota Divisi Multimedia',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/ryhnfaizuu_',
                ],
                'aktif' => true,
            ],
            [
                'nama' => 'Alfin Kamal Saputra',
                'jabatan' => 'Anggota Divisi Multimedia',
                'periode' => '2025/2026',
                'foto' => null,
                'sosmed' => [
                    'instagram' => 'https://instagram.com/alfinkamal007',
                ],
                'aktif' => true,
            ],
        ];

        foreach ($penguruses as $pengurus) {
            $pengurus['urutan'] = self::getUrutan($pengurus['jabatan']);
            Pengurus::create($pengurus);
        }
    }
}
