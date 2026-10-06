<script setup lang="ts">
import StatCard from '@/Components/Dashboard/StatCard.vue';
import ThemeToggle from '@/Components/ThemeToggle.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { usePermission } from '@/lib/usePermission';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

const { user, roles, permissions, isSuperAdmin } = usePermission();

const greetingTitle = computed(() => {
    if (isSuperAdmin.value) {
        return 'Selamat datang, Super Administrator';
    }
    if (user.value?.name) {
        return `Selamat datang, ${user.value.name}`;
    }
    return 'Selamat datang di UKM LAOS';
});
</script>

<template>
    <Head title="Dashboard - UKM LAOS Fasilkom UNEJ" />

    <AuthenticatedLayout>
        <template #header>
            <div
                class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
            >
                <div>
                    <h1
                        class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl lg:text-3xl dark:text-white"
                    >
                        {{ greetingTitle }}
                    </h1>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Ringkasan operasional dan modul kegiatan UKM LAOS
                        Fasilkom UNEJ.
                    </p>
                </div>
            </div>
        </template>

        <!-- 4 Metric Cards Grid Matching Screenshot -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
            <!-- 1. Total Pengurus -->
            <StatCard
                title="TOTAL PENGURUS"
                value="142"
                icon="user-group"
                variant="emerald"
            />

            <!-- 2. Program Kerja -->
            <StatCard
                title="PROGRAM KERJA"
                value="6"
                icon="check-circle"
                variant="emerald"
            />

            <!-- 3. Divisi Organisasi -->
            <StatCard
                title="DIVISI ORGANISASI"
                value="5"
                icon="nodes"
                variant="amber"
            />

            <!-- 4. Materi & Publikasi -->
            <StatCard
                title="MATERI & PUBLIKASI"
                value="38"
                icon="book"
                variant="blue"
            />
        </div>
    </AuthenticatedLayout>
</template>
