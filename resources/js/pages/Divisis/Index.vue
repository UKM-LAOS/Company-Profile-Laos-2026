<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import DivisiFormModal from './Partials/DivisiFormModal.vue';
import DeleteDivisiConfirmModal from './Partials/DeleteDivisiConfirmModal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps<{
    divisis: any;
    filters: any;
}>();

const search = ref(props.filters.search);

watch(search, (value) => {
    router.get(
        route('divisis.index'),
        { search: value },
        {
            preserveState: true,
            replace: true,
        },
    );
});

const isModalOpen = ref(false);
const selectedDivisi = ref<any>(null);

const openCreateModal = () => {
    selectedDivisi.value = null;
    isModalOpen.value = true;
};

const openEditModal = (divisi: any) => {
    selectedDivisi.value = divisi;
    isModalOpen.value = true;
};

const isDeleteModalOpen = ref(false);
const divisiToDelete = ref<any>(null);

const openDeleteModal = (divisi: any) => {
    divisiToDelete.value = divisi;
    isDeleteModalOpen.value = true;
};
</script>

<template>
    <Head title="Kelola Divisi" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl dark:text-white">Kelola Divisi</h1>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Manajemen struktur 5 divisi teknis dan operasional UKM LAOS Fasilkom UNEJ periode kepengurusan aktif.</p>
        <div class="p-6 sm:p-8">
            <div
                class="mb-6 flex flex-col items-start justify-between md:flex-row md:items-center"
            >
                <div>
                    <h1
                        class="text-3xl font-bold text-slate-800 dark:text-slate-200"
                    >
                        Kelola Divisi
                    </h1>
                    <p class="mt-1 text-slate-500 dark:text-slate-400">
                        Manajemen struktur 5 divisi teknis dan operasional UKM
                        LAOS Fasilkom UNEJ periode kepengurusan aktif.
                    </p>
                </div>
            </div>
        </template>

        <div class="space-y-6">
            <div class="bg-white dark:bg-[#1e2336] rounded-xl border border-slate-200 dark:border-slate-800/60 shadow-sm">
                <div class="p-4 sm:p-6">
                    
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
                        <div class="relative w-full sm:max-w-md">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input
                                    type="text"
                                    v-model="search"
                                    class="block w-full pl-10 pr-3 py-2 border border-slate-300 rounded-md leading-5 bg-white placeholder-slate-500 focus:outline-none focus:placeholder-slate-400 focus:border-green-500 focus:ring-1 focus:ring-green-500 sm:text-sm transition duration-150 ease-in-out dark:bg-slate-700 dark:border-slate-600 dark:text-white"
                                    placeholder="Cari bedasarkan Divisi"
                                />
                            </div>
                            <PrimaryButton @click="openCreateModal" class="w-full sm:w-auto justify-center bg-green-500 hover:bg-green-600 text-white font-semibold rounded-md flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            <div
                class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800/60 dark:bg-[#1e2336]"
            >
                <div class="p-6">
                    <div class="mb-6 flex items-center justify-between">
                        <div class="relative w-full max-w-3xl">
                            <div
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"
                            >
                                <svg
                                    class="h-5 w-5 text-gray-400"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                            <input
                                type="text"
                                v-model="search"
                                class="block w-full rounded-md border border-slate-300 bg-white py-2 pr-3 pl-10 leading-5 placeholder-slate-500 transition duration-150 ease-in-out focus:border-green-500 focus:placeholder-slate-400 focus:ring-1 focus:ring-green-500 focus:outline-none sm:text-sm dark:border-slate-600 dark:bg-slate-700 dark:text-white"
                                placeholder="Cari bedasarkan Divisi"
                            />
                        </div>
                        <PrimaryButton
                            @click="openCreateModal"
                            class="ml-4 flex items-center rounded-md bg-green-500 font-semibold text-white hover:bg-green-600"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="mr-1 h-5 w-5"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            Tambah Divisi
                        </PrimaryButton>
                    </div>

                    <div class="overflow-x-auto">
                        <table
                            class="min-w-full divide-y divide-slate-200 overflow-hidden rounded-lg border border-slate-200 dark:divide-slate-700 dark:border-slate-700"
                        >
                            <thead class="bg-slate-50 dark:bg-slate-800">
                                <tr>
                                    <th
                                        scope="col"
                                        class="w-full px-6 py-3 text-left text-xs font-semibold tracking-wider text-slate-500 uppercase"
                                    >
                                        NAMA DIVISI
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-center text-xs font-semibold tracking-wider whitespace-nowrap text-slate-500 uppercase"
                                    >
                                        AKSI
                                    </th>
                                </tr>
                            </thead>
                            <tbody
                                class="divide-y divide-slate-200 bg-white dark:divide-slate-800 dark:bg-slate-900"
                            >
                                <tr
                                    v-for="divisi in divisis.data"
                                    :key="divisi.id"
                                    class="hover:bg-slate-50 dark:hover:bg-slate-800"
                                >
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div
                                                class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-green-100 text-green-600"
                                            >
                                                <img
                                                    v-if="divisi.logo"
                                                    :src="
                                                        '/storage/' +
                                                        divisi.logo
                                                    "
                                                    alt=""
                                                    class="h-10 w-10 rounded-full object-cover"
                                                />
                                                <svg
                                                    v-else
                                                    class="h-6 w-6"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                                                    />
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div
                                                    class="text-sm font-semibold text-slate-900 dark:text-white"
                                                >
                                                    {{ divisi.nama }}
                                                </div>
                                                <div
                                                    class="max-w-2xl text-sm break-words whitespace-normal text-slate-500"
                                                >
                                                    {{
                                                        divisi.deskripsi ||
                                                        'Belum ada deskripsi'
                                                    }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td
                                        class="px-6 py-4 text-center text-sm font-medium whitespace-nowrap"
                                    >
                                        <div
                                            class="flex items-center justify-center space-x-3"
                                        >
                                            <button
                                                class="text-slate-400 hover:text-slate-600"
                                            >
                                                <svg
                                                    class="h-5 w-5"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                    ></path>
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                    ></path>
                                                </svg>
                                            </button>
                                            <button
                                                @click="openEditModal(divisi)"
                                                class="text-slate-400 hover:text-blue-600"
                                            >
                                                <svg
                                                    class="h-5 w-5"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
                                                    ></path>
                                                </svg>
                                            </button>
                                            <button
                                                @click="openDeleteModal(divisi)"
                                                class="text-slate-400 hover:text-red-600"
                                            >
                                                <svg
                                                    class="h-5 w-5"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                    ></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="divisis.data.length === 0">
                                    <td
                                        colspan="2"
                                        class="px-6 py-8 text-center text-slate-500"
                                    >
                                        Tidak ada divisi yang ditemukan.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination (Simple mocked version for visual match, actual links can be implemented if needed) -->
                    <div class="mt-6 flex items-center justify-between">
                        <div class="text-sm text-slate-500">
                            Menampilkan {{ divisis.from || 0 }}-{{
                                divisis.to || 0
                            }}
                            dari {{ divisis.total }} divisi
                        </div>
                        <div class="flex items-center space-x-1">
                            <button
                                class="rounded-md border border-slate-200 bg-white px-3 py-1 text-slate-500 hover:bg-slate-50 disabled:opacity-50"
                                :disabled="!divisis.prev_page_url"
                                @click="
                                    divisis.prev_page_url &&
                                    router.get(divisis.prev_page_url)
                                "
                            >
                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 19l-7-7 7-7"
                                    ></path>
                                </svg>
                            </button>
                            <button
                                class="rounded-md border border-transparent bg-green-500 px-3 py-1 font-medium text-white"
                            >
                                1
                            </button>
                            <button
                                class="rounded-md border border-slate-200 bg-white px-3 py-1 text-slate-500 hover:bg-slate-50 disabled:opacity-50"
                                :disabled="!divisis.next_page_url"
                                @click="
                                    divisis.next_page_url &&
                                    router.get(divisis.next_page_url)
                                "
                            >
                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 5l7 7-7 7"
                                    ></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <DivisiFormModal
            :show="isModalOpen"
            :divisi="selectedDivisi"
            @close="isModalOpen = false"
        />

        <DeleteDivisiConfirmModal
            :show="isDeleteModalOpen"
            :divisi="divisiToDelete"
            @close="isDeleteModalOpen = false"
            @deleted="divisiToDelete = null"
        />
    </AuthenticatedLayout>
</template>
