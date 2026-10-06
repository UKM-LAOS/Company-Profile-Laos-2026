<script setup lang="ts">
import AppButton from '@/Components/AppButton.vue';
import AppIcon from '@/Components/AppIcon.vue';
import Modal from '@/Components/Modal.vue';
import { useForm } from '@inertiajs/vue3';
import type { RoleItem } from './RolePermissionModal.vue';

const props = defineProps<{
    show: boolean;
    role?: RoleItem | null;
}>();

const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'deleted'): void;
}>();

const form = useForm({});

function deleteRole() {
    if (!props.role) return;
    form.delete(route('roles.destroy', props.role.id), {
        preserveScroll: true,
        onSuccess: () => {
            emit('deleted');
            emit('close');
        },
    });
}
</script>

<template>
    <Modal :show="show" max-width="md" @close="$emit('close')">
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
                        Hapus Peran
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
            </div>

            <p
                class="mt-4 text-xs leading-relaxed text-slate-600 dark:text-slate-300"
            >
                Apakah Anda yakin ingin menghapus peran
                <span
                    class="font-mono font-bold text-slate-900 dark:text-white"
                >
                    {{ role?.name }}
                </span>
                ? Hak akses yang terhubung pada peran ini akan dilepas dari
                sistem.
            </p>

            <div
                v-if="role?.users_count && role.users_count > 0"
                class="mt-3.5 flex items-start gap-2.5 rounded-xl border border-amber-200 bg-amber-50 p-3 text-xs text-amber-800 dark:border-amber-900/60 dark:bg-amber-950/40 dark:text-amber-200"
            >
                <AppIcon
                    name="lock"
                    class-name="h-4 w-4 shrink-0 text-amber-600 dark:text-amber-400 mt-0.5"
                />
                <p>
                    Peran ini masih digunakan oleh
                    <strong>{{ role.users_count }} pengguna</strong>. Anda harus
                    mengalihkan peran pengguna tersebut terlebih dahulu sebelum
                    dapat menghapusnya.
                </p>
            </div>

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
                    :loading="form.processing"
                    :disabled="
                        Boolean(role?.users_count && role.users_count > 0)
                    "
                    class="w-full sm:w-auto"
                    @click="deleteRole"
                >
                    Hapus Peran
                </AppButton>
            </div>
        </div>
    </Modal>
</template>
