<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const isMobileMenuOpen = ref(false);
</script>

<template>
    <nav
        class="sticky top-0 z-50 w-full border-b border-gray-100 bg-white/90 backdrop-blur-md"
    >
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-20 items-center justify-between">
                <!-- Logo -->
                <div class="flex flex-shrink-0 items-center gap-2">
                    <img
                        src="/assets/logo.png"
                        alt="UKM LAOS"
                        class="h-10 w-auto"
                    />
                    <span class="text-xl font-bold text-green-500"
                        >UKM LAOS</span
                    >
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-8 items-center">
                    <Link
                        :href="route('home')"
                        :class="route().current('home') ? 'text-green-500 font-semibold text-sm hover:text-green-600 transition' : 'text-gray-600 font-medium text-sm hover:text-green-500 transition'"
                    >
                        Home
                    </Link>
                    <Link
                        :href="route('blog.index')"
                        :class="route().current('blog.*') ? 'text-green-500 font-semibold text-sm hover:text-green-600 transition' : 'text-gray-600 font-medium text-sm hover:text-green-500 transition'"
                    >
                        Blog
                    </Link>
                    <a href="#" class="text-gray-600 font-medium text-sm hover:text-green-500 transition">Program Kerja</a>
                    <Link
                        :href="route('tentang-kami')"
                        :class="route().current('tentang-kami') ? 'text-green-500 font-semibold text-sm hover:text-green-600 transition' : 'text-gray-600 font-medium text-sm hover:text-green-500 transition'"
                    >
                        Tentang Kami
                    </Link>
                    <a href="#" class="text-gray-600 font-medium text-sm hover:text-green-500 transition">Pengurus</a>
                </div>

                <!-- Mobile menu button -->
                <div class="flex items-center md:hidden">
                    <button
                        @click="isMobileMenuOpen = !isMobileMenuOpen"
                        class="text-gray-600 transition hover:text-green-500 focus:outline-none"
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

        <!-- Mobile Menu -->
        <div v-show="isMobileMenuOpen" class="md:hidden bg-white border-b border-gray-100 shadow-lg absolute w-full left-0">
            <div class="px-4 pt-2 pb-6 space-y-1">
                <Link
                    :href="route('home')"
                    @click="isMobileMenuOpen = false"
                    :class="route().current('home') ? 'block px-3 py-3 rounded-md text-base font-medium text-green-600 bg-green-50' : 'block px-3 py-3 rounded-md text-base font-medium text-gray-700 hover:text-green-600 hover:bg-gray-50'"
                >
                    Home
                </Link>
                <Link
                    :href="route('blog.index')"
                    @click="isMobileMenuOpen = false"
                    :class="route().current('blog.*') ? 'block px-3 py-3 rounded-md text-base font-medium text-green-600 bg-green-50' : 'block px-3 py-3 rounded-md text-base font-medium text-gray-700 hover:text-green-600 hover:bg-gray-50'"
                >
                    Blog
                </Link>
                <a href="#" @click="isMobileMenuOpen = false" class="block px-3 py-3 rounded-md text-base font-medium text-gray-700 hover:text-green-600 hover:bg-gray-50">Program Kerja</a>
                <Link
                    :href="route('tentang-kami')"
                    @click="isMobileMenuOpen = false"
                    :class="route().current('tentang-kami') ? 'block px-3 py-3 rounded-md text-base font-medium text-green-600 bg-green-50' : 'block px-3 py-3 rounded-md text-base font-medium text-gray-700 hover:text-green-600 hover:bg-gray-50'"
                >
                    Tentang Kami
                </Link>
                <a href="#" @click="isMobileMenuOpen = false" class="block px-3 py-3 rounded-md text-base font-medium text-gray-700 hover:text-green-600 hover:bg-gray-50">Pengurus</a>
            </div>
        </div>
    </nav>
</template>
