<?php

use App\Models\Divisi;
use App\Models\Program;
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

    foreach (['view_work_programs', 'create_work_programs', 'edit_work_programs', 'delete_work_programs'] as $permission) {
        Permission::create(['name' => $permission, 'guard_name' => 'web']);
    }

    Storage::fake('public');
});

function programUser(array $permissions = ['view_work_programs', 'create_work_programs', 'edit_work_programs', 'delete_work_programs']): User
{
    $user = User::factory()->create();
    $user->givePermissionTo($permissions);

    return $user;
}

function programPayload(Divisi $divisi, array $overrides = []): array
{
    return array_replace([
        'divisi_id' => $divisi->id,
        'judul_program' => 'LAOS Web & Cloud Bootcamp',
        'slug' => 'laos-web-cloud-bootcamp',
        'location_name' => 'Gedung Fasilkom UNEJ',
        'open_regis_panitia' => '2026-05-01',
        'close_regis_panitia' => '2026-05-10',
        'gform_panitia' => 'https://forms.gle/sample-panitia',
        'open_regis_peserta' => '2026-05-15',
        'close_regis_peserta' => '2026-05-30',
        'gform_peserta' => 'https://forms.gle/sample-peserta',
    ], $overrides);
}

test('program routes require authentication', function (string $method) {
    $divisi = Divisi::factory()->create();
    $program = Program::factory()->create(['divisi_id' => $divisi->id]);
    $url = in_array($method, ['put', 'delete']) ? "/programs/{$program->id}" : '/programs';

    $this->{$method}($url, programPayload($divisi))->assertRedirect(route('login'));
})->with(['get', 'post', 'put', 'delete']);

test('program routes require view_work_programs permission', function (string $method) {
    $userWithoutPermission = User::factory()->create();
    $divisi = Divisi::factory()->create();
    $program = Program::factory()->create(['divisi_id' => $divisi->id]);
    $url = in_array($method, ['put', 'delete']) ? "/programs/{$program->id}" : '/programs';

    $this->actingAs($userWithoutPermission)
        ->{$method}($url, programPayload($divisi))
        ->assertForbidden();
})->with(['get', 'post', 'put', 'delete']);

test('authorized user can view programs index with inertia', function () {
    $user = programUser();
    $divisi = Divisi::factory()->create(['nama' => 'Web Development']);
    Program::factory()->count(3)->create(['divisi_id' => $divisi->id]);

    $this->actingAs($user)
        ->get('/programs')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Programs/Index')
            ->has('programs.data', 3)
            ->has('divisis')
            ->has('stats')
            ->has('filters')
        );
});

test('authorized user can create a new program with photo upload to storage', function () {
    $user = programUser();
    $divisi = Divisi::factory()->create();
    $fakePhoto = UploadedFile::fake()->image('poster.jpg', 600, 800);

    $payload = programPayload($divisi, [
        'foto' => $fakePhoto,
    ]);

    $response = $this->actingAs($user)
        ->post('/programs', $payload);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('programs', [
        'divisi_id' => $divisi->id,
        'judul_program' => 'LAOS Web & Cloud Bootcamp',
        'slug' => 'laos-web-cloud-bootcamp',
        'location_name' => 'Gedung Fasilkom UNEJ',
    ]);

    $createdProgram = Program::where('slug', 'laos-web-cloud-bootcamp')->firstOrFail();
    expect($createdProgram->foto)->not->toBeNull();
    Storage::disk('public')->assertExists($createdProgram->foto);
});

test('program creation requires valid dates and unique title', function () {
    $user = programUser();
    $divisi = Divisi::factory()->create();

    // Missing required fields
    $this->actingAs($user)
        ->post('/programs', [])
        ->assertSessionHasErrors(['divisi_id', 'judul_program', 'open_regis_peserta', 'close_regis_peserta']);

    // Duplicate title
    Program::factory()->create([
        'divisi_id' => $divisi->id,
        'judul_program' => 'Existing Title',
        'slug' => 'existing-title',
    ]);

    $this->actingAs($user)
        ->post('/programs', programPayload($divisi, ['judul_program' => 'Existing Title']))
        ->assertSessionHasErrors(['judul_program']);
});

