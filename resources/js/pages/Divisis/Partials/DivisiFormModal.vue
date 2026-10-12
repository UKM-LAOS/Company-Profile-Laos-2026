<script setup lang="ts">
import AppButton from '@/Components/AppButton.vue';
import AppIcon from '@/Components/AppIcon.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const MAX_LOGO_BYTES = 2 * 1024 * 1024;

const props = defineProps<{
    show: boolean;
    divisi?: any;
}>();

const emit = defineEmits<{
    (event: 'close'): void;
    (event: 'saved'): void;
}>();

const isEditing = computed(() => Boolean(props.divisi));

const form = useForm({
    nama: '',
    deskripsi: '',
    logo: null as File | null,
});

const logoPreview = ref<string | null>(null);
const fileInput = ref<HTMLInputElement | null>(null);
const logoError = ref('');

watch(
    () => props.show,
    (show) => {
        if (show) {
            logoError.value = '';
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
    logoError.value = '';
    const target = e.target as HTMLInputElement;
    const file = target.files?.[0];

    if (!file) {
        form.logo = null;
        return;
    }

    if (file.size > MAX_LOGO_BYTES) {
        logoError.value = 'Ukuran logo maksimal 2 MB.';
        form.logo = null;
        target.value = '';
        return;
    }

    form.logo = file;
    logoPreview.value = URL.createObjectURL(file);
};

const removeLogo = () => {
    form.logo = null;
    logoPreview.value = null;
    if (fileInput.value) {
        fileInput.value.value = '';
    }
};

const close = () => {
    if (form.processing) return;
    emit('close');
    form.reset();
    form.clearErrors();
    logoError.value = '';
    if (fileInput.value) {
        fileInput.value.value = '';
    }
};

const submit = () => {
    if (form.processing) return;

    if (props.divisi) {
        form.transform((data) => ({
            ...data,
            nama: data.nama.trim(),
            deskripsi: data.deskripsi?.trim() || '',
            _method: 'put',
        })).post(route('divisis.update', props.divisi.id), {
            preserveScroll: true,
            forceFormData: true,
            onSuccess: () => {
                emit('saved');
                close();
            },
        });
    } else {
        form.transform((data) => ({
            ...data,
            nama: data.nama.trim(),
            deskripsi: data.deskripsi?.trim() || '',
        })).post(route('divisis.store'), {
            preserveScroll: true,
            forceFormData: true,
            onSuccess: () => {
                emit('saved');
                close();
            },
        });
    }
};
</script>

<template>
    <Modal :show="show" :closeable="!form.processing" @close="close">
        <div class="p-6 sm:p-7" role="dialog" aria-modal="true">
            <!-- Header Modal -->
            <div
                class="flex items-center justify-between border-b border-slate-100 pb-4 dark:border-slate-800"
            >
                <div>
                    <h2
                        class="text-base font-bold text-slate-900 dark:text-white"
                    >
                        {{ isEditing ? 'Ubah Data Divisi' : 'Tambah Divisi' }}
                    </h2>
                    <p
                        class="mt-0.5 text-xs text-slate-500 dark:text-slate-400"
                    >
                        {{
                            isEditing
                                ? 'Perbarui informasi divisi dan logo identitas.'
                                : 'Tambahkan struktur divisi baru ke dalam organisasi.'
                        }}
                    </p>
                </div>

                <button
                    type="button"
                    aria-label="Tutup formulir"
                    :disabled="form.processing"
                    class="cursor-pointer rounded-xl p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800 dark:hover:text-slate-300"
                    @click="close"
                >
                    <AppIcon name="close" class-name="h-4 w-4" />
                </button>
            </div>

            <!-- Form Body -->
            <form class="mt-5 space-y-4" @submit.prevent="submit">
                <!-- Nama Divisi -->
                <div>
                    <InputLabel for="nama" value="Nama Divisi" />
                    <TextInput
                        id="nama"
                        v-model="form.nama"
                        type="text"
                        class="mt-1.5 block w-full"
                        placeholder="Contoh: Web Development"
                        required
                        :disabled="form.processing"
                    />
                    <InputError :message="form.errors.nama" class="mt-1.5" />
                </div>

                <!-- Deskripsi -->
                <div>
                    <InputLabel for="deskripsi" value="Deskripsi Divisi" />
                    <textarea
                        id="deskripsi"
                        v-model="form.deskripsi"
                        rows="3"
                        class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-xs text-slate-900 placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none disabled:cursor-not-allowed disabled:bg-slate-50 disabled:text-slate-400 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 dark:disabled:bg-slate-800 dark:disabled:text-slate-400"
                        placeholder="Tuliskan fokus divisi, ruang lingkup kerja, atau tujuan bidang..."
                        :disabled="form.processing"
                    ></textarea>
                    <InputError
                        :message="form.errors.deskripsi"
                        class="mt-1.5"
                    />
                </div>

                <!-- Logo Divisi -->
                <div>
                    <InputLabel
                        for="logo"
                        value="Logo / Ikon Divisi (Opsional)"
                    />
                    <div class="mt-1.5 flex items-center gap-4">
                        <!-- Preview Box -->
                        <div
                            class="relative flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 shadow-xs dark:border-slate-700 dark:bg-slate-800"
                        >
                            <img
                                v-if="logoPreview"
                                :src="logoPreview"
                                alt="Logo Preview"
                                class="h-full w-full object-cover"
                            />
                            <AppIcon
                                v-else
                                name="divisi"
                                class-name="h-6 w-6 text-slate-300 dark:text-slate-600"
                            />
                        </div>

                        <!-- Upload & Remove Buttons -->
                        <div class="flex-1">
                            <input
                                id="logo"
                                ref="fileInput"
                                type="file"
                                accept="image/png,image/jpeg,image/webp,image/svg+xml"
                                class="hidden"
                                @change="handleFileChange"
                            />
                            <div class="flex items-center gap-2">
                                <AppButton
                                    type="button"
                                    variant="secondary"
                                    size="sm"
                                    :disabled="form.processing"
                                    @click="fileInput?.click()"
                                >
                                    {{
                                        logoPreview
                                            ? 'Ganti Logo'
                                            : 'Pilih Logo'
                                    }}
                                </AppButton>
                                <button
                                    v-if="logoPreview"
                                    type="button"
                                    class="text-xs text-rose-500 hover:text-rose-700 dark:text-rose-400"
                                    @click="removeLogo"
                                >
                                    Hapus
                                </button>
                            </div>
                            <p
                                class="mt-1 text-[11px] text-slate-400 dark:text-slate-500"
                            >
                                Format PNG, JPG, WebP atau SVG. Maksimal 2 MB.
                            </p>
                        </div>
                    </div>
                    <InputError
                        :message="logoError || form.errors.logo"
                        class="mt-1.5"
                    />
                </div>

                <!-- Action Buttons -->
                <div
                    class="mt-6 flex flex-col-reverse gap-2.5 border-t border-slate-100 pt-4 sm:flex-row sm:justify-end sm:gap-3 dark:border-slate-800"
                >
                    <AppButton
                        type="button"
                        variant="secondary"
                        :disabled="form.processing"
                        @click="close"
                    >
                        Batal
                    </AppButton>
                    <AppButton
                        type="submit"
                        variant="primary"
                        icon="check"
                        :loading="form.processing"
                        :disabled="form.processing"
                    >
                        {{ isEditing ? 'Simpan Perubahan' : 'Tambah Divisi' }}
                    </AppButton>
                </div>
            </form>
        </div>
    </Modal>
</template>
