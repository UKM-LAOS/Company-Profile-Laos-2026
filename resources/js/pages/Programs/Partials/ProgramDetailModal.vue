<script setup lang="ts">
import AppButton from '@/Components/AppButton.vue';
import AppIcon from '@/Components/AppIcon.vue';
import Modal from '@/Components/Modal.vue';
import type { ProgramItem } from '../types';

defineProps<{
    show: boolean;
    program: ProgramItem | null;
}>();

const emit = defineEmits<{
    (event: 'close'): void;
    (event: 'edit', program: ProgramItem): void;
}>();

function formatDateIndo(dateStr: string | null | undefined): string {
    if (!dateStr) return '-';
    try {
        const date = new Date(dateStr);
        if (isNaN(date.getTime())) return dateStr;
        return new Intl.DateTimeFormat('id-ID', {
            day: 'numeric',
            month: 'short',
            year: 'numeric',
        }).format(date);
    } catch {
        return dateStr;
    }
}

function statusBadge(status: string) {
    switch (status) {
        case 'open':
            return {
                label: 'Pendaftaran Dibuka',
                classes:
                    'bg-emerald-50 text-emerald-700 border-emerald-200/80 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/60',
                dot: 'bg-emerald-500 animate-pulse',
            };
        case 'upcoming':
            return {
                label: 'Akan Datang',
                classes:
                    'bg-amber-50 text-amber-700 border-amber-200/80 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800/60',
                dot: 'bg-amber-500',
            };
        case 'closed':
            return {
                label: 'Pendaftaran Ditutup',
                classes:
                    'bg-slate-100 text-slate-600 border-slate-200 dark:bg-slate-800/60 dark:text-slate-400 dark:border-slate-700',
                dot: 'bg-slate-400',
            };
        default:
            return {
                label: 'Tidak Aktif',
                classes:
                    'bg-slate-100 text-slate-500 border-slate-200 dark:bg-slate-800/40 dark:text-slate-400 dark:border-slate-700',
                dot: 'bg-slate-300',
            };
    }
}
</script>

