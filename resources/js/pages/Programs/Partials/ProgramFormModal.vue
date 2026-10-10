<script setup lang="ts">
import AppButton from '@/Components/AppButton.vue';
import AppIcon from '@/Components/AppIcon.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, ref, watch } from 'vue';
import type { DivisiOption, PengurusOption, ProgramItem } from '../types';

const MAX_FOTO_BYTES = 2 * 1024 * 1024; // 2 MB

const props = defineProps<{
    show: boolean;
    program: ProgramItem | null;
    divisis: DivisiOption[];
    penguruses: PengurusOption[];
}>();

const emit = defineEmits<{ (event: 'close'): void }>();

const isEditing = computed(() => props.program !== null);

const form = useForm<{
    divisi_id: number | '';
    pengurus_id: number | '';
    judul_program: string;
    slug: string;
    location_name: string;
    deskripsi: string;
    foto: File | null;
    hapus_foto: boolean;
    open_regis_panitia: string;
    close_regis_panitia: string;
    gform_panitia: string;
    open_regis_peserta: string;
    close_regis_peserta: string;
    gform_peserta: string;
}>({
    divisi_id: '',
    pengurus_id: '',
    judul_program: '',
    slug: '',
    location_name: '',
    deskripsi: '',
    foto: null,
    hapus_foto: false,
    open_regis_panitia: '',
    close_regis_panitia: '',
    gform_panitia: '',
    open_regis_peserta: '',
    close_regis_peserta: '',
    gform_peserta: '',
});

const fotoInput = ref<HTMLInputElement | null>(null);
const previewUrl = ref<string | null>(null);
const fotoClientError = ref('');
const autoSlug = ref(true);

const currentFoto = computed(() => {
    if (previewUrl.value) return previewUrl.value;
    if (form.hapus_foto) return null;
    return props.program?.foto_url ?? null;
});

function revokePreview() {
    if (previewUrl.value) URL.revokeObjectURL(previewUrl.value);
    previewUrl.value = null;
}

function slugify(text: string): string {
    return text
        .toString()
        .toLowerCase()
        .trim()
        .replace(/\s+/g, '-')
        .replace(/[^\w-]+/g, '')
        .replace(/-{2,}/g, '-');
}

function onJudulChange() {
    if (autoSlug.value) {
        form.slug = slugify(form.judul_program);
    }
}

function resetForm() {
    form.clearErrors();
    form.divisi_id = props.divisis[0]?.id ?? '';
    form.pengurus_id = '';
    form.judul_program = '';
    form.slug = '';
    form.location_name = '';
    form.deskripsi = '';
    form.foto = null;
    form.hapus_foto = false;
    form.open_regis_panitia = '';
    form.close_regis_panitia = '';
    form.gform_panitia = '';
    form.open_regis_peserta = '';
    form.close_regis_peserta = '';
    form.gform_peserta = '';
    autoSlug.value = true;
    revokePreview();
    fotoClientError.value = '';
    if (fotoInput.value) fotoInput.value.value = '';
}

function populateFromProgram(p: ProgramItem) {
    form.clearErrors();
    revokePreview();
    fotoClientError.value = '';
    if (fotoInput.value) fotoInput.value.value = '';

    form.divisi_id = p.divisi_id;
    form.pengurus_id = p.pengurus_id ?? '';
    form.judul_program = p.judul_program;
    form.slug = p.slug;
    form.location_name = p.location_name || '';
    form.deskripsi = p.deskripsi || '';
    form.foto = null;
    form.hapus_foto = false;
    form.open_regis_panitia = p.open_regis_panitia ?? '';
    form.close_regis_panitia = p.close_regis_panitia ?? '';
    form.gform_panitia = p.gform_panitia ?? '';
    form.open_regis_peserta = p.open_regis_peserta ?? '';
    form.close_regis_peserta = p.close_regis_peserta ?? '';
    form.gform_peserta = p.gform_peserta ?? '';
    autoSlug.value = false;
}

watch(
    [() => props.show, () => props.program],
    ([show, program]) => {
        if (!show) {
            revokePreview();
            return;
        }

        if (program) {
            populateFromProgram(program);
        } else {
            resetForm();
        }
    },
    { immediate: true },
);

onBeforeUnmount(revokePreview);

function onFotoChange(event: Event) {
    const file = (event.target as HTMLInputElement).files?.[0] ?? null;
    fotoClientError.value = '';
    revokePreview();

    if (!file) {
        form.foto = null;
        return;
    }

    if (file.size > MAX_FOTO_BYTES) {
        fotoClientError.value = 'Ukuran poster/foto maksimal 2 MB.';
        form.foto = null;
        (event.target as HTMLInputElement).value = '';
        return;
    }

    form.foto = file;
    form.hapus_foto = false;
    previewUrl.value = URL.createObjectURL(file);
}

