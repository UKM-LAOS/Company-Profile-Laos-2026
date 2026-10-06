<script setup lang="ts">
import AppButton from '@/Components/AppButton.vue';
import AppIcon from '@/Components/AppIcon.vue';
import DataTable, { type TableColumn } from '@/Components/Common/DataTable.vue';
import Pagination, {
    type PaginationLink,
} from '@/Components/Common/Pagination.vue';
import RefreshButton from '@/Components/Common/RefreshButton.vue';
import SearchInput from '@/Components/Common/SearchInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserConfirmModal from './Partials/DeleteUserConfirmModal.vue';
import UserFormModal, { type UserItem } from './Partials/UserFormModal.vue';
import { usePermission } from '@/lib/usePermission';
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const { can } = usePermission();

interface PaginatedUsers {
    data: UserItem[];
    from: number | null;
    to: number | null;
    total: number;
    links: PaginationLink[];
    current_page: number;
    last_page: number;
}

const props = defineProps<{
    users: PaginatedUsers;
    filters: {
        search?: string;
    };
    availableRoles: string[];
}>();

const page = usePage();
const currentUser = computed(() => page.props.auth.user);

const searchQuery = ref(props.filters.search || '');
const isRefreshing = ref(false);

const columns: TableColumn[] = [
    { key: 'name', label: 'PENGGUNA', headerClass: 'w-2/5' },
    { key: 'email', label: 'EMAIL' },
    { key: 'role', label: 'ROLE' },
    { key: 'created_at', label: 'TANGGAL DAFTAR' },
    {
        key: 'actions',
        label: 'AKSI',
        align: 'right',
        headerClass: 'text-right',
    },
];

// Modal States
const showFormModal = ref(false);
const showDeleteModal = ref(false);
const selectedUser = ref<UserItem | null>(null);

function openCreateModal() {
    selectedUser.value = null;
    showFormModal.value = true;
}

function openEditModal(user: UserItem) {
    selectedUser.value = user;
    showFormModal.value = true;
}

function openDeleteModal(user: UserItem) {
    selectedUser.value = user;
    showDeleteModal.value = true;
}

function handleSearch(query: string) {
    router.get(
        route('users.index'),
        { search: query || undefined },
        { preserveState: true, preserveScroll: true, replace: true },
    );
}

function refreshData() {
    isRefreshing.value = true;
    router.reload({
        only: ['users'],
        onFinish: () => {
            isRefreshing.value = false;
        },
    });
}

const avatarColorPalette = [
    'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300',
    'bg-teal-100 text-teal-800 dark:bg-teal-950/60 dark:text-teal-300',
    'bg-sky-100 text-sky-800 dark:bg-sky-950/60 dark:text-sky-300',
    'bg-purple-100 text-purple-800 dark:bg-purple-950/60 dark:text-purple-300',
    'bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300',
    'bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300',
];

function getAvatarColor(user: UserItem): string {
    const charCode = (user.name || '').charCodeAt(0) || 0;
    return avatarColorPalette[charCode % avatarColorPalette.length];
}

function getInitials(name: string): string {
    const parts = (name || '').trim().split(/\s+/);
    if (parts.length >= 2) {
        return (parts[0][0] + parts[1][0]).toUpperCase();
    }
    return (name || '').slice(0, 2).toUpperCase() || 'U';
}

function getRoleLabel(user: UserItem): string {
    const role = user.roles?.[0]?.name;
    switch (role) {
        case 'super_admin':
            return 'Super Admin';
        case 'admin':
            return 'Administrator';
        case 'member':
            return 'Anggota';
        default:
            return role
                ? role
                      .replace('_', ' ')
                      .replace(/\b\w/g, (c) => c.toUpperCase())
                : 'Anggota';
    }
}

