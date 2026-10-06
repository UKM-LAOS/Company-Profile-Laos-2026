<script setup lang="ts">
import AppButton from '@/Components/AppButton.vue';
import AppIcon from '@/Components/AppIcon.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { computed, watch } from 'vue';
import type { ShortlinkItem } from '../types';

const props = defineProps<{ show: boolean; shortlink: ShortlinkItem | null }>();
const emit = defineEmits<{ (event: 'close'): void }>();
const isEditing = computed(() => props.shortlink !== null);
const form = useForm({
    short_code: '',
    destination_url: '',
    is_active: true,
    expires_at: '',
});

function localDateTime(value: string | null): string {
    if (!value) return '';
    const date = new Date(value);
    if (Number.isNaN(date.getTime())) return '';
    const localDate = new Date(
        date.getTime() - date.getTimezoneOffset() * 60_000,
    );
    return localDate.toISOString().slice(0, 19);
}

watch(
    () => props.show,
    (show) => {
        if (!show) return;
        form.clearErrors();
        form.reset();
        if (props.shortlink) {
            form.short_code = props.shortlink.short_code;
            form.destination_url = props.shortlink.destination_url;
            form.is_active = props.shortlink.is_active;
            form.expires_at = localDateTime(props.shortlink.expires_at);
        }
    },
);

function close() {
    if (!form.processing) emit('close');
}

function submit() {
    if (form.processing) return;
    form.transform((data) => ({
        ...data,
        short_code: data.short_code.trim().toLowerCase(),
        destination_url: data.destination_url.trim(),
        expires_at: data.expires_at
            ? new Date(data.expires_at).toISOString()
            : null,
    }));
    const options = { preserveScroll: true, onSuccess: () => emit('close') };
    if (props.shortlink) {
        form.put(route('shortlinks.update', props.shortlink.id), options);
    } else {
        form.post(route('shortlinks.store'), options);
    }
}
</script>

<template>
    <Modal :show="show" :closeable="!form.processing" @close="close">
        <div
            class="p-6 sm:p-7"
            role="dialog"
            aria-modal="true"
            aria-labelledby="shortlink-form-title"
        >
            <div
                class="flex items-center justify-between border-b border-slate-100 pb-4 dark:border-slate-800"
            >
                <h2
                    id="shortlink-form-title"
                    class="text-base font-bold text-slate-900 dark:text-white"
                >
                    {{ isEditing ? 'Ubah Shortlink' : 'Tambah Shortlink' }}
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
                <div>
                    <InputLabel for="shortlink-code" value="Kode Shortlink" />
                    <TextInput
                        id="shortlink-code"
                        v-model="form.short_code"
                        class="mt-1.5"
                        type="text"
                        maxlength="50"
                        pattern="[A-Za-z0-9_\-]+"
                        placeholder="contoh: pendaftaran-2026"
                        autocomplete="off"
                        required
                        :disabled="form.processing"
                    />
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                        1–50 karakter: huruf, angka, tanda hubung, atau garis
                        bawah. Disimpan dalam huruf kecil.
                    </p>
                    <InputError
                        :message="form.errors.short_code"
                        class="mt-1"
                    />
                </div>
                <div>
                    <InputLabel
                        for="shortlink-destination"
                        value="URL Tujuan"
                    />
                    <TextInput
                        id="shortlink-destination"
                        v-model="form.destination_url"
                        class="mt-1.5"
                        type="url"
                        maxlength="2048"
                        pattern="[Hh][Tt][Tt][Pp][Ss]?://.*"
                        placeholder="https://contoh.com/pendaftaran"
                        required
                        :disabled="form.processing"
                    />
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                        Gunakan URL dengan protokol http:// atau https://.
                    </p>
                    <InputError
                        :message="form.errors.destination_url"
                        class="mt-1"
                    />
                </div>
                <div>
                    <InputLabel
                        for="shortlink-expiry"
                        value="Kedaluwarsa (Opsional)"
                    />
                    <TextInput
                        id="shortlink-expiry"
                        v-model="form.expires_at"
                        class="mt-1.5"
                        type="datetime-local"
                        step="1"
                        :min="localDateTime('1970-01-01T00:00:01Z')"
                        :max="localDateTime('2038-01-19T03:14:07Z')"
                        :disabled="form.processing"
                    />
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                        Waktu lokal perangkat Anda. Kosongkan untuk tanpa batas
                        waktu. Tanggal yang didukung antara 1970 dan Januari
                        2038.
                    </p>
                    <InputError
                        :message="form.errors.expires_at"
                        class="mt-1"
                    />
                </div>
                <div>
                    <label
                        for="shortlink-active"
                        class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300"
                    >
                        <input
                            id="shortlink-active"
                            v-model="form.is_active"
                            type="checkbox"
                            :disabled="form.processing"
                            class="h-4 w-4 rounded border-slate-300 accent-emerald-600"
                        />
                        Aktif
                    </label>
                    <InputError :message="form.errors.is_active" class="mt-1" />
                </div>
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
                            isEditing ? 'Simpan Perubahan' : 'Tambah Shortlink'
                        }}</AppButton
                    >
                </div>
            </form>
        </div>
    </Modal>
</template>
