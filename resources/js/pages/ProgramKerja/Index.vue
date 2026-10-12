<script setup lang="ts">
import Pagination from '@/Components/Common/Pagination.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import ProgramCard from './Partials/ProgramCard.vue';
import ProgramDetailModal from './Partials/ProgramDetailModal.vue';
import ProgramHero from './Partials/ProgramHero.vue';
import type { PaginatedPrograms, ProgramItem } from './types';

defineProps<{
    programs: PaginatedPrograms;
}>();

const isNavOpen = ref(false);

const selectedProgram = ref<ProgramItem | null>(null);
const isModalOpen = ref(false);

function handleOpenDetail(program: ProgramItem) {
    selectedProgram.value = program;
    isModalOpen.value = true;
}

function handleCloseModal() {
    isModalOpen.value = false;
    selectedProgram.value = null;
}
</script>

<template>
    <Head title="Program Kerja - UKM LAOS" />

    <div class="min-h-screen scroll-smooth bg-white font-sans text-[#1E1E1E]">
        <!-- Navbar matching Tentang Kami / deploy pattern -->
        <header class="sticky top-0 z-50 bg-white shadow-xs">
            <nav
                class="mx-auto flex h-[72px] max-w-7xl items-center justify-between gap-3 px-5 sm:px-8"
            >
                <Link href="/" class="flex shrink-0 items-center gap-2.5">
                    <img
                        src="/assets/logo.png"
                        alt="Logo UKM LAOS"
                        class="h-9 w-9 object-contain shrink-0"
                    />
                    <span class="text-lg font-semibold text-[#2DCC70]"
                        >UKM LAOS</span
                    >
                </Link>
                <div
                    class="hidden items-center gap-7 text-sm font-semibold text-[#636363] md:flex"
                >
                    <Link href="/" class="transition hover:text-[#2DCC70]"
                        >Home</Link
                    >
                    <Link href="/blog" class="transition hover:text-[#2DCC70]"
                        >Blog</Link
                    >
                    <Link
                        href="/program-kerja"
                        class="text-[#2DCC70]"
                        aria-current="page"
                        >Program Kerja</Link
                    >
                    <Link
                        href="/tentang-kami"
                        class="transition hover:text-[#2DCC70]"
                        >Tentang Kami</Link
                    >
                    <a href="/#pengurus" class="transition hover:text-[#2DCC70]"
                        >Pengurus</a
                    >
                </div>
                <div class="flex items-center gap-2 md:hidden">
                    <button
                        type="button"
                        class="flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 text-[#1E1E1E] transition hover:border-[#2DCC70] hover:text-[#2DCC70] cursor-pointer"
                        :aria-expanded="isNavOpen"
                        aria-label="Buka menu navigasi"
                        @click="isNavOpen = !isNavOpen"
                    >
                        <svg
                            v-if="!isNavOpen"
                            class="h-5 w-5"
                            viewBox="0 0 20 20"
                            fill="none"
                            aria-hidden="true"
                        >
                            <path
                                d="M3 5h14M3 10h14M3 15h14"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                            />
                        </svg>
                        <svg
                            v-else
                            class="h-5 w-5"
                            viewBox="0 0 20 20"
                            fill="none"
                            aria-hidden="true"
                        >
                            <path
                                d="M5 5l10 10M15 5L5 15"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                            />
                        </svg>
                    </button>
                </div>
            </nav>
            <div
                v-if="isNavOpen"
                class="border-t border-slate-100 bg-white px-5 pt-2 pb-5 md:hidden"
            >
                <div
                    class="flex flex-col gap-1 text-sm font-semibold text-[#636363]"
                >
                    <Link
                        href="/"
                        class="rounded-lg px-3 py-2.5 transition hover:bg-[#2DCC70]/10 hover:text-[#2DCC70]"
                        @click="isNavOpen = false"
                        >Home</Link
                    >
                    <Link
                        href="/blog"
                        class="rounded-lg px-3 py-2.5 transition hover:bg-[#2DCC70]/10 hover:text-[#2DCC70]"
                        @click="isNavOpen = false"
                        >Blog</Link
                    >
                    <Link
                        href="/program-kerja"
                        class="rounded-lg bg-[#2DCC70]/10 px-3 py-2.5 text-[#2DCC70]"
                        aria-current="page"
                        @click="isNavOpen = false"
                        >Program Kerja</Link
                    >
                    <Link
                        href="/tentang-kami"
                        class="rounded-lg px-3 py-2.5 transition hover:bg-[#2DCC70]/10 hover:text-[#2DCC70]"
                        @click="isNavOpen = false"
                        >Tentang Kami</Link
                    >
                    <a
                        href="/#pengurus"
                        class="rounded-lg px-3 py-2.5 transition hover:bg-[#2DCC70]/10 hover:text-[#2DCC70]"
                        @click="isNavOpen = false"
                        >Pengurus</a
                    >
                </div>
            </div>
        </header>

        <!-- Program Hero Partial matching Figma -->
        <ProgramHero />

        <!-- Program Kerja Kami Section -->
        <section id="program-list" class="py-12 sm:py-16">
            <div class="max-w-6xl mx-auto px-5 sm:px-8">
                <!-- Section Header -->
                <div class="text-center max-w-2xl mx-auto mb-12 sm:mb-16">
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-[#1E1E1E] tracking-tight">
                        Program Kerja Kami
                    </h2>
                    <p class="mt-3 text-base sm:text-lg text-[#636363] leading-relaxed">
                        Program kami nggak cuma seru, tapi juga bikin kamu makin berkembang. Kepoin sekarang yuk!
                    </p>
                </div>

                <!-- Program List Cards -->
                <div v-if="programs.data && programs.data.length > 0" class="space-y-10 sm:space-y-12">
                    <ProgramCard
                        v-for="program in programs.data"
                        :key="program.id"
                        :program="program"
                        @click-detail="handleOpenDetail"
                    />
                </div>

                <!-- Empty state -->
                <div v-else class="text-center py-16 bg-slate-50 rounded-3xl border border-slate-100">
                    <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <h3 class="mt-4 text-lg font-bold text-slate-800">Belum ada program kerja</h3>
                    <p class="mt-1 text-sm text-slate-500">Program kerja akan segera diumumkan.</p>
                </div>

                <!-- Pagination matching Figma -->
                <div class="mt-12 pt-6">
                    <Pagination
                        :links="programs.links"
                        :from="programs.from"
                        :to="programs.to"
                        :total="programs.total"
                        item-name="tautan terdaftar"
                        variant="circular"
                    />
                </div>
            </div>
        </section>

        <!-- Detail Modal Partial -->
        <ProgramDetailModal
            :show="isModalOpen"
            :program="selectedProgram"
            @close="handleCloseModal"
        />

        <!-- Footer matching Tentang Kami / deploy pattern -->
        <footer class="bg-[#176638] text-white mt-16">
            <div
                class="mx-auto grid max-w-7xl gap-10 px-5 py-12 sm:px-8 md:grid-cols-2 lg:grid-cols-4"
            >
                <div>
                    <div class="flex items-center gap-2.5">
                        <img
                            src="/assets/logo.png"
                            alt="Logo UKM LAOS"
                            class="h-9 w-9 object-contain brightness-110"
                        />
                        <span class="text-lg font-semibold">UKM LAOS</span>
                    </div>
                    <p class="mt-4 text-sm leading-relaxed text-white/85">
                        Memberdayakan siswa dengan pendidikan teknologi mutakhir
                        dan bimbingan dari pakar industri.
                    </p>
                </div>
                <nav aria-label="Quick Links">
                    <h3 class="font-semibold">Quick Links</h3>
                    <ul
                        class="mt-4 space-y-2.5 text-sm font-medium text-white/85"
                    >
                        <li>
                            <Link href="/" class="transition hover:text-white"
                                >Home</Link
                            >
                        </li>
                        <li>
                            <Link
                                href="/blog"
                                class="transition hover:text-white"
                                >Blog</Link
                            >
                        </li>
                        <li>
                            <Link
                                href="/program-kerja"
                                class="transition hover:text-white"
                                >Program Kerja</Link
                            >
                        </li>
                        <li>
                            <Link
                                href="/tentang-kami"
                                class="transition hover:text-white"
                                >Tentang Kami</Link
                            >
                        </li>
                        <li>
                            <a
                                href="/#pengurus"
                                class="transition hover:text-white"
                                >Pengurus</a
                            >
                        </li>
                    </ul>
                </nav>
                <div>
                    <h3 class="font-semibold">Kursus Kami</h3>
                    <ul
                        class="mt-4 space-y-2.5 text-sm font-medium text-white/85"
                    >
                        <li>Programming</li>
                        <li>Cyber Security</li>
                        <li>UI/UX Design</li>
                        <li>Digital Marketing</li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold">Kontak</h3>
                    <ul class="mt-4 space-y-2.5 text-sm text-white/85">
                        <li>Malang, East Java, Indonesia</li>
                        <li>
                            <a
                                href="mailto:info@ukmlaos.com"
                                class="transition hover:text-white"
                                >info@ukmlaos.com</a
                            >
                        </li>
                        <li>
                            <a
                                href="tel:+6281234567890"
                                class="transition hover:text-white"
                                >+62 812 3456 7890</a
                            >
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-white/15">
                <p
                    class="mx-auto max-w-7xl px-5 py-4 text-center text-sm text-white/85 sm:px-8"
                >
                    &copy; 2025 UKM LAOS. All rights reserved
                </p>
            </div>
        </footer>
    </div>
</template>
