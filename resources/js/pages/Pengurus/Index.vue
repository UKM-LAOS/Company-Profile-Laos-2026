<script setup lang="ts">
import AppButton from '@/Components/AppButton.vue';
import AppDropdownFilter from '@/Components/AppDropdownFilter.vue';
import AppIcon from '@/Components/AppIcon.vue';
import DataTable, { type TableColumn } from '@/Components/Common/DataTable.vue';
import Pagination from '@/Components/Common/Pagination.vue';
import RefreshButton from '@/Components/Common/RefreshButton.vue';
import SearchInput from '@/Components/Common/SearchInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { usePermission } from '@/lib/usePermission';
import { Head, router } from '@inertiajs/vue3';
import { computed, reactive, ref, watch } from 'vue';
import DeletePengurusModal from './Partials/DeletePengurusModal.vue';
import PengurusDetailModal from './Partials/PengurusDetailModal.vue';
import PengurusFormModal from './Partials/PengurusFormModal.vue';
import {
    initials,
    SOSMED_KEYS,
    SOSMED_LABELS,
    type PaginatedPengurus,
    type PengurusFilters,
    type PengurusItem,
    type PengurusOptions,
} from './types';

const props = defineProps<{
    penguruses: PaginatedPengurus;
    filters: PengurusFilters;
    options: PengurusOptions;
}>();

const { can } = usePermission();
const searchQuery = ref(props.filters.search || '');
const selectFilters = reactive({
    periode: props.filters.periode || '',
    jabatan: props.filters.jabatan || '',
    status: props.filters.status || '',
});
const isRefreshing = ref(false);
const showFormModal = ref(false);
const showDetailModal = ref(false);
const showDeleteModal = ref(false);
const selectedPengurus = ref<PengurusItem | null>(null);

const columns = computed<TableColumn[]>(() => [
    { key: 'nama', label: 'PENGURUS' },
    { key: 'jabatan', label: 'JABATAN & PERIODE' },
    { key: 'urutan', label: 'URUTAN', align: 'center' },
    { key: 'aktif', label: 'STATUS' },
    { key: 'actions', label: 'AKSI', align: 'right' },
]);

const avatarColors = [
    'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300',
    'bg-sky-100 text-sky-700 dark:bg-sky-950/60 dark:text-sky-300',
    'bg-violet-100 text-violet-700 dark:bg-violet-950/60 dark:text-violet-300',
    'bg-amber-100 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300',
    'bg-rose-100 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300',
];

const statusOptions = [
    { value: 'aktif', label: 'Aktif' },
    { value: 'nonaktif', label: 'Nonaktif' },
];

const periodeOptions = computed(() =>
    props.options.periode.map((p) => ({
        value: p,
        label: `Periode ${p}`,
    })),
);

watch(
    () => props.filters,
    (filters) => {
        searchQuery.value = filters.search || '';
        selectFilters.periode = filters.periode || '';
        selectFilters.jabatan = filters.jabatan || '';
        selectFilters.status = filters.status || '';
    },
);

function avatarColor(id: number): string {
    return avatarColors[id % avatarColors.length] ?? avatarColors[0]!;
}

function isKetua(jabatan: string): boolean {
    return jabatan.toLowerCase().includes('ketua');
}

function applyFilters() {
    router.get(
        route('pengurus.index'),
        {
            search: searchQuery.value || undefined,
            periode: selectFilters.periode || undefined,
            jabatan: selectFilters.jabatan || undefined,
            status: selectFilters.status || undefined,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            only: ['penguruses', 'filters'],
        },
    );
}

function handleSearch(search: string) {
    searchQuery.value = search;
    applyFilters();
}

function refreshData() {
    isRefreshing.value = true;
    router.reload({
        only: ['penguruses', 'options'],
        onFinish: () => {
            isRefreshing.value = false;
        },
    });
}

function openForm(pengurus: PengurusItem | null = null) {
    selectedPengurus.value = pengurus;
    showFormModal.value = true;
}

function closeFormModal() {
    showFormModal.value = false;
    selectedPengurus.value = null;
}

function openDetail(pengurus: PengurusItem) {
    selectedPengurus.value = pengurus;
    showDetailModal.value = true;
}

function closeDetailModal() {
    showDetailModal.value = false;
    selectedPengurus.value = null;
}

function openDelete(pengurus: PengurusItem) {
    selectedPengurus.value = pengurus;
    showDeleteModal.value = true;
}

function closeDeleteModal() {
    showDeleteModal.value = false;
    selectedPengurus.value = null;
}
</script>

