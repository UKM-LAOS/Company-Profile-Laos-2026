<script setup lang="ts">
import { computed } from 'vue';
import type { ProgramItem } from '../types';

const props = defineProps<{
    program: ProgramItem;
}>();

const emit = defineEmits<{
    (event: 'click-detail', program: ProgramItem): void;
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
        class="group flex flex-col md:flex-row items-center md:items-stretch gap-6 lg:gap-10 p-2 sm:p-3 rounded-3xl transition-all duration-200 hover:bg-slate-50/70"
    >
        <!-- Thumbnail / Photo Container -->
        <div
            class="relative w-full md:w-[440px] shrink-0 h-60 sm:h-64 md:h-[260px] rounded-2xl md:rounded-3xl overflow-hidden shadow-sm bg-slate-900 border border-slate-100/80"
            style="max-width: 100%; min-height: 220px;"
        >
            <img
                :src="displayImage"
                :alt="program.judul_program"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                style="width: 100%; height: 100%; object-fit: cover; display: block;"
                loading="lazy"
                @error="handleImageError"
            />

            <!-- Division badge if available -->
            <div
                v-if="program.divisi"
                class="absolute top-4 left-4 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white/95 text-emerald-800 shadow-xs backdrop-blur-xs"
            >
                {{ program.divisi.nama }}
            </div>
        </div>

        <!-- Content details -->
        <div class="flex flex-col justify-center flex-1 py-1 sm:py-3 w-full">
            <h3 class="text-2xl sm:text-[28px] font-bold text-[#1E1E1E] tracking-tight leading-snug group-hover:text-[#2DCC70] transition-colors">
                {{ program.judul_program }}
            </h3>

            <p class="mt-3 text-sm sm:text-base text-[#636363] leading-relaxed line-clamp-3">
                {{ program.deskripsi || 'Forum mingguan untuk diskusi, koordinasi program, sharing ide, dan evaluasi kegiatan. Tujuan: Meningkatkan komunikasi, kekompakan, dan update progress tim.' }}
            </p>

            <!-- Action Button -->
            <div class="mt-6">
                <button
                    type="button"
                    @click="emit('click-detail', program)"
                    class="inline-flex items-center gap-2.5 px-6 py-2.5 rounded-full bg-[#2DCC70] hover:bg-[#25b562] active:bg-emerald-700 text-white text-sm font-semibold shadow-md shadow-[#2DCC70]/20 transition-all cursor-pointer transform active:scale-95"
                >
                    <span>Lihat Kegiatan Kami</span>
                    <svg
                        class="w-4 h-4 transition-transform group-hover:translate-x-1"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </div>
        </div>
    </article>
</template>
