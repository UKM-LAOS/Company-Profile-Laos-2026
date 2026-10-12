<script setup lang="ts">
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';
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
    thumbnail?: string | null;
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

// Carousel Logic untuk Header Pinned Blogs
const defaultHeroBlog: BlogItem = {
    id: 0,
    judul: 'Cerita, Inovasi, dan Aksi dari UKM LAOS',
    slug: '',
    kategori: 'Berita Terbaru',
    is_unggulan: true,
    status: 'published',
    published_at: null,
    tanggal_rilis_formatted: '',
    views: 0,
    thumbnail_url: '/assets/blog-hero-bg.jpg',
    ringkasan:
        'Ikuti perjalanan inspiratif, program kreatif, dan kisah penggerak perubahan dari berbagai kegiatan UKM LAOS. Temukan insight, tips, dan berita terbaru yang membangun semangat sosial di kalangan generasi muda.',
};

const slides = computed<BlogItem[]>(() => {
    if (props.heroBlogs && props.heroBlogs.length > 0) {
        return props.heroBlogs;
    }
    return [defaultHeroBlog];
});

const currentSlide = ref(0);
const isPaused = ref(false);
let autoSlideTimer: ReturnType<typeof setInterval> | null = null;

let touchStartX = 0;
let touchEndX = 0;

const handleTouchStart = (e: TouchEvent) => {
    touchStartX = e.changedTouches[0].screenX;
};

const handleTouchEnd = (e: TouchEvent) => {
    touchEndX = e.changedTouches[0].screenX;
    if (touchStartX - touchEndX > 50) {
        nextSlide();
    } else if (touchEndX - touchStartX > 50) {
        prevSlide();
    }
};

const nextSlide = () => {
    if (slides.value.length <= 1) return;
    currentSlide.value = (currentSlide.value + 1) % slides.value.length;
};

const prevSlide = () => {
    if (slides.value.length <= 1) return;
    currentSlide.value =
        (currentSlide.value - 1 + slides.value.length) % slides.value.length;
};

const goToSlide = (idx: number) => {
    currentSlide.value = idx;
};

const startAutoSlide = () => {
    stopAutoSlide();
    autoSlideTimer = setInterval(() => {
        if (!isPaused.value && slides.value.length > 1) {
            nextSlide();
        }
    }, 5000);
};

const stopAutoSlide = () => {
    if (autoSlideTimer) {
        clearInterval(autoSlideTimer);
        autoSlideTimer = null;
    }
};

onMounted(() => {
    startAutoSlide();
});

onUnmounted(() => {
    stopAutoSlide();
});
</script>

