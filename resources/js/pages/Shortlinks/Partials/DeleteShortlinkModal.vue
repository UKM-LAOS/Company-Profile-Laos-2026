<script setup lang="ts">
import AppButton from '@/Components/AppButton.vue';
import AppIcon from '@/Components/AppIcon.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import { useForm } from '@inertiajs/vue3';
import { computed, watch } from 'vue';
import type { ShortlinkItem } from '../types';

const props = defineProps<{ show: boolean; shortlink: ShortlinkItem | null }>();
const emit = defineEmits<{ (event: 'close'): void }>();
const form = useForm({});
const error = computed(() =>
    Object.values(form.errors).find(
        (message): message is string => typeof message === 'string',
    ),
);

watch(
    () => props.show,
    (show) => {
        if (show) form.clearErrors();
    },
);

function close() {
    if (!form.processing) emit('close');
}

function deleteShortlink() {
    if (!props.shortlink || form.processing) return;
    form.delete(route('shortlinks.destroy', props.shortlink.id), {
        preserveScroll: true,
        onSuccess: () => emit('close'),
    });
}
</script>

<template>
    <Modal :show="show" :closeable="!form.processing" @close="close">
        <div
            class="p-6 sm:p-7"
            role="dialog"
            aria-modal="true"
            aria-labelledby="shortlink-delete-title"
        >
            <div class="flex items-center gap-3.5">
                <div
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400"
                >
                    <AppIcon name="trash" class-name="h-5 w-5" />
                </div>
                <h2
                    id="shortlink-delete-title"
                    class="text-base font-bold text-slate-900 dark:text-white"
                >
                    Hapus Shortlink
                </h2>
            </div>
            <p
                class="mt-4 text-sm leading-relaxed text-slate-600 dark:text-slate-300"
            >
                Hapus shortlink
                <span class="font-semibold text-slate-900 dark:text-white">{{
                    shortlink?.short_code
                }}</span
                >? Tindakan ini tidak dapat dibatalkan.
            </p>
            <InputError :message="error" class="mt-2" />
            <div
                class="mt-6 flex flex-col-reverse gap-2.5 border-t border-slate-100 pt-4 sm:flex-row sm:justify-end sm:gap-3 dark:border-slate-800"
            >
                <AppButton
                    type="button"
                    variant="secondary"
                    :disabled="form.processing"
                    @click="close"
                    >Batal</AppButton
                >
                <AppButton
                    type="button"
                    variant="danger"
                    icon="trash"
                    :loading="form.processing"
                    :disabled="form.processing"
                    @click="deleteShortlink"
                    >Hapus Shortlink</AppButton
                >
            </div>
        </div>
    </Modal>
</template>
