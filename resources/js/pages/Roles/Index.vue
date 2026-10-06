<script setup lang="ts">
import AppButton from '@/Components/AppButton.vue';
import AppIcon from '@/Components/AppIcon.vue';
import DataTable, { type TableColumn } from '@/Components/Common/DataTable.vue';
import Pagination, {
    type PaginationLink,
} from '@/Components/Common/Pagination.vue';
import RefreshButton from '@/Components/Common/RefreshButton.vue';
import SearchInput from '@/Components/Common/SearchInput.vue';
import TabSwitcher, { type TabItem } from '@/Components/Common/TabSwitcher.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteRoleModal from './Partials/DeleteRoleModal.vue';
import PermissionCatalogView from './Partials/PermissionCatalogView.vue';
import RoleFormModal from './Partials/RoleFormModal.vue';
import RolePermissionModal, {
    type PermissionCatalogItem,
    type RoleItem,
} from './Partials/RolePermissionModal.vue';
import { usePermission } from '@/lib/usePermission';
import { Head, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const { can } = usePermission();

interface PaginatedRoles {
    data: RoleItem[];
    from: number | null;
    to: number | null;
    total: number;
    links: PaginationLink[];
    current_page: number;
    last_page: number;
}

const props = defineProps<{
    roles: PaginatedRoles;
    filters: {
        search?: string;
    };
    groupedCatalog: Record<string, PermissionCatalogItem[]>;
    totalPermissions: number;
}>();

const activeTab = ref<'roles' | 'catalog'>('roles');
const searchQuery = ref(props.filters.search || '');
const isRefreshing = ref(false);

const tabs = computed<TabItem[]>(() => [
    {
        id: 'roles',
        label: 'Daftar Peran (Roles)',
        count: props.roles.total,
        icon: 'shield',
    },
    {
        id: 'catalog',
        label: 'Katalog Hak Akses',
        count: props.totalPermissions,
        icon: 'key',
    },
]);

const columns: TableColumn[] = [
    { key: 'name', label: 'NAMA PERAN', headerClass: 'w-1/3' },
    { key: 'guard_name', label: 'GUARD' },
    { key: 'users_count', label: 'TOTAL PENGURUS' },
    { key: 'permissions', label: 'HAK AKSES AKTIF' },
    { key: 'created_at', label: 'TANGGAL DIBUAT' },
    {
        key: 'actions',
        label: 'AKSI',
        align: 'right',
        headerClass: 'text-right',
    },
];

// Modal States
const showPermissionModal = ref(false);
const showFormModal = ref(false);
const showDeleteModal = ref(false);
const selectedRole = ref<RoleItem | null>(null);

function openCreateModal() {
    selectedRole.value = null;
    showFormModal.value = true;
}

function openEditModal(role: RoleItem | any) {
    selectedRole.value = role as RoleItem;
    showFormModal.value = true;
}

function openPermissionModal(role: RoleItem | any) {
    selectedRole.value = role as RoleItem;
    showPermissionModal.value = true;
}

function openDeleteModal(role: RoleItem | any) {
    selectedRole.value = role as RoleItem;
    showDeleteModal.value = true;
}

function handleSearch(query: string) {
    router.get(
        route('roles.index'),
        { search: query || undefined },
        { preserveState: true, preserveScroll: true, replace: true },
    );
}

function refreshData() {
    isRefreshing.value = true;
    router.reload({
        only: ['roles'],
        onFinish: () => {
            isRefreshing.value = false;
        },
    });
}

function formatRoleTitle(name: string): string {
    switch (name) {
        case 'super_admin':
            return 'Super Admin';
        case 'admin':
            return 'Administrator';
        case 'member':
            return 'Anggota';
        default:
            return name
                .replace('_', ' ')
                .replace(/\b\w/g, (c) => c.toUpperCase());
    }
}

function getRoleBadge(name: string) {
    if (name === 'super_admin') {
        return {
            label: 'SYSTEM',
            class: 'bg-purple-50 text-purple-700 border-purple-200/80 dark:bg-purple-950/50 dark:text-purple-300 dark:border-purple-800/60',
        };
    }
    if (name === 'admin' || name === 'member') {
        return {
            label: 'Default',
            class: 'bg-emerald-50 text-emerald-700 border-emerald-200/80 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-800/60',
        };
    }
    return {
        label: 'Custom',
        class: 'bg-slate-100 text-slate-700 border-slate-200/80 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700',
    };
}

function formatDate(dateStr: string): string {
    if (!dateStr) return '-';
    try {
        const d = new Date(dateStr);
        return d.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
        });
    } catch {
        return dateStr;
    }
}
</script>

