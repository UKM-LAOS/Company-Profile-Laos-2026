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
import DeleteBlogModal from './Partials/DeleteBlogModal.vue';

interface BlogItem {
    id: number;
    judul: string;
    slug: string;
    kategori: string;
    status: string;
    published_at: string | null;
    author_id: number;
    divisi_id: number;
    is_unggulan: boolean;
    author?: { id: number; name: string };
    divisi?: { id: number; nama: string };
}

const props = defineProps<{
    blogs: {
        data: BlogItem[];
        links: any[];
        from: number;
        to: number;
        total: number;
    };
    filters: {
        search?: string;
        kategori?: string;
        status?: string;
    };
    options: {
        kategori: string[];
        status: { value: string; label: string }[];
    };
}>();

const { can } = usePermission();

const searchQuery = ref(props.filters.search || '');
const selectFilters = reactive({
    kategori: props.filters.kategori || '',
    status: props.filters.status || '',
});
const isRefreshing = ref(false);

const showDeleteModal = ref(false);
const selectedBlog = ref<BlogItem | null>(null);

const columns = computed<TableColumn[]>(() => [
    { key: 'judul', label: 'JUDUL & SLUG' },
    { key: 'kategori', label: 'KATEGORI' },
    { key: 'penulis', label: 'PENULIS' },
    { key: 'tanggal', label: 'TANGGAL RILIS' },
    { key: 'status', label: 'STATUS' },
    { key: 'actions', label: 'AKSI', align: 'right' },
]);

const avatarColors = [
    'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300',
    'bg-sky-100 text-sky-700 dark:bg-sky-950/60 dark:text-sky-300',
    'bg-violet-100 text-violet-700 dark:bg-violet-950/60 dark:text-violet-300',
    'bg-amber-100 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300',
    'bg-rose-100 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300',
];

function getAvatarColor(id: number = 0): string {
    return avatarColors[id % avatarColors.length] ?? avatarColors[0]!;
}

function getInitials(name: string): string {
    return name
        .split(' ')
        .map((n) => n[0])
        .slice(0, 2)
        .join('')
        .toUpperCase();
}

const kategoriOptions = computed(() =>
    props.options.kategori.map((k) => ({
        value: k,
        label: k,
    })),
);

watch(
    () => props.filters,
    (filters) => {
        searchQuery.value = filters.search || '';
        selectFilters.kategori = filters.kategori || '';
        selectFilters.status = filters.status || '';
    },
);

function applyFilters() {
    router.get(
        route('blogs.index'),
        {
            search: searchQuery.value || undefined,
            kategori: selectFilters.kategori || undefined,
            status: selectFilters.status || undefined,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            only: ['blogs', 'filters'],
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
        only: ['blogs', 'options'],
        onFinish: () => {
            isRefreshing.value = false;
        },
    });
}

function navigateToCreate() {
    router.get(route('blogs.create'));
}

function navigateToEdit(id: number) {
    router.get(route('blogs.edit', id));
}

function openDelete(blog: BlogItem) {
    selectedBlog.value = blog;
    showDeleteModal.value = true;
}

function closeDeleteModal() {
    showDeleteModal.value = false;
    selectedBlog.value = null;
}

function formatDate(dateStr: string | null): string {
    if (!dateStr) return '-';
    const date = new Date(dateStr);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
}
</script>

