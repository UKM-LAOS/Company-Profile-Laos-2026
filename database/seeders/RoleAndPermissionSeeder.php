<?php

namespace Database\Seeders;

use App\Enums\Permission as PermissionEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Clean up any stale permissions not in the Enum
        $validPermissions = array_map(fn ($c) => $c->value, PermissionEnum::cases());
        Permission::whereNotIn('name', $validPermissions)->delete();

        // 1. Create permissions from Enum
        foreach (PermissionEnum::cases() as $permissionCase) {
            Permission::firstOrCreate([
                'name' => $permissionCase->value,
                'guard_name' => 'web',
            ]);
        }

        // 2. Create roles
        $superAdminRole = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);

        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $memberRole = Role::firstOrCreate([
            'name' => 'member',
            'guard_name' => 'web',
        ]);

        // 3. Assign permissions to roles
        $superAdminRole->syncPermissions(Permission::all());

        // Admin has all permissions
        $adminRole->syncPermissions(Permission::all());

        // Member has read-only permissions
        $memberRole->syncPermissions([
            PermissionEnum::VIEW_DASHBOARD->value,
            PermissionEnum::VIEW_DIVISIONS->value,
            PermissionEnum::VIEW_WORK_PROGRAMS->value,
            PermissionEnum::VIEW_NEWS->value,
            PermissionEnum::VIEW_COMMITTEE->value,
            PermissionEnum::VIEW_SHORTLINKS->value,
        ]);

        // 4. Create default users for each role (if not existing)
        $superAdminUnej = User::firstOrCreate(
            ['email' => 'admin@laos.unej.ac.id'],
            [
                'name' => 'Super Admin UKM',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $superAdminUnej->syncRoles([$superAdminRole]);

        $superAdminUser = User::firstOrCreate(
            ['email' => 'superadmin@laos.test'],
            [
                'name' => 'Super Admin LAOS',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $superAdminUser->syncRoles([$superAdminRole]);

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@laos.test'],
            [
                'name' => 'Administrator LAOS',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $adminUser->syncRoles([$adminRole]);

        $memberUser = User::firstOrCreate(
            ['email' => 'member@laos.test'],
            [
                'name' => 'Member LAOS',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $memberUser->syncRoles([$memberRole]);
    }
}
