<?php

use App\Models\Shortlink;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function () {
    config(['app.key' => 'base64:'.base64_encode(str_repeat('a', 32))]);
    $this->withoutVite();

    app(PermissionRegistrar::class)->forgetCachedPermissions();

    foreach (['view_shortlinks', 'create_shortlinks', 'edit_shortlinks', 'delete_shortlinks'] as $permission) {
        Permission::create(['name' => $permission, 'guard_name' => 'web']);
    }
});

function shortlinkUser(array $permissions = ['view_shortlinks', 'create_shortlinks', 'edit_shortlinks', 'delete_shortlinks']): User
{
    $user = User::factory()->create();
    $user->givePermissionTo($permissions);

    return $user;
}

function shortlinkPayload(array $overrides = []): array
{
    return array_replace([
        'short_code' => 'company-profile',
        'destination_url' => 'https://example.com/company',
        'is_active' => true,
        'expires_at' => null,
    ], $overrides);
}

test('shortlink routes require authentication', function (string $method) {
    $shortlink = Shortlink::factory()->create();
    $url = in_array($method, ['put', 'delete']) ? "/shortlinks/{$shortlink->id}" : '/shortlinks';

    $this->{$method}($url, shortlinkPayload())->assertRedirect(route('login'));

    $this->assertDatabaseCount('shortlinks', 1);
})->with(['get', 'post', 'put', 'delete']);

test('every shortlink route requires view permission', function (string $method) {
    $shortlink = Shortlink::factory()->create();
    $user = shortlinkUser(['create_shortlinks', 'edit_shortlinks', 'delete_shortlinks']);
    $url = in_array($method, ['put', 'delete']) ? "/shortlinks/{$shortlink->id}" : '/shortlinks';

    $this->actingAs($user)->{$method}($url, shortlinkPayload())->assertForbidden();

    $this->assertDatabaseCount('shortlinks', 1);
})->with(['get', 'post', 'put', 'delete']);

test('write operations require their own permission', function (string $method) {
    $shortlink = Shortlink::factory()->create();
    $url = $method === 'post' ? '/shortlinks' : "/shortlinks/{$shortlink->id}";

    $this->actingAs(shortlinkUser(['view_shortlinks']))
        ->{$method}($url, shortlinkPayload())
        ->assertForbidden();

    $this->assertDatabaseCount('shortlinks', 1);
    expect($shortlink->fresh()->short_code)->toBe($shortlink->short_code);
})->with(['post', 'put', 'delete']);

test('index returns paginated shortlinks and safe author details', function () {
    $user = shortlinkUser(['view_shortlinks']);
    Shortlink::factory()->count(11)->create(['user_id' => $user->id]);

    $this->actingAs($user)->get('/shortlinks')->assertOk()->assertInertia(fn (Assert $page) => $page
        ->component('Shortlinks/Index')
        ->has('shortlinks.data', 10)
        ->where('shortlinks.total', 11)
        ->where('shortlinks.data.0.user.id', $user->id)
        ->where('shortlinks.data.0.user.name', $user->name)
        ->missing('shortlinks.data.0.user.email')
        ->where('filters.search', '')
    );
});

test('index searches both code and destination and preserves pagination filters', function () {
    Shortlink::factory()->count(11)->create(['destination_url' => 'https://example.com/matching']);
    Shortlink::factory()->create(['short_code' => 'matching-code', 'destination_url' => 'https://example.com/other']);
    Shortlink::factory()->create(['short_code' => 'excluded', 'destination_url' => 'https://example.com/other']);

    $this->actingAs(shortlinkUser(['view_shortlinks']))
        ->get('/shortlinks?search=matching')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('filters.search', 'matching')
            ->where('shortlinks.total', 12)
            ->where('shortlinks.next_page_url', fn ($url) => str_contains($url, 'search=matching'))
        );
});

test('index treats zero as a search term and supports deleted authors', function () {
    $author = User::factory()->create();
    $shortlink = Shortlink::factory()->create(['short_code' => '0', 'user_id' => $author->id]);
    Shortlink::factory()->create(['short_code' => 'other', 'destination_url' => 'https://example.com/other']);
    $author->delete();

    $this->actingAs(shortlinkUser(['view_shortlinks']))->get('/shortlinks?search=0')
        ->assertOk()->assertInertia(fn (Assert $page) => $page
        ->where('shortlinks.total', 1)
        ->where('shortlinks.data.0.id', $shortlink->id)
        ->where('shortlinks.data.0.user', null)
        ->where('filters.search', '0')
        );
});

