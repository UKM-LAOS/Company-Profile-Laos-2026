<script setup lang="ts">
import AppButton from '@/Components/AppButton.vue';
import AppIcon from '@/Components/AppIcon.vue';
import Pagination, {
    type PaginationLink,
} from '@/Components/Common/Pagination.vue';
import RefreshButton from '@/Components/Common/RefreshButton.vue';
import SearchInput from '@/Components/Common/SearchInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { usePermission } from '@/lib/usePermission';
import { Head, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import DeleteDivisiConfirmModal from './Partials/DeleteDivisiConfirmModal.vue';
import DivisiFormModal from './Partials/DivisiFormModal.vue';

interface DivisiItem {
    id: number;
    nama: string;
    slug: string;
    deskripsi?: string | null;
    logo?: string | null;
    created_at?: string | null;
    updated_at?: string | null;
}

interface PaginatedDivisis {
    data: DivisiItem[];
    from: number | null;
    to: number | null;
    total: number;
    links: PaginationLink[];
    current_page: number;
    last_page: number;
}

const props = defineProps<{
    divisis: PaginatedDivisis;
    filters: {
        search?: string;
    };
}>();

const { can } = usePermission();

const searchQuery = ref(props.filters.search || '');
const isRefreshing = ref(false);

watch(
    () => props.filters,
    (filters) => {
        searchQuery.value = filters.search || '';
    },
);

// Modal States
const isModalOpen = ref(false);
const selectedDivisi = ref<DivisiItem | null>(null);

const isDeleteModalOpen = ref(false);
const divisiToDelete = ref<DivisiItem | null>(null);

function openCreateModal() {
    selectedDivisi.value = null;
    isModalOpen.value = true;
}

function openEditModal(divisi: DivisiItem) {
    selectedDivisi.value = divisi;
    isModalOpen.value = true;
}

function openDeleteModal(divisi: DivisiItem) {
    divisiToDelete.value = divisi;
    isDeleteModalOpen.value = true;
}

function handleSearch(query: string) {
    router.get(
        route('divisis.index'),
        { search: query || undefined },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            only: ['divisis', 'filters'],
        },
    );
}

function refreshData() {
    isRefreshing.value = true;
    router.reload({
        only: ['divisis', 'filters'],
        onFinish: () => {
            isRefreshing.value = false;
        },
    });
}

function getInitials(nama: string): string {
    const words = nama.trim().split(/\s+/);
    if (words.length === 1) return words[0].slice(0, 2).toUpperCase();
    return (words[0][0] + words[1][0]).toUpperCase();
}
</script>

