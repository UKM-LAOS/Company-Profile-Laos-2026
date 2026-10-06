<?php

namespace App\Http\Controllers;

use App\Enums\Permission as PermissionEnum;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleController extends Controller
{
    /**
     * Display a listing of roles and permission catalog.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('view_roles');
        $search = $request->input('search');

        $roles = Role::with('permissions')
            ->withCount('users')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Roles/Index', [
            'roles' => $roles,
            'filters' => [
                'search' => $search ?? '',
            ],
            'groupedCatalog' => PermissionEnum::groupedCatalog(),
            'totalPermissions' => Permission::count(),
        ]);
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create_roles');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:roles,name'],
        ]);

        $normalizedName = strtolower(str_replace(' ', '_', trim($validated['name'])));

        Role::create([
            'name' => $normalizedName,
            'guard_name' => 'web',
        ]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->back()->with('success', 'Peran baru berhasil ditambahkan.');
    }

    /**
     * Update the specified role in storage.
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        Gate::authorize('edit_roles');

        if ($role->name === 'super_admin') {
            return redirect()->back()->with('error', 'Peran Super Admin sistem tidak dapat diubah.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', Rule::unique('roles', 'name')->ignore($role->id)],
        ]);

        $normalizedName = strtolower(str_replace(' ', '_', trim($validated['name'])));

        $role->name = $normalizedName;
        $role->save();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->back()->with('success', 'Nama peran berhasil diperbarui.');
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(Role $role): RedirectResponse
    {
        Gate::authorize('delete_roles');

        if ($role->name === 'super_admin') {
            return redirect()->back()->with('error', 'Peran Super Admin sistem tidak dapat dihapus.');
        }

        if ($role->users()->count() > 0) {
            return redirect()->back()->with('error', "Peran tidak dapat dihapus karena masih digunakan oleh {$role->users()->count()} pengguna.");
        }

        $role->delete();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->back()->with('success', 'Peran berhasil dihapus.');
    }

    /**
     * Sync permissions for the given role.
     */
    public function syncPermissions(Request $request, Role $role): RedirectResponse
    {
        Gate::authorize('edit_roles');

        if ($role->name === 'super_admin') {
            return redirect()->back()->with('info', 'Peran Super Admin memiliki akses penuh (bypass) terhadap seluruh izin sistem.');
        }

        $validated = $request->validate([
            'permissions' => ['array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        $role->syncPermissions($validated['permissions'] ?? []);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->back()->with('success', "Konfigurasi hak akses untuk peran '{$role->name}' berhasil disimpan.");
    }
}