<template>
    <Modal :show="show" max-width="2xl" @close="emit('close')">
        <div v-if="program" class="p-6 sm:p-7">
            <!-- Modal Header -->
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/60 dark:text-indigo-400"
                    >
                        <AppIcon name="proker" class-name="h-5 w-5" />
                    </div>
                    <div>
                        <span
                            v-if="program.divisi"
                            class="inline-block text-xs font-semibold tracking-wider text-indigo-600 uppercase dark:text-indigo-400"
                        >
                            {{ program.divisi.nama }}
                        </span>
                        <h2
                            class="text-lg font-bold text-slate-900 sm:text-xl dark:text-white"
                        >
                            {{ program.judul_program }}
                        </h2>
                    </div>
                </div>
                <button
                    type="button"
                    class="rounded-xl p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800 dark:hover:text-slate-200"
                    @click="emit('close')"
                >
                    <AppIcon name="close" class-name="h-5 w-5" />
                </button>
            </div>

            <!-- Content Grid -->
            <div class="mt-6 space-y-6">
                <!-- Poster Banner if available -->
                <div
                    v-if="program.foto_url"
                    class="overflow-hidden rounded-2xl border border-slate-200/80 bg-slate-900 shadow-xs dark:border-slate-800"
                >
                    <img
                        :src="program.foto_url"
                        :alt="program.judul_program"
                        class="max-h-72 w-full object-cover object-center"
                    />
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div
                        class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4 dark:border-slate-800/80 dark:bg-slate-900/60"
                    >
                        <div
                            class="text-xs font-medium text-slate-500 dark:text-slate-400"
                        >
                            Lokasi / Tempat
                        </div>
                        <div
                            class="mt-1 font-semibold text-slate-800 dark:text-slate-200"
                        >
                            {{ program.location_name || '-' }}
                        </div>
                    </div>
                    <div
                        class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4 dark:border-slate-800/80 dark:bg-slate-900/60"
                    >
                        <div
                            class="text-xs font-medium text-slate-500 dark:text-slate-400"
                        >
                            Slug URL
                        </div>
                        <div
                            class="mt-1 font-mono text-xs font-semibold text-slate-700 dark:text-slate-300"
                        >
                            /programs/{{ program.slug }}
                        </div>
                    </div>
                </div>

                <!-- Deskripsi Program -->
                <div
                    v-if="program.deskripsi"
                    class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4 dark:border-slate-800/80 dark:bg-slate-900/60"
                >
                    <div
                        class="text-xs font-medium text-slate-500 dark:text-slate-400"
                    >
                        Deskripsi Program Kerja
                    </div>
                    <div
                        class="mt-1 text-sm whitespace-pre-line leading-relaxed text-slate-700 dark:text-slate-300"
                    >
                        {{ program.deskripsi }}
                    </div>
                </div>

                <!-- Registrasi Peserta (Utama) -->
                <div
                    class="rounded-2xl border border-emerald-100 bg-emerald-50/30 p-5 dark:border-emerald-900/40 dark:bg-emerald-950/20"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span
                                class="text-base font-bold text-slate-900 dark:text-white"
                            >
                                Registrasi Peserta
                            </span>
                            <span
                                class="rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-bold text-emerald-700 dark:bg-emerald-900/60 dark:text-emerald-300"
                            >
                                Wajib
                            </span>
                        </div>
                        <span
                            :class="[
                                'inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-xs font-semibold',
                                statusBadge(program.status_peserta).classes,
                            ]"
                        >
                            <span
                                :class="[
                                    'h-1.5 w-1.5 rounded-full',
                                    statusBadge(program.status_peserta).dot,
                                ]"
                            />
                            {{ statusBadge(program.status_peserta).label }}
                        </span>
                    </div>

                    <div
                        class="mt-3 flex flex-wrap items-center justify-between gap-3 text-sm text-slate-600 dark:text-slate-300"
                    >
                        <div>
                            <span
                                class="block text-xs text-slate-400 dark:text-slate-500"
                                >Jadwal Pelaksanaan</span
                            >
                            <span
                                class="font-medium text-slate-800 dark:text-slate-200"
                            >
                                {{
                                    formatDateIndo(program.open_regis_peserta)
                                }}
                                &mdash;
                                {{
                                    formatDateIndo(program.close_regis_peserta)
                                }}
                            </span>
                        </div>

                        <a
                            v-if="program.gform_peserta"
                            :href="program.gform_peserta"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-emerald-700"
                        >
                            <span>Buka Form Peserta</span>
                            <AppIcon
                                name="chevron-right"
                                class-name="h-3.5 w-3.5"
                            />
                        </a>
                        <span v-else class="text-xs text-slate-400 italic">
                            Belum ada tautan form peserta
                        </span>
                    </div>
                </div>

                <!-- Registrasi Panitia -->
                <div
                    class="rounded-2xl border border-slate-200/80 bg-white p-5 dark:border-slate-800 dark:bg-slate-900"
                >
                    <div class="flex items-center justify-between">
                        <span
                            class="text-base font-bold text-slate-900 dark:text-white"
                        >
                            Registrasi Panitia / Volunteer
                        </span>
                        <span
                            :class="[
                                'inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-xs font-semibold',
                                statusBadge(program.status_panitia).classes,
                            ]"
                        >
                            <span
                                :class="[
                                    'h-1.5 w-1.5 rounded-full',
                                    statusBadge(program.status_panitia).dot,
                                ]"
                            />
                            {{ statusBadge(program.status_panitia).label }}
                        </span>
                    </div>

                    <div
                        v-if="
                            program.open_regis_panitia &&
                            program.close_regis_panitia
                        "
                        class="mt-3 flex flex-wrap items-center justify-between gap-3 text-sm text-slate-600 dark:text-slate-300"
                    >
                        <div>
                            <span
                                class="block text-xs text-slate-400 dark:text-slate-500"
                                >Jadwal Perekrutan Panitia</span
                            >
                            <span
                                class="font-medium text-slate-800 dark:text-slate-200"
                            >
                                {{
                                    formatDateIndo(program.open_regis_panitia)
                                }}
                                &mdash;
                                {{
                                    formatDateIndo(program.close_regis_panitia)
                                }}
                            </span>
                        </div>

                        <a
                            v-if="program.gform_panitia"
                            :href="program.gform_panitia"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-indigo-700"
                        >
                            <span>Buka Form Panitia</span>
                            <AppIcon
                                name="chevron-right"
                                class-name="h-3.5 w-3.5"
                            />
                        </a>
                        <span v-else class="text-xs text-slate-400 italic">
                            Belum ada tautan form panitia
                        </span>
                    </div>
                    <div v-else class="mt-2 text-xs text-slate-400 italic">
                        Tidak ada pendaftaran panitia khusus untuk program ini.
                    </div>
                </div>
            </div>

            <!-- Footer Modal -->
            <div
                class="mt-6 flex justify-end gap-3 border-t border-slate-100 pt-4 dark:border-slate-800"
            >
                <AppButton variant="secondary" @click="emit('close')">
                    Tutup
                </AppButton>
                <AppButton
                    variant="primary"
                    icon="edit"
                    @click="
                        emit('edit', program);
                        emit('close');
                    "
                >
                    Edit Program Kerja
                </AppButton>
            </div>
        </div>
    </Modal>
</template>