<template>
    <Head title="Manajemen Peran & Hak Akses" />

    <AuthenticatedLayout>
        <template #header>
            <div
                class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
            >
                <div>
                    <h1
                        class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl dark:text-white"
                    >
                        Manajemen Peran & Hak Akses
                    </h1>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Kelola struktur peran organisasi, hak akses granular
                        fitur, dan kontrol otorisasi akun pengurus.
                    </p>
                </div>

                <div v-if="can('create_roles')" class="flex items-center gap-3">
                    <AppButton
                        type="button"
                        variant="primary"
                        size="md"
                        icon="plus"
                        class="w-full sm:w-auto"
                        @click="openCreateModal"
                    >
                        Tambah Peran
                    </AppButton>
                </div>
            </div>
        </template>

        <div class="space-y-6">
            <!-- Navigation Tabs (Pill Switcher) -->
            <div>
                <TabSwitcher v-model="activeTab" :tabs="tabs" />
            </div>

            <!-- Tab 1: Roles List Table -->
            <div v-if="activeTab === 'roles'" class="space-y-4">
                <!-- Search and Refresh Toolbar -->
                <div class="flex items-center gap-3">
                    <div class="flex-1">
                        <SearchInput
                            v-model="searchQuery"
                            placeholder="Cari nama peran..."
                            @search="handleSearch"
                        />
                    </div>
                    <RefreshButton
                        :loading="isRefreshing"
                        @click="refreshData"
                    />
                </div>

                <!-- Roles Table -->
                <DataTable
                    :columns="columns"
                    :items="roles.data"
                    empty-title="Peran Tidak Ditemukan"
                    empty-description="Belum ada data peran yang cocok dengan kriteria pencarian Anda."
                >
                    <!-- Role Name Column (No pastel initials avatar, clean text + badge) -->
                    <template #cell-name="{ item }">
                        <div class="flex flex-col gap-1 py-1">
                            <div class="flex items-center gap-2">
                                <span
                                    class="text-sm font-bold text-slate-900 dark:text-white"
                                >
                                    {{ formatRoleTitle(item.name) }}
                                </span>
                                <span
                                    class="inline-flex items-center rounded-md border px-1.5 py-0.5 text-[10px] font-semibold tracking-wider uppercase"
                                    :class="getRoleBadge(item.name).class"
                                >
                                    {{ getRoleBadge(item.name).label }}
                                </span>
                            </div>
                            <span
                                class="font-mono text-xs text-slate-500 dark:text-slate-400"
                            >
                                {{ item.name }}
                            </span>
                        </div>
                    </template>

                    <!-- Guard Name Column -->
                    <template #cell-guard_name="{ item }">
                        <span
                            class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 font-mono text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-400"
                        >
                            {{ item.guard_name || 'web' }}
                        </span>
                    </template>

                    <!-- Users Count Column -->
                    <template #cell-users_count="{ item }">
                        <div
                            class="flex items-center gap-1.5 text-xs font-medium text-slate-700 dark:text-slate-300"
                        >
                            <AppIcon
                                name="pengurus"
                                class-name="h-4 w-4 text-slate-400 dark:text-slate-500"
                            />
                            <span>{{ item.users_count }} Pengurus</span>
                        </div>
                    </template>

                    <!-- Active Permissions Column -->
                    <template #cell-permissions="{ item }">
                        <!-- Super Admin: Full Access Bypass -->
                        <div
                            v-if="item.name === 'super_admin'"
                            class="flex items-center gap-2"
                        >
                            <div
                                class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-950/80 dark:text-emerald-400"
                            >
                                <AppIcon
                                    name="check"
                                    class-name="h-3 w-3 stroke-[3]"
                                />
                            </div>
                            <div>
                                <span
                                    class="text-xs font-bold text-emerald-700 dark:text-emerald-400"
                                >
                                    Akses Penuh (Bypass)
                                </span>
                                <p
                                    class="text-[11px] text-slate-400 dark:text-slate-500"
                                >
                                    Semua Izin Aktif
                                </p>
                            </div>
                        </div>

                        <!-- Regular Roles: Count of Permissions -->
                        <div v-else class="flex items-center gap-2.5">
                            <span
                                class="inline-flex items-center gap-1 rounded-full border border-slate-200/80 bg-slate-50 px-2.5 py-1 text-xs font-medium text-slate-700 dark:border-slate-700 dark:bg-slate-800/80 dark:text-slate-300"
                            >
                                <span
                                    class="font-bold text-slate-900 dark:text-white"
                                >
                                    {{ item.permissions?.length || 0 }}
                                </span>
                                <span class="text-slate-400">/</span>
                                <span>{{ totalPermissions }} Izin</span>
                            </span>
                        </div>
                    </template>

                    <!-- Created At Column -->
                    <template #cell-created_at="{ item }">
                        <span
                            class="text-xs text-slate-600 dark:text-slate-400"
                        >
                            {{ formatDate(item.created_at) }}
                        </span>
                    </template>

                    <!-- Actions Column -->
                    <template #cell-actions="{ item }">
                        <div class="flex items-center justify-end gap-1.5">
                            <!-- Button: Atur Hak Akses -->
                            <button
                                v-if="can('edit_roles') || can('view_roles')"
                                type="button"
                                class="inline-flex cursor-pointer items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-700 shadow-xs transition-colors hover:border-emerald-300 hover:bg-emerald-50/50 hover:text-emerald-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:border-emerald-700 dark:hover:bg-emerald-950/30 dark:hover:text-emerald-400"
                                title="Konfigurasi Hak Akses"
                                @click="openPermissionModal(item)"
                            >
                                <AppIcon
                                    name="sliders"
                                    class-name="h-3.5 w-3.5 text-slate-500 dark:text-slate-400"
                                />
                                <span class="hidden sm:inline">Atur Izin</span>
                            </button>

                            <!-- Edit Role Name (Hidden/Disabled for super_admin) -->
                            <button
                                v-if="
                                    can('edit_roles') &&
                                    item.name !== 'super_admin'
                                "
                                type="button"
                                class="cursor-pointer rounded-xl p-1.5 text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800 dark:hover:text-slate-300"
                                title="Ubah Nama Peran"
                                @click="openEditModal(item)"
                            >
                                <AppIcon name="edit" class-name="h-4 w-4" />
                            </button>

                            <!-- Delete Role (Hidden/Disabled for super_admin) -->
                            <button
                                v-if="
                                    can('delete_roles') &&
                                    item.name !== 'super_admin'
                                "
                                type="button"
                                class="cursor-pointer rounded-xl p-1.5 text-slate-400 transition-colors hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-950/40 dark:hover:text-rose-400"
                                title="Hapus Peran"
                                @click="openDeleteModal(item)"
                            >
                                <AppIcon name="trash" class-name="h-4 w-4" />
                            </button>

                            <!-- System Role Lock indicator for super_admin -->
                            <span
                                v-if="item.name === 'super_admin'"
                                class="inline-flex items-center p-1.5 text-slate-400"
                                title="Peran Sistem Dilindungi"
                            >
                                <AppIcon name="lock" class-name="h-4 w-4" />
                            </span>
                        </div>
                    </template>

                    <!-- Footer Pagination -->
                    <template #footer>
                        <Pagination
                            :links="roles.links"
                            :from="roles.from"
                            :to="roles.to"
                            :total="roles.total"
                            item-name="peran"
                        />
                    </template>
                </DataTable>
            </div>

            <!-- Tab 2: Master Permissions Catalog -->
            <div v-else-if="activeTab === 'catalog'">
                <PermissionCatalogView
                    :grouped-catalog="groupedCatalog"
                    :total-permissions="totalPermissions"
                />
            </div>
        </div>

        <!-- Modals -->
        <RolePermissionModal
            :show="showPermissionModal"
            :role="selectedRole"
            :grouped-catalog="groupedCatalog"
            :total-permissions="totalPermissions"
            @close="showPermissionModal = false"
            @saved="refreshData"
        />

        <RoleFormModal
            :show="showFormModal"
            :role="selectedRole"
            @close="showFormModal = false"
            @saved="refreshData"
        />

        <DeleteRoleModal
            :show="showDeleteModal"
            :role="selectedRole"
            @close="showDeleteModal = false"
            @deleted="refreshData"
        />
    </AuthenticatedLayout>
</template>
