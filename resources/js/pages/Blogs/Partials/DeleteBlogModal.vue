<script setup lang="ts">
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps<{
    show: boolean;
    blog: { id: number; judul: string } | null;
}>();

const emit = defineEmits<{
    (e: 'close'): void;
}>();

const titleInput = ref<HTMLInputElement | null>(null);

const form = useForm({});

watch(
    () => props.show,
    (show) => {
        if (!show) {
            form.reset();
            form.clearErrors();
        }
    },
);

const hapusBerita = () => {
    if (!props.blog) return;

    form.delete(route('blogs.destroy', props.blog.id), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => titleInput.value?.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    emit('close');
};
</script>

<template>
    <Modal :show="show" max-width="md" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-slate-900 dark:text-slate-100">
                Konfirmasi Hapus Berita
            </h2>

            <p class="mt-3 text-sm text-slate-600 dark:text-slate-400">
                Apakah Anda yakin ingin menghapus berita
                <span class="font-semibold text-slate-900 dark:text-white"
                    >"{{ blog?.judul }}"</span
                >? Data berita ini akan dihapus dari sistem dan tidak akan
                ditampilkan lagi kepada publik.
            </p>

            <div class="mt-6 flex justify-end gap-3">
                <SecondaryButton @click="closeModal">Batal</SecondaryButton>

                <DangerButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="hapusBerita"
                >
                    Ya, Hapus Berita
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>
