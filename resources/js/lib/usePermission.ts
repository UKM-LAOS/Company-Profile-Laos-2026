import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import type { PageProps } from '@/types';
import type { PermissionName, RoleName, User } from '@/types/auth';

export function usePermission() {
    const page = usePage<PageProps>();

    const user = computed<User | null>(() => page.props.auth?.user ?? null);

    const roles = computed<RoleName[]>(() => user.value?.roles ?? []);

    const permissions = computed<PermissionName[]>(
        () => user.value?.permissions ?? [],
    );

    const isSuperAdmin = computed<boolean>(() =>
        roles.value.includes('super_admin'),
    );

    /**
     * Check if the authenticated user has a specific role.
     */
    function hasRole(role: RoleName): boolean {
        return roles.value.includes(role);
    }

    /**
     * Check if the user has any of the specified roles.
     */
    function hasAnyRole(roleList: RoleName[]): boolean {
        return roleList.some((role) => roles.value.includes(role));
    }

    /**
     * Check if the user has all of the specified roles.
     */
    function hasAllRoles(roleList: RoleName[]): boolean {
        return roleList.every((role) => roles.value.includes(role));
    }

    /**
     * Check if the authenticated user has a specific permission.
     * Super Admin always has full access (matches backend Gate::before).
     */
    function can(permission: PermissionName): boolean {
        if (isSuperAdmin.value) {
            return true;
        }

        return permissions.value.includes(permission);
    }

    /**
     * Check if the user has at least one of the specified permissions.
     */
    function canAny(permissionList: PermissionName[]): boolean {
        if (isSuperAdmin.value) {
            return true;
        }

        return permissionList.some((perm) => permissions.value.includes(perm));
    }

    /**
     * Check if the user has all of the specified permissions.
     */
    function canAll(permissionList: PermissionName[]): boolean {
        if (isSuperAdmin.value) {
            return true;
        }

        return permissionList.every((perm) => permissions.value.includes(perm));
    }

    return {
        user,
        roles,
        permissions,
        isSuperAdmin,
        hasRole,
        hasAnyRole,
        hasAllRoles,
        can,
        canAny,
        canAll,
    };
}
