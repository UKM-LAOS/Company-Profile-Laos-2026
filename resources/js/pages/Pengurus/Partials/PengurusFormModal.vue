<script setup lang="ts">
import AppButton from '@/Components/AppButton.vue';
import AppIcon from '@/Components/AppIcon.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, ref, watch } from 'vue';
import {
    initials,
    SOSMED_KEYS,
    SOSMED_LABELS,
    type PengurusItem,
    type SosmedKey,
} from '../types';

const MAX_FOTO_BYTES = 2 * 1024 * 1024;

const props = defineProps<{ show: boolean; pengurus: PengurusItem | null }>();
const emit = defineEmits<{ (event: 'close'): void }>();
const isEditing = computed(() => props.pengurus !== null);

const form = useForm<{
    nama: string;
    jabatan: string;
    periode: string;
    urutan: number;
    aktif: boolean;
    foto: File | null;
    hapus_foto: boolean;
    sosmed: Record<SosmedKey, string>;
}>({
    nama: '',
    jabatan: '',
    periode: defaultPeriode(),
    urutan: 0,
    aktif: true,
    foto: null,
    hapus_foto: false,
    sosmed: { instagram: '', linkedin: '', github: '' },
});

const fotoInput = ref<HTMLInputElement | null>(null);
const previewUrl = ref<string | null>(null);
const fotoClientError = ref('');

const currentFoto = computed(() => {
    if (previewUrl.value) return previewUrl.value;
    if (form.hapus_foto) return null;
    return props.pengurus?.foto_url ?? null;
});

const sosmedErrors = computed(() => {
    const errors = form.errors as Record<string, string | undefined>;
    return Object.fromEntries(
        SOSMED_KEYS.map((key) => [key, errors[`sosmed.${key}`]]),
    ) as Record<SosmedKey, string | undefined>;
});

function defaultPeriode(): string {
    return '2025/2026';
}

function revokePreview() {
    if (previewUrl.value) URL.revokeObjectURL(previewUrl.value);
    previewUrl.value = null;
}

function resetToEmpty() {
    form.clearErrors();
    form.nama = '';
    form.jabatan = '';
    form.periode = defaultPeriode();
    form.urutan = 0;
    form.aktif = true;
    form.foto = null;
    form.hapus_foto = false;
    form.sosmed = {
        instagram: '',
        linkedin: '',
        github: '',
    };
    revokePreview();
    fotoClientError.value = '';
    if (fotoInput.value) fotoInput.value.value = '';
}

function populateFromPengurus(p: PengurusItem) {
    form.clearErrors();
    revokePreview();
    fotoClientError.value = '';
    if (fotoInput.value) fotoInput.value.value = '';

    form.nama = p.nama;
    form.jabatan = p.jabatan;
    form.periode = p.periode;
    form.urutan = p.urutan;
    form.aktif = p.aktif;
    form.foto = null;
    form.hapus_foto = false;
    form.sosmed = {
        instagram: p.sosmed?.instagram ?? '',
        linkedin: p.sosmed?.linkedin ?? '',
        github: p.sosmed?.github ?? '',
    };
}

