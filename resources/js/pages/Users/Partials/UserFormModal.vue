<script setup lang="ts">
import AppButton from '@/Components/AppButton.vue';
import AppIcon from '@/Components/AppIcon.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { computed, watch } from 'vue';

export interface UserItem {
    id: number;
    name: string;
    email: string;
    created_at: string;
    roles?: Array<{ name: string; id: number }>;
}

const props = defineProps<{
    show: boolean;
    user?: UserItem | null;
    availableRoles: string[];
}>();

const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'saved'): void;
}>();

const isEditing = computed(() => !!props.user);

const form = useForm({
    name: '',
    email: '',
    role: 'member',
    password: '',
    password_confirmation: '',
});

watch(
    () => props.show,
    (show) => {
        if (show) {
            form.clearErrors();
            if (props.user) {
                form.name = props.user.name;
                form.email = props.user.email;
                form.role = props.user.roles?.[0]?.name || 'member';
                form.password = '';
                form.password_confirmation = '';
            } else {
                form.reset();
                form.role = props.availableRoles[0] || 'member';
            }
        }
    },
);

function roleDisplayName(roleName: string): string {
    switch (roleName) {
        case 'super_admin':
            return 'Super Admin';
        case 'admin':
            return 'Administrator';
        case 'member':
            return 'Anggota (Member)';
        default:
            return roleName
                .replace('_', ' ')
                .replace(/\b\w/g, (c) => c.toUpperCase());
    }
}

function submit() {
    if (isEditing.value && props.user) {
        form.put(route('users.update', props.user.id), {
            preserveScroll: true,
            onSuccess: () => {
                emit('saved');
                emit('close');
            },
        });
    } else {
        form.post(route('users.store'), {
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
    <Modal :show="show" @close="$emit('close')">
        <div class="p-6 sm:p-7">
            <!-- Modal Header -->
            <div
                class="flex items-center justify-between border-b border-slate-100 pb-4 dark:border-slate-800"
            >
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400"
                    >
                        <AppIcon
                            :name="isEditing ? 'edit' : 'plus'"
                            class-name="h-5 w-5"
                        />
                    </div>
                    <div>
                        <h2
                            class="text-base font-bold text-slate-900 dark:text-white"
                        >
                            {{
                                isEditing
                                    ? 'Ubah Data Pengguna'
                                    : 'Tambah Pengguna Baru'
                            }}
                        </h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            {{
                                isEditing
                                    ? 'Perbarui informasi profil dan hak akses peran pengguna.'
                                    : 'Daftarkan pengguna baru dan berikan hak akses peran (RBAC).'
                            }}
                        </p>
                    </div>
                </div>

                <button
                    type="button"
                    @click="$emit('close')"
                    class="cursor-pointer rounded-xl p-1.5 text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800 dark:hover:text-slate-300"
                >
                    <AppIcon name="close" class-name="h-4 w-4" />
                </button>
            </div>

            <!-- Modal Form -->
            <form @submit.prevent="submit" class="mt-5 space-y-4">
                <!-- Nama Lengkap -->
                <div>
                    <InputLabel
                        for="modal_name"
                        value="Nama Lengkap"
                        class="text-xs font-semibold text-slate-700 dark:text-slate-300"
                    />
                    <TextInput
                        id="modal_name"
                        v-model="form.name"
                        type="text"
                        class="mt-1.5 block w-full"
                        placeholder="Contoh: Fadhil Rahman"
                        required
                    />
                    <InputError :message="form.errors.name" class="mt-1" />
                </div>

                <!-- Alamat Email -->
                <div>
                    <InputLabel
                        for="modal_email"
                        value="Alamat Email"
                        class="text-xs font-semibold text-slate-700 dark:text-slate-300"
                    />
                    <TextInput
                        id="modal_email"
                        v-model="form.email"
                        type="email"
                        class="mt-1.5 block w-full"
                        placeholder="contoh@mail.unej.ac.id"
                        required
                    />
                    <InputError :message="form.errors.email" class="mt-1" />
                </div>

                <!-- Peran (Role) -->
                <div>
                    <InputLabel
                        for="modal_role"
                        value="Hak Akses Peran (Role)"
                        class="text-xs font-semibold text-slate-700 dark:text-slate-300"
                    />
                    <div class="relative mt-1.5">
                        <select
                            id="modal_role"
                            v-model="form.role"
                            class="block w-full cursor-pointer appearance-none rounded-xl border border-slate-200/90 bg-white px-4 py-2.5 text-sm text-slate-800 shadow-xs transition-colors focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100 dark:focus:border-emerald-500 dark:focus:ring-emerald-500/20"
                            required
                        >
                            <option
                                v-for="roleName in availableRoles"
                                :key="roleName"
                                :value="roleName"
                            >
                                {{ roleDisplayName(roleName) }}
                            </option>
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400"
                        >
                            <AppIcon
                                name="chevron-right"
                                class-name="h-4 w-4 rotate-90"
                            />
                        </div>
                    </div>
                    <InputError :message="form.errors.role" class="mt-1" />
                </div>

                <!-- Kata Sandi -->
                <div>
                    <InputLabel
                        for="modal_password"
                        :value="
                            isEditing ? 'Kata Sandi (Opsional)' : 'Kata Sandi'
                        "
                        class="text-xs font-semibold text-slate-700 dark:text-slate-300"
                    />
                    <TextInput
                        id="modal_password"
                        v-model="form.password"
                        type="password"
                        class="mt-1.5 block w-full"
                        :placeholder="
                            isEditing
                                ? 'Kosongkan jika tidak ingin mengubah sandi'
                                : 'Minimal 8 karakter'
                        "
                        :required="!isEditing"
                    />
                    <InputError :message="form.errors.password" class="mt-1" />
                </div>

                <!-- Modal Actions -->
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
                        type="submit"
                        variant="primary"
                        size="md"
                        icon="check"
                        class="w-full sm:w-auto"
                        :loading="form.processing"
                        :disabled="form.processing"
                    >
                        {{
                            isEditing
                                ? 'Simpan Perubahan'
                                : 'Tambahkan Pengguna'
                        }}
                    </AppButton>
                </div>
            </form>
        </div>
    </Modal>
</template>
