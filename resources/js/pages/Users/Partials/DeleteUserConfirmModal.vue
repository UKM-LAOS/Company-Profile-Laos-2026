<script setup lang="ts">
import AppButton from '@/Components/AppButton.vue';
import AppIcon from '@/Components/AppIcon.vue';
import Modal from '@/Components/Modal.vue';
import { useForm } from '@inertiajs/vue3';
import type { UserItem } from './UserFormModal.vue';

const props = defineProps<{
    show: boolean;
    user?: UserItem | null;
}>();

const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'deleted'): void;
}>();

const form = useForm({});

function deleteUser() {
    if (!props.user) return;
    form.delete(route('users.destroy', props.user.id), {
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
                        Hapus Pengguna
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
            </div>

            <p
                class="mt-4 text-xs leading-relaxed text-slate-600 dark:text-slate-300"
            >
                Apakah Anda yakin ingin menghapus akun pengguna
                <span class="font-bold text-slate-900 dark:text-white">
                    {{ user?.name }}
                </span>
                (<span class="text-slate-500">{{ user?.email }}</span
                >)? Semua data dan hak akses yang terkait dengan akun ini akan
                dihapus dari sistem.
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
                    @click="deleteUser"
                >
                    Hapus Pengguna
                </AppButton>
            </div>
        </div>
    </Modal>
</template>
