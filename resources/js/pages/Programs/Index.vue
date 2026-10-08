<script setup lang="ts">
import AppButton from '@/Components/AppButton.vue';
import AppDropdownFilter from '@/Components/AppDropdownFilter.vue';
import AppIcon from '@/Components/AppIcon.vue';
import Pagination from '@/Components/Common/Pagination.vue';
import RefreshButton from '@/Components/Common/RefreshButton.vue';
import SearchInput from '@/Components/Common/SearchInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { usePermission } from '@/lib/usePermission';
import { Head, router } from '@inertiajs/vue3';
import { computed, reactive, ref, watch } from 'vue';
import DeleteProgramModal from './Partials/DeleteProgramModal.vue';
import ProgramDetailModal from './Partials/ProgramDetailModal.vue';
import ProgramFormModal from './Partials/ProgramFormModal.vue';
import {
    type DivisiOption,
    type ExecutionStatus,
    type PaginatedPrograms,
    type PengurusOption,
    type ProgramFilters,
    type ProgramItem,
    type ProgramPIC,
    type ProgramStats,
} from './types';

const props = defineProps<{
    programs: PaginatedPrograms;
    divisis: DivisiOption[];
    penguruses: PengurusOption[];
    filters: ProgramFilters;
    stats: ProgramStats;
}>();

const { can } = usePermission();

const searchQuery = ref(props.filters.search || '');
const selectFilters = reactive({
    divisi_id: props.filters.divisi_id || '',
    status: props.filters.status || '',
});

const isRefreshing = ref(false);
const showFormModal = ref(false);
const showDetailModal = ref(false);
const showDeleteModal = ref(false);
const selectedProgram = ref<ProgramItem | null>(null);

const statusOptions = [
    { value: 'mendatang', label: 'Mendatang' },
    { value: 'berjalan', label: 'Berjalan' },
    { value: 'selesai', label: 'Selesai' },
];

const divisiOptions = computed(() =>
    props.divisis.map((d) => ({
        value: String(d.id),
        label: d.nama,
    })),
);

watch(
    () => props.filters,
    (filters) => {
        searchQuery.value = filters.search || '';
        selectFilters.divisi_id = filters.divisi_id || '';
        selectFilters.status = filters.status || '';
    },
);

function applyFilters() {
    router.get(
        route('programs.index'),
        {
            search: searchQuery.value || undefined,
            divisi_id: selectFilters.divisi_id || undefined,
            status: selectFilters.status || undefined,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            only: ['programs', 'filters', 'stats'],
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
        only: ['programs', 'divisis', 'penguruses', 'stats', 'filters'],
        onFinish: () => {
            isRefreshing.value = false;
        },
    });
}

function openCreateModal() {
    selectedProgram.value = null;
    showFormModal.value = true;
}

function openEditModal(program: ProgramItem) {
    selectedProgram.value = program;
    showFormModal.value = true;
}

function openDetailModal(program: ProgramItem) {
    selectedProgram.value = program;
    showDetailModal.value = true;
}

function openDeleteModal(program: ProgramItem) {
    selectedProgram.value = program;
    showDeleteModal.value = true;
}

const MONTH_NAMES_ID = [
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
];

const MONTH_NAMES_SHORT_ID = [
    'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
    'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des',
];

