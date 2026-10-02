<?php

namespace Database\Seeders;

use App\Enums\Permission as PermissionEnum;
use App\Enums\Role as RoleEnum;
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

        // 1. Create permissions from Enum
        foreach (PermissionEnum::cases() as $permissionCase) {
            Permission::firstOrCreate([
                'name' => $permissionCase->value,
                'guard_name' => 'web',
            ]);
        }

        // 2. Create roles from Enum
        $superAdminRole = Role::firstOrCreate([
            'name' => RoleEnum::SUPER_ADMIN->value,
            'guard_name' => 'web',
        ]);

        $adminRole = Role::firstOrCreate([
            'name' => RoleEnum::ADMIN->value,
            'guard_name' => 'web',
        ]);

        $memberRole = Role::firstOrCreate([
            'name' => RoleEnum::MEMBER->value,
            'guard_name' => 'web',
        ]);

        // 3. Assign permissions to roles
        $superAdminRole->syncPermissions(Permission::all());

        $adminRole->syncPermissions([
            PermissionEnum::VIEW_CONTENT->value,
            PermissionEnum::CREATE_CONTENT->value,
            PermissionEnum::EDIT_CONTENT->value,
            PermissionEnum::DELETE_CONTENT->value,
            PermissionEnum::PUBLISH_CONTENT->value,
            PermissionEnum::MANAGE_SETTINGS->value,
            PermissionEnum::MANAGE_USERS->value,
        ]);

        $memberRole->syncPermissions([
            PermissionEnum::VIEW_CONTENT->value,
        ]);

        // 4. Create default users for each role (if not existing)
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
