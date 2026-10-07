<?php

use App\Models\Blog;
use App\Models\Divisi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

beforeEach(function () {
    config(['app.key' => 'base64:'.base64_encode(str_repeat('a', 32))]);
    $this->withoutVite();

    app(PermissionRegistrar::class)->forgetCachedPermissions();

    foreach (['view_news', 'create_news', 'edit_news', 'delete_news'] as $permission) {
        Permission::create(['name' => $permission, 'guard_name' => 'web']);
    }
});

function blogUser(array $permissions = ['view_news', 'create_news', 'edit_news', 'delete_news']): User
{
    $user = User::factory()->create();
    $user->givePermissionTo($permissions);
    return $user;
}

function blogPayload(array $overrides = []): array
{
    $divisi = Divisi::firstOrCreate(
        ['nama' => 'BPH'],
        ['deskripsi' => 'Badan Pengurus Harian', 'slug' => 'bph']
    );

    $payload = array_replace([
        'divisi_id' => $divisi->id,
        'judul' => 'Judul Berita Test ' . Str::random(5),
        'kategori' => 'Tutorial',
        'konten' => '<p>Test content</p>',
        'status' => 'published',
        'meta_description' => 'Meta desc test',
        'is_unggulan' => false,
    ], $overrides);

    if (!isset($payload['slug'])) {
        $payload['slug'] = Str::slug($payload['judul']);
    }

    return $payload;
}

// ==========================================
// AUTHORIZATION & ACCESS TESTS
// ==========================================

test('guest cannot access blogs index', function () {
    $this->get(route('blogs.index'))
        ->assertRedirect(route('login'));
});

test('user without view_news permission cannot access blogs index', function () {
    $user = User::factory()->create();
    
    $this->actingAs($user)
        ->get(route('blogs.index'))
        ->assertForbidden();
});

test('user with view_news permission can access blogs index', function () {
    $user = blogUser(['view_news']);
    
    $this->actingAs($user)
        ->get(route('blogs.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Blogs/Index')
            ->has('blogs')
            ->has('filters')
            ->has('options')
        );
});

test('user without create_news permission cannot access create page', function () {
    $user = blogUser(['view_news']);
    
    $this->actingAs($user)
        ->get(route('blogs.create'))
        ->assertForbidden();
});

test('user with create_news permission can access create page', function () {
    $user = blogUser(['view_news', 'create_news']);
    
    $this->actingAs($user)
        ->get(route('blogs.create'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Blogs/Form')
            ->has('divisis')
        );
});

// ==========================================
// CREATE TESTS
// ==========================================

test('user can create a blog post', function () {
    $user = blogUser(['view_news', 'create_news']);
    $payload = blogPayload(['judul' => 'Berita Baru']);

    $this->actingAs($user)
        ->post(route('blogs.store'), $payload)
        ->assertRedirect(route('blogs.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('blogs', [
        'judul' => 'Berita Baru',
        'slug' => 'berita-baru',
        'status' => 'published',
        'author_id' => $user->id,
    ]);
});

test('published blog automatically gets published_at timestamp', function () {
    $user = blogUser(['view_news', 'create_news']);
    $payload = blogPayload(['status' => 'published']);

    $this->actingAs($user)
        ->post(route('blogs.store'), $payload);

    $blog = Blog::first();
    expect($blog->published_at)->not->toBeNull();
});

test('draft blog does not get published_at timestamp', function () {
    $user = blogUser(['view_news', 'create_news']);
    $payload = blogPayload(['status' => 'draft']);

    $this->actingAs($user)
        ->post(route('blogs.store'), $payload);

    $blog = Blog::first();
    expect($blog->published_at)->toBeNull();
});

// ==========================================
// EDIT & UPDATE TESTS
// ==========================================

test('user without edit_news permission cannot access edit page', function () {
    $user = blogUser(['view_news']);
    $payload = blogPayload();
    $payload['author_id'] = $user->id;
    $blog = Blog::create($payload);
    
    $this->actingAs($user)
        ->get(route('blogs.edit', $blog))
        ->assertForbidden();
});

test('user with edit_news permission can access edit page', function () {
    $user = blogUser(['view_news', 'edit_news']);
    $payload = blogPayload();
    $payload['author_id'] = $user->id;
    $blog = Blog::create($payload);
    
    $this->actingAs($user)
        ->get(route('blogs.edit', $blog))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Blogs/Form')
            ->has('blog')
            ->where('blog.id', $blog->id)
        );
});

test('user can update a blog post', function () {
    $user = blogUser(['view_news', 'edit_news']);
    
    $initialPayload = blogPayload(['judul' => 'Judul Lama', 'status' => 'draft']);
    $initialPayload['author_id'] = $user->id;
    $blog = Blog::create($initialPayload);

    $updatePayload = array_merge($initialPayload, [
        'judul' => 'Judul Baru',
        'status' => 'published',
    ]);

    $this->actingAs($user)
        ->put(route('blogs.update', $blog), $updatePayload)
        ->assertRedirect(route('blogs.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('blogs', [
        'id' => $blog->id,
        'judul' => 'Judul Baru',
        'slug' => 'judul-baru',
        'status' => 'published',
    ]);
});

// ==========================================
// DELETE TESTS
// ==========================================

test('user without delete_news permission cannot delete blog', function () {
    $user = blogUser(['view_news']);
    $payload = blogPayload();
    $payload['author_id'] = $user->id;
    $blog = Blog::create($payload);

    $this->actingAs($user)
        ->delete(route('blogs.destroy', $blog))
        ->assertForbidden();

    $this->assertDatabaseHas('blogs', ['id' => $blog->id]);
});

test('user can delete a blog post', function () {
    $user = blogUser(['view_news', 'delete_news']);
    $payload = blogPayload();
    $payload['author_id'] = $user->id;
    $blog = Blog::create($payload);

    $this->actingAs($user)
        ->delete(route('blogs.destroy', $blog))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertSoftDeleted('blogs', ['id' => $blog->id]);
});
