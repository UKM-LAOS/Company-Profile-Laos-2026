<script setup lang="ts">
import AppButton from '@/Components/AppButton.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import AppDropdownFilter from '@/Components/AppDropdownFilter.vue';
import TiptapEditor from '@/Components/Common/TiptapEditor.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    blog?: {
        id: number;
        judul: string;
        kategori: string;
        konten: string;
        meta_description: string | null;
        is_unggulan: boolean;
        status: string;
        divisi_id: number;
    };
    divisis: { id: number; nama: string }[];
}>();

const isEdit = !!props.blog;

const form = useForm({
    judul: props.blog?.judul ?? '',
    kategori: props.blog?.kategori ?? '',
    konten: props.blog?.konten ?? '',
    meta_description: props.blog?.meta_description ?? '',
    is_unggulan: props.blog?.is_unggulan ?? false,
    status: props.blog?.status ?? 'draft',
    divisi_id: props.blog?.divisi_id ? String(props.blog.divisi_id) : '',
});

const divisiOptions = computed(() => {
    return props.divisis.map((d) => ({
        value: String(d.id),
        label: d.nama,
    }));
});

const statusOptions = [
    { value: 'draft', label: 'Draf (Simpan tanpa dipublikasikan)' },
    { value: 'published', label: 'Terbit (Publikasikan sekarang)' },
];

function submit() {
    if (isEdit && props.blog) {
        form.put(route('blogs.update', props.blog.id));
    } else {
        form.post(route('blogs.store'));
    }
}
</script>

<template>
    <Head :title="isEdit ? 'Ubah Berita' : 'Tambah Berita'" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link
                    :href="route('blogs.index')"
                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 transition-colors hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800"
                    title="Kembali"
                >
                    <span class="sr-only">Kembali</span>
                    <svg
                        class="h-4 w-4"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <polyline points="15 18 9 12 15 6" />
                    </svg>
                </Link>
                <div>
                    <h1
                        class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl dark:text-white"
                    >
                        {{ isEdit ? 'Ubah Berita' : 'Tambah Berita' }}
                    </h1>
                </div>
            </div>
        </template>

        <div
            class="mx-auto max-w-5xl rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs md:p-8 dark:border-slate-800/80 dark:bg-slate-900"
        >
            <form @submit.prevent="submit" class="space-y-8">
                <!-- Data Utama -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="col-span-1 md:col-span-2">
                        <InputLabel
                            for="judul"
                            value="Judul Artikel"
                            required
                        />
                        <TextInput
                            id="judul"
                            v-model="form.judul"
                            type="text"
                            class="mt-1 block w-full"
                            placeholder="Masukkan judul artikel yang menarik..."
                            required
                            autocomplete="off"
                        />
                        <InputError :message="form.errors.judul" class="mt-2" />
                    </div>

                    <div>
                        <InputLabel for="kategori" value="Kategori" required />
                        <TextInput
                            id="kategori"
                            v-model="form.kategori"
                            type="text"
                            class="mt-1 block w-full"
                            placeholder="Contoh: Tutorial Linux, Kegiatan"
                            required
                            autocomplete="off"
                        />
                        <InputError
                            :message="form.errors.kategori"
                            class="mt-2"
                        />
                    </div>

                    <div>
                        <InputLabel
                            for="divisi_id"
                            value="Divisi Terkait"
                            required
                            class="mb-1"
                        />
                        <AppDropdownFilter
                            id="divisi_id"
                            v-model="form.divisi_id"
                            placeholder="Pilih Divisi"
                            :options="divisiOptions"
                            :searchable="true"
                            :full-width="true"
                        />
                        <InputError
                            :message="form.errors.divisi_id"
                            class="mt-2"
                        />
                    </div>
                </div>

                <!-- Konten Editor -->
                <div>
                    <InputLabel value="Konten Artikel" required class="mb-2" />
                    <TiptapEditor
                        v-model="form.konten"
                        :error="form.errors.konten"
                    />
                </div>

                <!-- Metadata & Setting -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="col-span-1 md:col-span-2">
                        <InputLabel
                            for="meta_description"
                            value="Meta Description (Opsional)"
                        />
                        <textarea
                            id="meta_description"
                            v-model="form.meta_description"
                            rows="2"
                            class="mt-1 block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 shadow-xs transition-colors duration-150 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:placeholder-slate-500 dark:focus:border-emerald-500 dark:focus:ring-emerald-500/20"
                            placeholder="Deskripsi singkat untuk keperluan SEO dan pencarian..."
                        ></textarea>
                        <InputError
                            :message="form.errors.meta_description"
                            class="mt-2"
                        />
                    </div>

                    <div>
                        <InputLabel
                            for="status"
                            value="Status Publikasi"
                            required
                            class="mb-1"
                        />
                        <AppDropdownFilter
                            id="status"
                            v-model="form.status"
                            placeholder="Pilih Status"
                            :options="statusOptions"
                            :full-width="true"
                        />
                        <InputError
                            :message="form.errors.status"
                            class="mt-2"
                        />
                    </div>

                    <div class="flex items-center">
                        <div class="mt-6 flex items-center">
                            <Checkbox
                                id="is_unggulan"
                                v-model:checked="form.is_unggulan"
                            />
                            <label
                                for="is_unggulan"
                                class="ml-2 cursor-pointer text-sm font-medium text-slate-700 dark:text-slate-300"
                            >
                                Jadikan Artikel Unggulan (Pinned)
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Aksi -->
                <div
                    class="flex items-center justify-end gap-3 border-t border-slate-200 pt-6 dark:border-slate-800"
                >
                    <Link
                        :href="route('blogs.index')"
                        class="inline-flex h-10 items-center justify-center rounded-xl bg-white px-4 text-sm font-semibold text-slate-700 shadow-xs ring-1 ring-slate-300 transition-all ring-inset hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-200 dark:ring-slate-700 dark:hover:bg-slate-700"
                    >
                        Batal
                    </Link>
                    <AppButton
                        type="submit"
                        variant="primary"
                        :class="{ 'opacity-70': form.processing }"
                        :disabled="form.processing"
                        icon="check"
                    >
                        {{ isEdit ? 'Simpan Perubahan' : 'Simpan Berita' }}
                    </AppButton>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
