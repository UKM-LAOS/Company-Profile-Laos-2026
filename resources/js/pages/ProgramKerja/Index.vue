<script setup lang="ts">
import Pagination from '@/Components/Common/Pagination.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import ProgramCard from './Partials/ProgramCard.vue';
import ProgramDetailModal from './Partials/ProgramDetailModal.vue';
import ProgramHero from './Partials/ProgramHero.vue';
import type { PaginatedPrograms, ProgramItem } from './types';
import PublicNavbar from '@/Components/Navbar/PublicNavbar.vue';
import PublicFooter from '@/Components/Footer/PublicFooter.vue';

defineProps<{
    programs: PaginatedPrograms;
}>();

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

    <div
        class="min-h-screen scroll-smooth bg-white font-sans text-[#1E1E1E] transition-colors duration-300 dark:bg-slate-950 dark:text-slate-100"
    >
        <!-- Navbar matching Tentang Kami / deploy pattern -->
        <PublicNavbar />

        <!-- Program Hero Partial matching Figma -->
        <ProgramHero />

        <!-- Program Kerja Kami Section -->
        <section id="program-list" v-reveal class="py-12 sm:py-16 lg:py-20">
            <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-12">
                <!-- Section Header -->
                <div
                    class="mx-auto mb-14 max-w-2xl text-center sm:mb-16 lg:mb-20"
                >
                    <h2
                        class="text-3xl font-extrabold tracking-tight text-[#1E1E1E] sm:text-4xl lg:text-[42px] dark:text-white"
                    >
                        Program Kerja Kami
                    </h2>
                    <p
                        class="mt-3.5 text-base leading-relaxed text-[#636363] sm:text-lg dark:text-slate-400"
                    >
                        Program kami nggak cuma seru, tapi juga bikin kamu makin
                        berkembang. Kepoin sekarang yuk!
                    </p>
                </div>

                <!-- Program List Cards matching Figma node 22:126 -->
                <div
                    v-if="programs.data && programs.data.length > 0"
                    class="space-y-12 sm:space-y-16 lg:space-y-20"
                >
                    <ProgramCard
                        v-for="program in programs.data"
                        :key="program.id"
                        :program="program"
                    />
                </div>

                <!-- Empty state -->
                <div
                    v-else
                    class="rounded-3xl border border-slate-100 bg-slate-50 py-16 text-center dark:border-slate-800 dark:bg-slate-900/60"
                >
                    <svg
                        class="mx-auto h-12 w-12 text-slate-400 dark:text-slate-500"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                        />
                    </svg>
                    <h3
                        class="mt-4 text-lg font-bold text-slate-800 dark:text-slate-200"
                    >
                        Belum ada program kerja
                    </h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Program kerja akan segera diumumkan.
                    </p>
                </div>

                <!-- Pagination matching Figma -->
                <div class="mt-14 pt-8">
                    <Pagination
                        :links="programs.links"
                        :from="programs.from"
                        :to="programs.to"
                        :total="programs.total"
                        item-name="program kerja"
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

        <PublicFooter />
    </div>
</template>
