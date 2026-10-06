<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Divisi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::first();
        $web = Divisi::where('slug', 'web-development')->first();
        $uiux = Divisi::where('slug', 'multimedia-ui-ux-design')->first();
        $bph = Divisi::where('slug', 'badan-pengurus-harian')->first();

        $defaultDivisiId = $web?->id ?? Divisi::first()?->id ?? 1;
        $authorId = $admin?->id ?? 1;

        $articles = [
            [
                'divisi_id' => $web?->id ?? $defaultDivisiId,
                'author_id' => $authorId,
                'judul' => 'Mengenal Ekosistem Open Source Modern di Lingkungan Kampus',
                'slug' => 'mengenal-ekosistem-open-source-modern-di-lingkungan-kampus',
                'kategori' => 'informasi',
                'konten' => '<p>Open source bukan sekadar kode gratis, melainkan filosofi kolaborasi tanpa batas. Di UKM LAOS (Linux and Open Source), mahasiswa diajak untuk berkontribusi langsung pada proyek-proyek teknologi nyata.</p><p>Melalui keterbukaan kode, pengembang pemula dapat mempelajari arsitektur aplikasi skala enterprise dan membangun portofolio berstandar industri.</p>',
                'meta_description' => 'Panduan pengenalan ekosistem open-source modern bagi mahasiswa pengembang teknologi.',
                'is_unggulan' => true,
                'views' => 142,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(10),
            ],
            [
                'divisi_id' => $web?->id ?? $defaultDivisiId,
                'author_id' => $authorId,
                'judul' => 'Panduan Memulai Inertia.js dengan Vue 3 dan Laravel 11',
                'slug' => 'panduan-memulai-inertia-js-dengan-vue-3-dan-laravel-11',
                'kategori' => 'tutorial',
                'konten' => '<p>Inertia.js menjembatani kesenjangan antara Single Page Application (SPA) dan arsitektur server-driven monolith. Dengan Inertia, Anda tidak perlu lagi membangun REST API terpisah hanya untuk dashboard admin interaktif.</p><p>Tutorial ini membedah konfigurasi awal, setup routing Ziggy, hingga integrasi state management reaktif di Vue 3.</p>',
                'meta_description' => 'Tutorial komprehensif implementasi Inertia.js bersama Vue 3 dan Laravel 11 terkini.',
                'is_unggulan' => true,
                'views' => 285,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(5),
            ],
            [
                'divisi_id' => $uiux?->id ?? $defaultDivisiId,
                'author_id' => $authorId,
                'judul' => 'Prinsip Desain Antarmuka Glassmorphism & Aksesibilitas Web',
                'slug' => 'prinsip-desain-antarmuka-glassmorphism-aksesibilitas-web',
                'kategori' => 'tips-trik',
                'konten' => '<p>Tren desain modern seperti Glassmorphism memberikan estetika visual yang futuristik dan memikat. Namun, kontras warna dan keterbacaan tipografi tetap harus menjadi prioritas utama demi standar aksesibilitas WCAG.</p><p>Pelajari trik penggunaan backdrop-filter dan layer opacity yang ramah mata.</p>',
                'meta_description' => 'Tips memadukan estetika glassmorphism modern dengan standar kontras aksesibilitas.',
                'is_unggulan' => false,
                'views' => 96,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(2),
            ],
            [
                'divisi_id' => $bph?->id ?? $defaultDivisiId,
                'author_id' => $authorId,
                'judul' => 'Rilis Resmi Kepengurusan UKM LAOS Periode 2025/2026',
                'slug' => 'rilis-resmi-kepengurusan-ukm-laos-periode-2025-2026',
                'kategori' => 'press-release',
                'konten' => '<p>UKM LAOS dengan bangga mengumumkan susunan fungsionaris baru untuk periode 2025/2026 yang mengusung semangat akselerasi riset open source dan kolaborasi lintas komunitas.</p><p>Mari songsong inovasi baru bersama seluruh anggota keluarga besar UKM LAOS!</p>',
                'meta_description' => 'Siaran pers pengumuman formasi kepengurusan baru UKM LAOS periode 2025/2026.',
                'is_unggulan' => false,
                'views' => 310,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(1),
            ],
        ];

        foreach ($articles as $article) {
            Blog::firstOrCreate(
                ['slug' => $article['slug']],
                $article
            );
        }
    }
}

