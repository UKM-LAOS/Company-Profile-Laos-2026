<script setup lang="ts">
import AppButton from '@/Components/AppButton.vue';
import AppIcon from '@/Components/AppIcon.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { computed, watch } from 'vue';
import type { RoleItem } from './RolePermissionModal.vue';

const props = defineProps<{
    show: boolean;
    role?: RoleItem | null;
}>();

const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'saved'): void;
}>();

const isEditing = computed(() => !!props.role);

const form = useForm({
    name: '',
});

watch(
    () => props.show,
    (show) => {
        if (show) {
            form.clearErrors();
            if (props.role) {
                form.name = props.role.name;
            } else {
                form.reset();
            }
        }
    },
);

function submit() {
    if (isEditing.value && props.role) {
        form.put(route('roles.update', props.role.id), {
            preserveScroll: true,
            onSuccess: () => {
                emit('saved');
                emit('close');
            },
        });
    } else {
        form.post(route('roles.store'), {
            preserveScroll: true,
            onSuccess: () => {
                emit('saved');
                emit('close');
            },
        });
    }
}
</script>

<template>
    <Modal :show="show" max-width="lg" @close="$emit('close')">
        <form @submit.prevent="submit" class="p-6 sm:p-7">
            <!-- Header -->
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400"
                    >
                        <AppIcon
                            :name="isEditing ? 'edit' : 'shield'"
                            class-name="h-5 w-5"
                        />
                    </div>
                    <div>
                        <h2
                            class="text-lg font-bold text-slate-900 dark:text-white"
                        >
                            {{ isEditing ? 'Edit Nama Peran' : 'Tambah Peran' }}
                        </h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            {{
                                isEditing
                                    ? 'Ubah nama pengenal peran otorisasi dalam sistem.'
                                    : 'Tambahkan peran baru untuk mengelompokkan hak akses.'
                            }}
                        </p>
                    </div>
                </div>

                <button
                    type="button"
                    class="cursor-pointer rounded-xl p-2 text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800 dark:hover:text-slate-300"
                    @click="$emit('close')"
                >
                    <AppIcon name="close" class-name="h-5 w-5" />
                </button>
            </div>

            <!-- Form Body -->
            <div class="mt-6 space-y-4">
                <div>
                    <InputLabel for="role-name" value="Nama Peran" />
                    <TextInput
                        id="role-name"
                        v-model="form.name"
                        type="text"
                        class="mt-1.5 block w-full"
                        placeholder="contoh: koordinator_divisi, bendahara"
                        required
                        autofocus
                    />
                    <InputError :message="form.errors.name" class="mt-1.5" />
                    <p
                        class="mt-1.5 text-xs text-slate-500 dark:text-slate-400"
                    >
                        Nama peran akan disimpan dalam format huruf kecil tanpa
                        spasi.
                    </p>
                </div>
            </div>

            <!-- Footer Actions -->
            <div
                class="mt-7 flex flex-col-reverse gap-2.5 border-t border-slate-100 pt-5 sm:flex-row sm:items-center sm:justify-end sm:gap-3 dark:border-slate-800"
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
                    type="submit"
                    variant="primary"
                    size="md"
                    :loading="form.processing"
                    class="w-full sm:w-auto"
                >
                    {{ isEditing ? 'Simpan Perubahan' : 'Buat Peran' }}
                </AppButton>
            </div>
        </form>
    </Modal>
</template>