<template>
    <Head title="Blog & Berita - UKM LAOS">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <div class="min-h-screen font-sans text-gray-900 dark:text-slate-100 bg-white dark:bg-slate-950 flex flex-col selection:bg-emerald-500 selection:text-white transition-colors duration-300">
        <PublicNavbar />

        <main class="flex-1">
            <!-- Hero Carousel Section (Pure Horizontal Slider matching Figma node 1:1481) -->
            <section
                class="relative bg-slate-950 text-white overflow-hidden min-h-[460px] sm:min-h-[500px] lg:h-[520px] flex items-center select-none"
                @mouseenter="isPaused = true"
                @mouseleave="isPaused = false"
                @touchstart.passive="handleTouchStart"
                @touchend.passive="handleTouchEnd"
            >
                <!-- Pure Horizontal Sliding Track (No Blink, 60fps GPU-Accelerated) -->
                <div
                    class="flex h-full w-full transition-transform duration-700 ease-in-out"
                    :style="{ transform: `translateX(-${currentSlide * 100}%)` }"
                >
                    <div
                        v-for="(slide, idx) in slides"
                        :key="`slide-${slide.id}-${idx}`"
                        class="relative min-w-full w-full flex-shrink-0 flex items-center"
                    >
                        <!-- Slide Background Image with Vignette & Dark Overlay -->
                        <div class="absolute inset-0 z-0 overflow-hidden">
                            <img
                                :src="slide.thumbnail_url || '/assets/blog-hero-bg.jpg'"
                                :alt="slide.judul"
                                class="w-full h-full object-cover object-center"
                            />
                            <div class="absolute inset-0 bg-black/65 backdrop-blur-[0.5px]"></div>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/45 to-black/60"></div>
                        </div>

                        <!-- Slide Content Container -->
                        <div class="relative z-10 max-w-7xl mx-auto px-6 sm:px-12 md:px-16 lg:px-20 xl:px-24 pt-28 pb-20 lg:pt-32 lg:pb-24 w-full">
                            <!-- Clickable Card Area linking to Blog Detail -->
                            <component
                                :is="slide.slug ? Link : 'div'"
                                :href="slide.slug ? route('blog.show', slide.slug) : undefined"
                                class="group block max-w-4xl text-left cursor-pointer"
                            >
                                <!-- Category & Pinned Badge -->
                                <div class="flex items-center gap-2.5 mb-3.5 sm:mb-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#2DCC70]/20 border border-[#2DCC70]/40 text-xs sm:text-sm font-semibold text-[#2DCC70] backdrop-blur-sm">
                                        <svg
                                            v-if="slide.is_unggulan"
                                            class="w-3.5 h-3.5 fill-[#2DCC70]"
                                            viewBox="0 0 24 24"
                                        >
                                            <path d="M12 2l2.9 6.6 7.1.6-5.3 4.8 1.6 7-6.3-3.7-6.3 3.7 1.6-7L2 9.2l7.1-.6L12 2z" />
                                        </svg>
                                        {{ slide.is_unggulan ? 'Artikel Unggulan' : 'Artikel Terbaru' }}
                                    </span>
                                    <span
                                        v-if="slide.kategori"
                                        class="text-xs sm:text-sm font-medium text-gray-300 capitalize"
                                    >
                                        &bull; {{ slide.kategori }}
                                    </span>
                                    <span
                                        v-if="slide.tanggal_rilis_formatted"
                                        class="hidden sm:inline text-xs sm:text-sm text-gray-400"
                                    >
                                        &bull; {{ slide.tanggal_rilis_formatted }}
                                    </span>
                                </div>

                                <!-- Main Heading matching Figma node 1:1485 -->
                                <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-[42px] font-bold tracking-tight text-white leading-[1.2] mb-4 sm:mb-5 group-hover:text-[#2DCC70] transition-colors duration-200 line-clamp-2">
                                    {{ slide.judul }}
                                </h1>

                                <!-- Subtitle / Description matching Figma node 1:1486 -->
                                <p class="text-gray-200 text-sm sm:text-base lg:text-lg leading-relaxed line-clamp-3 max-w-3xl font-normal opacity-95">
                                    {{ slide.ringkasan || slide.meta_description || 'Ikuti perjalanan inspiratif, program kreatif, dan kisah penggerak perubahan dari berbagai kegiatan UKM LAOS.' }}
                                </p>

                                <!-- Call To Action Button -->
                                <div class="mt-6 sm:mt-7 flex items-center gap-3">
                                    <span
                                        class="inline-flex items-center gap-2.5 rounded-full bg-[#2DCC70] px-6 py-2.5 sm:px-7 sm:py-3 text-sm font-semibold text-white shadow-md shadow-[#2DCC70]/25 transition-all duration-300 group-hover:bg-[#25B562] group-hover:shadow-lg group-hover:shadow-[#2DCC70]/40 group-hover:scale-105"
                                    >
                                        <span>Baca Selengkapnya</span>
                                        <svg
                                            class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </span>
                                </div>
                            </component>
                        </div>
                    </div>
                </div>

                <!-- Carousel Controls Arrows (if more than 1 slide) -->
                <button
                    v-if="slides.length > 1"
                    @click.stop="prevSlide"
                    type="button"
                    class="absolute left-3 sm:left-6 lg:left-10 top-1/2 -translate-y-1/2 z-30 flex h-10 w-10 sm:h-12 sm:w-12 items-center justify-center rounded-full bg-black/40 text-white/90 backdrop-blur-md transition-all duration-300 hover:bg-[#2DCC70] hover:text-white hover:scale-110 active:scale-95 border border-white/15 shadow-lg"
                    aria-label="Artikel sebelumnya"
                >
                    <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <button
                    v-if="slides.length > 1"
                    @click.stop="nextSlide"
                    type="button"
                    class="absolute right-3 sm:right-6 lg:right-10 top-1/2 -translate-y-1/2 z-30 flex h-10 w-10 sm:h-12 sm:w-12 items-center justify-center rounded-full bg-black/40 text-white/90 backdrop-blur-md transition-all duration-300 hover:bg-[#2DCC70] hover:text-white hover:scale-110 active:scale-95 border border-white/15 shadow-lg"
                    aria-label="Artikel berikutnya"
                >
                    <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <!-- Pagination Indicator Dots matching Figma node 1:1487 (Frame 456480386) -->
                <div
                    v-if="slides.length > 1"
                    class="absolute bottom-6 sm:bottom-8 left-1/2 -translate-x-1/2 z-30 flex items-center justify-center gap-2 sm:gap-2.5"
                >
                    <button
                        v-for="(_, idx) in slides"
                        :key="`dot-${idx}`"
                        type="button"
                        @click.stop="goToSlide(idx)"
                        :class="[
                            'h-2 sm:h-2.5 rounded-full transition-all duration-300 cursor-pointer focus:outline-none',
                            currentSlide === idx
                                ? 'w-7 sm:w-8 bg-[#2DCC70] shadow-sm shadow-[#2DCC70]/50'
                                : 'w-2 sm:w-2.5 bg-white/40 hover:bg-white/80'
                        ]"
                        :aria-label="`Pindah ke slide ${idx + 1}`"
                    ></button>
                </div>
            </section>

            <!-- Main Content Area -->
            <section class="py-16 sm:py-20 lg:py-24 bg-white dark:bg-slate-950 transition-colors duration-300">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Section Header -->
                    <div class="text-center mb-10 sm:mb-14">
                        <div class="inline-flex items-center px-6 py-1.5 rounded-full border border-gray-300 dark:border-slate-800 bg-white dark:bg-slate-900 text-xs font-bold tracking-widest text-gray-700 dark:text-slate-300 uppercase mb-4 shadow-2xs">
                            BERITA
                        </div>
                        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                            Temukan Berita Terbaru dari UKM LAOS
                        </h2>
                    </div>

                    <!-- Search & Filter Bar -->
                    <div class="max-w-4xl mx-auto mb-12 sm:mb-16">
                        <div class="flex flex-col sm:flex-row items-center gap-3 bg-white dark:bg-slate-900 p-2 sm:p-2.5 rounded-2xl border border-gray-200 dark:border-slate-800 shadow-xs focus-within:border-emerald-500 focus-within:ring-2 focus-within:ring-emerald-500/20 transition-all duration-200">
                            <!-- Search Input -->
                            <div class="relative flex-1 w-full flex items-center">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 dark:text-slate-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input
                                    v-model="search"
                                    type="text"
                                    placeholder="Cari artikel, topik, atau penulis..."
                                    class="w-full pl-10 pr-4 py-2.5 bg-transparent border-none text-sm text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:outline-none focus:ring-0"
                                />
                                <button
                                    v-if="search"
                                    @click="search = ''"
                                    type="button"
                                    class="mr-2 text-gray-400 hover:text-gray-600 dark:hover:text-slate-200 transition"
                                    title="Bersihkan pencarian"
                                >
                                    <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Category Filter Dropdown -->
                            <div class="w-full sm:w-56 shrink-0 border-t sm:border-t-0 sm:border-l border-gray-100 dark:border-slate-800 pt-2 sm:pt-0 sm:pl-3">
                                <select
                                    v-model="selectedKategori"
                                    class="w-full py-2.5 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-gray-700 dark:text-slate-200 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 cursor-pointer"
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
                            class="group bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden"
                        >
                            <Link :href="route('blog.show', blog.slug)" class="block flex-1 flex flex-col">
                                <!-- Card Thumbnail Image -->
                                <div class="relative aspect-[16/10] overflow-hidden bg-slate-100 dark:bg-slate-800">
                                    <img
                                        :src="blog.thumbnail_url"
                                        :alt="blog.judul"
                                        loading="lazy"
                                        class="w-full h-full object-cover"
                                    />
                                    <!-- Category Pill Badge over image -->
                                    <span class="absolute top-3 left-3 bg-white/90 dark:bg-slate-900/90 backdrop-blur-xs text-emerald-800 dark:text-emerald-400 text-xs font-semibold px-2.5 py-1 rounded-md shadow-xs border dark:border-emerald-800/40">
                                        {{ blog.kategori }}
                                    </span>
                                </div>

                                <!-- Card Content -->
                                <div class="p-5 sm:p-6 flex-1 flex flex-col justify-between">
                                    <div>
                                        <!-- Title -->
                                        <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors line-clamp-2 mb-2.5 leading-snug">
                                            {{ blog.judul }}
                                        </h3>

                                        <!-- Summary / Excerpt -->
                                        <p class="text-sm text-gray-500 dark:text-slate-400 line-clamp-2 leading-relaxed mb-4">
                                            {{ blog.ringkasan }}
                                        </p>
                                    </div>

                                    <!-- Card Meta Footer -->
                                    <div class="pt-4 border-t border-gray-100 dark:border-slate-800 flex items-center justify-between text-xs text-gray-400 dark:text-slate-500">
                                        <span class="font-normal">
                                            Waktu Post: {{ blog.tanggal_rilis_formatted }}
                                        </span>
                                        <span class="text-emerald-600 dark:text-emerald-400 font-medium group-hover:translate-x-0.5 transition-transform flex items-center gap-1">
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
                        class="text-center py-20 bg-slate-50/60 dark:bg-slate-900/60 rounded-3xl border border-dashed border-gray-200 dark:border-slate-800 max-w-2xl mx-auto"
                    >
                        <div class="w-16 h-16 bg-emerald-100 dark:bg-emerald-950/50 rounded-full flex items-center justify-center mx-auto mb-4 text-emerald-600 dark:text-emerald-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Tidak ada artikel ditemukan</h3>
                        <p class="text-gray-500 dark:text-slate-400 text-sm max-w-md mx-auto mb-6">
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
                            item-name="artikel"
                            variant="circular"
                        />
                    </div>
                </div>
            </section>
        </main>

        <PublicFooter :divisis="divisis" />
    </div>
</template>
