<?php

use App\Models\Pengurus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function () {
    config(['app.key' => 'base64:'.base64_encode(str_repeat('a', 32))]);
    $this->withoutVite();

    app(PermissionRegistrar::class)->forgetCachedPermissions();

    foreach (['view_committee', 'create_committee', 'edit_committee', 'delete_committee'] as $permission) {
        Permission::create(['name' => $permission, 'guard_name' => 'web']);
    }

    Storage::fake('public');
});

function committeeUser(array $permissions = ['view_committee', 'create_committee', 'edit_committee', 'delete_committee']): User
{
    $user = User::factory()->create();
    $user->givePermissionTo($permissions);

    return $user;
}

function pengurusPayload(array $overrides = []): array
{
    return array_replace([
        'nama' => 'Fadhil Rahman',
        'jabatan' => 'Ketua Umum',
        'periode' => '2025/2026',
        'urutan' => 1,
        'aktif' => true,
        'sosmed' => [
            'instagram' => 'https://instagram.com/fadhil',
            'linkedin' => 'https://linkedin.com/in/fadhil',
            'github' => 'https://github.com/fadhil',
        ],
    ], $overrides);
}

test('pengurus routes require authentication', function (string $method) {
    $pengurus = Pengurus::factory()->create();
    $url = in_array($method, ['put', 'delete']) ? "/pengurus/{$pengurus->id}" : '/pengurus';

    $this->{$method}($url, pengurusPayload())->assertRedirect(route('login'));

    $this->assertDatabaseCount('penguruses', 1);
})->with(['get', 'post', 'put', 'delete']);

test('every pengurus route requires view permission', function (string $method) {
    $pengurus = Pengurus::factory()->create();
    $user = committeeUser(['create_committee', 'edit_committee', 'delete_committee']);
    $url = in_array($method, ['put', 'delete']) ? "/pengurus/{$pengurus->id}" : '/pengurus';

    $this->actingAs($user)->{$method}($url, pengurusPayload())->assertForbidden();

    $this->assertDatabaseCount('penguruses', 1);
})->with(['get', 'post', 'put', 'delete']);

test('write operations require their own permission', function (string $method) {
    $pengurus = Pengurus::factory()->create();
    $url = $method === 'post' ? '/pengurus' : "/pengurus/{$pengurus->id}";

    $this->actingAs(committeeUser(['view_committee']))
        ->{$method}($url, pengurusPayload())
        ->assertForbidden();

    $this->assertDatabaseCount('penguruses', 1);
})->with(['post', 'put', 'delete']);

test('index returns paginated penguruses and options', function () {
    $user = committeeUser(['view_committee']);
    Pengurus::factory()->count(11)->create(['periode' => '2025/2026']);

    $this->actingAs($user)->get('/pengurus')->assertOk()->assertInertia(fn (Assert $page) => $page
        ->component('Pengurus/Index')
        ->has('penguruses.data', 10)
        ->where('penguruses.total', 11)
        ->has('options.periode')
        ->has('options.jabatan')
        ->where('filters.search', '')
    );
});

test('index searches nama and jabatan', function () {
    Pengurus::factory()->create(['nama' => 'Budi Santoso', 'jabatan' => 'Sekretaris']);
    Pengurus::factory()->create(['nama' => 'Dewi Lestari', 'jabatan' => 'Ketua Umum']);
    Pengurus::factory()->create(['nama' => 'Andi Wijaya', 'jabatan' => 'Bendahara']);

    $user = committeeUser(['view_committee']);

    $this->actingAs($user)->get('/pengurus?search=Budi')
        ->assertOk()->assertInertia(fn (Assert $page) => $page
        ->where('penguruses.total', 1)
        ->where('penguruses.data.0.nama', 'Budi Santoso')
        );

    $this->actingAs($user)->get('/pengurus?search=Ketua')
        ->assertOk()->assertInertia(fn (Assert $page) => $page
        ->where('penguruses.total', 1)
        ->where('penguruses.data.0.nama', 'Dewi Lestari')
        );
});

test('index filters by periode, jabatan, and status', function () {
    Pengurus::factory()->create(['periode' => '2024/2025', 'jabatan' => 'BPH', 'aktif' => true]);
    Pengurus::factory()->create(['periode' => '2025/2026', 'jabatan' => 'BPH', 'aktif' => false]);
    Pengurus::factory()->create(['periode' => '2025/2026', 'jabatan' => 'Divisi Web', 'aktif' => true]);
    Pengurus::factory()->create(['periode' => '2025/2026', 'jabatan' => 'Ketua Umum', 'urutan' => 1, 'aktif' => true]);
    Pengurus::factory()->create(['periode' => '2025/2026', 'jabatan' => 'Wakil Ketua Umum', 'urutan' => 2, 'aktif' => true]);

    $user = committeeUser(['view_committee']);

    $this->actingAs($user)->get('/pengurus?periode=2025/2026&status=aktif')
        ->assertOk()->assertInertia(fn (Assert $page) => $page
        ->where('penguruses.total', 3)
        );

    $this->actingAs($user)->get('/pengurus?jabatan=Ketua')
        ->assertOk()->assertInertia(fn (Assert $page) => $page
        ->where('penguruses.total', 2)
        ->where('options.jabatan.0', 'Ketua')
        );
});

