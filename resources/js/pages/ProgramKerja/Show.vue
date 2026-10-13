<script setup lang="ts">
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppImage from '@/Components/Common/AppImage.vue';
import PublicNavbar from '@/Components/Navbar/PublicNavbar.vue';
import PublicFooter from '@/Components/Footer/PublicFooter.vue';
import type { ProgramItem } from './types';

const props = defineProps<{
    program: ProgramItem;
    relatedPrograms?: ProgramItem[];
    divisis?: Array<any>;
}>();

const defaultFallbackImage =
    'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=1200&q=80';

const displayImage = computed(() => {
    return props.program.foto_url || defaultFallbackImage;
});

function handleImageError(event: Event) {
    const target = event.target as HTMLImageElement;
    if (target && target.src !== defaultFallbackImage) {
        target.src = defaultFallbackImage;
    }
}

function formatDateIndo(dateStr: string | null | undefined): string {
    if (!dateStr) return '-';
    try {
        const date = new Date(dateStr);
        if (isNaN(date.getTime())) return dateStr;
        return new Intl.DateTimeFormat('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
        }).format(date);
    } catch {
        return dateStr;
    }
}

// Compute whether registration is currently active
const isRegistrationOpen = computed(() => {
    if (!props.program.open_regis_peserta || !props.program.close_regis_peserta)
        return false;
    const now = new Date();
    const open = new Date(props.program.open_regis_peserta);
    const close = new Date(props.program.close_regis_peserta);
    close.setHours(23, 59, 59, 999);
    return now >= open && now <= close;
});
</script>

