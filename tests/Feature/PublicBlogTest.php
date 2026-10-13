<?php

use App\Models\Blog;
use App\Models\Divisi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

beforeEach(function () {
    config(['app.key' => 'base64:'.base64_encode(str_repeat('a', 32))]);
    $this->withoutVite();

    $this->divisi = Divisi::firstOrCreate(
        ['nama' => 'Web Development'],
        ['deskripsi' => 'Divisi Web', 'slug' => 'web-development']
    );

    $this->author = User::factory()->create(['name' => 'John Doe']);
});

function createPublishedBlog(array $overrides = []): Blog
{
    $divisi = Divisi::firstOrCreate(
        ['nama' => 'Web Development'],
        ['deskripsi' => 'Divisi Web', 'slug' => 'web-development']
    );

    $judul = $overrides['judul'] ?? ('Artikel Test '.Str::random(5));

    return Blog::create(array_replace([
        'divisi_id' => $divisi->id,
        'author_id' => 1,
        'judul' => $judul,
        'slug' => Str::slug($judul),
        'kategori' => 'Tutorial',
        'konten' => '<p>Konten artikel lengkap dengan tips dan trik menarik.</p>',
        'meta_description' => 'Ringkasan singkat artikel untuk pengujian.',
        'status' => 'published',
        'published_at' => now()->subDay(),
        'is_unggulan' => false,
        'views' => 10,
    ], $overrides));
}

function createDraftBlog(array $overrides = []): Blog
{
    $divisi = Divisi::firstOrCreate(
        ['nama' => 'Web Development'],
        ['deskripsi' => 'Divisi Web', 'slug' => 'web-development']
    );

    $judul = $overrides['judul'] ?? ('Draft Artikel Test '.Str::random(5));

    return Blog::create(array_replace([
        'divisi_id' => $divisi->id,
        'author_id' => 1,
        'judul' => $judul,
        'slug' => Str::slug($judul),
        'kategori' => 'Informasi',
        'konten' => '<p>Konten draf yang belum boleh dilihat publik.</p>',
        'meta_description' => 'Ringkasan draf.',
        'status' => 'draft',
        'published_at' => null,
        'is_unggulan' => false,
        'views' => 0,
    ], $overrides));
}

test('guest can access public blog index and sees published blogs', function () {
    $publishedBlog = createPublishedBlog(['judul' => 'Artikel Publik Terbit']);
    $draftBlog = createDraftBlog(['judul' => 'Artikel Masih Draf']);

    $response = $this->get(route('blog.index'));

    $response->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Blog/Index')
            ->has('blogs.data', 1)
            ->where('blogs.data.0.judul', 'Artikel Publik Terbit')
            ->has('kategoriOptions')
            ->has('heroBlogs')
            ->has('filters')
        );
});

test('public blog index does not show future scheduled blogs', function () {
    $futureBlog = createPublishedBlog([
        'judul' => 'Artikel Terbit Besok',
        'published_at' => now()->addDay(),
    ]);

    $response = $this->get(route('blog.index'));

    $response->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Blog/Index')
            ->has('blogs.data', 0)
        );
});

test('guest can filter public blogs by kategori', function () {
    createPublishedBlog(['judul' => 'Tutorial Vue 3', 'kategori' => 'Tutorial']);
    createPublishedBlog(['judul' => 'Berita Kegiatan Fasilkom', 'kategori' => 'Kegiatan']);

    $response = $this->get(route('blog.index', ['kategori' => 'Tutorial']));

    $response->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Blog/Index')
            ->has('blogs.data', 1)
            ->where('blogs.data.0.judul', 'Tutorial Vue 3')
            ->where('filters.kategori', 'Tutorial')
        );
});

test('guest can search public blogs by keyword', function () {
    createPublishedBlog(['judul' => 'Mengenal Linux Ubuntu', 'konten' => '<p>Panduan instalasi.</p>']);
    createPublishedBlog(['judul' => 'Dasar Pemrograman Python', 'konten' => '<p>Sintaks dasar.</p>']);

    $response = $this->get(route('blog.index', ['search' => 'Ubuntu']));

    $response->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Blog/Index')
            ->has('blogs.data', 1)
            ->where('blogs.data.0.judul', 'Mengenal Linux Ubuntu')
            ->where('filters.search', 'Ubuntu')
        );
});

test('guest can view published blog detail by slug', function () {
    $blog = createPublishedBlog([
        'judul' => 'Tips Desain UI/UX untuk UKM',
        'kategori' => 'Informasi',
        'konten' => '<h2>UI/UX untuk UMKM</h2><p>Penjelasan konten.</p>',
    ]);

    $response = $this->get(route('blog.show', $blog->slug));

    $response->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Blog/Show')
            ->has('blog')
            ->where('blog.judul', 'Tips Desain UI/UX untuk UKM')
            ->where('blog.kategori', 'Informasi')
            ->has('relatedBlogs')
        );
});

test('viewing public blog detail increments views count', function () {
    $blog = createPublishedBlog([
        'judul' => 'Artikel Menghitung Views',
        'views' => 10,
    ]);

    $this->get(route('blog.show', $blog->slug));

    expect($blog->fresh()->views)->toBe(11);
});

test('guest receives 404 when viewing draft blog detail', function () {
    $draft = createDraftBlog(['judul' => 'Artikel Rahasia Draf']);

    $response = $this->get(route('blog.show', $draft->slug));

    $response->assertNotFound();
});

test('guest receives 404 when viewing non-existent blog slug', function () {
    $response = $this->get(route('blog.show', 'slug-yang-tidak-ada'));

    $response->assertNotFound();
});

test('guest can view tiptap content with images and youtube embeds on blog detail', function () {
    $tiptapHtml = '<h2>Heading Artikel</h2><p>Paragraf teks.</p><img src="https://example.com/test.jpg" /><div data-youtube-video=""><iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ"></iframe></div>';

    $blog = createPublishedBlog([
        'judul' => 'Artikel dengan Media Lengkap',
        'konten' => $tiptapHtml,
    ]);

    $response = $this->get(route('blog.show', $blog->slug));

    $response->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Blog/Show')
            ->where('blog.konten', $tiptapHtml)
            ->where('blog.first_image', 'https://example.com/test.jpg')
            ->where('blog.thumbnail_url', 'https://example.com/test.jpg')
        );
});
