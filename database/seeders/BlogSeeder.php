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
        $web = Divisi::whereIn('slug', ['software-development', 'web-development'])->first();
        $uiux = Divisi::whereIn('slug', ['multimedia', 'multimedia-ui-ux-design'])->first();
        $bph = Divisi::where('slug', 'badan-pengurus-harian')->first();

        $defaultDivisi = Divisi::first();
        $defaultDivisiId = $web ? $web->id : ($defaultDivisi ? $defaultDivisi->id : 1);
        $authorId = $admin ? $admin->id : 1;

        $articles = [
            [
                'divisi_id' => $uiux ? $uiux->id : $defaultDivisiId,
                'author_id' => $authorId,
                'judul' => 'Tips Desain UI/UX untuk UKM',
                'slug' => 'tips-desain-ui-ux-untuk-ukm',
                'kategori' => 'Informasi',
                'konten' => '<h2>UI/UX untuk UMKM</h2>'
                    . '<p>Desain UI/UX yang baik sangat penting untuk kesuksesan sebuah UKM di dunia digital. Antarmuka pengguna yang intuitif dan pengalaman pengguna yang menyenangkan dapat meningkatkan kepuasan pelanggan dan memperkuat identitas merek. Berikut beberapa tips untuk membantu UKM dalam menciptakan desain UI/UX yang efektif:</p>'
                    . '<ol>'
                    . '<li><strong>Fokus pada Pengalaman Pengguna (UX)</strong><p>Pengalaman pengguna adalah hal utama dalam desain aplikasi atau situs web. Pastikan navigasi mudah dan alur interaksi tidak membingungkan. Setiap elemen desain harus memiliki tujuan yang jelas agar pengguna merasa nyaman.</p></li>'
                    . '<li><strong>Desain Responsif dan Mobile-Friendly</strong><p>Mengingat semakin banyak orang mengakses internet melalui ponsel, desain yang responsif sangat penting. Pastikan aplikasi atau website Anda tampil baik di berbagai perangkat, baik itu desktop maupun smartphone.</p></li>'
                    . '<li><strong>Gunakan Warna yang Sesuai dengan Brand</strong><p>Pilih palet warna yang mewakili identitas merek Anda. Warna yang konsisten akan memperkuat brand awareness dan memberikan kesan profesional. Hindari penggunaan terlalu banyak warna yang bisa membingungkan pengguna.</p></li>'
                    . '<li><strong>Simplicity is Key</strong><p>Desain yang sederhana dan bersih lebih disukai oleh pengguna. Hindari tampilan yang terlalu ramai atau berlebihan. Fokus pada elemen-elemen penting dan buat tata letak yang jelas dan mudah dipahami.</p></li>'
                    . '<li><strong>Prioritaskan Kecepatan dan Kinerja</strong><p>Pengguna tidak sabar menunggu halaman yang lama dimuat. Pastikan situs atau aplikasi Anda cepat dan responsif. Optimalkan gambar dan file untuk memastikan waktu loading yang singkat.</p></li>'
                    . '<li><strong>Gunakan Typography yang Mudah Dibaca</strong><p>Pemilihan font sangat mempengaruhi kenyamanan pengguna. Gunakan font yang jelas dan mudah dibaca di semua perangkat. Pastikan ukuran dan jarak antar huruf sesuai untuk meningkatkan keterbacaan.</p></li>'
                    . '<li><strong>Uji dan Dapatkan Umpan Balik</strong><p>Setelah desain selesai, lakukan uji coba dengan pengguna sesungguhnya untuk mendapatkan masukan. Feedback dari pengguna sangat penting untuk meningkatkan UX dan menyempurnakan desain Anda.</p></li>'
                    . '<li><strong>Konsistensi dalam Desain</strong><p>Pastikan elemen desain seperti tombol, ikon, dan navigasi konsisten di seluruh platform. Pengguna akan lebih mudah beradaptasi dengan desain yang memiliki pola dan elemen yang serupa di setiap halaman.</p></li>'
                    . '</ol>'
                    . '<img src="/assets/blog-default.jpg" alt="Wireframe Sketches UI/UX" />'
                    . '<h2>Kesimpulan</h2>'
                    . '<p>Desain UI/UX yang baik dapat membantu UKM menciptakan pengalaman yang menyenangkan bagi penggunanya. Fokus pada kemudahan akses, konsistensi, dan efisiensi adalah kunci untuk sukses dalam dunia digital. Dengan mengikuti tips ini, UKM Anda dapat membangun hubungan yang lebih baik dengan pelanggan dan memperkuat brand Anda di pasar yang kompetitif.</p>'
                    . '<div data-youtube-video=""><iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" allowfullscreen="true"></iframe></div>',
                'meta_description' => 'Panduan desain antarmuka pengguna untuk meningkatkan kepuasan pelanggan dan memperkuat identitas merek UKM.',
                'is_unggulan' => true,
                'views' => 520,
                'status' => 'published',
                'published_at' => Carbon::parse('2025-05-15 10:00:00'),
            ],
            [
                'divisi_id' => $web ? $web->id : $defaultDivisiId,
                'author_id' => $authorId,
                'judul' => 'Strategi Media Sosial untuk UKM',
                'slug' => 'strategi-media-sosial-untuk-ukm',
                'kategori' => 'Tips & Trik',
                'konten' => '<p>Cara sukses memanfaatkan media sosial untuk meningkatkan jangkauan pasar dan penjualan produk lokal secara optimal.</p>',
                'meta_description' => 'Cara sukses memanfaatkan media sosial untuk meningkatkan jangkauan pasar produk UKM.',
                'is_unggulan' => true,
                'views' => 340,
                'status' => 'published',
                'published_at' => Carbon::parse('2025-05-22 10:00:00'),
            ],
            [
                'divisi_id' => $bph ? $bph->id : $defaultDivisiId,
                'author_id' => $authorId,
                'judul' => 'Pentingnya Kolaborasi dalam UKM',
                'slug' => 'pentingnya-kolaborasi-dalam-ukm',
                'kategori' => 'Informasi',
                'konten' => '<p>Manfaat kolaborasi untuk perkembangan UKM di tengah pesatnya kompetisi industri teknologi modern.</p>',
                'meta_description' => 'Manfaat kolaborasi untuk perkembangan UKM di masa transformasi digital.',
                'is_unggulan' => false,
                'views' => 210,
                'status' => 'published',
                'published_at' => Carbon::parse('2025-05-30 10:00:00'),
            ],
            [
                'divisi_id' => $web ? $web->id : $defaultDivisiId,
                'author_id' => $authorId,
                'judul' => 'Cerita Sukses UKM LAOS',
                'slug' => 'cerita-sukses-ukm-laos',
                'kategori' => 'Cerita',
                'konten' => '<p>Kisah inspiratif anggota UKM LAOS dalam merintis karya software terbuka hingga digunakan ribuan pengguna.</p>',
                'meta_description' => 'Kisah inspiratif anggota UKM LAOS dalam mengembangkan perangkat lunak open source.',
                'is_unggulan' => false,
                'views' => 450,
                'status' => 'published',
                'published_at' => Carbon::parse('2025-06-05 10:00:00'),
            ],
            [
                'divisi_id' => $web ? $web->id : $defaultDivisiId,
                'author_id' => $authorId,
                'judul' => 'Keterampilan untuk Generasi Muda',
                'slug' => 'keterampilan-untuk-generasi-muda',
                'kategori' => 'Edukasi',
                'konten' => '<p>Pentingnya keterampilan digital di era sekarang untuk menunjang daya saing talenta muda di bursa kerja teknologi.</p>',
                'meta_description' => 'Pentingnya keterampilan digital di era sekarang untuk generasi penerus bangsa.',
                'is_unggulan' => false,
                'views' => 195,
                'status' => 'published',
                'published_at' => Carbon::parse('2025-06-12 10:00:00'),
            ],
            [
                'divisi_id' => $uiux ? $uiux->id : $defaultDivisiId,
                'author_id' => $authorId,
                'judul' => 'Pentingnya Brand Identity untuk UKM',
                'slug' => 'pentingnya-brand-identity-untuk-ukm',
                'kategori' => 'Tips & Trik',
                'konten' => '<p>Cara membangun identitas merek yang kuat dan konsisten agar produk UKM dapat memenangkan hati konsumen.</p>',
                'meta_description' => 'Cara membangun identitas merek yang kuat dan berkesan untuk pelaku usaha kreatif.',
                'is_unggulan' => false,
                'views' => 180,
                'status' => 'published',
                'published_at' => Carbon::parse('2025-06-18 10:00:00'),
            ],
            [
                'divisi_id' => $web ? $web->id : $defaultDivisiId,
                'author_id' => $authorId,
                'judul' => 'Menghadapi Tantangan Pemasaran',
                'slug' => 'menghadapi-tantangan-pemasaran',
                'kategori' => 'Informasi',
                'konten' => '<p>Solusi pemasaran untuk UKM di masa modern melalui optimalisasi kanal digital dan komunitas terbuka.</p>',
                'meta_description' => 'Solusi pemasaran untuk UKM di era digital berbasis kolaborasi dan efisiensi anggaran.',
                'is_unggulan' => false,
                'views' => 290,
                'status' => 'published',
                'published_at' => Carbon::parse('2025-05-15 10:00:00'),
            ],
            [
                'divisi_id' => $bph ? $bph->id : $defaultDivisiId,
                'author_id' => $authorId,
                'judul' => 'Pengelolaan Keuangan UKM yang Efektif',
                'slug' => 'pengelolaan-keuangan-ukm-yang-efektif',
                'kategori' => 'Tips & Trik',
                'konten' => '<p>Tips mengelola keuangan untuk UKM agar arus kas tetap sehat dan mampu mendukung ekspansi jangka panjang.</p>',
                'meta_description' => 'Tips mengelola keuangan untuk UKM agar operasional berjalan stabil dan berkelanjutan.',
                'is_unggulan' => false,
                'views' => 220,
                'status' => 'published',
                'published_at' => Carbon::parse('2025-07-03 10:00:00'),
            ],
            [
                'divisi_id' => $web ? $web->id : $defaultDivisiId,
                'author_id' => $authorId,
                'judul' => 'Inovasi Produk untuk UKM Global',
                'slug' => 'inovasi-produk-untuk-ukm-global',
                'kategori' => 'Inovasi',
                'konten' => '<p>Cara inovasi produk untuk bersaing di pasar global dengan mengadopsi standar internasional dan teknologi mutakhir.</p>',
                'meta_description' => 'Cara inovasi produk untuk bersaing di pasar mancanegara secara percaya diri.',
                'is_unggulan' => false,
                'views' => 310,
                'status' => 'published',
                'published_at' => Carbon::parse('2025-07-13 10:00:00'),
            ],
            [
                'divisi_id' => $web ? $web->id : $defaultDivisiId,
                'author_id' => $authorId,
                'judul' => 'Mengenal Ekosistem Open Source Modern di Lingkungan Kampus',
                'slug' => 'mengenal-ekosistem-open-source-modern-di-lingkungan-kampus',
                'kategori' => 'Informasi',
                'konten' => '<p>Open source bukan sekadar kode gratis, melainkan filosofi kolaborasi tanpa batas. Di UKM LAOS (Linux and Open Source), mahasiswa diajak untuk berkontribusi langsung pada proyek-proyek teknologi nyata.</p><p>Melalui keterbukaan kode, pengembang pemula dapat mempelajari arsitektur aplikasi skala enterprise dan membangun portofolio berstandar industri.</p>',
                'meta_description' => 'Panduan pengenalan ekosistem open-source modern bagi mahasiswa pengembang teknologi.',
                'is_unggulan' => true,
                'views' => 142,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(10),
            ],
            [
                'divisi_id' => $web ? $web->id : $defaultDivisiId,
                'author_id' => $authorId,
                'judul' => 'Panduan Memulai Inertia.js dengan Vue 3 dan Laravel 11',
                'slug' => 'panduan-memulai-inertia-js-dengan-vue-3-dan-laravel-11',
                'kategori' => 'Tutorial',
                'konten' => '<p>Inertia.js menjembatani kesenjangan antara Single Page Application (SPA) dan arsitektur server-driven monolith. Dengan Inertia, Anda tidak perlu lagi membangun REST API terpisah hanya untuk dashboard admin interaktif.</p><p>Tutorial ini membedah konfigurasi awal, setup routing Ziggy, hingga integrasi state management reaktif di Vue 3.</p>',
                'meta_description' => 'Tutorial komprehensif implementasi Inertia.js bersama Vue 3 dan Laravel 11 terkini.',
                'is_unggulan' => true,
                'views' => 285,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(5),
            ],
        ];

        foreach ($articles as $article) {
            Blog::updateOrCreate(
                ['slug' => $article['slug']],
                $article
            );
        }
    }
}