function formatTargetWaktu(startStr?: string | null, endStr?: string | null): string {
    if (!startStr && !endStr) return '-';
    if (startStr && (!endStr || startStr === endStr)) {
        try {
            const d = new Date(startStr);
            if (isNaN(d.getTime())) return startStr;
            const day = String(d.getDate()).padStart(2, '0');
            const month = MONTH_NAMES_ID[d.getMonth()];
            const year = d.getFullYear();
            return `${day} ${month} ${year}`;
        } catch {
            return startStr;
        }
    }

    if (startStr && endStr) {
        try {
            const dStart = new Date(startStr);
            const dEnd = new Date(endStr);
            if (isNaN(dStart.getTime()) || isNaN(dEnd.getTime())) return `${startStr} - ${endStr}`;

            const startDay = String(dStart.getDate()).padStart(2, '0');
            const endDay = String(dEnd.getDate()).padStart(2, '0');
            const startYear = dStart.getFullYear();
            const endYear = dEnd.getFullYear();

            // Same month and same year
            if (dStart.getMonth() === dEnd.getMonth() && startYear === endYear) {
                const month = MONTH_NAMES_ID[dStart.getMonth()];
                return `${startDay} - ${endDay} ${month} ${startYear}`;
            }

            // Different month, same year
            if (startYear === endYear) {
                const startMonth = MONTH_NAMES_SHORT_ID[dStart.getMonth()];
                const endMonth = MONTH_NAMES_SHORT_ID[dEnd.getMonth()];
                return `${startDay} ${startMonth} - ${endDay} ${endMonth} ${startYear}`;
            }

            return `${startDay} ${MONTH_NAMES_SHORT_ID[dStart.getMonth()]} ${startYear} - ${endDay} ${MONTH_NAMES_SHORT_ID[dEnd.getMonth()]} ${endYear}`;
        } catch {
            return `${startStr} - ${endStr}`;
        }
    }

    return endStr || '-';
}

const PIC_PALETTES = [
    'bg-[#065f46] text-white',
    'bg-[#1e293b] text-white',
    'bg-[#10b981] text-white',
    'bg-[#334155] text-white',
    'bg-[#0f766e] text-white',
    'bg-[#3b82f6] text-white',
];

function getProgramPIC(program: ProgramItem): ProgramPIC {
    if (program.pengurus) {
        const words = program.pengurus.nama.split(' ');
        let initials = words.length > 1 ? (words[0][0] + words[1][0]) : words[0].substring(0, 2);
        initials = initials.toUpperCase();
        
        const idx = Math.abs(program.pengurus.id) % PIC_PALETTES.length;
        
        return {
            nama: program.pengurus.nama,
            jabatan: program.pengurus.jabatan,
            initials: initials,
            avatarBg: PIC_PALETTES[idx],
        };
    }

    return {
        nama: '-',
        jabatan: '-',
        initials: '?',
        avatarBg: 'bg-slate-200 text-slate-500',
    };
}

