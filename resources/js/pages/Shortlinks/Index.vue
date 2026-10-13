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
import { computed, onMounted, ref, watch } from 'vue';
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

const origin = ref('');
const copiedId = ref<number | null>(null);

onMounted(() => {
    origin.value = window.location.origin;
});

function getFullUrl(code: string): string {
    const base = origin.value || '';
    return `${base}/${code}`;
}

function copyToClipboard(shortlink: ShortlinkItem) {
    const fullUrl = getFullUrl(shortlink.short_code);
    void navigator.clipboard.writeText(fullUrl).then(() => {
        copiedId.value = shortlink.id;
        setTimeout(() => {
            if (copiedId.value === shortlink.id) {
                copiedId.value = null;
            }
        }, 2000);
    });
}

const columns = computed<TableColumn[]>(() => [
    { key: 'short_code', label: 'TAUTAN SHORTLINK' },
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
                    <div class="flex items-center gap-2">
                        <a
                            :href="getFullUrl(item.short_code)"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="group inline-flex items-center gap-1 font-mono text-xs font-semibold text-emerald-600 transition hover:text-emerald-700 hover:underline dark:text-emerald-400 dark:hover:text-emerald-300"
                            :title="`Buka tautan: ${getFullUrl(item.short_code)}`"
                        >
                            <span>{{ getFullUrl(item.short_code) }}</span>
                            <svg
                                class="h-3.5 w-3.5 shrink-0 opacity-60 transition-opacity group-hover:opacity-100"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path
                                    d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"
                                ></path>
                                <polyline points="15 3 21 3 21 9"></polyline>
                                <line x1="10" y1="14" x2="21" y2="3"></line>
                            </svg>
                        </a>

                        <!-- Tombol Salin Tautan -->
                        <button
                            type="button"
                            class="inline-flex shrink-0 cursor-pointer items-center gap-1 rounded-lg border px-2 py-1 text-[11px] font-medium shadow-2xs transition"
                            :class="
                                copiedId === item.id
                                    ? 'border-emerald-500 bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300'
                                    : 'border-slate-200 bg-slate-50 text-slate-600 hover:border-slate-300 hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700'
                            "
                            :title="
                                copiedId === item.id
                                    ? 'Tautan berhasil disalin!'
                                    : 'Salin URL lengkap ke clipboard'
                            "
                            @click="copyToClipboard(item)"
                        >
                            <template v-if="copiedId === item.id">
                                <svg
                                    class="h-3.5 w-3.5 text-emerald-600 dark:text-emerald-400"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <polyline
                                        points="20 6 9 17 4 12"
                                    ></polyline>
                                </svg>
                                <span
                                    class="font-semibold text-emerald-600 dark:text-emerald-400"
                                    >Tersalin!</span
                                >
                            </template>
                            <template v-else>
                                <svg
                                    class="h-3.5 w-3.5 text-slate-500 dark:text-slate-400"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <rect
                                        x="9"
                                        y="9"
                                        width="13"
                                        height="13"
                                        rx="2"
                                        ry="2"
                                    ></rect>
                                    <path
                                        d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"
                                    ></path>
                                </svg>
                                <span>Salin</span>
                            </template>
                        </button>
                    </div>
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