test('creating a shortlink normalizes its code and ignores protected fields', function () {
    $user = shortlinkUser(['view_shortlinks', 'create_shortlinks']);
    $otherUser = User::factory()->create();

    $this->actingAs($user)->from('/shortlinks')->post('/shortlinks', shortlinkPayload([
        'short_code' => 'Company_Profile-2026',
        'user_id' => $otherUser->id,
        'click_count' => 999,
        'is_active' => false,
        'expires_at' => '2025-01-02 03:04:05',
    ]))->assertSessionHasNoErrors()->assertRedirect('/shortlinks');

    $shortlink = Shortlink::sole();
    expect($shortlink->short_code)->toBe('company_profile-2026')
        ->and($shortlink->user_id)->toBe($user->id)
        ->and($shortlink->click_count)->toBe(0)
        ->and($shortlink->is_active)->toBeFalse()
        ->and($shortlink->expires_at->format('Y-m-d H:i:s'))->toBe('2025-01-02 03:04:05');
});

test('creating a shortlink rejects invalid input', function (array $overrides, string $field) {
    $this->actingAs(shortlinkUser(['view_shortlinks', 'create_shortlinks']))
        ->post('/shortlinks', shortlinkPayload($overrides))
        ->assertSessionHasErrors($field);

    $this->assertDatabaseCount('shortlinks', 0);
})->with([
    'missing code' => [['short_code' => null], 'short_code'],
    'long code' => [['short_code' => str_repeat('a', 51)], 'short_code'],
    'code with a slash' => [['short_code' => 'invalid/code'], 'short_code'],
    'unicode code' => [['short_code' => 'café'], 'short_code'],
    'missing destination' => [['destination_url' => null], 'destination_url'],
    'relative destination' => [['destination_url' => '/company'], 'destination_url'],
    'javascript destination' => [['destination_url' => 'javascript:alert(1)'], 'destination_url'],
    'data destination' => [['destination_url' => 'data:text/html,test'], 'destination_url'],
    'ftp destination' => [['destination_url' => 'ftp://example.com/file'], 'destination_url'],
    'long destination' => [['destination_url' => 'https://example.com/'.str_repeat('a', 2048)], 'destination_url'],
    'missing status' => [['is_active' => null], 'is_active'],
    'invalid status' => [['is_active' => 'active'], 'is_active'],
    'invalid expiration' => [['expires_at' => 'not-a-date'], 'expires_at'],
]);

test('a duplicate code is rejected after lowercase normalization', function () {
    Shortlink::factory()->create(['short_code' => 'existing']);

    $this->actingAs(shortlinkUser())->post('/shortlinks', shortlinkPayload(['short_code' => 'EXISTING']))
        ->assertSessionHasErrors('short_code');

    $this->assertDatabaseCount('shortlinks', 1);
});

test('updating a shortlink preserves creator and click count', function () {
    $shortlink = Shortlink::factory()->create(['click_count' => 42, 'expires_at' => now()->subDay()]);
    $creatorId = $shortlink->user_id;
    $user = shortlinkUser(['view_shortlinks', 'edit_shortlinks']);

    $this->actingAs($user)->from('/shortlinks')->put("/shortlinks/{$shortlink->id}", shortlinkPayload([
        'short_code' => 'UPDATED',
        'destination_url' => 'http://example.com/new',
        'user_id' => $user->id,
        'click_count' => 0,
        'is_active' => false,
    ]))->assertSessionHasNoErrors()->assertRedirect('/shortlinks');

    expect($shortlink->refresh()->short_code)->toBe('updated')
        ->and($shortlink->destination_url)->toBe('http://example.com/new')
        ->and($shortlink->user_id)->toBe($creatorId)
        ->and($shortlink->click_count)->toBe(42)
        ->and($shortlink->is_active)->toBeFalse()
        ->and($shortlink->expires_at)->toBeNull();
});

test('updating allows its current code but rejects another existing code', function () {
    $shortlink = Shortlink::factory()->create(['short_code' => 'current']);
    Shortlink::factory()->create(['short_code' => 'other']);
    $this->actingAs(shortlinkUser(['view_shortlinks', 'edit_shortlinks']));

    $this->put("/shortlinks/{$shortlink->id}", shortlinkPayload(['short_code' => 'CURRENT']))
        ->assertSessionHasNoErrors();

    $this->put("/shortlinks/{$shortlink->id}", shortlinkPayload(['short_code' => 'OTHER']))
        ->assertSessionHasErrors('short_code');

    expect($shortlink->fresh()->short_code)->toBe('current');
});