function getRoleBadgeClass(user: UserItem): string {
    const role = user.roles?.[0]?.name;
    switch (role) {
        case 'super_admin':
            return 'bg-purple-50 text-purple-700 border-purple-100/80 dark:bg-purple-950/50 dark:text-purple-300 dark:border-purple-900/50';
        case 'admin':
            return 'bg-emerald-50 text-emerald-700 border-emerald-100/80 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-900/50';
        case 'member':
        default:
            return 'bg-slate-100 text-slate-700 border-slate-200/80 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700';
    }
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
    <Head title="Kelola Pengguna" />

    <AuthenticatedLayout>
        <template #header>
            <div
                class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
            >
                <div>
                    <h1
                        class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl dark:text-white"
                    >
                        Kelola Pengguna
                    </h1>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Kelola data seluruh pengurus, anggota, hak akses peran,
                        serta akun yang terdaftar di platform CMS UKM LAOS.
                    </p>
                </div>

                <div v-if="can('create_users')" class="flex items-center gap-3">
                    <AppButton
                        type="button"
                        variant="primary"
                        size="md"
                        icon="plus"
                        class="w-full sm:w-auto"
                        @click="openCreateModal"
                    >
                        Tambah Pengguna
                    </AppButton>
                </div>
            </div>
        </template>

        <div class="space-y-6">
            <!-- Search Bar & Refresh Button Controls -->
            <div class="flex items-center gap-3">
                <div class="flex-1">
                    <SearchInput
                        v-model="searchQuery"
                        placeholder="Cari berdasarkan nama, email..."
                        @search="handleSearch"
                    />
                </div>

                <RefreshButton :loading="isRefreshing" @click="refreshData" />
            </div>

            <!-- Reusable DataTable -->
            <DataTable
                :columns="columns"
                :items="users.data"
                empty-title="Pengguna Tidak Ditemukan"
                empty-description="Tidak ada data pengguna yang cocok dengan kriteria pencarian Anda."
            >
                <!-- Column: Pengguna (Avatar + Nama + ID) -->
                <template #cell-name="{ item }">
                    <div class="flex items-center gap-3">
                        <div
                            :class="[
                                'flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl text-xs font-bold tracking-wider select-none',
                                getAvatarColor(item),
                            ]"
                        >
                            {{ getInitials(item.name) }}
                        </div>
                        <div class="flex flex-col">
                            <span
                                class="font-bold text-slate-900 dark:text-slate-100"
                            >
                                {{ item.name }}
                            </span>
                            <span
                                class="text-[11px] font-medium text-slate-400 dark:text-slate-500"
                            >
                                ID: #{{ item.id }}
                            </span>
                        </div>
                    </div>
                </template>

                <!-- Column: Email -->
                <template #cell-email="{ item }">
                    <span
                        class="font-medium text-slate-600 dark:text-slate-300"
                    >
                        {{ item.email }}
                    </span>
                </template>

                <!-- Column: Role Badge -->
                <template #cell-role="{ item }">
                    <span
                        :class="[
                            'inline-flex items-center rounded-full border px-3 py-0.5 text-xs font-semibold',
                            getRoleBadgeClass(item),
                        ]"
                    >
                        {{ getRoleLabel(item) }}
                    </span>
                </template>

                <!-- Column: Tanggal Daftar -->
                <template #cell-created_at="{ item }">
                    <span class="text-xs text-slate-600 dark:text-slate-400">
                        {{ formatDate(item.created_at) }}
                    </span>
                </template>

                <!-- Column: Actions -->
                <template #cell-actions="{ item }">
                    <div class="flex items-center justify-end gap-1.5">
                        <!-- Edit Button -->
                        <button
                            v-if="can('edit_users')"
                            type="button"
                            @click="openEditModal(item)"
                            class="inline-flex h-8 w-8 cursor-pointer items-center justify-center rounded-xl text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200"
                            title="Ubah Data Pengguna"
                        >
                            <AppIcon name="edit" class-name="h-4 w-4" />
                        </button>

                        <!-- Delete Button (Disabled for self) -->
                        <template v-if="can('delete_users')">
                            <button
                                v-if="item.id !== currentUser.id"
                                type="button"
                                @click="openDeleteModal(item)"
                                class="inline-flex h-8 w-8 cursor-pointer items-center justify-center rounded-xl text-rose-500 transition-colors hover:bg-rose-50 hover:text-rose-700 dark:text-rose-400 dark:hover:bg-rose-950/50 dark:hover:text-rose-300"
                                title="Hapus Pengguna"
                            >
                                <AppIcon name="trash" class-name="h-4 w-4" />
                            </button>
                            <span
                                v-else
                                class="inline-flex h-8 w-8 cursor-not-allowed items-center justify-center text-slate-300 dark:text-slate-600"
                                title="Tidak dapat menghapus akun Anda sendiri"
                            >
                                <AppIcon
                                    name="trash"
                                    class-name="h-4 w-4 opacity-40"
                                />
                            </span>
                        </template>
                    </div>
                </template>

                <!-- Footer Pagination -->
                <template #footer>
                    <Pagination
                        :links="users.links"
                        :from="users.from"
                        :to="users.to"
                        :total="users.total"
                        item-name="pengguna"
                    />
                </template>
            </DataTable>
        </div>

        <!-- Create / Edit User Modal -->
        <UserFormModal
            :show="showFormModal"
            :user="selectedUser"
            :available-roles="availableRoles"
            @close="showFormModal = false"
            @saved="refreshData"
        />

        <!-- Delete Confirmation Modal -->
        <DeleteUserConfirmModal
            :show="showDeleteModal"
            :user="selectedUser"
            @close="showDeleteModal = false"
            @deleted="refreshData"
        />
    </AuthenticatedLayout>
</template>