<template>
    <Head title="Kelola Divisi" />

    <AuthenticatedLayout>
        <!-- Page Header -->
        <template #header>
            <div
                class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
            >
                <div>
                    <h1
                        class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl dark:text-white"
                    >
                        Kelola Divisi
                    </h1>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Struktur divisi teknis dan operasional UKM LAOS Fasilkom
                        UNEJ periode kepengurusan aktif.
                    </p>
                </div>

                <div
                    v-if="can('create_divisions')"
                    class="flex items-center gap-3"
                >
                    <AppButton
                        type="button"
                        variant="primary"
                        size="md"
                        icon="plus"
                        class="w-full sm:w-auto"
                        @click="openCreateModal"
                    >
                        Tambah Divisi
                    </AppButton>
                </div>
            </div>
        </template>

        <div class="space-y-6">
            <!-- Search & Refresh Control Card (Matches Programs & Pengurus) -->
            <div
                class="flex flex-col gap-3 rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs lg:flex-row lg:items-center dark:border-slate-800/80 dark:bg-slate-900"
            >
                <SearchInput
                    v-model="searchQuery"
                    class="flex-1"
                    placeholder="Cari nama atau deskripsi divisi..."
                    @search="handleSearch"
                />

                <RefreshButton :loading="isRefreshing" @click="refreshData" />
            </div>

            <!-- Divisi Table Card (Matches Programs & Pengurus) -->
            <div
                class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs dark:border-slate-800/80 dark:bg-slate-900"
            >
                <div class="scrollbar-thin overflow-x-auto">
                    <table
                        class="w-full min-w-[700px] border-collapse text-left text-sm"
                    >
                        <thead>
                            <tr
                                class="border-b border-slate-100 bg-[#f8fafc]/90 text-[11px] font-bold tracking-wider text-slate-400 uppercase dark:border-slate-800 dark:bg-slate-800/60 dark:text-slate-400"
                            >
                                <th class="py-4 pr-4 pl-6 lg:w-[35%]">
                                    DIVISI
                                </th>
                                <th class="px-4 py-4">DESKRIPSI</th>
                                <th class="py-4 pr-6 pl-4 text-right">AKSI</th>
                            </tr>
                        </thead>

                        <tbody
                            class="divide-y divide-slate-100 dark:divide-slate-800/80"
                        >
                            <tr
                                v-for="item in divisis.data"
                                :key="item.id"
                                class="transition hover:bg-slate-50/60 dark:hover:bg-slate-800/40"
                            >
                                <!-- Divisi Name & Logo Column -->
                                <td class="py-4.5 pr-4 pl-6 align-middle">
                                    <div class="flex items-center gap-3.5">
                                        <!-- Logo or Fallback Avatar -->
                                        <div
                                            v-if="item.logo"
                                            class="h-11 w-11 shrink-0 overflow-hidden rounded-xl border border-slate-200/60 bg-white shadow-xs dark:border-slate-700 dark:bg-slate-800"
                                        >
                                            <img
                                                :src="'/storage/' + item.logo"
                                                :alt="item.nama"
                                                class="h-full w-full object-cover"
                                            />
                                        </div>
                                        <div
                                            v-else
                                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-emerald-200/60 bg-emerald-50 text-xs font-bold text-emerald-700 shadow-xs dark:border-emerald-900/50 dark:bg-emerald-950/40 dark:text-emerald-300"
                                        >
                                            {{ getInitials(item.nama) }}
                                        </div>

                                        <div class="min-w-0">
                                            <p
                                                class="truncate text-sm font-semibold text-slate-900 dark:text-white"
                                            >
                                                {{ item.nama }}
                                            </p>
                                            <p
                                                class="mt-0.5 truncate font-mono text-[11px] text-slate-400 dark:text-slate-500"
                                            >
                                                {{ item.slug }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Deskripsi Column -->
                                <td class="px-4 py-4.5 align-middle">
                                    <p
                                        class="line-clamp-2 max-w-2xl text-xs text-slate-600 dark:text-slate-300"
                                    >
                                        {{ item.deskripsi || '-' }}
                                    </p>
                                </td>

                                <!-- Actions Column -->
                                <td
                                    class="py-4.5 pr-6 pl-4 text-right align-middle"
                                >
                                    <div
                                        class="flex items-center justify-end gap-1.5"
                                    >
                                        <!-- Edit Button -->
                                        <button
                                            v-if="can('edit_divisions')"
                                            type="button"
                                            class="inline-flex h-8 w-8 cursor-pointer items-center justify-center rounded-lg text-slate-400 transition hover:bg-emerald-50 hover:text-emerald-600 dark:hover:bg-emerald-950/60 dark:hover:text-emerald-400"
                                            title="Edit Divisi"
                                            @click="openEditModal(item)"
                                        >
                                            <AppIcon
                                                name="edit"
                                                class-name="h-4 w-4"
                                            />
                                        </button>

                                        <!-- Delete Button -->
                                        <button
                                            v-if="can('delete_divisions')"
                                            type="button"
                                            class="inline-flex h-8 w-8 cursor-pointer items-center justify-center rounded-lg text-slate-400 transition hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-950/60 dark:hover:text-rose-400"
                                            title="Hapus Divisi"
                                            @click="openDeleteModal(item)"
                                        >
                                            <AppIcon
                                                name="trash"
                                                class-name="h-4 w-4"
                                            />
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Empty State -->
                            <tr
                                v-if="
                                    !divisis.data || divisis.data.length === 0
                                "
                            >
                                <td colspan="3" class="py-16 text-center">
                                    <div
                                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 dark:bg-slate-800"
                                    >
                                        <AppIcon
                                            name="search"
                                            class-name="h-6 w-6"
                                        />
                                    </div>
                                    <h3
                                        class="mt-3 text-sm font-semibold text-slate-800 dark:text-slate-200"
                                    >
                                        Divisi Tidak Ditemukan
                                    </h3>
                                    <p
                                        class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                                    >
                                        Tidak ada data divisi yang cocok dengan
                                        kriteria pencarian Anda.
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Footer Pagination (Circular Style matching Programs & Pengurus) -->
                <Pagination
                    v-if="divisis.links && divisis.links.length > 3"
                    :links="divisis.links"
                    :from="divisis.from"
                    :to="divisis.to"
                    :total="divisis.total"
                    item-name="divisi"
                    variant="circular"
                />
                <div
                    v-else-if="divisis.total > 0"
                    class="flex items-center justify-between border-t border-slate-100 px-6 py-4 text-xs text-slate-500 dark:border-slate-800 dark:text-slate-400"
                >
                    <span>
                        Menampilkan
                        <strong
                            class="font-bold text-slate-800 dark:text-slate-200"
                        >
                            {{ divisis.from ?? 1 }}-{{
                                divisis.to ?? divisis.total
                            }}
                        </strong>
                        dari
                        <strong
                            class="font-bold text-slate-800 dark:text-slate-200"
                        >
                            {{ divisis.total }}
                        </strong>
                        divisi
                    </span>
                </div>
            </div>
        </div>

        <!-- Form Modal (Tambah / Edit) -->
        <DivisiFormModal
            :show="isModalOpen"
            :divisi="selectedDivisi"
            @close="isModalOpen = false"
            @saved="refreshData"
        />

        <!-- Delete Confirmation Modal -->
        <DeleteDivisiConfirmModal
            :show="isDeleteModalOpen"
            :divisi="divisiToDelete"
            @close="isDeleteModalOpen = false"
            @deleted="refreshData"
        />
    </AuthenticatedLayout>
</template>
