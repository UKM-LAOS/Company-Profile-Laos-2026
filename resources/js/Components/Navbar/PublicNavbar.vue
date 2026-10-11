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
                <div class="hidden space-x-8 md:flex">
                    <Link
                        :href="route('home')"
                        class="text-sm font-semibold text-green-500 transition hover:text-green-600"
                        >Home</Link
                    >
                    <a
                        href="#"
                        class="text-sm font-medium text-gray-600 transition hover:text-green-500"
                        >Blog</a
                    >
                    <a
                        href="#"
                        class="text-sm font-medium text-gray-600 transition hover:text-green-500"
                        >Program Kerja</a
                    >
                    <a
                        href="#"
                        class="text-sm font-medium text-gray-600 transition hover:text-green-500"
                        >Tentang Kami</a
                    >
                    <a
                        href="#"
                        class="text-sm font-medium text-gray-600 transition hover:text-green-500"
                        >Pengurus</a
                    >
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
        <div
            v-show="isMobileMenuOpen"
            class="absolute left-0 w-full border-b border-gray-100 bg-white shadow-lg md:hidden"
        >
            <div class="space-y-1 px-4 pt-2 pb-6">
                <Link
                    :href="route('home')"
                    @click="isMobileMenuOpen = false"
                    class="block rounded-md bg-green-50 px-3 py-3 text-base font-medium text-green-600"
                    >Home</Link
                >
                <a
                    href="#"
                    @click="isMobileMenuOpen = false"
                    class="block rounded-md px-3 py-3 text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-green-600"
                    >Blog</a
                >
                <a
                    href="#"
                    @click="isMobileMenuOpen = false"
                    class="block rounded-md px-3 py-3 text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-green-600"
                    >Program Kerja</a
                >
                <a
                    href="#"
                    @click="isMobileMenuOpen = false"
                    class="block rounded-md px-3 py-3 text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-green-600"
                    >Tentang Kami</a
                >
                <a
                    href="#"
                    @click="isMobileMenuOpen = false"
                    class="block rounded-md px-3 py-3 text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-green-600"
                    >Pengurus</a
                >
            </div>
        </div>
    </nav>
</template>