watch(
    [() => props.show, () => props.pengurus],
    ([show, pengurus]) => {
        if (!show) {
            revokePreview();
            return;
        }

        if (pengurus) {
            populateFromPengurus(pengurus);
        } else {
            resetToEmpty();
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
        fotoClientError.value = 'Ukuran foto maksimal 2 MB.';
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
    // Only meaningful for an existing record that already has a photo.
    form.hapus_foto = Boolean(props.pengurus?.foto_url);
}

function close() {
    if (!form.processing) emit('close');
}

function submit() {
    if (form.processing) return;

    form.transform((data) => ({
        ...data,
        nama: data.nama.trim(),
        jabatan: data.jabatan.trim(),
        periode: data.periode.trim(),
        sosmed: Object.fromEntries(
            SOSMED_KEYS.map((key) => [key, data.sosmed[key].trim()]),
        ),
        // Multipart uploads cannot use PUT directly; spoof it for updates.
        ...(props.pengurus ? { _method: 'put' } : {}),
    }));

    const options = {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => emit('close'),
    };

    if (props.pengurus) {
        form.post(route('pengurus.update', props.pengurus.id), options);
    } else {
        form.post(route('pengurus.store'), options);
    }
}
</script>

<template>
    <Modal :show="show" :closeable="!form.processing" @close="close">
        <div
            class="p-6 sm:p-7"
            role="dialog"
            aria-modal="true"
            aria-labelledby="pengurus-form-title"
        >
            <div
                class="flex items-center justify-between border-b border-slate-100 pb-4 dark:border-slate-800"
            >
                <h2
                    id="pengurus-form-title"
                    class="text-base font-bold text-slate-900 dark:text-white"
                >
                    {{ isEditing ? 'Ubah Data Pengurus' : 'Tambah Pengurus' }}
                </h2>
                <button
                    type="button"
                    aria-label="Tutup formulir"
                    :disabled="form.processing"
                    class="cursor-pointer rounded-xl p-1.5 text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800"
                    @click="close"
                >
                    <AppIcon name="close" class-name="h-4 w-4" />
                </button>
            </div>

            <form class="mt-5 space-y-4" @submit.prevent="submit">
                <!-- Foto -->
                <div class="flex items-center gap-4">
                    <img
                        v-if="currentFoto"
                        :src="currentFoto"
                        alt="Pratinjau foto pengurus"
                        class="h-16 w-16 shrink-0 rounded-full object-cover ring-2 ring-emerald-100 dark:ring-emerald-900"
                    />
                    <span
                        v-else
                        class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-slate-100 text-lg font-bold text-slate-400 dark:bg-slate-800 dark:text-slate-500"
                        aria-hidden="true"
                        >{{ form.nama ? initials(form.nama) : '?' }}</span
                    >
                    <div class="min-w-0 flex-1">
                        <InputLabel
                            for="pengurus-foto"
                            value="Foto (Opsional)"
                        />
                        <div class="mt-1.5 flex flex-wrap items-center gap-2">
                            <input
                                id="pengurus-foto"
                                ref="fotoInput"
                                type="file"
                                accept="image/jpeg,image/png,image/webp"
                                :disabled="form.processing"
                                class="block w-full max-w-xs cursor-pointer text-xs text-slate-500 file:mr-3 file:cursor-pointer file:rounded-lg file:border-0 file:bg-emerald-50 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100 dark:text-slate-400 dark:file:bg-emerald-950/60 dark:file:text-emerald-300"
                                @change="onFotoChange"
                            />
                            <button
                                v-if="currentFoto"
                                type="button"
                                :disabled="form.processing"
                                class="cursor-pointer text-xs font-semibold text-rose-600 hover:text-rose-700 dark:text-rose-400"
                                @click="removeFoto"
                            >
                                Hapus foto
                            </button>
                        </div>
                        <p
                            class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                        >
                            JPG, PNG, atau WEBP. Maksimal 2 MB.
                        </p>
                        <InputError
                            :message="fotoClientError || form.errors.foto"
                            class="mt-1"
                        />
                    </div>
                </div>

                <div>
                    <InputLabel for="pengurus-nama" value="Nama Lengkap" />
                    <TextInput
                        id="pengurus-nama"
                        v-model="form.nama"
                        class="mt-1.5"
                        type="text"
                        maxlength="255"
                        placeholder="contoh: Agung Kurniawan"
                        autocomplete="off"
                        required
                        :disabled="form.processing"
                    />
                    <InputError :message="form.errors.nama" class="mt-1" />
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <InputLabel for="pengurus-jabatan" value="Jabatan" />
                        <TextInput
                            id="pengurus-jabatan"
                            v-model="form.jabatan"
                            class="mt-1.5"
                            type="text"
                            maxlength="255"
                            list="pengurus-jabatan-suggestions"
                            placeholder="contoh: Ketua Umum"
                            autocomplete="off"
                            required
                            :disabled="form.processing"
                        />
                        <datalist id="pengurus-jabatan-suggestions">
                            <option value="Ketua Umum" />
                            <option value="Wakil Ketua Umum" />
                            <option value="Sekretaris Umum" />
                            <option value="Bendahara Umum" />
                            <option value="Koordinator Divisi" />
                        </datalist>
                        <InputError
                            :message="form.errors.jabatan"
                            class="mt-1"
                        />
                    </div>
                    <div>
                        <InputLabel for="pengurus-periode" value="Periode" />
                        <TextInput
                            id="pengurus-periode"
                            v-model="form.periode"
                            class="mt-1.5"
                            type="text"
                            maxlength="9"
                            pattern="\d{4}/\d{4}"
                            placeholder="2025/2026"
                            autocomplete="off"
                            required
                            :disabled="form.processing"
                        />
                        <InputError
                            :message="form.errors.periode"
                            class="mt-1"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <InputLabel
                            for="pengurus-urutan"
                            value="Urutan Tampil"
                        />
                        <input
                            id="pengurus-urutan"
                            v-model.number="form.urutan"
                            class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 shadow-xs transition-colors duration-150 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none disabled:cursor-not-allowed disabled:bg-slate-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:placeholder-slate-500 dark:focus:border-emerald-500 dark:focus:ring-emerald-500/20"
                            type="number"
                            min="0"
                            max="9999"
                            step="1"
                            required
                            :disabled="form.processing"
                        />
                        <p
                            class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                        >
                            Angka kecil tampil lebih dulu di Tentang Kami.
                        </p>
                        <InputError
                            :message="form.errors.urutan"
                            class="mt-1"
                        />
                    </div>
                    <div class="flex items-start sm:pt-8">
                        <label
                            for="pengurus-aktif"
                            class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300"
                        >
                            <input
                                id="pengurus-aktif"
                                v-model="form.aktif"
                                type="checkbox"
                                :disabled="form.processing"
                                class="h-4 w-4 rounded border-slate-300 accent-emerald-600"
                            />
                            Aktif (tampil di Tentang Kami)
                        </label>
                        <InputError :message="form.errors.aktif" class="mt-1" />
                    </div>
                </div>

                <fieldset
                    class="space-y-3 rounded-2xl border border-slate-100 p-4 dark:border-slate-800"
                >
                    <legend
                        class="px-1 text-xs font-bold tracking-wider text-slate-400 uppercase"
                    >
                        Sosial Media (Opsional)
                    </legend>
                    <div v-for="key in SOSMED_KEYS" :key="key">
                        <InputLabel
                            :for="`pengurus-sosmed-${key}`"
                            :value="SOSMED_LABELS[key]"
                        />
                        <TextInput
                            :id="`pengurus-sosmed-${key}`"
                            v-model="form.sosmed[key]"
                            class="mt-1.5"
                            type="url"
                            maxlength="255"
                            pattern="[Hh][Tt][Tt][Pp][Ss]?://.*"
                            :placeholder="`https://${key === 'linkedin' ? 'linkedin.com/in' : key + '.com'}/username`"
                            :disabled="form.processing"
                        />
                        <InputError :message="sosmedErrors[key]" class="mt-1" />
                    </div>
                    <InputError
                        :message="
                            (form.errors as Record<string, string | undefined>)
                                .sosmed
                        "
                    />
                </fieldset>

                <div
                    class="mt-6 flex flex-col-reverse gap-2.5 border-t border-slate-100 pt-4 sm:flex-row sm:justify-end sm:gap-3 dark:border-slate-800"
                >
                    <AppButton
                        type="button"
                        variant="secondary"
                        :disabled="form.processing"
                        @click="close"
                        >Batal</AppButton
                    >
                    <AppButton
                        type="submit"
                        variant="primary"
                        icon="check"
                        :loading="form.processing"
                        :disabled="form.processing"
                        >{{
                            isEditing ? 'Simpan Perubahan' : 'Tambah Pengurus'
                        }}</AppButton
                    >
                </div>
            </form>
        </div>
    </Modal>
</template>