function removeFoto() {
    revokePreview();
    form.foto = null;
    if (fotoInput.value) fotoInput.value.value = '';
    form.hapus_foto = Boolean(props.program?.foto_url);
}

function close() {
    if (!form.processing) emit('close');
}

function submit() {
    if (form.processing) return;

    form.transform((data) => ({
        ...data,
        judul_program: data.judul_program.trim(),
        slug: data.slug.trim(),
        location_name: data.location_name.trim(),
        gform_panitia: data.gform_panitia.trim() || null,
        gform_peserta: data.gform_peserta.trim() || null,
        open_regis_panitia: data.open_regis_panitia || null,
        close_regis_panitia: data.close_regis_panitia || null,
        pengurus_id: data.pengurus_id || null,
        ...(props.program ? { _method: 'put' } : {}),
    }));

    const options = {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => emit('close'),
    };

    if (props.program) {
        form.post(route('programs.update', props.program.id), options);
    } else {
        form.post(route('programs.store'), options);
    }
}
</script>

<template>
    <Modal
        :show="show"
        max-width="3xl"
        :closeable="!form.processing"
        @close="close"
    >
        <form
            class="p-6 sm:p-7"
            enctype="multipart/form-data"
            @submit.prevent="submit"
        >
            <!-- Header -->
            <div
                class="flex items-center justify-between border-b border-slate-100 pb-4 dark:border-slate-800"
            >
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/60 dark:text-indigo-400"
                    >
                        <AppIcon name="proker" class-name="h-5 w-5" />
                    </div>
                    <div>
                        <h2
                            class="text-lg font-bold text-slate-900 dark:text-white"
                        >
                            {{
                                isEditing
                                    ? 'Edit Program Kerja'
                                    : 'Tambah Program Kerja Baru'
                            }}
                        </h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            {{
                                isEditing
                                    ? 'Perbarui informasi dan jadwal program kerja.'
                                    : 'Lengkapi formulir untuk menambahkan program kerja ke sistem.'
                            }}
                        </p>
                    </div>
                </div>
                <button
                    type="button"
                    class="rounded-xl p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800 dark:hover:text-slate-200"
                    @click="close"
                >
                    <AppIcon name="close" class-name="h-5 w-5" />
                </button>
            </div>

            <!-- Scrollable Form Body -->
            <div class="mt-6 max-h-[70vh] space-y-6 overflow-y-auto px-1 pr-2">
                <!-- Section 1: Informasi Utama -->
                <div class="space-y-4">
                    <h3
                        class="text-xs font-bold tracking-wider text-slate-400 uppercase dark:text-slate-500"
                    >
                        1. Informasi Pokok
                    </h3>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Divisi -->
                        <div>
                            <InputLabel
                                for="program-divisi"
                                value="Divisi Penyelenggara *"
                            />
                            <select
                                id="program-divisi"
                                v-model="form.divisi_id"
                                class="mt-1 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100"
                                required
                            >
                                <option value="" disabled>Pilih Divisi</option>
                                <option
                                    v-for="d in divisis"
                                    :key="d.id"
                                    :value="d.id"
                                >
                                    {{ d.nama }}
                                </option>
                            </select>
                            <InputError
                                :message="form.errors.divisi_id"
                                class="mt-1.5"
                            />
                        </div>

                        <!-- PIC / Pengurus -->
                        <div>
                            <InputLabel
                                for="program-pengurus"
                                value="PIC / Penanggung Jawab"
                            />
                            <select
                                id="program-pengurus"
                                v-model="form.pengurus_id"
                                class="mt-1 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100"
                            >
                                <option value="">Tidak ada / Pilih Nanti</option>
                                <option
                                    v-for="p in penguruses"
                                    :key="p.id"
                                    :value="p.id"
                                >
                                    {{ p.nama }} - {{ p.jabatan }}
                                </option>
                            </select>
                            <InputError
                                :message="form.errors.pengurus_id"
                                class="mt-1.5"
                            />
                        </div>
                    </div>

                    <!-- Judul Program -->
                    <div>
                        <InputLabel
                            for="program-judul"
                            value="Judul / Nama Program Kerja *"
                        />
                        <TextInput
                            id="program-judul"
                            v-model="form.judul_program"
                            type="text"
                            class="mt-1 block w-full"
                            placeholder="Contoh: LAOS Web & API Intensive Bootcamp"
                            required
                            @input="onJudulChange"
                        />
                        <InputError
                            :message="form.errors.judul_program"
                            class="mt-1.5"
                        />
                    </div>

                    <!-- Slug & Lokasi Grid -->
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <div class="flex items-center justify-between">
                                <InputLabel
                                    for="program-slug"
                                    value="Slug URL *"
                                />
                                <button
                                    type="button"
                                    class="text-[11px] text-indigo-600 hover:underline dark:text-indigo-400"
                                    @click="
                                        autoSlug = !autoSlug;
                                        if (autoSlug) onJudulChange();
                                    "
                                >
                                    {{ autoSlug ? 'Edit Manual' : 'Otomatis' }}
                                </button>
                            </div>
                            <TextInput
                                id="program-slug"
                                v-model="form.slug"
                                type="text"
                                class="mt-1 block w-full font-mono text-xs"
                                placeholder="laos-web-bootcamp"
                                :readonly="autoSlug"
                                required
                            />
                            <InputError
                                :message="form.errors.slug"
                                class="mt-1.5"
                            />
                        </div>

                        <div>
                            <InputLabel
                                for="program-lokasi"
                                value="Lokasi / Tempat Kegiatan"
                            />
                            <TextInput
                                id="program-lokasi"
                                v-model="form.location_name"
                                type="text"
                                class="mt-1 block w-full"
                                placeholder="Contoh: Gedung Fasilkom UNEJ / Hybrid Zoom"
                            />
                            <InputError
                                :message="form.errors.location_name"
                                class="mt-1.5"
                            />
                        </div>
                    </div>

                    <!-- Deskripsi Program Kerja -->
                    <div>
                        <InputLabel
                            for="program-deskripsi"
                            value="Deskripsi Program Kerja"
                        />
                        <textarea
                            id="program-deskripsi"
                            v-model="form.deskripsi"
                            rows="3"
                            class="mt-1 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 shadow-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100 dark:placeholder-slate-500"
                            placeholder="Tuliskan deskripsi lengkap mengenai tujuan dan rincian agenda program kerja ini..."
                        />
                        <InputError
                            :message="form.errors.deskripsi"
                            class="mt-1.5"
                        />
                    </div>
                </div>

                <!-- Section 2: Poster / Foto Proker (Storage) -->
                <div
                    class="space-y-3 rounded-2xl border border-slate-200/80 bg-slate-50/50 p-4 dark:border-slate-800 dark:bg-slate-900/50"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <h3
                                class="text-xs font-bold tracking-wider text-slate-500 uppercase dark:text-slate-400"
                            >
                                2. Foto / Poster Kegiatan
                            </h3>
                            <p
                                class="text-xs text-slate-500 dark:text-slate-400"
                            >
                                Disimpan ke storage publik (JPG, PNG, atau WEBP,
                                maks 2 MB).
                            </p>
                        </div>
                        <button
                            v-if="currentFoto"
                            type="button"
                            class="text-xs font-semibold text-rose-600 hover:text-rose-700 dark:text-rose-400"
                            @click="removeFoto"
                        >
                            Hapus Foto
                        </button>
                    </div>

                    <div class="flex flex-col items-center gap-4 sm:flex-row">
                        <!-- Preview Box -->
                        <div
                            class="relative flex h-28 w-28 shrink-0 items-center justify-center overflow-hidden rounded-2xl border-2 border-dashed border-slate-300 bg-white text-slate-400 dark:border-slate-700 dark:bg-slate-900"
                        >
                            <img
                                v-if="currentFoto"
                                :src="currentFoto"
                                alt="Preview"
                                class="h-full w-full object-cover"
                            />
                            <div v-else class="p-2 text-center">
                                <AppIcon
                                    name="proker"
                                    class-name="mx-auto h-6 w-6 text-slate-300"
                                />
                                <span
                                    class="mt-1 block text-[10px] text-slate-400"
                                    >Belum ada foto</span
                                >
                            </div>
                        </div>

                        <!-- Upload Area -->
                        <div class="w-full flex-1">
                            <input
                                ref="fotoInput"
                                type="file"
                                accept="image/png,image/jpeg,image/webp"
                                class="hidden"
                                @change="onFotoChange"
                            />
                            <div
                                class="flex cursor-pointer flex-col items-center justify-center rounded-2xl border border-slate-200 bg-white p-4 text-center transition hover:border-indigo-400 hover:bg-indigo-50/20 dark:border-slate-800 dark:bg-slate-900 dark:hover:border-indigo-500/50"
                                @click="fotoInput?.click()"
                            >
                                <AppIcon
                                    name="plus"
                                    class-name="h-5 w-5 text-indigo-500"
                                />
                                <span
                                    class="mt-1 text-xs font-semibold text-slate-700 dark:text-slate-200"
                                >
                                    {{
                                        currentFoto
                                            ? 'Ganti Poster / Foto'
                                            : 'Pilih Foto / Poster'
                                    }}
                                </span>
                                <span class="text-[11px] text-slate-400">
                                    Klik untuk memilih berkas dari komputer
                                </span>
                            </div>
                            <InputError
                                :message="fotoClientError || form.errors.foto"
                                class="mt-1.5"
                            />
                        </div>
                    </div>
                </div>

                <!-- Section 3: Pendaftaran Peserta (Wajib) -->
                <div
                    class="space-y-4 rounded-2xl border border-emerald-200/80 bg-emerald-50/20 p-4 dark:border-emerald-900/40 dark:bg-emerald-950/20"
                >
                    <div class="flex items-center gap-2">
                        <h3
                            class="text-xs font-bold tracking-wider text-emerald-700 uppercase dark:text-emerald-400"
                        >
                            3. Pendaftaran Peserta (Utama)
                        </h3>
                        <span
                            class="rounded bg-emerald-100 px-1.5 py-0.5 text-[10px] font-bold text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200"
                        >
                            Wajib
                        </span>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <InputLabel
                                for="open-peserta"
                                value="Tanggal Buka Peserta *"
                            />
                            <TextInput
                                id="open-peserta"
                                v-model="form.open_regis_peserta"
                                type="date"
                                class="mt-1 block w-full"
                                required
                            />
                            <InputError
                                :message="form.errors.open_regis_peserta"
                                class="mt-1.5"
                            />
                        </div>
                        <div>
                            <InputLabel
                                for="close-peserta"
                                value="Tanggal Tutup Peserta *"
                            />
                            <TextInput
                                id="close-peserta"
                                v-model="form.close_regis_peserta"
                                type="date"
                                class="mt-1 block w-full"
                                required
                            />
                            <InputError
                                :message="form.errors.close_regis_peserta"
                                class="mt-1.5"
                            />
                        </div>
                    </div>

                    <div>
                        <InputLabel
                            for="gform-peserta"
                            value="Tautan Google Form / Link Pendaftaran Peserta"
                        />
                        <TextInput
                            id="gform-peserta"
                            v-model="form.gform_peserta"
                            type="url"
                            class="mt-1 block w-full"
                            placeholder="https://forms.gle/..."
                        />
                        <InputError
                            :message="form.errors.gform_peserta"
                            class="mt-1.5"
                        />
                    </div>
                </div>

                <!-- Section 4: Pendaftaran Panitia (Opsional) -->
                <div
                    class="space-y-4 rounded-2xl border border-slate-200/80 bg-slate-50/40 p-4 dark:border-slate-800 dark:bg-slate-900/40"
                >
                    <h3
                        class="text-xs font-bold tracking-wider text-slate-500 uppercase dark:text-slate-400"
                    >
                        4. Pendaftaran Panitia / Volunteer (Opsional)
                    </h3>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <InputLabel
                                for="open-panitia"
                                value="Tanggal Buka Panitia"
                            />
                            <TextInput
                                id="open-panitia"
                                v-model="form.open_regis_panitia"
                                type="date"
                                class="mt-1 block w-full"
                            />
                            <InputError
                                :message="form.errors.open_regis_panitia"
                                class="mt-1.5"
                            />
                        </div>
                        <div>
                            <InputLabel
                                for="close-panitia"
                                value="Tanggal Tutup Panitia"
                            />
                            <TextInput
                                id="close-panitia"
                                v-model="form.close_regis_panitia"
                                type="date"
                                class="mt-1 block w-full"
                            />
                            <InputError
                                :message="form.errors.close_regis_panitia"
                                class="mt-1.5"
                            />
                        </div>
                    </div>

                    <div>
                        <InputLabel
                            for="gform-panitia"
                            value="Tautan Google Form Pendaftaran Panitia"
                        />
                        <TextInput
                            id="gform-panitia"
                            v-model="form.gform_panitia"
                            type="url"
                            class="mt-1 block w-full"
                            placeholder="https://forms.gle/..."
                        />
                        <InputError
                            :message="form.errors.gform_panitia"
                            class="mt-1.5"
                        />
                    </div>
                </div>
            </div>

            <!-- Footer Action Buttons -->
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
                    :loading="form.processing"
                    :disabled="form.processing"
                >
                    {{
                        isEditing ? 'Simpan Perubahan' : 'Tambah Program Kerja'
                    }}
                </AppButton>
            </div>
        </form>
    </Modal>
</template>