test('updates require a valid full form and do not modify invalid records', function () {
    $shortlink = Shortlink::factory()->create();
    $this->actingAs(shortlinkUser(['view_shortlinks', 'edit_shortlinks']));

    $this->put("/shortlinks/{$shortlink->id}", [])
        ->assertSessionHasErrors(['short_code', 'destination_url', 'is_active']);

    $this->put("/shortlinks/{$shortlink->id}", shortlinkPayload(['destination_url' => 'javascript:alert(1)']))
        ->assertSessionHasErrors('destination_url');

    expect($shortlink->fresh()->destination_url)->toBe($shortlink->destination_url);
});

test('an expired shortlink can still be edited', function () {
    $shortlink = Shortlink::factory()->create(['expires_at' => '2025-01-02 03:04:05']);

    $this->actingAs(shortlinkUser(['view_shortlinks', 'edit_shortlinks']))
        ->put("/shortlinks/{$shortlink->id}", shortlinkPayload(['expires_at' => '2025-01-02 03:04:05']))
        ->assertSessionHasNoErrors();

    expect($shortlink->refresh()->expires_at->format('Y-m-d H:i:s'))->toBe('2025-01-02 03:04:05');
});

test('a shortlink accepts code and destination at their maximum lengths', function () {
    $payload = shortlinkPayload([
        'short_code' => str_repeat('a', 50),
        'destination_url' => 'https://example.com/'.str_repeat('a', 2028),
    ]);

    $this->actingAs(shortlinkUser(['view_shortlinks', 'create_shortlinks']))
        ->post('/shortlinks', $payload)->assertSessionHasNoErrors();

    $this->assertDatabaseHas('shortlinks', [
        'short_code' => $payload['short_code'],
        'destination_url' => $payload['destination_url'],
    ]);
});

test('deleting a shortlink removes it permanently', function () {
    $shortlink = Shortlink::factory()->create();

    $this->actingAs(shortlinkUser(['view_shortlinks', 'delete_shortlinks']))
        ->from('/shortlinks')->delete("/shortlinks/{$shortlink->id}")
        ->assertRedirect('/shortlinks');

    $this->assertDatabaseMissing('shortlinks', ['id' => $shortlink->id]);
});

test('expiration offsets preserve the instant when creating and updating', function () {
    $this->actingAs(shortlinkUser())->post('/shortlinks', shortlinkPayload([
        'expires_at' => '2026-10-06T17:00:00+07:00',
    ]))->assertSessionHasNoErrors();

    $shortlink = Shortlink::sole();
    expect($shortlink->expires_at->utc()->format('Y-m-d H:i:s'))->toBe('2026-10-06 10:00:00');

    $this->put("/shortlinks/{$shortlink->id}", shortlinkPayload([
        'expires_at' => '2026-10-07T17:00:00+07:00',
    ]))->assertSessionHasNoErrors();

    expect($shortlink->refresh()->expires_at->utc()->format('Y-m-d H:i:s'))->toBe('2026-10-07 10:00:00');
});

test('writes to a nonexistent shortlink return not found', function (string $method) {
    $this->actingAs(shortlinkUser())->{$method}('/shortlinks/999999', shortlinkPayload())
        ->assertNotFound();
})->with(['put', 'delete']);