test('index redirects overflow page to last valid page', function () {
    Pengurus::factory()->count(5)->create();
    $user = committeeUser(['view_committee']);

    $this->actingAs($user)->get('/pengurus?page=99')
        ->assertRedirect(route('pengurus.index', ['page' => 1]));
});

test('creating pengurus persists data and stores photo in public disk', function () {
    $user = committeeUser();
    $file = UploadedFile::fake()->image('avatar.jpg', 300, 300);

    $response = $this->actingAs($user)->post('/pengurus', pengurusPayload([
        'foto' => $file,
    ]));

    $response->assertRedirect();
    $this->assertDatabaseCount('penguruses', 1);

    $pengurus = Pengurus::first();
    expect($pengurus->nama)->toBe('Fadhil Rahman')
        ->and($pengurus->foto)->not->toBeNull()
        ->and($pengurus->sosmed['instagram'])->toBe('https://instagram.com/fadhil');

    Storage::disk('public')->assertExists($pengurus->foto);
});

test('updating pengurus replaces photo and removes old file from disk', function () {
    $user = committeeUser();
    $oldFile = UploadedFile::fake()->image('old.jpg');
    $path = $oldFile->store('pengurus', 'public');

    $pengurus = Pengurus::factory()->create(['foto' => $path]);

    $newFile = UploadedFile::fake()->image('new.png');
    $response = $this->actingAs($user)->put("/pengurus/{$pengurus->id}", pengurusPayload([
        'nama' => 'Nama Baru',
        'foto' => $newFile,
    ]));

    $response->assertRedirect();

    $pengurus->refresh();
    expect($pengurus->nama)->toBe('Nama Baru')
        ->and($pengurus->foto)->not->toBe($path);

    Storage::disk('public')->assertMissing($path);
    Storage::disk('public')->assertExists($pengurus->foto);
});

test('updating pengurus with hapus_foto deletes photo from storage', function () {
    $user = committeeUser();
    $file = UploadedFile::fake()->image('profile.jpg');
    $path = $file->store('pengurus', 'public');

    $pengurus = Pengurus::factory()->create(['foto' => $path]);

    $response = $this->actingAs($user)->put("/pengurus/{$pengurus->id}", pengurusPayload([
        'hapus_foto' => true,
    ]));

    $response->assertRedirect();
    $pengurus->refresh();

    expect($pengurus->foto)->toBeNull();
    Storage::disk('public')->assertMissing($path);
});

test('deleting pengurus performs soft delete and keeps photo in storage', function () {
    $user = committeeUser();
    $file = UploadedFile::fake()->image('profile.jpg');
    $path = $file->store('pengurus', 'public');

    $pengurus = Pengurus::factory()->create(['foto' => $path]);

    $response = $this->actingAs($user)->delete("/pengurus/{$pengurus->id}");
    $response->assertRedirect();

    $this->assertSoftDeleted('penguruses', ['id' => $pengurus->id]);
    Storage::disk('public')->assertExists($path);
});

test('periode validation requires consecutive years', function (string $invalidPeriode) {
    $user = committeeUser();

    $response = $this->actingAs($user)->post('/pengurus', pengurusPayload([
        'periode' => $invalidPeriode,
    ]));

    $response->assertSessionHasErrors('periode');
})->with([
    '2025-2025',
    '2026/2026',
    '2025/2027',
    '2025/2024',
    'abcd/efgh',
]);

test('periode shorthand format is normalized to standard YYYY/YYYY format', function (string $input, string $expected) {
    $user = committeeUser();

    $response = $this->actingAs($user)->post('/pengurus', pengurusPayload([
        'periode' => $input,
    ]));

    $response->assertRedirect();
    $this->assertDatabaseHas('penguruses', [
        'nama' => 'Fadhil Rahman',
        'periode' => $expected,
    ]);
})->with([
    ['25-26', '2025/2026'],
    ['25/26', '2025/2026'],
    ['2025-2026', '2025/2026'],
]);

test('sosmed empty values are filtered to null', function () {
    $user = committeeUser();

    $this->actingAs($user)->post('/pengurus', pengurusPayload([
        'sosmed' => [
            'instagram' => '',
            'linkedin' => '',
            'github' => '',
        ],
    ]))->assertRedirect();

    $pengurus = Pengurus::first();
    expect($pengurus->sosmed)->toBeNull();
});