function getProgramExecutionStatus(program: ProgramItem): ExecutionStatus {
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    const parseDateAtMidnight = (dateStr?: string | null): Date | null => {
        if (!dateStr) return null;
        const parts = dateStr.slice(0, 10).split('-').map(Number);
        if (parts.length === 3 && !isNaN(parts[0]) && !isNaN(parts[1]) && !isNaN(parts[2])) {
            return new Date(parts[0], parts[1] - 1, parts[2]);
        }
        const d = new Date(dateStr);
        return isNaN(d.getTime()) ? null : new Date(d.getFullYear(), d.getMonth(), d.getDate());
    };

    const parseDateAtEndOfDay = (dateStr?: string | null): Date | null => {
        if (!dateStr) return null;
        const parts = dateStr.slice(0, 10).split('-').map(Number);
        if (parts.length === 3 && !isNaN(parts[0]) && !isNaN(parts[1]) && !isNaN(parts[2])) {
            return new Date(parts[0], parts[1] - 1, parts[2], 23, 59, 59, 999);
        }
        const d = new Date(dateStr);
        return isNaN(d.getTime()) ? null : new Date(d.getFullYear(), d.getMonth(), d.getDate(), 23, 59, 59, 999);
    };

    const startDate = parseDateAtMidnight(program.open_regis_peserta);
    const endDate = parseDateAtEndOfDay(program.close_regis_peserta);

    if (startDate && endDate) {
        if (today < startDate) {
            return {
                key: 'mendatang',
                label: 'Mendatang',
                progress: 0,
                badgeClass: 'bg-slate-100 text-slate-600 border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700',
                dotClass: 'bg-slate-400',
                textClass: 'text-slate-500 dark:text-slate-400 font-semibold',
                barClass: 'bg-slate-300 dark:bg-slate-600',
            };
        }
        if (today > endDate) {
            return {
                key: 'selesai',
                label: 'Selesai',
                progress: 100,
                badgeClass: 'bg-[#006D37]/10 text-[#006D37] border-[#006D37]/30 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800',
                dotClass: 'bg-[#006D37]',
                textClass: 'text-[#006D37] dark:text-emerald-400 font-bold',
                barClass: 'bg-[#006D37] dark:bg-emerald-500',
            };
        }

        const totalDuration = endDate.getTime() - startDate.getTime();
        const elapsed = today.getTime() - startDate.getTime();
        let calcProgress = 50;
        if (totalDuration > 0) {
            const ratio = Math.round((elapsed / totalDuration) * 100);
            calcProgress = Math.min(95, Math.max(10, ratio));
        }

        return {
            key: 'berjalan',
            label: 'Berjalan',
            progress: calcProgress,
            badgeClass: 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-800',
            dotClass: 'bg-emerald-500 animate-pulse',
            textClass: 'text-[#10b981] font-semibold',
            barClass: 'bg-[#10b981]',
        };
    }

    if (program.status_peserta === 'closed') {
        return {
            key: 'selesai',
            label: 'Selesai',
            progress: 100,
            badgeClass: 'bg-[#006D37]/10 text-[#006D37] border-[#006D37]/30 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800',
            dotClass: 'bg-[#006D37]',
            textClass: 'text-[#006D37] dark:text-emerald-400 font-bold',
            barClass: 'bg-[#006D37] dark:bg-emerald-500',
        };
    }
    if (program.status_peserta === 'open') {
        return {
            key: 'berjalan',
            label: 'Berjalan',
            progress: 50,
            badgeClass: 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-800',
            dotClass: 'bg-emerald-500 animate-pulse',
            textClass: 'text-[#10b981] font-semibold',
            barClass: 'bg-[#10b981]',
        };
    }

    return {
        key: 'mendatang',
        label: 'Mendatang',
        progress: 0,
        badgeClass: 'bg-slate-100 text-slate-600 border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700',
        dotClass: 'bg-slate-400',
        textClass: 'text-slate-500 dark:text-slate-400 font-semibold',
        barClass: 'bg-slate-300 dark:bg-slate-600',
    };
}

function getDivisiBadgeClass(divisiNama?: string): string {
    const name = (divisiNama || '').toLowerCase();
    if (name.includes('security') || name.includes('cyber')) {
        return 'bg-[#d1fae5] text-[#065f46] border border-[#a7f3d0]/60 dark:bg-emerald-950/70 dark:text-emerald-300 dark:border-emerald-800';
    }
    if (name.includes('multimedia') || name.includes('design')) {
        return 'bg-[#dbeafe] text-[#1e40af] border border-[#bfdbfe]/60 dark:bg-blue-950/70 dark:text-blue-300 dark:border-blue-800';
    }
    if (name.includes('humas') || name.includes('keorganisasian')) {
        return 'bg-[#d1e7dd] text-[#0f5132] border border-[#a3cfbb]/60 dark:bg-teal-950/70 dark:text-teal-300 dark:border-teal-800';
    }
    if (name.includes('hrm') || name.includes('litbang') || name.includes('bph')) {
        return 'bg-[#d4edda] text-[#155724] border border-[#c3e6cb]/60 dark:bg-emerald-950/70 dark:text-emerald-300 dark:border-emerald-800';
    }
    if (name.includes('web')) {
        return 'bg-[#e0e7ff] text-[#3730a3] border border-[#c7d2fe]/60 dark:bg-indigo-950/70 dark:text-indigo-300 dark:border-indigo-800';
    }
    if (name.includes('mobile') || name.includes('iot')) {
        return 'bg-[#fef3c7] text-[#92400e] border border-[#fde68a]/60 dark:bg-amber-950/70 dark:text-amber-300 dark:border-amber-800';
    }
    return 'bg-slate-100 text-slate-700 border border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700';
}

