<script setup lang="ts">
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import PublicNavbar from '@/Components/Navbar/PublicNavbar.vue';
import PublicFooter from '@/Components/Footer/PublicFooter.vue';
import Pagination, {
    type PaginationLink,
} from '@/Components/Common/Pagination.vue';
import AppImage from '@/Components/Common/AppImage.vue';
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

    <div
        class="flex min-h-screen flex-col bg-white font-sans text-gray-900 transition-colors duration-300 selection:bg-emerald-500 selection:text-white dark:bg-slate-950 dark:text-slate-100"
    >
        <PublicNavbar />

        <main class="flex-1">
            <!-- Hero Carousel Section (Pure Horizontal Slider matching Figma node 1:1481) -->
            <section
                v-reveal
                class="relative flex min-h-[460px] items-center overflow-hidden bg-slate-950 text-white select-none sm:min-h-[500px] lg:h-[520px]"
                @mouseenter="isPaused = true"
                @mouseleave="isPaused = false"
                @touchstart.passive="handleTouchStart"
                @touchend.passive="handleTouchEnd"
            >
                <!-- Pure Horizontal Sliding Track (No Blink, 60fps GPU-Accelerated) -->
                <div
                    class="flex h-full w-full transition-transform duration-700 ease-in-out"
                    :style="{
                        transform: `translateX(-${currentSlide * 100}%)`,
                    }"
                >
                    <div
                        v-for="(slide, idx) in slides"
                        :key="`slide-${slide.id}-${idx}`"
                        class="relative flex w-full min-w-full flex-shrink-0 items-center"
                    >
                        <!-- Slide Background Image with Vignette & Dark Overlay -->
                        <div
                            class="absolute inset-0 z-0 overflow-hidden bg-slate-950"
                        >
                            <AppImage
                                :src="
                                    slide.thumbnail_url ||
                                    '/assets/blog-hero-bg.jpg'
                                "
                                :alt="slide.judul"
                                wrapper-class="h-full w-full"
                                image-class="h-full w-full object-cover object-center scale-105 filter brightness-[0.32] contrast-110"
                                loading="eager"
                            />
                            <!-- Base uniform dark overlay with micro-blur -->
                            <div
                                class="absolute inset-0 z-1 bg-black/75 backdrop-blur-[1px]"
                            ></div>
                            <!-- Strong horizontal gradient to protect text on the left -->
                            <div
                                class="absolute inset-0 z-2 bg-gradient-to-r from-black/95 via-black/80 to-black/45"
                            ></div>
                            <!-- Subtle vertical gradient for top navbar & bottom section blend -->
                            <div
                                class="absolute inset-0 z-2 bg-gradient-to-t from-slate-950 via-transparent to-black/70"
                            ></div>
                        </div>

                        <!-- Slide Content Container -->
                        <div
                            class="relative z-10 mx-auto w-full max-w-7xl px-6 pt-28 pb-20 sm:px-12 md:px-16 lg:px-20 lg:pt-32 lg:pb-24 xl:px-24"
                        >
                            <!-- Clickable Card Area linking to Blog Detail -->
                            <component
                                :is="slide.slug ? Link : 'div'"
                                v-reveal="{ delay: 120 }"
                                :href="
                                    slide.slug
                                        ? route('blog.show', slide.slug)
                                        : undefined
                                "
                                class="group block max-w-4xl cursor-pointer text-left"
                            >
                                <!-- Category & Pinned Badge -->
                                <div
                                    class="mb-3.5 flex items-center gap-2.5 sm:mb-4"
                                >
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full border border-[#2DCC70]/50 bg-[#162e20]/90 px-3 py-1 text-xs font-semibold text-[#3eed88] shadow-md backdrop-blur-md sm:text-sm"
                                    >
                                        <svg
                                            v-if="slide.is_unggulan"
                                            class="h-3.5 w-3.5 fill-[#2DCC70]"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                d="M12 2l2.9 6.6 7.1.6-5.3 4.8 1.6 7-6.3-3.7-6.3 3.7 1.6-7L2 9.2l7.1-.6L12 2z"
                                            />
                                        </svg>
                                        {{
                                            slide.is_unggulan
                                                ? 'Artikel Unggulan'
                                                : 'Artikel Terbaru'
                                        }}
                                    </span>
                                    <span
                                        v-if="slide.kategori"
                                        class="text-xs font-semibold text-slate-200 capitalize drop-shadow-[0_1px_3px_rgba(0,0,0,0.9)] sm:text-sm"
                                    >
                                        &bull; {{ slide.kategori }}
                                    </span>
                                    <span
                                        v-if="slide.tanggal_rilis_formatted"
                                        class="hidden text-xs text-slate-300 drop-shadow-[0_1px_3px_rgba(0,0,0,0.9)] sm:inline sm:text-sm"
                                    >
                                        &bull;
                                        {{ slide.tanggal_rilis_formatted }}
                                    </span>
                                </div>

                                <!-- Main Heading matching Figma node 1:1485 with strong contrast -->
                                <h1
                                    class="mb-4 line-clamp-2 text-2xl leading-[1.2] font-extrabold tracking-tight text-white drop-shadow-[0_2px_10px_rgba(0,0,0,0.95)] transition-colors duration-200 group-hover:text-[#2DCC70] sm:mb-5 sm:text-3xl md:text-4xl lg:text-[42px]"
                                >
                                    {{ slide.judul }}
                                </h1>

                                <!-- Subtitle / Description matching Figma node 1:1486 -->
                                <p
                                    class="line-clamp-3 max-w-3xl text-sm leading-relaxed font-normal text-slate-100 opacity-95 drop-shadow-[0_1px_6px_rgba(0,0,0,0.9)] sm:text-base lg:text-lg"
                                >
                                    {{
                                        slide.ringkasan ||
                                        slide.meta_description ||
                                        'Ikuti perjalanan inspiratif, program kreatif, dan kisah penggerak perubahan dari berbagai kegiatan UKM LAOS.'
                                    }}
                                </p>

                                <!-- Call To Action Button -->
                                <div
                                    class="mt-6 flex items-center gap-3 sm:mt-7"
                                >
                                    <span
                                        class="inline-flex items-center gap-2.5 rounded-full bg-[#2DCC70] px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-[#2DCC70]/25 transition-all duration-300 group-hover:scale-105 group-hover:bg-[#25B562] group-hover:shadow-lg group-hover:shadow-[#2DCC70]/40 sm:px-7 sm:py-3"
                                    >
                                        <span>Baca Selengkapnya</span>
                                        <svg
                                            class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2.5"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3"
                                            />
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
                    class="absolute top-1/2 left-3 z-30 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full border border-white/15 bg-black/40 text-white/90 shadow-lg backdrop-blur-md transition-all duration-300 hover:scale-110 hover:bg-[#2DCC70] hover:text-white active:scale-95 sm:left-6 sm:h-12 sm:w-12 lg:left-10"
                    aria-label="Artikel sebelumnya"
                >
                    <svg
                        class="h-5 w-5 sm:h-6 sm:w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2.5"
                            d="M15 19l-7-7 7-7"
                        />
                    </svg>
                </button>

                <button
                    v-if="slides.length > 1"
                    @click.stop="nextSlide"
                    type="button"
                    class="absolute top-1/2 right-3 z-30 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full border border-white/15 bg-black/40 text-white/90 shadow-lg backdrop-blur-md transition-all duration-300 hover:scale-110 hover:bg-[#2DCC70] hover:text-white active:scale-95 sm:right-6 sm:h-12 sm:w-12 lg:right-10"
                    aria-label="Artikel berikutnya"
                >
                    <svg
                        class="h-5 w-5 sm:h-6 sm:w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2.5"
                            d="M9 5l7 7-7 7"
                        />
                    </svg>
                </button>

                <!-- Pagination Indicator Dots matching Figma node 1:1487 (Frame 456480386) -->
                <div
                    v-if="slides.length > 1"
                    class="absolute bottom-6 left-1/2 z-30 flex -translate-x-1/2 items-center justify-center gap-2 sm:bottom-8 sm:gap-2.5"
                >
                    <button
                        v-for="(_, idx) in slides"
                        :key="`dot-${idx}`"
                        type="button"
                        @click.stop="goToSlide(idx)"
                        :class="[
                            'h-2 cursor-pointer rounded-full transition-all duration-300 focus:outline-none sm:h-2.5',
                            currentSlide === idx
                                ? 'w-7 bg-[#2DCC70] shadow-sm shadow-[#2DCC70]/50 sm:w-8'
                                : 'w-2 bg-white/40 hover:bg-white/80 sm:w-2.5',
                        ]"
                        :aria-label="`Pindah ke slide ${idx + 1}`"
                    ></button>
                </div>
            </section>

            <!-- Main Content Area -->
            <section
                v-reveal
                class="bg-white py-16 transition-colors duration-300 sm:py-20 lg:py-24 dark:bg-slate-950"
            >
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <!-- Section Header -->
                    <div class="mb-10 text-center sm:mb-14">
                        <div
                            class="mb-4 inline-flex items-center rounded-full border border-gray-300 bg-white px-6 py-1.5 text-xs font-bold tracking-widest text-gray-700 uppercase shadow-2xs dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300"
                        >
                            BERITA
                        </div>
                        <h2
                            class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl lg:text-4xl dark:text-white"
                        >
                            Temukan Berita Terbaru dari UKM LAOS
                        </h2>
                    </div>

                    <!-- Search & Filter Bar -->
                    <div class="mx-auto mb-12 max-w-4xl sm:mb-16">
                        <div
                            class="flex flex-col items-center gap-3 rounded-2xl border border-gray-200 bg-white p-2 shadow-xs transition-all duration-200 focus-within:border-emerald-500 focus-within:ring-2 focus-within:ring-emerald-500/20 sm:flex-row sm:p-2.5 dark:border-slate-800 dark:bg-slate-900"
                        >
                            <!-- Search Input -->
                            <div
                                class="relative flex w-full flex-1 items-center"
                            >
                                <div
                                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 dark:text-slate-500"
                                >
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
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                        />
                                    </svg>
                                </div>
                                <input
                                    v-model="search"
                                    type="text"
                                    placeholder="Cari artikel, topik, atau penulis..."
                                    class="w-full border-none bg-transparent py-2.5 pr-4 pl-10 text-sm text-gray-800 placeholder-gray-400 focus:ring-0 focus:outline-none dark:text-white dark:placeholder-slate-500"
                                />
                                <button
                                    v-if="search"
                                    @click="search = ''"
                                    type="button"
                                    class="mr-2 text-gray-400 transition hover:text-gray-600 dark:hover:text-slate-200"
                                    title="Bersihkan pencarian"
                                >
                                    <svg
                                        class="h-4 w-4"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </button>
                            </div>

                            <!-- Category Filter Dropdown -->
                            <div
                                class="w-full shrink-0 border-t border-gray-100 pt-2 sm:w-56 sm:border-t-0 sm:border-l sm:pt-0 sm:pl-3 dark:border-slate-800"
                            >
                                <select
                                    v-model="selectedKategori"
                                    class="w-full cursor-pointer rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm font-medium text-gray-700 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 focus:outline-none dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200"
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
                        class="grid grid-cols-1 gap-6 sm:gap-8 md:grid-cols-2 lg:grid-cols-3"
                    >
                        <article
                            v-for="blog in blogs.data"
                            :key="blog.id"
                            class="group flex flex-col overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl dark:border-slate-800 dark:bg-slate-900"
                        >
                            <Link
                                :href="route('blog.show', blog.slug)"
                                class="block flex flex-1 flex-col"
                            >
                                <!-- Card Thumbnail Image -->
                                <AppImage
                                    :src="blog.thumbnail_url"
                                    :alt="blog.judul"
                                    aspect-ratio="16/10"
                                    loading="lazy"
                                    wrapper-class="relative w-full overflow-hidden bg-slate-100 dark:bg-slate-800"
                                    image-class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                >
                                    <!-- Category Pill Badge over image -->
                                    <span
                                        class="absolute top-3 left-3 z-10 rounded-md border bg-white/90 px-2.5 py-1 text-xs font-semibold text-emerald-800 shadow-xs backdrop-blur-xs dark:border-emerald-800/40 dark:bg-slate-900/90 dark:text-emerald-400"
                                    >
                                        {{ blog.kategori }}
                                    </span>
                                </AppImage>

                                <!-- Card Content -->
                                <div
                                    class="flex flex-1 flex-col justify-between p-5 sm:p-6"
                                >
                                    <div>
                                        <!-- Title -->
                                        <h3
                                            class="mb-2.5 line-clamp-2 text-lg leading-snug font-bold text-gray-900 transition-colors group-hover:text-emerald-600 sm:text-xl dark:text-white dark:group-hover:text-emerald-400"
                                        >
                                            {{ blog.judul }}
                                        </h3>

                                        <!-- Summary / Excerpt -->
                                        <p
                                            class="mb-4 line-clamp-2 text-sm leading-relaxed text-gray-500 sm:text-[14px] dark:text-slate-400"
                                        >
                                            {{ blog.ringkasan }}
                                        </p>
                                    </div>

                                    <!-- Card Meta Footer -->
                                    <div
                                        class="flex items-center justify-between border-t border-gray-100 pt-4 text-xs text-gray-400 dark:border-slate-800 dark:text-slate-500"
                                    >
                                        <span class="font-normal">
                                            Waktu Post:
                                            {{ blog.tanggal_rilis_formatted }}
                                        </span>
                                        <span
                                            class="flex items-center gap-1 font-medium text-emerald-600 transition-transform group-hover:translate-x-0.5 dark:text-emerald-400"
                                        >
                                            Baca
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
                                        </span>
                                    </div>
                                </div>
                            </Link>
                        </article>
                    </div>

                    <!-- Empty State -->
                    <div
                        v-else
                        class="mx-auto max-w-2xl rounded-3xl border border-dashed border-gray-200 bg-slate-50/60 py-20 text-center dark:border-slate-800 dark:bg-slate-900/60"
                    >
                        <div
                            class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400"
                        >
                            <svg
                                class="h-8 w-8"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"
                                />
                            </svg>
                        </div>
                        <h3
                            class="mb-1 text-lg font-bold text-gray-900 dark:text-white"
                        >
                            Tidak ada artikel ditemukan
                        </h3>
                        <p
                            class="mx-auto mb-6 max-w-md text-sm text-gray-500 dark:text-slate-400"
                        >
                            Belum ada artikel publik yang sesuai dengan kata
                            kunci atau kategori yang Anda pilih.
                        </p>
                        <button
                            @click="resetFilters"
                            type="button"
                            class="inline-flex items-center rounded-full bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700"
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
