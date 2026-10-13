<script setup lang="ts">
import StatCard from '@/Components/Dashboard/StatCard.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { usePermission } from '@/lib/usePermission';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

interface RecentItem {
    id: number | string;
    title: string;
    badge: string;
    url?: string;
}

const props = defineProps<{
    counts?: Record<string, number>;
    recent?: Record<string, RecentItem[]>;
}>();

const { user, isSuperAdmin, can } = usePermission();

const greetingTitle = computed(() => {
    if (isSuperAdmin.value) {
        return 'Selamat datang, Super Administrator';
    }
    if (user.value?.name) {
        return `Selamat datang, ${user.value.name}`;
    }
    return 'Selamat datang di UKM LAOS';
});

// Metrik Stat Card Atas (Menggunakan komponen StatCard bawaan)
const statCards = computed(() => {
    const cards = [];

    if (can('view_work_programs')) {
        cards.push({
            title: 'PROGRAM KERJA',
            value: props.counts?.programs ?? 0,
            icon: 'proker' as const,
            variant: 'emerald' as const,
            route: route('programs.index'),
        });
    }

    if (can('view_news')) {
        cards.push({
            title: 'BERITA & ARTIKEL',
            value: props.counts?.blogs ?? 0,
            icon: 'berita' as const,
            variant: 'blue' as const,
            route: route('blogs.index'),
        });
    }

    if (can('view_committee')) {
        cards.push({
            title: 'TOTAL PENGURUS',
            value: props.counts?.pengurus ?? 0,
            icon: 'pengurus' as const,
            variant: 'emerald' as const,
            route: route('pengurus.index'),
        });
    }

    if (can('view_divisions')) {
        cards.push({
            title: 'DIVISI ORGANISASI',
            value: props.counts?.divisis ?? 0,
            icon: 'divisi' as const,
            variant: 'amber' as const,
            route: route('divisis.index'),
        });
    }

    if (can('view_shortlinks')) {
        cards.push({
            title: 'TAUTAN SHORTLINK',
            value: props.counts?.shortlinks ?? 0,
            icon: 'shortlink' as const,
            variant: 'purple' as const,
            route: route('shortlinks.index'),
        });
    }

    if (can('view_users')) {
        cards.push({
            title: 'TOTAL PENGGUNA',
            value: props.counts?.users ?? 0,
            icon: 'pengguna' as const,
            variant: 'blue' as const,
            route: route('users.index'),
        });
    }

    return cards;
});

// Widget Ringkasan Menu / Modul CMS
const modules = computed(() => {
    const list = [];

    // 1. Program Kerja
    if (can('view_work_programs')) {
        list.push({
            key: 'programs',
            title: 'Program Kerja Terbaru',
            viewUrl: route('programs.index'),
            canCreate: can('create_work_programs'),
            createUrl: route('programs.index'),
            emptyText: 'Belum ada data program kerja yang tersimpan.',
            items: props.recent?.programs ?? [],
        });
    }

    // 2. Berita & Artikel
    if (can('view_news')) {
        list.push({
            key: 'blogs',
            title: 'Berita & Artikel Terbaru',
            viewUrl: route('blogs.index'),
            canCreate: can('create_news'),
            createUrl: route('blogs.create'),
            emptyText: 'Belum ada artikel berita yang tersimpan.',
            items: props.recent?.blogs ?? [],
        });
    }

    // 3. Pengurus
    if (can('view_committee')) {
        list.push({
            key: 'pengurus',
            title: 'Pengurus Terbaru',
            viewUrl: route('pengurus.index'),
            canCreate: can('create_committee'),
            createUrl: route('pengurus.index'),
            emptyText: 'Belum ada data pengurus yang tersimpan.',
            items: props.recent?.pengurus ?? [],
        });
    }

    // 4. Divisi Organisasi
    if (can('view_divisions')) {
        list.push({
            key: 'divisis',
            title: 'Divisi Organisasi',
            viewUrl: route('divisis.index'),
            canCreate: can('create_divisions'),
            createUrl: route('divisis.index'),
            emptyText: 'Belum ada divisi yang tersimpan.',
            items: props.recent?.divisis ?? [],
        });
    }

    // 5. Shortlink
    if (can('view_shortlinks')) {
        list.push({
            key: 'shortlinks',
            title: 'Tautan Pendek (Shortlink)',
            viewUrl: route('shortlinks.index'),
            canCreate: can('create_shortlinks'),
            createUrl: route('shortlinks.index'),
            emptyText: 'Belum ada tautan singkat yang tersimpan.',
            items: props.recent?.shortlinks ?? [],
        });
    }

    // 6. Pengguna Sistem
    if (can('view_users')) {
        list.push({
            key: 'users',
            title: 'Pengguna Sistem',
            viewUrl: route('users.index'),
            canCreate: can('create_users'),
            createUrl: route('users.index'),
            emptyText: 'Belum ada data pengguna yang tersimpan.',
            items: props.recent?.users ?? [],
        });
    }

    return list;
});
</script>

