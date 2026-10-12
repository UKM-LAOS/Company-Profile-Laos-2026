<script setup lang="ts">
import AppButton from '@/Components/AppButton.vue';
import AppIcon from '@/Components/AppIcon.vue';
import Modal from '@/Components/Modal.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
    show: boolean;
    divisi?: any;
}>();

const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'deleted'): void;
}>();

const form = useForm({});

function deleteDivisi() {
    if (!props.divisi) return;
    form.delete(route('divisis.destroy', props.divisi.id), {
        preserveScroll: true,
        onSuccess: () => {
            emit('deleted');
            emit('close');
        },
    });
}
</script>

<template>
    <Modal :show="show" @close="$emit('close')">
        <div class="p-6 sm:p-7">
            <div class="flex items-center gap-3.5">
                <div
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400"
                >
                    <AppIcon name="trash" class-name="h-5 w-5" />
                </div>
                <div>
                    <h2
                        class="text-base font-bold text-slate-900 dark:text-white"
                    >
                        Hapus Divisi
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
            </div>

            <p
                class="mt-4 text-xs leading-relaxed text-slate-600 dark:text-slate-300"
            >
                Apakah Anda yakin ingin menghapus divisi
                <span class="font-bold text-slate-900 dark:text-white">
                    {{ divisi?.nama }}
                </span>
                ? Semua data program kerja dan blog yang terkait dengan divisi
                ini juga akan ikut terhapus dari sistem.
            </p>

            <div
                class="mt-6 flex flex-col-reverse gap-2.5 border-t border-slate-100 pt-4 sm:flex-row sm:items-center sm:justify-end sm:gap-3 dark:border-slate-800"
            >
                <AppButton
                    type="button"
                    variant="secondary"
                    size="md"
                    class="w-full sm:w-auto"
                    @click="$emit('close')"
                >
                    Batal
                </AppButton>

                <AppButton
                    type="button"
                    variant="danger"
                    size="md"
                    icon="trash"
                    class="w-full sm:w-auto"
                    :loading="form.processing"
                    :disabled="form.processing"
                    @click="deleteDivisi"
                >
                    Hapus Divisi
                </AppButton>
            </div>
        </div>
    </Modal>
</template>