function exportToCsv() {
    const headers = [
        'Program Kerja',
        'Deskripsi',
        'Divisi',
        'Target Waktu',
        'PIC',
        'Jabatan PIC',
        'Status',
        'Progres (%)',
    ];

    const rows = props.programs.data.map((item) => {
        const pic = getProgramPIC(item);
        const status = getProgramExecutionStatus(item);
        const targetWaktu = formatTargetWaktu(
            item.open_regis_peserta,
            item.close_regis_peserta,
        );
        return [
            `"${item.judul_program.replace(/"/g, '""')}"`,
            `"${(item.deskripsi || '').replace(/"/g, '""')}"`,
            `"${(item.divisi?.nama || '').replace(/"/g, '""')}"`,
            `"${targetWaktu}"`,
            `"${pic.nama.replace(/"/g, '""')}"`,
            `"${pic.jabatan.replace(/"/g, '""')}"`,
            `"${status.label}"`,
            `"${status.progress}"`,
        ].join(',');
    });

    const csvContent =
        'data:text/csv;charset=utf-8,\uFEFF' +
        [headers.join(','), ...rows].join('\n');
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement('a');
    link.setAttribute('href', encodedUri);
    link.setAttribute(
        'download',
        `program_kerja_laos_${new Date().toISOString().slice(0, 10)}.csv`,
    );
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>

<template>
    <Head title="Kelola Program Kerja" />

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
                        Kelola Program Kerja
                    </h1>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Kelola agenda kegiatan, timeline pelaksanaan, PIC divisi, serta status program kerja UKM LAOS.
                    </p>
                </div>

                <AppButton
                    v-if="can('create_work_programs')"
                    id="btn-tambah-proker"
                    type="button"
                    variant="primary"
                    icon="plus"
                    @click="openCreateModal"
                >
                    Tambah Proker
                </AppButton>
            </div>
        </template>

        <div class="space-y-6">
            <!-- Filter & Controls Bar -->
            <div
                class="flex flex-col gap-3 rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs lg:flex-row lg:items-center dark:border-slate-800/80 dark:bg-slate-900"
            >
                <SearchInput
                    v-model="searchQuery"
                    class="flex-1"
                    placeholder="Cari program kerja, divisi, PIC..."
                    @search="handleSearch"
                />

                <div
                    class="grid grid-cols-1 gap-2.5 sm:grid-cols-2 lg:flex lg:items-center"
                >
                    <AppDropdownFilter
                        id="filter-divisi"
                        v-model="selectFilters.divisi_id"
                        placeholder="Semua Divisi"
                        aria-label="Filter divisi"
                        :options="divisiOptions"
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

                <div class="flex items-center gap-2">
                    <AppButton
                        id="btn-ekspor-data"
                        type="button"
                        variant="secondary"
                        rounded="xl"
                        icon="download"
                        @click="exportToCsv"
                    >
                        Ekspor Data
                    </AppButton>

                    <RefreshButton :loading="isRefreshing" @click="refreshData" />
                </div>
            </div>

            <!-- Program Kerja Table Card -->
            <div
                class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs dark:border-slate-800/80 dark:bg-slate-900"
            >
                <div class="scrollbar-thin overflow-x-auto">
                    <table class="w-full min-w-[1020px] border-collapse text-left text-sm">
                        <thead>
                            <tr
                                class="border-b border-slate-100 bg-[#f8fafc]/90 text-[11px] font-bold tracking-wider text-slate-400 uppercase dark:border-slate-800 dark:bg-slate-800/60 dark:text-slate-400"
                            >
                                <th class="py-4 pr-4 pl-6 min-w-[340px] lg:w-[38%] lg:min-w-[420px]">PROGRAM KERJA</th>
                                <th class="px-4 py-4 min-w-[150px]">DIVISI</th>
                                <th class="px-4 py-4 min-w-[170px]">TARGET WAKTU</th>
                                <th class="px-4 py-4 min-w-[200px]">PIC / PENANGGUNG JAWAB</th>
                                <th class="px-4 py-4 min-w-[180px]">STATUS & PROGRES</th>
                                <th class="py-4 pr-6 pl-4 text-right min-w-[80px]">AKSI</th>
                            </tr>
                        </thead>
                        <tbody
                            class="divide-y divide-slate-100 dark:divide-slate-800/80"
                        >
                            <tr
                                v-for="item in programs.data"
                                :key="item.id"
                                class="transition hover:bg-slate-50/60 dark:hover:bg-slate-800/40"
                            >
                                <!-- Program Kerja Column -->
                                <td class="py-4.5 pr-4 pl-6 align-top">
                                    <div class="flex items-start gap-3.5 min-w-[300px] max-w-xl">
                                        <div
                                            v-if="item.foto_url"
                                            class="shrink-0 h-14 w-14 overflow-hidden rounded-xl border border-slate-200/60 shadow-xs dark:border-slate-700"
                                        >
                                            <img
                                                :src="item.foto_url"
                                                :alt="item.judul_program"
                                                class="h-full w-full object-cover"
                                            />
                                        </div>
                                        <div
                                            v-else
                                            class="shrink-0 flex h-14 w-14 items-center justify-center rounded-xl border border-slate-200/60 bg-slate-50 dark:border-slate-800 dark:bg-slate-900/50"
                                        >
                                            <AppIcon name="proker" class-name="h-6 w-6 text-slate-300 dark:text-slate-600" />
                                        </div>
                                        
                                        <div class="flex-1">
                                            <button
                                                type="button"
                                                @click="openDetailModal(item)"
                                                class="block text-left text-sm font-bold text-slate-900 transition hover:text-emerald-600 dark:text-white dark:hover:text-emerald-400"
                                            >
                                                {{ item.judul_program }}
                                            </button>
                                            <p
                                                class="mt-1 line-clamp-2 text-xs leading-relaxed text-slate-500 dark:text-slate-400"
                                            >
                                                {{ item.deskripsi || 'Belum ada deskripsi program kerja.' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Divisi Column -->
                                <td class="px-4 py-4.5 align-top whitespace-nowrap">
                                    <span
                                        :class="[
                                            'inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold',
                                            getDivisiBadgeClass(item.divisi?.nama),
                                        ]"
                                    >
                                        {{ item.divisi?.nama || 'Umum' }}
                                    </span>
                                </td>

                                <!-- Target Waktu Column -->
                                <td
                                    class="px-4 py-4.5 align-top text-xs text-slate-600 whitespace-nowrap dark:text-slate-300"
                                >
                                    <div class="flex items-center gap-2">
                                        <AppIcon
                                            name="calendar"
                                            class-name="h-4 w-4 text-slate-400 shrink-0"
                                        />
                                        <span class="font-medium text-slate-700 dark:text-slate-300">
                                            {{
                                                formatTargetWaktu(
                                                    item.open_regis_peserta,
                                                    item.close_regis_peserta,
                                                )
                                            }}
                                        </span>
                                    </div>
                                </td>

                                <!-- PIC / Penanggung Jawab Column -->
                                <td class="px-4 py-4.5 align-top whitespace-nowrap">
                                    <div class="flex items-center gap-2.5">
                                        <div
                                            :class="[
                                                'flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-xs font-bold ring-2 ring-white dark:ring-slate-900',
                                                getProgramPIC(item).avatarBg,
                                            ]"
                                        >
                                            {{ getProgramPIC(item).initials }}
                                        </div>
                                        <div>
                                            <div
                                                class="text-xs font-semibold text-slate-800 dark:text-slate-200"
                                            >
                                                {{ getProgramPIC(item).nama }}
                                            </div>
                                            <div
                                                class="text-[11px] text-slate-400"
                                            >
                                                {{ getProgramPIC(item).jabatan }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Status & Progres Column -->
                                <td class="px-4 py-4.5 align-top whitespace-nowrap">
                                    <div class="space-y-1.5">
                                        <div
                                            :class="[
                                                'text-xs',
                                                getProgramExecutionStatus(item).textClass,
                                            ]"
                                        >
                                            {{ getProgramExecutionStatus(item).label }}
                                        </div>

                                        <div
                                            class="flex items-center gap-2 text-[11px] text-slate-500"
                                        >
                                            <div
                                                class="h-1.5 w-20 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800"
                                            >
                                                <div
                                                    :class="[
                                                        'h-full rounded-full',
                                                        getProgramExecutionStatus(item).barClass,
                                                    ]"
                                                    :style="{
                                                        width: `${getProgramExecutionStatus(item).progress}%`,
                                                    }"
                                                />
                                            </div>
                                            <span
                                                class="font-medium text-slate-600 dark:text-slate-400"
                                            >
                                                {{
                                                    getProgramExecutionStatus(item)
                                                        .progress
                                                }}%
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Aksi Column -->
                                <td
                                    class="py-4.5 pr-6 pl-4 text-right align-top whitespace-nowrap"
                                >
                                    <div
                                        class="flex items-center justify-end gap-1"
                                    >
                                        <!-- Detail Button -->
                                        <button
                                            type="button"
                                            class="inline-flex h-8 w-8 cursor-pointer items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800 dark:hover:text-slate-200"
                                            title="Lihat Detail"
                                            @click="openDetailModal(item)"
                                        >
                                            <AppIcon
                                                name="eye"
                                                class-name="h-4 w-4"
                                            />
                                        </button>

                                        <!-- Edit Button -->
                                        <button
                                            v-if="can('edit_work_programs')"
                                            type="button"
                                            class="inline-flex h-8 w-8 cursor-pointer items-center justify-center rounded-lg text-slate-400 transition hover:bg-emerald-50 hover:text-emerald-600 dark:hover:bg-emerald-950/60 dark:hover:text-emerald-400"
                                            title="Edit Program Kerja"
                                            @click="openEditModal(item)"
                                        >
                                            <AppIcon
                                                name="edit"
                                                class-name="h-4 w-4"
                                            />
                                        </button>

                                        <!-- Delete Button -->
                                        <button
                                            v-if="can('delete_work_programs')"
                                            type="button"
                                            class="inline-flex h-8 w-8 cursor-pointer items-center justify-center rounded-lg text-slate-400 transition hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-950/60 dark:hover:text-rose-400"
                                            title="Hapus Program Kerja"
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
                                v-if="!programs.data || programs.data.length === 0"
                            >
                                <td colspan="6" class="py-12 text-center">
                                    <div
                                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 dark:bg-slate-800"
                                    >
                                        <AppIcon
                                            name="proker"
                                            class-name="h-6 w-6"
                                        />
                                    </div>
                                    <h3
                                        class="mt-3 text-sm font-semibold text-slate-800 dark:text-slate-200"
                                    >
                                        Belum ada program kerja
                                    </h3>
                                    <p
                                        class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                                    >
                                        Program kerja yang sesuai filter akan
                                        ditampilkan di sini.
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Footer Pagination -->
                <Pagination
                    v-if="programs.links && programs.links.length > 3"
                    :links="programs.links"
                    :from="programs.from"
                    :to="programs.to"
                    :total="programs.total"
                    item-name="program kerja"
                    variant="circular"
                />
                <div
                    v-else-if="programs.total > 0"
                    class="flex items-center justify-between border-t border-slate-100 px-6 py-4 text-xs text-slate-500 dark:border-slate-800 dark:text-slate-400"
                >
                    <span>
                        Menampilkan
                        <strong
                            class="font-bold text-slate-800 dark:text-slate-200"
                        >
                            {{ programs.from ?? 1 }}-{{ programs.to ?? programs.total }}
                        </strong>
                        dari
                        <strong
                            class="font-bold text-slate-800 dark:text-slate-200"
                        >
                            {{ programs.total }}
                        </strong>
                        program kerja
                    </span>
                </div>
            </div>
        </div>

        <!-- Modals -->
        <ProgramFormModal
            :show="showFormModal"
            :program="selectedProgram"
            :divisis="divisis"
            :penguruses="penguruses"
            @close="showFormModal = false"
        />

        <ProgramDetailModal
            :show="showDetailModal"
            :program="selectedProgram"
            @close="showDetailModal = false"
            @edit="openEditModal"
        />

        <DeleteProgramModal
            :show="showDeleteModal"
            :program="selectedProgram"
            @close="showDeleteModal = false"
        />
    </AuthenticatedLayout>
</template>
