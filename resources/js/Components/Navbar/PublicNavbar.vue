<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed, onMounted, onUnmounted, ref } from 'vue';
import { useTheme } from '@/lib/useTheme';

const { isDark, toggleTheme } = useTheme();

const isMobileMenuOpen = ref(false);
const isScrolled = ref(false);

const isDarkHero = computed(() => {
    try {
        return route().current('blog.*');
    } catch {
        return false;
    }
});

function handleScroll() {
    isScrolled.value = window.scrollY > 20;
}

onMounted(() => {
    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll();
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
    <nav
        :class="[
            'sticky top-0 z-50 w-full transition-all duration-300',
            isScrolled || isMobileMenuOpen
                ? 'border-b border-gray-100/80 bg-white/90 shadow-xs backdrop-blur-md dark:border-slate-800/80 dark:bg-slate-900/90'
                : 'border-b border-transparent bg-transparent shadow-none',
            '-mb-20',
        ]"
    >
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-20 items-center justify-between">
                <!-- Logo -->
                <Link
                    :href="route('home')"
                    class="flex flex-shrink-0 items-center gap-2.5"
                >
                    <img
                        src="/assets/logo.png"
                        alt="UKM LAOS"
                        class="h-11 w-auto sm:h-12"
                    />

                    <div class="flex flex-col">
                        <span class="text-xl font-bold text-[#2DCC70]"
                            >UKM LAOS</span
                        >
                        <span
                            class="-mt-1 text-[11px] font-bold tracking-wider text-gray-600 sm:text-[12px] dark:text-slate-400"
                        >
                            LOGICWARE AND OPEN SOURCE
                        </span>
                    </div>
                </Link>

                <!-- Desktop Menu -->
                <div class="hidden items-center space-x-7 md:flex lg:space-x-8">
                    <Link
                        :href="route('home')"
                        :class="[
                            'text-sm font-medium transition duration-200',
                            route().current('home')
                                ? 'font-bold text-[#2DCC70]'
                                : !isScrolled && isDarkHero && !isDark
                                  ? 'text-white/85 hover:text-white'
                                  : 'text-gray-600 hover:text-[#2DCC70] dark:text-slate-300 dark:hover:text-[#2DCC70]',
                        ]"
                    >
                        Home
                    </Link>
                    <Link
                        :href="route('blog.index')"
                        :class="[
                            'text-sm font-medium transition duration-200',
                            route().current('blog.*')
                                ? 'font-bold text-[#2DCC70]'
                                : !isScrolled && isDarkHero && !isDark
                                  ? 'text-white/85 hover:text-white'
                                  : 'text-gray-600 hover:text-[#2DCC70] dark:text-slate-300 dark:hover:text-[#2DCC70]',
                        ]"
                    >
                        Blog
                    </Link>
                    <Link
                        :href="route('program-kerja.index')"
                        :class="[
                            'text-sm font-medium transition duration-200',
                            route().current('program-kerja.index')
                                ? 'font-bold text-[#2DCC70]'
                                : !isScrolled && isDarkHero && !isDark
                                  ? 'text-white/85 hover:text-white'
                                  : 'text-gray-600 hover:text-[#2DCC70] dark:text-slate-300 dark:hover:text-[#2DCC70]',
                        ]"
                    >
                        Program Kerja
                    </Link>
                    <Link
                        :href="route('tentang-kami')"
                        :class="[
                            'text-sm font-medium transition duration-200',
                            route().current('tentang-kami')
                                ? 'font-bold text-[#2DCC70]'
                                : !isScrolled && isDarkHero && !isDark
                                  ? 'text-white/85 hover:text-white'
                                  : 'text-gray-600 hover:text-[#2DCC70] dark:text-slate-300 dark:hover:text-[#2DCC70]',
                        ]"
                    >
                        Tentang Kami
                    </Link>

                    <!-- Dark Mode Toggle Button (Desktop) -->
                    <button
                        type="button"
                        @click="toggleTheme"
                        class="relative inline-flex h-9 w-9 items-center justify-center rounded-xl border border-gray-200/80 bg-white/80 p-2 text-gray-700 shadow-2xs backdrop-blur-xs transition-all duration-300 hover:border-[#2DCC70] hover:text-[#2DCC70] dark:border-slate-700/80 dark:bg-slate-800/80 dark:text-amber-300 dark:hover:border-amber-300 dark:hover:text-amber-200"
                        :title="
                            isDark
                                ? 'Ganti ke Mode Terang'
                                : 'Ganti ke Mode Gelap'
                        "
                        :aria-label="
                            isDark
                                ? 'Ganti ke Mode Terang'
                                : 'Ganti ke Mode Gelap'
                        "
                    >
                        <!-- Sun Icon (Active in Dark Mode) -->
                        <svg
                            v-if="isDark"
                            class="h-4.5 w-4.5 transition-transform duration-300 hover:rotate-45"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"
                            />
                        </svg>
                        <!-- Moon Icon (Active in Light Mode) -->
                        <svg
                            v-else
                            class="h-4.5 w-4.5 transition-transform duration-300 hover:-rotate-12"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"
                            />
                        </svg>
                    </button>
                </div>

                <!-- Mobile Action Area -->
                <div class="flex items-center gap-2 md:hidden">
                    <!-- Dark Mode Toggle Button (Mobile Quick Button) -->
                    <button
                        type="button"
                        @click="toggleTheme"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-gray-200/80 bg-white/80 p-2 text-gray-700 backdrop-blur-xs transition dark:border-slate-700/80 dark:bg-slate-800/80 dark:text-amber-300"
                        :aria-label="
                            isDark
                                ? 'Ganti ke Mode Terang'
                                : 'Ganti ke Mode Gelap'
                        "
                    >
                        <svg
                            v-if="isDark"
                            class="h-4.5 w-4.5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"
                            />
                        </svg>
                        <svg
                            v-else
                            class="h-4.5 w-4.5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"
                            />
                        </svg>
                    </button>

                    <!-- Mobile Menu Hamburger Button -->
                    <button
                        @click="isMobileMenuOpen = !isMobileMenuOpen"
                        :class="[
                            'rounded-xl p-2 transition focus:outline-none',
                            !isScrolled && isDarkHero && !isDark
                                ? 'text-white hover:text-[#2DCC70]'
                                : 'text-gray-600 hover:text-[#2DCC70] dark:text-slate-300 dark:hover:text-[#2DCC70]',
                        ]"
                        aria-label="Toggle navigation menu"
                    >
                        <svg
                            v-if="!isMobileMenuOpen"
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                        </svg>
                        <svg
                            v-else
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Drawer -->
        <div
            v-show="isMobileMenuOpen"
            class="absolute top-full left-0 w-full border-b border-gray-100 bg-white/95 shadow-lg backdrop-blur-md md:hidden dark:border-slate-800 dark:bg-slate-900/95"
        >
            <div class="space-y-1.5 px-4 pt-3 pb-6">
                <Link
                    :href="route('home')"
                    @click="isMobileMenuOpen = false"
                    :class="
                        route().current('home')
                            ? 'block rounded-xl bg-emerald-50 px-4 py-3 text-base font-bold text-[#2DCC70] dark:bg-emerald-950/40 dark:text-[#2DCC70]'
                            : 'block rounded-xl px-4 py-3 text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-[#2DCC70] dark:text-slate-200 dark:hover:bg-slate-800 dark:hover:text-[#2DCC70]'
                    "
                >
                    Home
                </Link>
                <Link
                    :href="route('blog.index')"
                    @click="isMobileMenuOpen = false"
                    :class="
                        route().current('blog.*')
                            ? 'block rounded-xl bg-emerald-50 px-4 py-3 text-base font-bold text-[#2DCC70] dark:bg-emerald-950/40 dark:text-[#2DCC70]'
                            : 'block rounded-xl px-4 py-3 text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-[#2DCC70] dark:text-slate-200 dark:hover:bg-slate-800 dark:hover:text-[#2DCC70]'
                    "
                >
                    Blog
                </Link>
                <Link
                    :href="route('program-kerja.index')"
                    @click="isMobileMenuOpen = false"
                    :class="
                        route().current('program-kerja.index')
                            ? 'block rounded-xl bg-emerald-50 px-4 py-3 text-base font-bold text-[#2DCC70] dark:bg-emerald-950/40 dark:text-[#2DCC70]'
                            : 'block rounded-xl px-4 py-3 text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-[#2DCC70] dark:text-slate-200 dark:hover:bg-slate-800 dark:hover:text-[#2DCC70]'
                    "
                >
                    Program Kerja
                </Link>
                <Link
                    :href="route('tentang-kami')"
                    @click="isMobileMenuOpen = false"
                    :class="
                        route().current('tentang-kami')
                            ? 'block rounded-xl bg-emerald-50 px-4 py-3 text-base font-bold text-[#2DCC70] dark:bg-emerald-950/40 dark:text-[#2DCC70]'
                            : 'block rounded-xl px-4 py-3 text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-[#2DCC70] dark:text-slate-200 dark:hover:bg-slate-800 dark:hover:text-[#2DCC70]'
                    "
                >
                    Tentang Kami
                </Link>
            </div>
        </div>
    </nav>
</template>
