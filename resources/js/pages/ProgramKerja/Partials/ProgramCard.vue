<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import type { ProgramItem } from '../types';

const props = defineProps<{
    program: ProgramItem;
}>();

const defaultFallbackImage = 'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=800&q=80';

const displayImage = computed(() => {
    return props.program.foto_url || defaultFallbackImage;
});

function handleImageError(event: Event) {
    const target = event.target as HTMLImageElement;
    if (target && target.src !== defaultFallbackImage) {
        target.src = defaultFallbackImage;
    }
}
</script>

<template>
    <article
        class="group flex flex-col lg:flex-row items-center lg:items-center gap-8 lg:gap-11 xl:gap-14 w-full"
    >
        <!-- Framed Thumbnail / Photo Container matching Figma node 1:247 -->
        <div class="relative w-full lg:w-[500px] xl:w-[560px] shrink-0">
            <div
                class="bg-white dark:bg-slate-900 rounded-[16px] p-2 sm:p-2.5 shadow-[0px_2px_12px_rgba(0,0,0,0.08)] dark:shadow-none border border-slate-100 dark:border-slate-800 transition-all duration-300 group-hover:shadow-[0px_8px_24px_rgba(45,204,112,0.12)]"
            >
                <div
                    class="relative w-full h-[240px] sm:h-[290px] lg:h-[315px] rounded-[12px] overflow-hidden bg-[#2DCC70]"
                >
                    <img
                        :src="displayImage"
                        :alt="program.judul_program"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                        loading="lazy"
                        @error="handleImageError"
                    />
                </div>
            </div>
        </div>

        <!-- Content Details matching Figma node 1:279 -->
        <div class="flex flex-col justify-center flex-1 w-full space-y-4 sm:space-y-6 lg:space-y-8">
            <!-- Title matching Figma text-[36px] Poppins:Medium -->
            <!-- Title -->
            <h3 class="text-lg sm:text-xl font-bold sm:font-semibold text-[#1E1E1E] dark:text-white tracking-tight leading-snug group-hover:text-[#2DCC70] transition-colors">
                {{ program.judul_program }}
            </h3>

            <!-- Description -->
            <p class="text-sm sm:text-[14px] text-[#636363] dark:text-slate-300 leading-relaxed line-clamp-4 font-normal">
                {{ program.deskripsi || 'Forum mingguan untuk diskusi, koordinasi program, sharing ide, dan evaluasi kegiatan. Tujuan: Meningkatkan komunikasi, kekompakan, dan update progress tim.' }}
            </p>

            <!-- Action Button matching Figma node 1:282 -->
            <div class="pt-1">
                <Link
                    :href="route('program-kerja.show', program.slug || program.id)"
                    class="inline-flex items-center gap-2.5 h-[50px] sm:h-[56px] px-8 rounded-full bg-[#2DCC70] hover:bg-[#25B562] active:bg-[#1D9A50] text-white font-semibold text-[15px] border-2 border-white dark:border-slate-800 shadow-md shadow-[#2DCC70]/20 hover:shadow-lg hover:shadow-[#2DCC70]/30 transition-all duration-300 cursor-pointer group/btn hover:-translate-y-0.5"
                >
                    <span>Lihat Kegiatan Kami</span>
                    <svg
                        class="w-4 h-4 transition-transform duration-300 group-hover/btn:translate-x-1"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2.5"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </Link>
            </div>
        </div>
    </article>
</template>