test('authorized user can update an existing program and replace photo', function () {
    $user = programUser();
    $divisi = Divisi::factory()->create();

    $oldPhoto = UploadedFile::fake()->image('old.jpg');
    $oldPath = $oldPhoto->store('programs', 'public');

    $program = Program::factory()->create([
        'divisi_id' => $divisi->id,
        'judul_program' => 'Old Program Title',
        'foto' => $oldPath,
    ]);

    Storage::disk('public')->assertExists($oldPath);

    $newPhoto = UploadedFile::fake()->image('new.png');
    $payload = programPayload($divisi, [
        'judul_program' => 'Updated Program Title',
        'foto' => $newPhoto,
    ]);

    $response = $this->actingAs($user)
        ->put("/programs/{$program->id}", $payload);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $program->refresh();
    expect($program->judul_program)->toBe('Updated Program Title');
    expect($program->foto)->not->toBe($oldPath);

    // Old photo removed from storage, new photo exists
    Storage::disk('public')->assertMissing($oldPath);
    Storage::disk('public')->assertExists($program->foto);
});

test('authorized user can remove photo from program', function () {
    $user = programUser();
    $divisi = Divisi::factory()->create();

    $photo = UploadedFile::fake()->image('poster.jpg');
    $path = $photo->store('programs', 'public');

    $program = Program::factory()->create([
        'divisi_id' => $divisi->id,
        'foto' => $path,
    ]);

    $payload = programPayload($divisi, [
        'hapus_foto' => true,
    ]);

    $this->actingAs($user)
        ->put("/programs/{$program->id}", $payload)
        ->assertRedirect();

    $program->refresh();
    expect($program->foto)->toBeNull();
    Storage::disk('public')->assertMissing($path);
});

test('authorized user can soft delete a program', function () {
    $user = programUser();
    $divisi = Divisi::factory()->create();
    $program = Program::factory()->create(['divisi_id' => $divisi->id]);

    $this->actingAs($user)
        ->delete("/programs/{$program->id}")
        ->assertRedirect();

    $this->assertSoftDeleted('programs', ['id' => $program->id]);
});

test('user without delete permission cannot delete a program', function () {
    $user = programUser(['view_work_programs', 'create_work_programs', 'edit_work_programs']);
    $divisi = Divisi::factory()->create();
    $program = Program::factory()->create(['divisi_id' => $divisi->id]);

    $this->actingAs($user)
        ->delete("/programs/{$program->id}")
        ->assertForbidden();

    $this->assertNotSoftDeleted('programs', ['id' => $program->id]);
});

test('programs can be filtered by time status: mendatang, berjalan, and selesai', function () {
    $user = programUser();
    $divisi = Divisi::factory()->create();

    // Past / Selesai (close_regis_peserta in the past)
    Program::factory()->create([
        'divisi_id' => $divisi->id,
        'judul_program' => 'Program Selesai',
        'open_regis_peserta' => now()->subDays(10)->format('Y-m-d'),
        'close_regis_peserta' => now()->subDays(2)->format('Y-m-d'),
    ]);

    // Active / Berjalan (currently within registration dates)
    Program::factory()->create([
        'divisi_id' => $divisi->id,
        'judul_program' => 'Program Berjalan',
        'open_regis_peserta' => now()->subDays(2)->format('Y-m-d'),
        'close_regis_peserta' => now()->addDays(5)->format('Y-m-d'),
    ]);

    // Future / Mendatang (open_regis_peserta in the future)
    Program::factory()->create([
        'divisi_id' => $divisi->id,
        'judul_program' => 'Program Mendatang',
        'open_regis_peserta' => now()->addDays(5)->format('Y-m-d'),
        'close_regis_peserta' => now()->addDays(15)->format('Y-m-d'),
    ]);

    $this->actingAs($user)
        ->get('/programs?status=mendatang')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->has('programs.data', 1)
            ->where('programs.data.0.judul_program', 'Program Mendatang')
        );

    $this->actingAs($user)
        ->get('/programs?status=berjalan')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->has('programs.data', 1)
            ->where('programs.data.0.judul_program', 'Program Berjalan')
        );

    $this->actingAs($user)
        ->get('/programs?status=selesai')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->has('programs.data', 1)
            ->where('programs.data.0.judul_program', 'Program Selesai')
        );
});

