<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppImage from '@/Components/Common/AppImage.vue';
import type { ProgramItem } from '../types';

const props = defineProps<{
    program: ProgramItem;
}>();

const defaultFallbackImage =
    'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=800&q=80';

const displayImage = computed(() => {
    return props.program.foto_url || defaultFallbackImage;
});
</script>

<template>
    <article
        class="group flex w-full flex-col items-center gap-8 lg:flex-row lg:items-center lg:gap-11 xl:gap-14"
    >
        <!-- Framed Thumbnail / Photo Container matching Figma node 1:247 -->
        <div class="relative w-full shrink-0 lg:w-[500px] xl:w-[560px]">
            <div
                class="rounded-[16px] border border-slate-100 bg-white p-2 shadow-[0px_2px_12px_rgba(0,0,0,0.08)] transition-all duration-300 group-hover:shadow-[0px_8px_24px_rgba(45,204,112,0.12)] sm:p-2.5 dark:border-slate-800 dark:bg-slate-900 dark:shadow-none"
            >
                <div
                    class="relative h-[240px] w-full overflow-hidden rounded-[12px] bg-slate-100 sm:h-[290px] lg:h-[315px] dark:bg-slate-800"
                >
                    <AppImage
                        :src="displayImage"
                        :alt="program.judul_program"
                        :fallback-src="defaultFallbackImage"
                        loading="lazy"
                        wrapper-class="h-full w-full"
                        image-class="transition-transform duration-500 group-hover:scale-105"
                    />
                </div>
            </div>
        </div>

        <!-- Content Details matching Figma node 1:279 -->
        <div
            class="flex w-full flex-1 flex-col justify-center space-y-4 sm:space-y-6 lg:space-y-8"
        >
            <!-- Title matching Figma text-[36px] Poppins:Medium -->
            <!-- Title -->
            <h3
                class="text-lg leading-snug font-bold tracking-tight text-[#1E1E1E] transition-colors group-hover:text-[#2DCC70] sm:text-xl sm:font-semibold dark:text-white"
            >
                {{ program.judul_program }}
            </h3>

            <!-- Description -->
            <p
                class="line-clamp-4 text-sm leading-relaxed font-normal text-[#636363] sm:text-[14px] dark:text-slate-300"
            >
                {{
                    program.deskripsi ||
                    'Forum mingguan untuk diskusi, koordinasi program, sharing ide, dan evaluasi kegiatan. Tujuan: Meningkatkan komunikasi, kekompakan, dan update progress tim.'
                }}
            </p>

            <!-- Action Button matching Figma node 1:282 -->
            <div class="pt-1">
                <Link
                    :href="
                        route('program-kerja.show', program.slug || program.id)
                    "
                    class="group/btn inline-flex h-[50px] cursor-pointer items-center gap-2.5 rounded-full border-2 border-white bg-[#2DCC70] px-8 text-[15px] font-semibold text-white shadow-md shadow-[#2DCC70]/20 transition-all duration-300 hover:-translate-y-0.5 hover:bg-[#25B562] hover:shadow-lg hover:shadow-[#2DCC70]/30 active:bg-[#1D9A50] sm:h-[56px] dark:border-slate-800"
                >
                    <span>Lihat Kegiatan Kami</span>
                    <svg
                        class="h-4 w-4 transition-transform duration-300 group-hover/btn:translate-x-1"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2.5"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M14 5l7 7m0 0l-7 7m7-7H3"
                        />
                    </svg>
                </Link>
            </div>
        </div>
    </article>
</template>
