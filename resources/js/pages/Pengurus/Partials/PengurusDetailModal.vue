<script setup lang="ts">
import AppButton from '@/Components/AppButton.vue';
import AppIcon from '@/Components/AppIcon.vue';
import Modal from '@/Components/Modal.vue';
import { computed } from 'vue';
import {
    initials,
    SOSMED_KEYS,
    SOSMED_LABELS,
    type PengurusItem,
} from '../types';

const props = defineProps<{ show: boolean; pengurus: PengurusItem | null }>();
const emit = defineEmits<{ (event: 'close'): void }>();

const sosmedLinks = computed(() =>
    SOSMED_KEYS.flatMap((key) => {
        const url = props.pengurus?.sosmed?.[key];
        return url ? [{ key, label: SOSMED_LABELS[key], url }] : [];
    }),
);
</script>

<template>
    <Modal :show="show" max-width="md" @close="emit('close')">
        <div
            v-if="pengurus"
            class="p-6 sm:p-7"
            role="dialog"
            aria-modal="true"
            aria-labelledby="pengurus-detail-title"
        >
            <div class="flex items-start justify-between">
                <h2
                    id="pengurus-detail-title"
                    class="text-base font-bold text-slate-900 dark:text-white"
                >
                    Detail Pengurus
                </h2>
                <button
                    type="button"
                    aria-label="Tutup detail"
                    class="cursor-pointer rounded-xl p-1.5 text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800"
                    @click="emit('close')"
                >
                    <AppIcon name="close" class-name="h-4 w-4" />
                </button>
            </div>

            <div class="mt-5 flex flex-col items-center text-center">
                <img
                    v-if="pengurus.foto_url"
                    :src="pengurus.foto_url"
                    :alt="`Foto ${pengurus.nama}`"
                    class="h-24 w-24 rounded-full object-cover ring-4 ring-emerald-50 dark:ring-emerald-950"
                />
                <span
                    v-else
                    class="flex h-24 w-24 items-center justify-center rounded-full bg-emerald-100 text-2xl font-bold text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300"
                    aria-hidden="true"
                    >{{ initials(pengurus.nama) }}</span
                >
                <p
                    class="mt-4 text-lg font-bold text-slate-900 dark:text-white"
                >
                    {{ pengurus.nama }}
                </p>
                <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">
                    {{ pengurus.jabatan }} · Periode {{ pengurus.periode }}
                </p>
            </div>

            <dl
                class="mt-6 grid grid-cols-2 gap-3 rounded-2xl bg-slate-50 p-4 text-sm dark:bg-slate-800/50"
            >
                <div>
                    <dt class="text-xs text-slate-400">Status</dt>
                    <dd
                        :class="[
                            'mt-0.5 font-semibold',
                            pengurus.aktif
                                ? 'text-emerald-600 dark:text-emerald-400'
                                : 'text-slate-500',
                        ]"
                    >
                        {{ pengurus.aktif ? 'Aktif' : 'Nonaktif' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs text-slate-400">Urutan Tampil</dt>
                    <dd
                        class="mt-0.5 font-semibold text-slate-800 dark:text-slate-100"
                    >
                        {{ pengurus.urutan }}
                    </dd>
                </div>
            </dl>

            <div class="mt-4">
                <p
                    class="text-xs font-bold tracking-wider text-slate-400 uppercase"
                >
                    Sosial Media
                </p>
                <ul v-if="sosmedLinks.length" class="mt-2 space-y-1.5">
                    <li v-for="link in sosmedLinks" :key="link.key">
                        <a
                            :href="link.url"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="flex items-center justify-between gap-3 rounded-xl border border-slate-100 px-3 py-2 text-sm transition-colors hover:border-emerald-200 hover:bg-emerald-50/50 dark:border-slate-800 dark:hover:border-emerald-900 dark:hover:bg-emerald-950/30"
                        >
                            <span
                                class="font-semibold text-slate-700 dark:text-slate-200"
                                >{{ link.label }}</span
                            >
                            <span class="truncate text-xs text-slate-400">{{
                                link.url
                            }}</span>
                        </a>
                    </li>
                </ul>
                <p v-else class="mt-2 text-sm text-slate-400">
                    Belum ada sosial media.
                </p>
            </div>

            <div
                class="mt-6 flex justify-end border-t border-slate-100 pt-4 dark:border-slate-800"
            >
                <AppButton
                    type="button"
                    variant="secondary"
                    @click="emit('close')"
                    >Tutup</AppButton
                >
            </div>
        </div>
    </Modal>
</template>
