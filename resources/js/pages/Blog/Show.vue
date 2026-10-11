<script setup lang="ts">
import { computed, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PublicNavbar from '@/Components/Navbar/PublicNavbar.vue';
import PublicFooter from '@/Components/Footer/PublicFooter.vue';
import type { BlogItem } from './Index.vue';

const props = defineProps<{
    blog: BlogItem;
    relatedBlogs?: BlogItem[];
    divisis?: Array<any>;
}>();

const copied = ref(false);

const shareUrl = computed(() => {
    return typeof window !== 'undefined' ? window.location.href : '';
});

const whatsappShareUrl = computed(() => {
    return `https://api.whatsapp.com/send?text=${encodeURIComponent(props.blog.judul + ' - ' + shareUrl.value)}`;
});

const linkedinShareUrl = computed(() => {
    return `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(shareUrl.value)}`;
});

const twitterShareUrl = computed(() => {
    return `https://twitter.com/intent/tweet?url=${encodeURIComponent(shareUrl.value)}&text=${encodeURIComponent(props.blog.judul)}`;
});

function copyToClipboard() {
    if (typeof window !== 'undefined' && navigator.clipboard) {
        navigator.clipboard.writeText(window.location.href);
        copied.value = true;
        setTimeout(() => {
            copied.value = false;
        }, 2200);
    }
}
</script>

<template>
    <Head :title="`${blog.judul} - UKM LAOS`">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
        <meta
            v-if="blog.meta_description"
            name="description"
            :content="blog.meta_description"
        />
        <meta property="og:title" :content="blog.judul" />
        <meta
            property="og:description"
            :content="blog.ringkasan || blog.meta_description || ''"
        />
        <meta property="og:image" :content="blog.thumbnail_url" />
    </Head>

    <div class="min-h-screen font-sans text-gray-900 bg-white flex flex-col selection:bg-emerald-500 selection:text-white">
        <PublicNavbar />

        <main class="flex-1">
            <!-- Article Header / Banner Section (Matches Mockup 2) -->
            <section class="relative bg-slate-900 text-white overflow-hidden py-16 sm:py-20 lg:py-28">
                <!-- Background Image & Overlay -->
                <div class="absolute inset-0 z-0">
                    <img
                        :src="blog.thumbnail_url"
                        :alt="blog.judul"
                        class="w-full h-full object-cover object-center brightness-40 filter blur-[1px] scale-105"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/80 to-slate-900/60"></div>
                </div>

                <!-- Header Content Container -->
                <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Category Badge -->
                    <div class="mb-4">
                        <Link
                            :href="route('blog.index', { kategori: blog.kategori })"
                            class="inline-block text-emerald-400 font-semibold text-sm sm:text-base tracking-wide uppercase hover:text-emerald-300 transition"
                        >
                            {{ blog.kategori }}
                        </Link>
                    </div>

                    <!-- Article Title -->
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-tight mb-6">
                        {{ blog.judul }}
                    </h1>

                    <!-- Article Meta -->
                    <div class="flex flex-wrap items-center gap-4 text-xs sm:text-sm text-gray-300">
                        <span class="flex items-center gap-1.5 font-normal">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Waktu Post: {{ blog.tanggal_rilis_formatted }}
                        </span>

                        <span v-if="blog.author" class="flex items-center gap-1.5">
                            <span class="text-gray-500">•</span>
                            <span class="text-gray-300">Oleh <strong class="text-white font-medium">{{ blog.author.name }}</strong></span>
                        </span>

                        <span v-if="blog.divisi" class="flex items-center gap-1.5">
                            <span class="text-gray-500">•</span>
                            <span class="text-emerald-300 font-medium">{{ blog.divisi.nama }}</span>
                        </span>

                        <span v-if="blog.views" class="flex items-center gap-1.5">
                            <span class="text-gray-500">•</span>
                            <span>{{ blog.views }} kali dibaca</span>
                        </span>
                    </div>
                </div>
            </section>

            <!-- Main Article Content Body -->
            <article class="py-12 sm:py-16 lg:py-20 bg-white">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Back Link -->
                    <div class="mb-8">
                        <Link
                            :href="route('blog.index')"
                            class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition group"
                        >
                            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali ke Semua Artikel
                        </Link>
                    </div>

                    <!-- Tiptap Rendered Rich Text Content -->
                    <div
                        class="article-content"
                        v-html="blog.konten"
                    ></div>

                    <!-- Share Section (Matches Mockup 2) -->
                    <div class="mt-14 pt-8 border-t border-gray-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="text-sm sm:text-base font-semibold text-gray-800">
                            Bagikan posting ini :
                        </div>

                        <!-- Social Share Action Buttons -->
                        <div class="flex items-center gap-3">
                            <!-- WhatsApp -->
                            <a
                                :href="whatsappShareUrl"
                                target="_blank"
                                rel="noopener noreferrer"
                                title="Bagikan ke WhatsApp"
                                class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all duration-200 flex items-center justify-center shadow-xs"
                            >
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.299.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.353.101.173.45 1.016 1.34 1.579.57.362 1.05.474 1.2.546.15.072.239.058.328-.043.089-.101.376-.434.477-.584.101-.15.202-.125.337-.075s.867.409 1.016.483.249.111.286.173c.037.062.037.361-.107.766zM12 2C6.477 2 2 6.477 2 12c0 1.89.525 3.66 1.438 5.168L2 22l4.981-1.396A9.957 9.957 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm0 18.167c-1.637 0-3.167-.47-4.464-1.28l-.32-.2-2.965.83.842-2.893-.209-.333A8.138 8.138 0 013.833 12c0-4.503 3.664-8.167 8.167-8.167 4.503 0 8.167 3.664 8.167 8.167 0 4.503-3.664 8.167-8.167 8.167z"/>
                                </svg>
                            </a>

                            <!-- LinkedIn -->
                            <a
                                :href="linkedinShareUrl"
                                target="_blank"
                                rel="noopener noreferrer"
                                title="Bagikan ke LinkedIn"
                                class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all duration-200 flex items-center justify-center shadow-xs"
                            >
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                </svg>
                            </a>

                            <!-- Twitter / X -->
                            <a
                                :href="twitterShareUrl"
                                target="_blank"
                                rel="noopener noreferrer"
                                title="Bagikan ke Twitter / X"
                                class="w-10 h-10 rounded-full bg-slate-100 text-slate-700 hover:bg-slate-900 hover:text-white transition-all duration-200 flex items-center justify-center shadow-xs"
                            >
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                </svg>
                            </a>

                            <!-- Copy Link -->
                            <button
                                @click="copyToClipboard"
                                type="button"
                                :title="copied ? 'Tautan tersalin!' : 'Salin tautan artikel'"
                                class="w-10 h-10 rounded-full bg-slate-100 text-slate-600 hover:bg-emerald-600 hover:text-white transition-all duration-200 flex items-center justify-center shadow-xs relative"
                            >
                                <svg v-if="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <svg v-else class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Related Articles (Optional UX enhancement) -->
                    <div v-if="relatedBlogs && relatedBlogs.length > 0" class="mt-20 pt-12 border-t border-gray-100">
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-8">
                            Artikel Terbaru Lainnya
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <article
                                v-for="related in relatedBlogs"
                                :key="related.id"
                                class="group bg-white rounded-2xl border border-gray-100 shadow-xs hover:shadow-md transition-all duration-300 flex flex-col overflow-hidden"
                            >
                                <Link :href="route('blog.show', related.slug)" class="block flex-1 flex flex-col">
                                    <div class="aspect-[16/10] overflow-hidden bg-slate-100">
                                        <img
                                            :src="related.thumbnail_url"
                                            :alt="related.judul"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                        />
                                    </div>
                                    <div class="p-4 flex-1 flex flex-col justify-between">
                                        <div>
                                            <span class="text-xs font-semibold text-emerald-600 uppercase">{{ related.kategori }}</span>
                                            <h4 class="text-sm font-bold text-gray-900 group-hover:text-emerald-600 transition-colors line-clamp-2 mt-1 mb-2">
                                                {{ related.judul }}
                                            </h4>
                                        </div>
                                        <div class="text-[11px] text-gray-400 pt-2 border-t border-gray-50">
                                            Waktu Post: {{ related.tanggal_rilis_formatted }}
                                        </div>
                                    </div>
                                </Link>
                            </article>
                        </div>
                    </div>
                </div>
            </article>
        </main>

        <PublicFooter :divisis="divisis" />
    </div>
</template>

<style>
/* Rich Text Typography for Tiptap Rendered Content */
.article-content {
    color: #334155;
    font-size: 1.0625rem;
    line-height: 1.85;
}

.article-content h1,
.article-content h2,
.article-content h3,
.article-content h4 {
    color: #0f172a;
    font-weight: 700;
    line-height: 1.35;
    margin-top: 2.25rem;
    margin-bottom: 0.85rem;
}

.article-content h1 {
    font-size: 1.875rem;
}

.article-content h2 {
    font-size: 1.5rem;
}

.article-content h3 {
    font-size: 1.25rem;
}

.article-content h4 {
    font-size: 1.125rem;
}

.article-content p {
    margin-top: 1.15rem;
    margin-bottom: 1.25rem;
    color: #334155;
}

.article-content ul {
    list-style-type: disc;
    padding-left: 1.75rem;
    margin-top: 1.15rem;
    margin-bottom: 1.25rem;
}

.article-content ol {
    list-style-type: decimal;
    padding-left: 1.75rem;
    margin-top: 1.15rem;
    margin-bottom: 1.25rem;
}

.article-content li {
    margin-top: 0.5rem;
    margin-bottom: 0.5rem;
    padding-left: 0.25rem;
}

.article-content strong,
.article-content b {
    font-weight: 700;
    color: #0f172a;
}

.article-content em,
.article-content i {
    font-style: italic;
}

.article-content s,
.article-content strike {
    text-decoration: line-through;
}

.article-content blockquote {
    font-weight: 500;
    font-style: italic;
    color: #475569;
    border-left: 4px solid #10b981;
    background-color: #f0fdf4;
    padding: 0.85rem 1.25rem;
    margin: 1.75rem 0;
    border-radius: 0 0.75rem 0.75rem 0;
}

.article-content img {
    border-radius: 1rem;
    max-width: 100%;
    height: auto;
    margin: 2rem auto;
    display: block;
    box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.08);
}

.article-content div[data-youtube-video] {
    width: 100%;
    aspect-ratio: 16 / 9;
    border-radius: 1rem;
    overflow: hidden;
    margin: 2rem 0;
    box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.08);
}

.article-content div[data-youtube-video] iframe,
.article-content iframe {
    width: 100%;
    height: 100%;
    border: none;
    border-radius: 1rem;
}

.article-content a {
    color: #059669;
    text-decoration: underline;
    font-weight: 500;
}

.article-content a:hover {
    color: #047857;
}

.article-content code {
    background-color: #f1f5f9;
    color: #0f172a;
    padding: 0.2rem 0.4rem;
    border-radius: 0.375rem;
    font-size: 0.875em;
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
}

.article-content pre {
    background-color: #0f172a;
    color: #f8fafc;
    padding: 1rem 1.25rem;
    border-radius: 0.75rem;
    overflow-x: auto;
    margin: 1.5rem 0;
}

.article-content pre code {
    background-color: transparent;
    color: inherit;
    padding: 0;
}
</style>
