<script setup lang="ts">
import type { ProgramItem } from '../types';

defineProps<{
    show: boolean;
    program: ProgramItem | null;
}>();

const emit = defineEmits<{
    (event: 'close'): void;
}>();

function formatDateIndo(dateStr: string | null | undefined): string {
    if (!dateStr) return '-';
    try {
        const date = new Date(dateStr);
        if (isNaN(date.getTime())) return dateStr;
        return new Intl.DateTimeFormat('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
        }).format(date);
    } catch {
        return dateStr;
    }
}
</script>

<template>
    <div
        v-if="show && program"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-black/60 backdrop-blur-xs animate-in fade-in duration-200"
        @click.self="emit('close')"
    >
        <div
            class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-slate-100 p-6 sm:p-8 relative"
        >
            <!-- Close Button -->
            <button
                type="button"
                @click="emit('close')"
                class="absolute top-5 right-5 p-2 rounded-full text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition cursor-pointer"
                aria-label="Tutup"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Photo -->
            <div class="w-full h-56 sm:h-72 rounded-2xl overflow-hidden bg-slate-900 mb-6">
                <img
                    :src="program.foto_url || 'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=800&q=80'"
                    :alt="program.judul_program"
                    class="w-full h-full object-cover"
                    style="width: 100%; height: 100%; object-fit: cover;"
                />
            </div>

            <!-- Division and Meta -->
            <div class="flex flex-wrap items-center gap-2 mb-3">
                <span
                    v-if="program.divisi"
                    class="px-3 py-1 rounded-full text-xs font-semibold bg-[#2DCC70]/10 text-[#2DCC70]"
                >
                    {{ program.divisi.nama }}
                </span>
                <span
                    v-if="program.location_name && program.location_name !== 'NUL'"
                    class="flex items-center gap-1 text-xs text-slate-500 font-medium"
                >
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    </svg>
                    {{ program.location_name }}
                </span>
            </div>

            <!-- Title -->
            <h3 class="text-2xl sm:text-3xl font-extrabold text-[#1E1E1E]">
                {{ program.judul_program }}
            </h3>

            <!-- Dates Info -->
            <div class="mt-4 p-4 rounded-2xl bg-slate-50 border border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs sm:text-sm">
                <div>
                    <span class="text-slate-400 block">Pendaftaran Peserta:</span>
                    <span class="font-semibold text-slate-700">
                        {{ formatDateIndo(program.open_regis_peserta) }} s/d {{ formatDateIndo(program.close_regis_peserta) }}
                    </span>
                </div>
                <div v-if="program.pengurus">
                    <span class="text-slate-400 block">Penanggung Jawab:</span>
                    <span class="font-semibold text-slate-700">
                        {{ program.pengurus.nama }}
                    </span>
                </div>
            </div>

            <!-- Full Description -->
            <div class="mt-6 text-[#636363] text-sm sm:text-base leading-relaxed space-y-3 whitespace-pre-line">
                {{ program.deskripsi || 'Belum ada deskripsi lengkap untuk kegiatan ini.' }}
            </div>

            <!-- Footer Actions -->
            <div class="mt-8 pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <button
                    type="button"
                    @click="emit('close')"
                    class="px-5 py-2.5 rounded-full text-sm font-medium text-slate-600 hover:bg-slate-100 transition cursor-pointer"
                >
                    Tutup
                </button>
                <a
                    v-if="program.gform_peserta"
                    :href="program.gform_peserta"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="px-6 py-2.5 rounded-full bg-[#2DCC70] hover:bg-[#25b562] text-white text-sm font-semibold shadow-md shadow-[#2DCC70]/20 transition flex items-center gap-2 cursor-pointer"
                >
                    <span>Daftar Peserta</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</template>