<template>
    <Head title="Dashboard - UKM LAOS Fasilkom UNEJ" />

    <AuthenticatedLayout>
        <div class="space-y-8">
            <!-- Header Ucapan & Deskripsi -->
            <div>
                <h1
                    class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl dark:text-white"
                >
                    {{ greetingTitle }}
                </h1>
                <p
                    class="mt-1.5 text-sm text-slate-500 sm:text-base dark:text-slate-400"
                >
                    Kelola konten, kegiatan, dan seluruh modul organisasi UKM
                    LAOS dari sini.
                </p>
            </div>

            <!-- Bagian Stat Metric Cards Grid (3 di atas, 3 di bawah: 3 3) -->
            <div
                v-if="statCards.length > 0"
                class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3"
            >
                <Link
                    v-for="card in statCards"
                    :key="card.title"
                    :href="card.route"
                    class="transition hover:-translate-y-1"
                >
                    <StatCard
                        :title="card.title"
                        :value="card.value"
                        :icon="card.icon"
                        :variant="card.variant"
                    />
                </Link>
            </div>

            <!-- Bagian Widget Ringkasan Tabel CMS (Layout 2 Kolom Grid) -->
            <div
                v-if="modules.length > 0"
                class="grid grid-cols-1 gap-6 lg:grid-cols-2"
            >
                <div
                    v-for="widget in modules"
                    :key="widget.key"
                    class="flex flex-col justify-between rounded-2xl border border-slate-200/90 bg-white p-6 shadow-xs transition hover:shadow-md dark:border-slate-800 dark:bg-slate-900"
                >
                    <!-- Header Card Ringkasan -->
                    <div
                        class="flex items-center justify-between border-b border-slate-100 pb-4 dark:border-slate-800"
                    >
                        <Link
                            :href="widget.viewUrl"
                            class="group flex items-center gap-1.5 text-lg font-bold text-slate-900 transition hover:text-[#2DCC70] dark:text-white dark:hover:text-[#2DCC70]"
                        >
                            <span>{{ widget.title }}</span>
                            <span
                                class="text-slate-400 transition-transform group-hover:translate-x-1 dark:text-slate-500"
                                aria-hidden="true"
                            >
                                &gt;
                            </span>
                        </Link>

                        <!-- Tombol Tambah jika user memiliki permission create -->
                        <Link
                            v-if="widget.canCreate"
                            :href="widget.createUrl"
                            class="inline-flex items-center gap-1 rounded-lg bg-[#2DCC70] px-3.5 py-1.5 text-xs font-semibold text-white shadow-xs transition hover:bg-[#25B562] active:bg-[#1D9A50]"
                        >
                            + Tambah
                        </Link>
                    </div>

                    <!-- Isi Baris List Ringkasan (Tabel Mini) -->
                    <div
                        class="flex-1 divide-y divide-slate-100 dark:divide-slate-800"
                    >
                        <div
                            v-for="(item, idx) in widget.items"
                            :key="item.id || idx"
                            class="flex items-center justify-between gap-4 rounded-lg px-1.5 py-3.5 transition hover:bg-slate-50 dark:hover:bg-slate-800/40"
                        >
                            <Link
                                :href="item.url || widget.viewUrl"
                                class="max-w-[65%] truncate text-sm font-medium text-slate-800 transition hover:text-[#2DCC70] hover:underline sm:max-w-[70%] dark:text-slate-200 dark:hover:text-[#2DCC70]"
                            >
                                {{ item.title }}
                            </Link>
                            <span
                                class="shrink-0 rounded-full border border-slate-200 bg-slate-50 px-2.5 py-0.5 text-xs font-medium text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300"
                            >
                                {{ item.badge }}
                            </span>
                        </div>

                        <!-- Keadaan Kosong (Empty State) -->
                        <div
                            v-if="widget.items.length === 0"
                            class="py-12 text-center text-sm text-slate-400 dark:text-slate-500"
                        >
                            {{ widget.emptyText }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jika Akun belum memiliki izin modul apapun -->
            <div
                v-if="modules.length === 0"
                class="rounded-2xl border border-slate-200 bg-white p-12 text-center dark:border-slate-800 dark:bg-slate-900"
            >
                <p
                    class="text-base font-semibold text-slate-900 dark:text-white"
                >
                    Belum ada modul yang dapat diakses
                </p>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                    Akun Anda belum memiliki izin hak akses untuk melihat modul
                    CMS. Silakan hubungi Administrator.
                </p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
