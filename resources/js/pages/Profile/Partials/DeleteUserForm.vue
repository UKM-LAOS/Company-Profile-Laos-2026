<script setup lang="ts">
import AppButton from '@/Components/AppButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
    nextTick(() => passwordInput.value?.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value?.focus(),
        onFinish: () => {
            form.reset();
        },
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2
                class="text-base font-semibold text-slate-900 dark:text-slate-100"
            >
                Hapus Akun
            </h2>

            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Setelah akun Anda dihapus, semua sumber daya dan datanya akan
                dihapus secara permanen.
            </p>
        </header>

        <div>
            <AppButton
                type="button"
                variant="danger"
                size="md"
                @click="confirmUserDeletion"
            >
                Hapus Akun
            </AppButton>
        </div>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2
                    class="text-lg font-semibold text-slate-900 dark:text-slate-100"
                >
                    Apakah Anda yakin ingin menghapus akun Anda?
                </h2>

                <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                    Setelah akun Anda dihapus, semua sumber daya dan datanya
                    akan dihapus secara permanen. Silakan masukkan kata sandi
                    Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun
                    secara permanen.
                </p>

                <div class="mt-6">
                    <InputLabel
                        for="password"
                        value="Kata Sandi"
                        class="sr-only"
                    />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full sm:w-3/4"
                        placeholder="Masukkan kata sandi..."
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <AppButton
                        type="button"
                        variant="secondary"
                        size="md"
                        @click="closeModal"
                    >
                        Batal
                    </AppButton>

                    <AppButton
                        type="button"
                        variant="danger"
                        size="md"
                        :disabled="form.processing"
                        :loading="form.processing"
                        @click="deleteUser"
                    >
                        Hapus Akun
                    </AppButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
