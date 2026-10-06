<script setup lang="ts">
import AppLogo from '@/Components/AppLogo.vue';
import SidebarItem from '@/Components/Sidebar/SidebarItem.vue';
import SidebarProfile from '@/Components/Sidebar/SidebarProfile.vue';
import { usePermission } from '@/lib/usePermission';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const { can } = usePermission();

interface MenuItem {
    title: string;
    href: string;
    icon:
        | 'dashboard'
        | 'divisi'
        | 'proker'
        | 'berita'
        | 'pengurus'
        | 'pengguna'
        | 'roles'
        | 'shortlink';
    routeName?: string;
    permission?: string;
}

interface MenuGroup {
    title: string;
    items: MenuItem[];
}

const menuGroups: MenuGroup[] = [
    {
        title: 'UTAMA',
        items: [
            {
                title: 'Dashboard',
                href:
                    typeof route === 'function'
                        ? route('dashboard')
                        : '/dashboard',
                icon: 'dashboard',
                routeName: 'dashboard',
            },
        ],
    },
    {
        title: 'CMS',
        items: [
            {
                title: 'Kelola Divisi',
                href: '#',
                icon: 'divisi',
                permission: 'view_divisions',
            },
            {
                title: 'Kelola Program Kerja',
                href: '#',
                icon: 'proker',
                permission: 'view_work_programs',
            },
            {
                title: 'Kelola Berita',
                href: '#',
                icon: 'berita',
                permission: 'view_news',
            },
            {
                title: 'Kelola Pengurus',
                href: '#',
                icon: 'pengurus',
                permission: 'view_committee',
            },
        ],
    },
    {
        title: 'SISTEM',
        items: [
            {
                title: 'Kelola Pengguna',
                href:
                    typeof route === 'function'
                        ? route('users.index')
                        : '/users',
                icon: 'pengguna',
                routeName: 'users.index',
                permission: 'view_users',
            },
            {
                title: 'Hak Akses & Role',
                href:
                    typeof route === 'function'
                        ? route('roles.index')
                        : '/roles',
                icon: 'roles',
                routeName: 'roles.index',
                permission: 'view_roles',
            },
        ],
    },
    {
        title: 'FITUR',
        items: [
            {
                title: 'Shortlink',
                href:
                    typeof route === 'function'
                        ? route('shortlinks.index')
                        : '/shortlinks',
                icon: 'shortlink',
                routeName: 'shortlinks.index',
                permission: 'view_shortlinks',
            },
        ],
    },
];

/**
 * Filter groups and menu items based on user RBAC permissions.
 * If all items in a group are hidden, the group header is also automatically hidden.
 */
const authorizedMenuGroups = computed(() => {
    return menuGroups
        .map((group) => {
            const filteredItems = group.items.filter((item) => {
                // If item does not require permission, always show
                if (!item.permission) {
                    return true;
                }
                // Check if user has permission (Super Admin always passes via usePermission)
                return can(item.permission);
            });

            return {
                ...group,
                items: filteredItems,
            };
        })
        .filter((group) => group.items.length > 0);
});

function isItemActive(item: MenuItem): boolean {
    if (item.routeName && typeof route === 'function') {
        try {
            return route().current(item.routeName);
        } catch {
            return false;
        }
    }
    return false;
}
</script>

<template>
    <aside
        class="flex h-full w-64 flex-col justify-between border-r border-slate-100 bg-white px-5 py-6 dark:border-slate-800 dark:bg-slate-900"
    >
        <!-- Top: Logo & Navigation Menu -->
        <div class="flex flex-col gap-8 overflow-y-auto">
            <!-- Brand Logo -->
            <div class="px-2">
                <Link
                    :href="
                        typeof route === 'function'
                            ? route('dashboard')
                            : '/dashboard'
                    "
                    class="inline-block"
                >
                    <AppLogo />
                </Link>
            </div>

            <!-- RBAC Navigation Menu Groups -->
            <nav class="space-y-6">
                <div
                    v-for="group in authorizedMenuGroups"
                    :key="group.title"
                    class="space-y-2"
                >
                    <!-- Section Header -->
                    <div
                        class="px-4 text-[11px] font-bold tracking-wider text-slate-400 uppercase dark:text-slate-500"
                    >
                        {{ group.title }}
                    </div>

                    <!-- Items in section -->
                    <div class="space-y-1">
                        <SidebarItem
                            v-for="item in group.items"
                            :key="item.title"
                            :title="item.title"
                            :href="item.href"
                            :icon="item.icon"
                            :active="isItemActive(item)"
                        />
                    </div>
                </div>
            </nav>
        </div>

        <!-- Bottom: User Profile Card -->
        <div class="border-t border-slate-100 pt-4 dark:border-slate-800">
            <SidebarProfile />
        </div>
    </aside>
</template>
