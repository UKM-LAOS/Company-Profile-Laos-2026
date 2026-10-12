<script setup lang="ts">
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps<{
    show: boolean;
    divisi?: any;
}>();

const emit = defineEmits(['close']);

const form = useForm({
    nama: '',
    deskripsi: '',
    logo: null as File | null,
});

const logoPreview = ref<string | null>(null);
const fileInput = ref<HTMLInputElement | null>(null);

watch(
    () => props.show,
    (show) => {
        if (show) {
            if (props.divisi) {
                form.nama = props.divisi.nama;
                form.deskripsi = props.divisi.deskripsi || '';
                logoPreview.value = props.divisi.logo
                    ? `/storage/${props.divisi.logo}`
                    : null;
            } else {
                form.reset();
                logoPreview.value = null;
            }
        }
    },
);

const handleFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.logo = target.files[0];
        logoPreview.value = URL.createObjectURL(target.files[0]);
    }
};

const submit = () => {
    if (props.divisi) {
        form.transform((data) => ({
            ...data,
            _method: 'put',
        })).post(route('divisis.update', props.divisi.id), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    } else {
        form.transform((data) => data).post(route('divisis.store'), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    }
};

const closeModal = () => {
    emit('close');
    form.reset();
    form.clearErrors();
    if (fileInput.value) {
        fileInput.value.value = '';
    }
};
</script>

<template>
    <Modal :show="show" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ divisi ? 'Edit Divisi' : 'Tambah Divisi' }}
            </h2>

            <form @submit.prevent="submit" class="mt-6 space-y-6">
                <div>
                    <InputLabel for="nama" value="Nama Divisi" />
                    <TextInput
                        id="nama"
                        v-model="form.nama"
                        type="text"
                        class="mt-1 block w-full"
                        required
                    />
                    <InputError :message="form.errors.nama" class="mt-2" />
                </div>

                <div>
                    <InputLabel for="deskripsi" value="Deskripsi (Opsional)" />
                    <textarea
                        id="deskripsi"
                        v-model="form.deskripsi"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600"
                        rows="3"
                    ></textarea>
                    <InputError :message="form.errors.deskripsi" class="mt-2" />
                </div>

                <div>
                    <InputLabel for="logo" value="Logo Divisi (Opsional)" />
                    <input
                        id="logo"
                        ref="fileInput"
                        type="file"
                        accept="image/*"
                        @change="handleFileChange"
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:rounded-md file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300"
                    />
                    <InputError :message="form.errors.logo" class="mt-2" />

                    <div v-if="logoPreview" class="mt-4">
                        <p class="mb-2 text-sm text-gray-500">Preview:</p>
                        <img
                            :src="logoPreview"
                            alt="Logo Preview"
                            class="h-20 w-20 rounded-md border border-gray-200 object-contain dark:border-gray-700"
                        />
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal">
                        Batal
                    </SecondaryButton>

                    <PrimaryButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Simpan
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>
