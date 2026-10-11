<script setup lang="ts">
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import PublicNavbar from '@/Components/Navbar/PublicNavbar.vue';
import PublicFooter from '@/Components/Footer/PublicFooter.vue';
import Pagination, { type PaginationLink } from '@/Components/Common/Pagination.vue';
import AppIcon from '@/Components/AppIcon.vue';

export interface BlogItem {
    id: number;
    judul: string;
    slug: string;
    kategori: string;
    konten?: string;
    meta_description?: string | null;
    is_unggulan: boolean;
    status: string;
    published_at: string | null;
    tanggal_rilis_formatted: string;
    views: number;
    thumbnail_url: string;
    ringkasan: string;
    author?: { id: number; name: string } | null;
    divisi?: { id: number; nama: string } | null;
}

interface PaginatedBlogs {
    data: BlogItem[];
    links: PaginationLink[];
    from: number | null;
    to: number | null;
    total: number;
    current_page: number;
    last_page: number;
}

const props = defineProps<{
    blogs: PaginatedBlogs;
    filters: {
        search: string;
        kategori: string;
    };
    kategoriOptions: string[];
    heroBlogs: BlogItem[];
    divisis?: Array<any>;
}>();

const search = ref(props.filters.search ?? '');
const selectedKategori = ref(props.filters.kategori ?? '');
const activeHeroIndex = ref(0);

// Hero slide navigation
function setHeroIndex(index: number) {
    activeHeroIndex.value = index;
}

// Filter handling
let searchTimeout: ReturnType<typeof setTimeout> | null = null;

function applyFilters() {
    router.get(
        route('blog.index'),
        {
            search: search.value.trim() || undefined,
            kategori: selectedKategori.value || undefined,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
}

watch(search, () => {
    if (searchTimeout) clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        applyFilters();
    }, 350);
});

watch(selectedKategori, () => {
    applyFilters();
});

function resetFilters() {
    search.value = '';
    selectedKategori.value = '';
    applyFilters();
}
</script>