<template>
    <Head :title="`${program.judul_program} - UKM LAOS`" />

    <div
        class="flex min-h-screen flex-col bg-white font-sans text-gray-900 transition-colors duration-300 selection:bg-emerald-500 selection:text-white dark:bg-slate-950 dark:text-slate-100"
    >
        <PublicNavbar />

        <main class="flex-1">
            <!-- Hero / Header Banner Section -->
            <section
                v-reveal
                class="relative bg-gradient-to-b from-[#EEFAF4] via-[#EEFAF4]/60 to-white pt-28 pb-12 transition-colors duration-300 sm:pt-32 sm:pb-16 lg:pt-36 lg:pb-20 dark:from-[#06170F] dark:via-[#092216] dark:to-slate-950"
            >
                <div
                    class="relative z-10 mx-auto max-w-5xl px-5 sm:px-8 lg:px-10"
                >
                    <!-- Breadcrumbs -->
                    <nav
                        class="mb-6 flex items-center gap-2 text-xs font-medium text-[#636363] sm:text-sm dark:text-slate-400"
                    >
                        <Link
                            :href="route('home')"
                            class="transition hover:text-[#2DCC70]"
                            >Beranda</Link
                        >
                        <span>/</span>
                        <Link
                            :href="route('program-kerja.index')"
                            class="transition hover:text-[#2DCC70]"
                            >Program Kerja</Link
                        >
                        <span>/</span>
                        <span
                            class="max-w-[200px] truncate font-semibold text-emerald-700 sm:max-w-md dark:text-emerald-400"
                        >
                            {{ program.judul_program }}
                        </span>
                    </nav>

                    <!-- Division & Status Badges -->
                    <div
                        class="mb-4 flex flex-wrap items-center gap-2.5 sm:mb-5"
                    >
                        <span
                            v-if="program.divisi"
                            class="inline-flex items-center gap-1.5 rounded-full border border-[#2DCC70]/30 bg-[#2DCC70]/15 px-3.5 py-1 text-xs font-semibold text-[#2DCC70] sm:text-sm"
                        >
                            {{ program.divisi.nama }}
                        </span>

                        <span
                            v-if="isRegistrationOpen"
                            class="inline-flex items-center gap-1 rounded-full border border-emerald-500/20 bg-emerald-500/10 px-3 py-1 text-xs font-semibold text-emerald-600 dark:text-emerald-400"
                        >
                            <span
                                class="h-1.5 w-1.5 animate-pulse rounded-full bg-emerald-500"
                            ></span>
                            Pendaftaran Dibuka
                        </span>
                    </div>

                    <!-- Program Title -->
                    <h1
                        class="mb-6 text-3xl leading-[1.2] font-extrabold tracking-tight text-[#1E1E1E] sm:text-4xl lg:text-5xl dark:text-white"
                    >
                        {{ program.judul_program }}
                    </h1>

                    <!-- Key Metadata Row -->
                    <div
                        class="flex flex-wrap items-center gap-x-6 gap-y-3 text-xs text-[#636363] sm:text-sm dark:text-slate-300"
                    >
                        <span
                            v-if="
                                program.location_name &&
                                program.location_name !== 'NUL'
                            "
                            class="flex items-center gap-2 font-medium"
                        >
                            <svg
                                class="h-4 w-4 text-[#2DCC70]"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                />
                            </svg>
                            {{ program.location_name }}
                        </span>

                        <span
                            v-if="program.pengurus"
                            class="flex items-center gap-2 font-medium"
                        >
                            <svg
                                class="h-4 w-4 text-[#2DCC70]"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                />
                            </svg>
                            PJ: {{ program.pengurus.nama }}
                        </span>

                        <span
                            v-if="program.open_regis_peserta"
                            class="flex items-center gap-2 font-medium"
                        >
                            <svg
                                class="h-4 w-4 text-[#2DCC70]"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                />
                            </svg>
                            {{ formatDateIndo(program.open_regis_peserta) }}
                        </span>
                    </div>
                </div>
            </section>

            <!-- Main Detail Body Section -->
            <section
                v-reveal
                class="bg-white py-10 transition-colors duration-300 sm:py-14 lg:py-16 dark:bg-slate-950"
            >
                <div class="mx-auto max-w-5xl px-5 sm:px-8 lg:px-10">
                    <!-- Photo Container with Elegant Frame -->
                    <div
                        class="relative mb-10 aspect-[16/9] w-full overflow-hidden rounded-2xl border border-slate-100 bg-slate-900 shadow-lg sm:mb-12 sm:rounded-3xl dark:border-slate-800"
                    >
                        <AppImage
                            :src="displayImage"
                            :alt="program.judul_program"
                            :fallback-src="defaultFallbackImage"
                            loading="eager"
                            wrapper-class="w-full h-full"
                        />
                    </div>

                    <!-- Grid Info Cards (Timeline, PJ, Location) -->
                    <div
                        class="mb-12 grid grid-cols-1 gap-4 sm:gap-6 md:grid-cols-3"
                    >
                        <!-- Periode Peserta -->
                        <div
                            class="rounded-2xl border border-slate-100 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-900"
                        >
                            <div
                                class="mb-2 flex items-center gap-2.5 text-sm font-bold text-[#2DCC70]"
                            >
                                <svg
                                    class="h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                                <span>Pendaftaran Peserta</span>
                            </div>
                            <p
                                class="text-sm font-semibold text-[#1E1E1E] sm:text-base dark:text-white"
                            >
                                {{ formatDateIndo(program.open_regis_peserta) }}
                            </p>
                            <p
                                class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                            >
                                s/d
                                {{
                                    formatDateIndo(program.close_regis_peserta)
                                }}
                            </p>
                        </div>

                        <!-- Lokasi Kegiatan -->
                        <div
                            class="rounded-2xl border border-slate-100 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-900"
                        >
                            <div
                                class="mb-2 flex items-center gap-2.5 text-sm font-bold text-[#2DCC70]"
                            >
                                <svg
                                    class="h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                    />
                                </svg>
                                <span>Tempat / Lokasi</span>
                            </div>
                            <p
                                class="text-sm font-semibold text-[#1E1E1E] sm:text-base dark:text-white"
                            >
                                {{
                                    program.location_name &&
                                    program.location_name !== 'NUL'
                                        ? program.location_name
                                        : 'Fakultas Ilmu Komputer UNEJ'
                                }}
                            </p>
                            <p
                                class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                            >
                                Universitas Jember
                            </p>
                        </div>

                        <!-- Penanggung Jawab -->
                        <div
                            class="rounded-2xl border border-slate-100 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-900"
                        >
                            <div
                                class="mb-2 flex items-center gap-2.5 text-sm font-bold text-[#2DCC70]"
                            >
                                <svg
                                    class="h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                                <span>Penanggung Jawab</span>
                            </div>
                            <p
                                class="text-sm font-semibold text-[#1E1E1E] sm:text-base dark:text-white"
                            >
                                {{
                                    program.pengurus?.nama ||
                                    'Pengurus UKM LAOS'
                                }}
                            </p>
                            <p
                                class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                            >
                                {{ program.divisi?.nama || 'Organisasi' }}
                            </p>
                        </div>
                    </div>

                    <!-- Full Description -->
                    <div class="mb-14">
                        <h2
                            class="mb-4 text-xl font-bold text-[#1E1E1E] sm:text-2xl dark:text-white"
                        >
                            Tentang Kegiatan Ini
                        </h2>
                        <div
                            class="prose dark:prose-invert max-w-none text-base leading-relaxed whitespace-pre-line text-[#52525B] sm:text-lg dark:text-slate-300"
                        >
                            {{
                                program.deskripsi ||
                                'Forum mingguan untuk diskusi, koordinasi program, sharing ide, dan evaluasi kegiatan. Tujuan: Meningkatkan komunikasi, kekompakan, dan update progress tim.'
                            }}
                        </div>
                    </div>

                    <!-- Link Pendaftaran (Simple & Minimalist) -->
                    <div
                        v-if="program.gform_peserta || program.gform_panitia"
                        class="mb-12 flex flex-wrap items-center gap-3.5"
                    >
                        <a
                            v-if="program.gform_peserta"
                            :href="program.gform_peserta"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex cursor-pointer items-center gap-2 rounded-full bg-[#2DCC70] px-6 py-2.5 text-sm font-semibold text-white shadow-xs transition-all duration-200 hover:bg-[#25B562] active:bg-[#1D9A50]"
                        >
                            <span>Daftar Peserta</span>
                            <svg
                                class="h-4 w-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                                />
                            </svg>
                        </a>
                        <a
                            v-if="program.gform_panitia"
                            :href="program.gform_panitia"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex cursor-pointer items-center gap-2 rounded-full border border-gray-200 bg-white px-6 py-2.5 text-sm font-semibold text-gray-700 transition-all duration-200 hover:border-[#2DCC70] hover:text-[#2DCC70] dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300"
                        >
                            <span>Daftar Panitia</span>
                            <svg
                                class="h-4 w-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                                />
                            </svg>
                        </a>
                    </div>

                    <!-- Navigation Footer / Back to Program List -->
                    <div
                        class="flex items-center justify-between border-t border-slate-200 pt-8 dark:border-slate-800"
                    >
                        <Link
                            :href="route('program-kerja.index')"
                            class="group inline-flex items-center gap-2 text-sm font-semibold text-emerald-600 transition hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300"
                        >
                            <svg
                                class="h-4 w-4 transition-transform group-hover:-translate-x-1"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2.5"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"
                                />
                            </svg>
                            <span>Kembali ke Semua Program Kerja</span>
                        </Link>
                    </div>

                    <!-- Related Other Programs -->
                    <div
                        v-if="relatedPrograms && relatedPrograms.length > 0"
                        class="mt-20 border-t border-slate-100 pt-12 dark:border-slate-800"
                    >
                        <h3
                            class="mb-8 text-2xl font-bold text-[#1E1E1E] dark:text-white"
                        >
                            Program Kerja Lainnya
                        </h3>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                            <article
                                v-for="related in relatedPrograms"
                                :key="related.id"
                                class="group flex flex-col overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-xs transition-all duration-300 hover:shadow-md dark:border-slate-800 dark:bg-slate-900"
                            >
                                <Link
                                    :href="
                                        route(
                                            'program-kerja.show',
                                            related.slug || related.id,
                                        )
                                    "
                                    class="block flex flex-1 flex-col"
                                >
                                    <div
                                        class="aspect-[16/10] overflow-hidden bg-slate-900"
                                    >
                                        <AppImage
                                            :src="
                                                related.foto_url ||
                                                defaultFallbackImage
                                            "
                                            :alt="related.judul_program"
                                            :fallback-src="defaultFallbackImage"
                                            loading="lazy"
                                            wrapper-class="w-full h-full"
                                            image-class="transition-transform duration-500 group-hover:scale-105"
                                        />
                                    </div>
                                    <div
                                        class="flex flex-1 flex-col justify-between p-5"
                                    >
                                        <div>
                                            <span
                                                v-if="related.divisi"
                                                class="text-xs font-semibold text-[#2DCC70] uppercase"
                                            >
                                                {{ related.divisi.nama }}
                                            </span>
                                            <h4
                                                class="mt-1.5 mb-2 line-clamp-2 text-lg font-bold text-[#1E1E1E] transition-colors group-hover:text-[#2DCC70] sm:text-xl dark:text-white"
                                            >
                                                {{ related.judul_program }}
                                            </h4>
                                            <p
                                                class="line-clamp-2 text-sm leading-relaxed text-[#636363] sm:text-[14px] dark:text-slate-400"
                                            >
                                                {{ related.deskripsi }}
                                            </p>
                                        </div>
                                        <div
                                            class="mt-4 flex items-center gap-1 border-t border-slate-100 pt-3 text-xs font-semibold text-emerald-600 transition-transform group-hover:translate-x-1 dark:border-slate-800 dark:text-emerald-400"
                                        >
                                            <span>Lihat Detail</span>
                                            <svg
                                                class="h-3.5 w-3.5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 5l7 7-7 7"
                                                />
                                            </svg>
                                        </div>
                                    </div>
                                </Link>
                            </article>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <PublicFooter :divisis="divisis" />
    </div>
</template>
