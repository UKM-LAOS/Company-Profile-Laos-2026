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
        class="animate-in fade-in fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 backdrop-blur-xs duration-200 sm:p-6"
        @click.self="emit('close')"
    >
        <div
            class="relative max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-3xl border border-slate-100 bg-white p-6 text-[#1E1E1E] shadow-2xl sm:p-8 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100"
        >
            <!-- Close Button -->
            <button
                type="button"
                @click="emit('close')"
                class="absolute top-5 right-5 cursor-pointer rounded-full p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800 dark:hover:text-slate-200"
                aria-label="Tutup"
            >
                <svg
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>

            <!-- Photo -->
            <div
                class="mb-6 h-56 w-full overflow-hidden rounded-2xl bg-slate-900 sm:h-72"
            >
                <img
                    :src="
                        program.foto_url ||
                        'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=800&q=80'
                    "
                    :alt="program.judul_program"
                    class="h-full w-full object-cover"
                    style="width: 100%; height: 100%; object-fit: cover"
                />
            </div>

            <!-- Division and Meta -->
            <div class="mb-3 flex flex-wrap items-center gap-2">
                <span
                    v-if="program.divisi"
                    class="rounded-full bg-[#2DCC70]/10 px-3 py-1 text-xs font-semibold text-[#2DCC70]"
                >
                    {{ program.divisi.nama }}
                </span>
                <span
                    v-if="
                        program.location_name && program.location_name !== 'NUL'
                    "
                    class="flex items-center gap-1 text-xs font-medium text-slate-500 dark:text-slate-400"
                >
                    <svg
                        class="h-3.5 w-3.5 text-slate-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                        />
                    </svg>
                    {{ program.location_name }}
                </span>
            </div>

            <!-- Title -->
            <h3
                class="text-lg font-bold text-[#1E1E1E] sm:text-xl dark:text-white"
            >
                {{ program.judul_program }}
            </h3>

            <!-- Dates Info -->
            <div
                class="mt-4 grid grid-cols-1 gap-3 rounded-2xl border border-slate-100 bg-slate-50 p-4 text-xs sm:grid-cols-2 sm:text-sm dark:border-slate-700/60 dark:bg-slate-800/60"
            >
                <div>
                    <span class="block text-slate-400"
                        >Pendaftaran Peserta:</span
                    >
                    <span
                        class="font-semibold text-slate-700 dark:text-slate-200"
                    >
                        {{ formatDateIndo(program.open_regis_peserta) }} s/d
                        {{ formatDateIndo(program.close_regis_peserta) }}
                    </span>
                </div>
                <div v-if="program.pengurus">
                    <span class="block text-slate-400">Penanggung Jawab:</span>
                    <span
                        class="font-semibold text-slate-700 dark:text-slate-200"
                    >
                        {{ program.pengurus.nama }}
                    </span>
                </div>
            </div>

            <!-- Full Description -->
            <div
                class="mt-6 space-y-3 text-sm leading-relaxed whitespace-pre-line text-[#636363] sm:text-[14px] dark:text-slate-300"
            >
                {{
                    program.deskripsi ||
                    'Belum ada deskripsi lengkap untuk kegiatan ini.'
                }}
            </div>

            <!-- Footer Actions -->
            <div
                class="mt-8 flex items-center justify-end gap-3 border-t border-slate-100 pt-4 dark:border-slate-800"
            >
                <button
                    type="button"
                    @click="emit('close')"
                    class="cursor-pointer rounded-full px-5 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800"
                >
                    Tutup
                </button>
                <a
                    v-if="program.gform_peserta"
                    :href="program.gform_peserta"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="flex cursor-pointer items-center gap-2 rounded-full bg-[#2DCC70] px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-[#2DCC70]/20 transition hover:bg-[#25b562]"
                >
                    <span>Daftar Peserta</span>
                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                        />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</template>