<template>
    <Head title="Kelola Pengurus" />
    <AuthenticatedLayout>
        <template #header>
            <div
                class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
            >
                <div>
                    <h1
                        class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl dark:text-white"
                    >
                        Kelola Pengurus
                    </h1>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Struktur kepengurusan UKM LAOS Fasilkom UNEJ yang
                        ditampilkan di halaman Tentang Kami.
                    </p>
                </div>
                <AppButton
                    v-if="can('create_committee')"
                    type="button"
                    variant="primary"
                    icon="plus"
                    @click="openForm()"
                    >Tambah Pengurus</AppButton
                >
            </div>
        </template>

        <div class="space-y-6">
            <div
                class="flex flex-col gap-3 rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs lg:flex-row lg:items-center dark:border-slate-800/80 dark:bg-slate-900"
            >
                <SearchInput
                    v-model="searchQuery"
                    class="flex-1"
                    placeholder="Cari nama atau jabatan pengurus..."
                    @search="handleSearch"
                />
                <div
                    class="grid grid-cols-1 gap-2.5 sm:grid-cols-3 lg:flex lg:items-center"
                >
                    <AppDropdownFilter
                        id="filter-periode"
                        v-model="selectFilters.periode"
                        placeholder="Semua Periode"
                        aria-label="Filter periode"
                        :options="periodeOptions"
                        @change="applyFilters"
                    />
                    <AppDropdownFilter
                        id="filter-jabatan"
                        v-model="selectFilters.jabatan"
                        placeholder="Semua Jabatan"
                        aria-label="Filter jabatan"
                        :options="options.jabatan"
                        :searchable="true"
                        @change="applyFilters"
                    />
                    <AppDropdownFilter
                        id="filter-status"
                        v-model="selectFilters.status"
                        placeholder="Semua Status"
                        aria-label="Filter status"
                        :options="statusOptions"
                        @change="applyFilters"
                    />
                </div>
                <RefreshButton :loading="isRefreshing" @click="refreshData" />
            </div>

            <DataTable
                :columns="columns"
                :items="penguruses.data"
                empty-title="Pengurus Tidak Ditemukan"
                empty-description="Belum ada pengurus yang sesuai dengan pencarian atau filter Anda."
            >
                <template #cell-nama="{ item }">
                    <div class="flex items-center gap-3">
                        <img
                            v-if="item.foto_url"
                            :src="item.foto_url"
                            :alt="`Foto ${item.nama}`"
                            class="h-10 w-10 shrink-0 rounded-full object-cover ring-2 ring-white dark:ring-slate-900"
                            loading="lazy"
                        />
                        <span
                            v-else
                            :class="[
                                'flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-sm font-bold',
                                avatarColor(item.id),
                            ]"
                            aria-hidden="true"
                            >{{ initials(item.nama) }}</span
                        >
                        <div class="min-w-0">
                            <p
                                class="truncate text-sm font-semibold text-slate-900 dark:text-white"
                            >
                                {{ item.nama }}
                            </p>
                            <p
                                v-if="item.sosmed"
                                class="mt-0.5 truncate text-[11px] text-slate-400 dark:text-slate-500"
                            >
                                {{
                                    SOSMED_KEYS.filter(
                                        (key) => item.sosmed?.[key],
                                    )
                                        .map((key) => SOSMED_LABELS[key])
                                        .join(' · ')
                                }}
                            </p>
                        </div>
                    </div>
                </template>
                <template #cell-jabatan="{ item }">
                    <span
                        :class="[
                            'inline-flex rounded-full px-2.5 py-0.5 text-[11px] font-semibold',
                            isKetua(item.jabatan)
                                ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300'
                                : 'bg-sky-50 text-sky-700 dark:bg-sky-950/50 dark:text-sky-300',
                        ]"
                        >{{ item.jabatan }}</span
                    >
                    <p
                        class="mt-1 text-[11px] text-slate-400 dark:text-slate-500"
                    >
                        Periode {{ item.periode }}
                    </p>
                </template>
                <template #cell-urutan="{ item }">
                    <span
                        class="font-mono font-semibold text-slate-700 dark:text-slate-200"
                        >{{ item.urutan }}</span
                    >
                </template>
                <template #cell-aktif="{ item }">
                    <span
                        :class="[
                            'inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-[11px] font-semibold',
                            item.aktif
                                ? 'border-emerald-100 bg-emerald-50 text-emerald-700 dark:border-emerald-900 dark:bg-emerald-950/50 dark:text-emerald-300'
                                : 'border-slate-200 bg-slate-100 text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300',
                        ]"
                    >
                        <span
                            :class="[
                                'h-1.5 w-1.5 rounded-full',
                                item.aktif ? 'bg-emerald-500' : 'bg-slate-400',
                            ]"
                        />
                        {{ item.aktif ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </template>
                <template #cell-actions="{ item }">
                    <div class="flex items-center justify-end gap-1.5">
                        <button
                            type="button"
                            class="inline-flex h-8 w-8 cursor-pointer items-center justify-center rounded-xl text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200"
                            :aria-label="`Lihat detail ${item.nama}`"
                            @click="openDetail(item)"
                        >
                            <AppIcon name="eye" class-name="h-4 w-4" />
                        </button>
                        <button
                            v-if="can('edit_committee')"
                            type="button"
                            class="inline-flex h-8 w-8 cursor-pointer items-center justify-center rounded-xl text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200"
                            :aria-label="`Ubah pengurus ${item.nama}`"
                            @click="openForm(item)"
                        >
                            <AppIcon name="edit" class-name="h-4 w-4" />
                        </button>
                        <button
                            v-if="can('delete_committee')"
                            type="button"
                            class="inline-flex h-8 w-8 cursor-pointer items-center justify-center rounded-xl text-rose-500 transition-colors hover:bg-rose-50 hover:text-rose-700 dark:text-rose-400 dark:hover:bg-rose-950/50 dark:hover:text-rose-300"
                            :aria-label="`Hapus pengurus ${item.nama}`"
                            @click="openDelete(item)"
                        >
                            <AppIcon name="trash" class-name="h-4 w-4" />
                        </button>
                    </div>
                </template>
                <template #footer
                    ><Pagination
                        :links="penguruses.links"
                        :from="penguruses.from"
                        :to="penguruses.to"
                        :total="penguruses.total"
                        item-name="pengurus"
                /></template>
            </DataTable>
        </div>

        <PengurusFormModal
            :show="showFormModal"
            :pengurus="selectedPengurus"
            @close="closeFormModal"
        />
        <PengurusDetailModal
            :show="showDetailModal"
            :pengurus="selectedPengurus"
            @close="closeDetailModal"
        />
        <DeletePengurusModal
            :show="showDeleteModal"
            :pengurus="selectedPengurus"
            @close="closeDeleteModal"
        />
    </AuthenticatedLayout>
</template>