<template>
    <Head title="Kelola Berita" />
    <AuthenticatedLayout>
        <template #header>
            <div
                class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
            >
                <div>
                    <h1
                        class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl dark:text-white"
                    >
                        Kelola Berita
                    </h1>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Kelola artikel publikasi, tutorial terbuka, dan
                        dokumentasi warta UKM LAOS Fasilkom UNEJ.
                    </p>
                </div>
                <AppButton
                    v-if="can('create_news')"
                    type="button"
                    variant="primary"
                    icon="plus"
                    @click="navigateToCreate"
                >
                    Tambah Berita
                </AppButton>
            </div>
        </template>

        <div class="space-y-6">
            <div
                class="flex flex-col gap-3 rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs lg:flex-row lg:items-center dark:border-slate-800/80 dark:bg-slate-900"
            >
                <SearchInput
                    v-model="searchQuery"
                    class="flex-1"
                    placeholder="Cari berdasarkan judul berita, kategori, penulis..."
                    @search="handleSearch"
                />
                <div
                    class="grid grid-cols-1 gap-2.5 sm:grid-cols-2 lg:flex lg:items-center"
                >
                    <AppDropdownFilter
                        id="filter-kategori"
                        v-model="selectFilters.kategori"
                        placeholder="Semua Kategori"
                        aria-label="Filter kategori"
                        :options="kategoriOptions"
                        :searchable="true"
                        @change="applyFilters"
                    />
                    <AppDropdownFilter
                        id="filter-status"
                        v-model="selectFilters.status"
                        placeholder="Semua Status"
                        aria-label="Filter status"
                        :options="options.status"
                        @change="applyFilters"
                    />
                </div>
                <RefreshButton :loading="isRefreshing" @click="refreshData" />
            </div>

            <DataTable
                :columns="columns"
                :items="blogs.data"
                empty-title="Berita Tidak Ditemukan"
                empty-description="Belum ada berita yang sesuai dengan pencarian atau filter Anda."
            >
                <!-- JUDUL & SLUG -->
                <template #cell-judul="{ item }">
                    <div class="w-48 sm:w-64 md:w-72 lg:w-80">
                        <p
                            class="truncate text-sm font-semibold text-slate-900 dark:text-white"
                            :title="item.judul"
                        >
                            {{ item.judul }}
                            <span
                                v-if="item.is_unggulan"
                                title="Artikel Unggulan"
                                class="ml-1 text-amber-500"
                                >★</span
                            >
                        </p>
                        <p
                            class="mt-0.5 truncate font-mono text-[11px] text-slate-400 dark:text-slate-500"
                            :title="'/blog/' + item.slug"
                        >
                            /blog/{{ item.slug }}
                        </p>
                    </div>
                </template>

                <!-- KATEGORI -->
                <template #cell-kategori="{ item }">
                    <span
                        class="inline-flex rounded-full border border-slate-200 bg-slate-100 px-2.5 py-0.5 text-[11px] font-semibold text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300"
                    >
                        {{ item.kategori }}
                    </span>
                </template>

                <!-- PENULIS -->
                <template #cell-penulis="{ item }">
                    <div class="flex items-center gap-2.5">
                        <span
                            :class="[
                                'flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-xs font-bold ring-1 ring-white/50 dark:ring-slate-900/50',
                                getAvatarColor(item.author_id),
                            ]"
                            aria-hidden="true"
                        >
                            {{ getInitials(item.author?.name || 'LAOS') }}
                        </span>
                        <div class="min-w-0 flex-1">
                            <p
                                class="truncate text-sm font-semibold text-slate-900 dark:text-white"
                            >
                                {{ item.author?.name || 'Anonim' }}
                            </p>
                            <p
                                class="mt-0.5 truncate text-[10px] tracking-wider text-slate-400 uppercase dark:text-slate-500"
                            >
                                {{ item.divisi?.nama || 'Pengurus Inti' }}
                            </p>
                        </div>
                    </div>
                </template>

                <!-- TANGGAL RILIS -->
                <template #cell-tanggal="{ item }">
                    <span class="text-sm text-slate-600 dark:text-slate-300">
                        {{ formatDate(item.published_at) }}
                    </span>
                </template>

                <!-- STATUS -->
                <template #cell-status="{ item }">
                    <span
                        :class="[
                            'inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-[11px] font-semibold',
                            item.status === 'published'
                                ? 'border-emerald-100 bg-emerald-50 text-emerald-700 dark:border-emerald-900 dark:bg-emerald-950/50 dark:text-emerald-300'
                                : 'border-slate-200 bg-slate-100 text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300',
                        ]"
                    >
                        <span
                            :class="[
                                'h-1.5 w-1.5 rounded-full',
                                item.status === 'published'
                                    ? 'bg-emerald-500'
                                    : 'bg-slate-400',
                            ]"
                        />
                        {{ item.status === 'published' ? 'Terbit' : 'Draf' }}
                    </span>
                </template>

                <!-- AKSI -->
                <template #cell-actions="{ item }">
                    <div class="flex items-center justify-end gap-1.5">
                        <button
                            v-if="can('edit_news')"
                            type="button"
                            class="inline-flex h-8 w-8 cursor-pointer items-center justify-center rounded-xl text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200"
                            :aria-label="`Edit berita ${item.judul}`"
                            @click="navigateToEdit(item.id)"
                        >
                            <AppIcon name="edit" class-name="h-4 w-4" />
                        </button>
                        <button
                            v-if="can('delete_news')"
                            type="button"
                            class="inline-flex h-8 w-8 cursor-pointer items-center justify-center rounded-xl text-rose-500 transition-colors hover:bg-rose-50 hover:text-rose-700 dark:text-rose-400 dark:hover:bg-rose-950/50 dark:hover:text-rose-300"
                            :aria-label="`Hapus berita ${item.judul}`"
                            @click="openDelete(item)"
                        >
                            <AppIcon name="trash" class-name="h-4 w-4" />
                        </button>
                    </div>
                </template>

                <!-- FOOTER / PAGINATION -->
                <template #footer>
                    <Pagination
                        :links="blogs.links"
                        :from="blogs.from"
                        :to="blogs.to"
                        :total="blogs.total"
                        item-name="berita"
                    />
                </template>
            </DataTable>
        </div>

        <DeleteBlogModal
            :show="showDeleteModal"
            :blog="selectedBlog"
            @close="closeDeleteModal"
        />
    </AuthenticatedLayout>
</template>