test('a code collision during creation returns a validation error', function () {
    Shortlink::creating(function (Shortlink $shortlink) {
        DB::table('shortlinks')->insert([
            'short_code' => $shortlink->short_code,
            'user_id' => null,
            'destination_url' => 'https://example.com/competitor',
            'is_active' => true,
            'click_count' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    });

    try {
        $this->actingAs(shortlinkUser())->from('/shortlinks')
            ->post('/shortlinks', shortlinkPayload())
            ->assertSessionHasErrors('short_code');

        $this->assertDatabaseCount('shortlinks', 1);
        $this->assertDatabaseHas('shortlinks', ['destination_url' => 'https://example.com/competitor']);
    } finally {
        Shortlink::flushEventListeners();
    }
});

test('a code collision during update preserves the original record', function () {
    $shortlink = Shortlink::factory()->create(['short_code' => 'original']);
    Shortlink::updating(function (Shortlink $shortlink) {
        DB::table('shortlinks')->insert([
            'short_code' => $shortlink->short_code,
            'user_id' => null,
            'destination_url' => 'https://example.com/competitor',
            'is_active' => true,
            'click_count' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    });

    try {
        $this->actingAs(shortlinkUser())->from('/shortlinks')
            ->put("/shortlinks/{$shortlink->id}", shortlinkPayload(['short_code' => 'collision']))
            ->assertSessionHasErrors('short_code');

        $this->assertDatabaseCount('shortlinks', 2);
        expect($shortlink->fresh()->short_code)->toBe('original')
            ->and($shortlink->fresh()->destination_url)->toBe($shortlink->destination_url);
    } finally {
        Shortlink::flushEventListeners();
    }
});

test('expiration outside the database timestamp range is rejected', function (string $expiration, string $method) {
    $shortlink = $method === 'put' ? Shortlink::factory()->create() : null;
    $url = $shortlink ? "/shortlinks/{$shortlink->id}" : '/shortlinks';

    $this->actingAs(shortlinkUser())->{$method}($url, shortlinkPayload(['expires_at' => $expiration]))
        ->assertSessionHasErrors('expires_at');

    $this->assertDatabaseCount('shortlinks', $shortlink ? 1 : 0);

    if ($shortlink) {
        expect($shortlink->fresh()->expires_at)->toBeNull();
    }
})->with(['1970-01-01T00:00:00Z', '2038-01-19T03:14:08Z'])->with(['post', 'put']);

test('expiration accepts timestamp boundaries and normalizes timezone offsets', function (string $expiration, string $stored) {
    $this->actingAs(shortlinkUser())->post('/shortlinks', shortlinkPayload(['expires_at' => $expiration]))
        ->assertSessionHasNoErrors();

    expect(Shortlink::sole()->expires_at->utc()->format('Y-m-d H:i:s'))->toBe($stored);
})->with([
    'lower bound' => ['1970-01-01T00:00:01Z', '1970-01-01 00:00:01'],
    'upper bound' => ['2038-01-19T03:14:07Z', '2038-01-19 03:14:07'],
    'offset at lower bound' => ['1970-01-01T07:00:01+07:00', '1970-01-01 00:00:01'],
]);

test('deleting the final entry on a page clamps the filtered listing to the last page', function () {
    $shortlinks = Shortlink::factory()->count(11)->create(['destination_url' => 'https://example.com/matching']);
    $this->actingAs(shortlinkUser());

    $response = $this->from('/shortlinks?search=matching&page=2')->delete("/shortlinks/{$shortlinks->first()->id}");
    $response->assertRedirect('/shortlinks?search=matching&page=2');

    $pageRedirect = $this->get($response->headers->get('Location'));
    $pageRedirect->assertRedirect('/shortlinks?search=matching&page=1');

    $this->get($pageRedirect->headers->get('Location'))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->where('filters.search', 'matching')
        ->where('shortlinks.current_page', 1)
        ->has('shortlinks.data', 10)
    );
});

test('an empty filtered listing always displays page one', function () {
    $response = $this->actingAs(shortlinkUser())->get('/shortlinks?search=matching&page=2');
    $response->assertRedirect('/shortlinks?search=matching&page=1');

    $this->get($response->headers->get('Location'))
        ->assertOk()->assertInertia(fn (Assert $page) => $page
        ->where('filters.search', 'matching')
        ->where('shortlinks.current_page', 1)
        ->has('shortlinks.data', 0)
        );
});

test('patch accepts a full shortlink update', function () {
    $shortlink = Shortlink::factory()->create();

    $this->actingAs(shortlinkUser())->patch("/shortlinks/{$shortlink->id}", shortlinkPayload())
        ->assertSessionHasNoErrors();

    expect($shortlink->fresh()->short_code)->toBe('company-profile');
});

test('index rejects an array search parameter', function () {
    $this->actingAs(shortlinkUser())->get('/shortlinks?search[]=matching')
        ->assertSessionHasErrors('search');
});

test('super admins can manage shortlinks through the global gate bypass', function () {
    Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole('super_admin');
    $this->actingAs($user);

    $this->get('/shortlinks')->assertOk();
    $this->post('/shortlinks', shortlinkPayload())->assertSessionHasNoErrors();
    $shortlink = Shortlink::sole();
    $this->put("/shortlinks/{$shortlink->id}", shortlinkPayload(['short_code' => 'updated']))
        ->assertSessionHasNoErrors();
    $this->delete("/shortlinks/{$shortlink->id}")->assertRedirect();

    $this->assertDatabaseCount('shortlinks', 0);
});
