<script setup lang="ts">
import AppButton from '@/Components/AppButton.vue';
import AppIcon from '@/Components/AppIcon.vue';
import DataTable, { type TableColumn } from '@/Components/Common/DataTable.vue';
import Pagination from '@/Components/Common/Pagination.vue';
import RefreshButton from '@/Components/Common/RefreshButton.vue';
import SearchInput from '@/Components/Common/SearchInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { usePermission } from '@/lib/usePermission';
import { Head, router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import DeleteShortlinkModal from './Partials/DeleteShortlinkModal.vue';
import ShortlinkFormModal from './Partials/ShortlinkFormModal.vue';
import type { PaginatedShortlinks, ShortlinkItem } from './types';

const props = defineProps<{
    shortlinks: PaginatedShortlinks;
    filters: { search?: string };
}>();

const { can } = usePermission();
const searchQuery = ref(props.filters.search || '');
const isRefreshing = ref(false);
const showFormModal = ref(false);
const showDeleteModal = ref(false);
const selectedShortlink = ref<ShortlinkItem | null>(null);

const columns = computed<TableColumn[]>(() => [
    { key: 'short_code', label: 'KODE' },
    { key: 'destination_url', label: 'URL TUJUAN' },
    { key: 'user', label: 'PEMBUAT' },
    { key: 'click_count', label: 'KLIK', align: 'right' },
    { key: 'is_active', label: 'STATUS' },
    { key: 'expires_at', label: 'KEDALUWARSA' },
    ...(can('edit_shortlinks') || can('delete_shortlinks')
        ? [{ key: 'actions', label: 'AKSI', align: 'right' as const }]
        : []),
]);

watch(
    () => props.filters.search,
    (search) => {
        searchQuery.value = search || '';
    },
);

function openForm(shortlink: ShortlinkItem | null = null) {
    selectedShortlink.value = shortlink;
    showFormModal.value = true;
}

function openDelete(shortlink: ShortlinkItem) {
    selectedShortlink.value = shortlink;
    showDeleteModal.value = true;
}

function handleSearch(search: string) {
    router.get(
        route('shortlinks.index'),
        { search: search || undefined },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
}

function refreshData() {
    isRefreshing.value = true;
    router.reload({
        only: ['shortlinks'],
        onFinish: () => {
            isRefreshing.value = false;
        },
    });
}

function formatExpiry(value: string | null): string {
    if (!value) return 'Tanpa batas waktu';
    const date = new Date(value);
    if (Number.isNaN(date.getTime())) return '-';
    return date.toLocaleString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
}
</script>

<template>
    <Head title="Kelola Shortlink" />
    <AuthenticatedLayout>
        <template #header>
            <div
                class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
            >
                <div>
                    <h1
                        class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl dark:text-white"
                    >
                        Kelola Shortlink
                    </h1>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Kelola kode, URL tujuan, status, dan masa berlaku
                        shortlink.
                    </p>
                </div>
                <AppButton
                    v-if="can('create_shortlinks')"
                    type="button"
                    variant="primary"
                    icon="plus"
                    @click="openForm()"
                    >Tambah Shortlink</AppButton
                >
            </div>
        </template>

        <div class="space-y-6">
            <div class="flex items-center gap-3">
                <SearchInput
                    v-model="searchQuery"
                    class="flex-1"
                    placeholder="Cari kode atau URL tujuan..."
                    @search="handleSearch"
                />
                <RefreshButton :loading="isRefreshing" @click="refreshData" />
            </div>
            <DataTable
                :columns="columns"
                :items="shortlinks.data"
                empty-title="Shortlink Tidak Ditemukan"
                empty-description="Belum ada shortlink yang sesuai dengan pencarian Anda."
            >
                <template #cell-short_code="{ item }">
                    <span
                        class="font-mono font-semibold text-slate-900 dark:text-slate-100"
                        >{{ item.short_code }}</span
                    >
                </template>
                <template #cell-destination_url="{ item }">
                    <span
                        class="block max-w-xs truncate"
                        :title="item.destination_url"
                        >{{ item.destination_url }}</span
                    >
                </template>
                <template #cell-user="{ item }">{{
                    item.user?.name ?? '-'
                }}</template>
                <template #cell-is_active="{ item }">
                    <span
                        :class="[
                            'inline-flex rounded-full border px-3 py-0.5 text-xs font-semibold',
                            item.is_active
                                ? 'border-emerald-100 bg-emerald-50 text-emerald-700 dark:border-emerald-900 dark:bg-emerald-950/50 dark:text-emerald-300'
                                : 'border-slate-200 bg-slate-100 text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300',
                        ]"
                        >{{ item.is_active ? 'Aktif' : 'Nonaktif' }}</span
                    >
                </template>
                <template #cell-expires_at="{ item }">{{
                    formatExpiry(item.expires_at)
                }}</template>
                <template #cell-actions="{ item }">
                    <div class="flex items-center justify-end gap-1.5">
                        <button
                            v-if="can('edit_shortlinks')"
                            type="button"
                            class="inline-flex h-8 w-8 cursor-pointer items-center justify-center rounded-xl text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200"
                            :aria-label="`Ubah shortlink ${item.short_code}`"
                            @click="openForm(item)"
                        >
                            <AppIcon name="edit" class-name="h-4 w-4" />
                        </button>
                        <button
                            v-if="can('delete_shortlinks')"
                            type="button"
                            class="inline-flex h-8 w-8 cursor-pointer items-center justify-center rounded-xl text-rose-500 transition-colors hover:bg-rose-50 hover:text-rose-700 dark:text-rose-400 dark:hover:bg-rose-950/50 dark:hover:text-rose-300"
                            :aria-label="`Hapus shortlink ${item.short_code}`"
                            @click="openDelete(item)"
                        >
                            <AppIcon name="trash" class-name="h-4 w-4" />
                        </button>
                    </div>
                </template>
                <template #footer
                    ><Pagination
                        :links="shortlinks.links"
                        :from="shortlinks.from"
                        :to="shortlinks.to"
                        :total="shortlinks.total"
                        item-name="shortlink"
                /></template>
            </DataTable>
        </div>

        <ShortlinkFormModal
            :show="showFormModal"
            :shortlink="selectedShortlink"
            @close="showFormModal = false"
        />
        <DeleteShortlinkModal
            :show="showDeleteModal"
            :shortlink="selectedShortlink"
            @close="showDeleteModal = false"
        />
    </AuthenticatedLayout>
</template>