<template>
    <Head title="Blog & Berita - UKM LAOS">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <div class="min-h-screen font-sans text-gray-900 bg-white flex flex-col selection:bg-emerald-500 selection:text-white">
        <PublicNavbar />

        <main class="flex-1">
            <!-- Hero Section -->
            <section class="relative bg-slate-900 text-white overflow-hidden min-h-[380px] lg:min-h-[460px] flex items-center">
                <!-- Background Image & Overlay -->
                <div class="absolute inset-0 z-0">
                    <img
                        src="/assets/blog-hero-bg.jpg"
                        alt="UKM LAOS Blog Hero"
                        class="w-full h-full object-cover object-center brightness-60 scale-105 filter blur-[0.5px]"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/70 to-slate-900/40"></div>
                </div>

                <!-- Hero Content Container -->
                <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 w-full">
                    <div class="max-w-3xl">
                        <!-- Pill or Label -->
                        <span class="inline-block text-emerald-400 font-medium text-sm sm:text-base tracking-wide mb-3">
                            Artikel terbaru
                        </span>

                        <!-- Main Heading -->
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-tight mb-4">
                            Cerita, Inovasi, dan Aksi dari UKM LAOS
                        </h1>

                        <!-- Subtitle Description -->
                        <p class="text-gray-200 text-sm sm:text-base lg:text-lg leading-relaxed max-w-2xl mb-8">
                            Ikuti perjalanan inspiratif, program kreatif, dan kisah penggerak perubahan dari berbagai kegiatan UKM LAOS. Temukan insight, tips, dan berita terbaru yang membangun semangat sosial di kalangan generasi muda.
                        </p>
                    </div>

                    <!-- Hero Carousel Dots (visual matching mockup) -->
                    <div class="flex items-center gap-2 mt-6">
                        <button
                            v-for="(dot, idx) in 5"
                            :key="idx"
                            type="button"
                            @click="setHeroIndex(idx)"
                            class="h-2 rounded-full transition-all duration-300"
                            :class="activeHeroIndex === idx ? 'w-8 bg-white' : 'w-2 bg-white/40 hover:bg-white/70'"
                            :title="`Slide ${idx + 1}`"
                        ></button>
                    </div>
                </div>
            </section>

            <!-- Main Content Area -->
            <section class="py-16 sm:py-20 lg:py-24 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Section Header -->
                    <div class="text-center mb-10 sm:mb-14">
                        <div class="inline-flex items-center px-6 py-1.5 rounded-full border border-gray-300 bg-white text-xs font-bold tracking-widest text-gray-700 uppercase mb-4 shadow-2xs">
                            BERITA
                        </div>
                        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-gray-900 tracking-tight">
                            Temukan Berita Terbaru dari UKM LAOS
                        </h2>
                    </div>

                    <!-- Search & Filter Bar -->
                    <div class="max-w-4xl mx-auto mb-12 sm:mb-16">
                        <div class="flex flex-col sm:flex-row items-center gap-3 bg-white p-2 sm:p-2.5 rounded-2xl border border-gray-200 shadow-xs focus-within:border-emerald-500 focus-within:ring-2 focus-within:ring-emerald-500/20 transition-all duration-200">
                            <!-- Search Input -->
                            <div class="relative flex-1 w-full flex items-center">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input
                                    v-model="search"
                                    type="text"
                                    placeholder="Cari artikel, topik, atau penulis..."
                                    class="w-full pl-10 pr-4 py-2.5 bg-transparent border-none text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-0"
                                />
                                <button
                                    v-if="search"
                                    @click="search = ''"
                                    type="button"
                                    class="mr-2 text-gray-400 hover:text-gray-600 transition"
                                    title="Bersihkan pencarian"
                                >
                                    <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Category Filter Dropdown -->
                            <div class="w-full sm:w-56 shrink-0 border-t sm:border-t-0 sm:border-l border-gray-100 pt-2 sm:pt-0 sm:pl-3">
                                <select
                                    v-model="selectedKategori"
                                    class="w-full py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-gray-700 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 cursor-pointer"
                                >
                                    <option value="">Semua Kategori</option>
                                    <option
                                        v-for="kat in kategoriOptions"
                                        :key="kat"
                                        :value="kat"
                                    >
                                        {{ kat }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Articles Grid -->
                    <div
                        v-if="blogs.data && blogs.data.length > 0"
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8"
                    >
                        <article
                            v-for="blog in blogs.data"
                            :key="blog.id"
                            class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden"
                        >
                            <Link :href="route('blog.show', blog.slug)" class="block flex-1 flex flex-col">
                                <!-- Card Thumbnail Image -->
                                <div class="relative aspect-[16/10] overflow-hidden bg-slate-100">
                                    <img
                                        :src="blog.thumbnail_url"
                                        :alt="blog.judul"
                                        loading="lazy"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                    />
                                    <!-- Category Pill Badge over image -->
                                    <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-xs text-emerald-800 text-xs font-semibold px-2.5 py-1 rounded-md shadow-xs">
                                        {{ blog.kategori }}
                                    </span>
                                </div>

                                <!-- Card Content -->
                                <div class="p-5 sm:p-6 flex-1 flex flex-col justify-between">
                                    <div>
                                        <!-- Title -->
                                        <h3 class="text-base sm:text-lg font-bold text-gray-900 group-hover:text-emerald-600 transition-colors line-clamp-2 mb-2.5 leading-snug">
                                            {{ blog.judul }}
                                        </h3>

                                        <!-- Summary / Excerpt -->
                                        <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed mb-4">
                                            {{ blog.ringkasan }}
                                        </p>
                                    </div>

                                    <!-- Card Meta Footer -->
                                    <div class="pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-400">
                                        <span class="font-normal">
                                            Waktu Post: {{ blog.tanggal_rilis_formatted }}
                                        </span>
                                        <span class="text-emerald-600 font-medium group-hover:translate-x-0.5 transition-transform flex items-center gap-1">
                                            Baca
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            </Link>
                        </article>
                    </div>

                    <!-- Empty State -->
                    <div
                        v-else
                        class="text-center py-20 bg-slate-50/60 rounded-3xl border border-dashed border-gray-200 max-w-2xl mx-auto"
                    >
                        <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4 text-emerald-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">Tidak ada artikel ditemukan</h3>
                        <p class="text-gray-500 text-sm max-w-md mx-auto mb-6">
                            Belum ada artikel publik yang sesuai dengan kata kunci atau kategori yang Anda pilih.
                        </p>
                        <button
                            @click="resetFilters"
                            type="button"
                            class="inline-flex items-center px-5 py-2.5 rounded-full text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 transition shadow-sm"
                        >
                            Reset Filter
                        </button>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-12 sm:mt-16">
                        <Pagination
                            :links="blogs.links"
                            :from="blogs.from"
                            :to="blogs.to"
                            :total="blogs.total"
                            item-name="tautan terdaftar"
                            variant="circular"
                        />
                    </div>
                </div>
            </section>
        </main>

        <PublicFooter :divisis="divisis" />
    </div>
</template>
