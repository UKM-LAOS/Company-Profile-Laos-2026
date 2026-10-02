<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { usePermission } from '@/lib/usePermission';
import { Head } from '@inertiajs/vue3';

const { user, roles, permissions, isSuperAdmin, can, hasRole } =
    usePermission();
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2
                    class="text-xl leading-tight font-semibold text-gray-800 dark:text-gray-200"
                >
                    Dashboard
                </h2>
                <div class="flex items-center gap-2">
                    <span
                        v-for="role in roles"
                        :key="role"
                        class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300"
                    >
                        Role: {{ role }}
                    </span>
                    <span
                        v-if="!roles.length"
                        class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300"
                    >
                        No Role Assigned
                    </span>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <!-- Welcome Card -->
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium">
                            Selamat Datang, {{ user?.name }}!
                        </h3>
                        <p
                            class="mt-1 text-sm text-gray-500 dark:text-gray-400"
                        >
                            Anda login dengan email
                            <strong>{{ user?.email }}</strong
                            >. Sistem autentikasi dan otorisasi RBAC (Role-Based
                            Access Control) aktif.
                        </p>
                    </div>
                </div>

                <!-- RBAC Status & Permissions Card -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Roles Section -->
                    <div
                        class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800"
                    >
                        <h4
                            class="text-base font-semibold text-gray-900 dark:text-gray-100"
                        >
                            Peran Anda (Roles)
                        </h4>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <span
                                v-for="role in roles"
                                :key="role"
                                class="inline-flex items-center rounded-md bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700 ring-1 ring-blue-700/10 ring-inset dark:bg-blue-900/30 dark:text-blue-300"
                            >
                                {{ role }}
                            </span>
                            <span
                                v-if="!roles.length"
                                class="text-xs text-gray-400"
                            >
                                Belum ada role yang di-assign. Jalankan seeder
                                database.
                            </span>
                        </div>
                    </div>

                    <!-- Super Admin / Bypass Status -->
                    <div
                        class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800"
                    >
                        <h4
                            class="text-base font-semibold text-gray-900 dark:text-gray-100"
                        >
                            Hak Akses Utama (Gate Status)
                        </h4>
                        <div class="mt-3">
                            <p
                                v-if="isSuperAdmin"
                                class="text-sm text-emerald-600 dark:text-emerald-400"
                            >
                                ✨
                                <strong>Super Admin Bypass Aktif:</strong> Anda
                                memiliki akses penuh ke seluruh fitur dan
                                permission sistem.
                            </p>
                            <p
                                v-else-if="hasRole('admin')"
                                class="text-sm text-indigo-600 dark:text-indigo-400"
                            >
                                🛡️ <strong>Administrator:</strong> Akses
                                pengelolaan konten dan pengguna.
                            </p>
                            <p
                                v-else
                                class="text-sm text-gray-600 dark:text-gray-400"
                            >
                                👤 <strong>Member:</strong> Akses standar
                                pengguna.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Feature Access Grid Demonstrating can() -->
                <div
                    class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800"
                >
                    <h4
                        class="text-base font-semibold text-gray-900 dark:text-gray-100"
                    >
                        Otorisasi Fitur (Demonstrasi `can()`)
                    </h4>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Daftar modul yang dapat Anda akses berdasarkan
                        permission yang dimiliki:
                    </p>

                    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div
                            class="rounded-md border p-4"
                            :class="
                                can('manage_users')
                                    ? 'border-emerald-200 bg-emerald-50 dark:border-emerald-900 dark:bg-emerald-950/20'
                                    : 'border-gray-200 bg-gray-50 opacity-60 dark:border-gray-700 dark:bg-gray-800'
                            "
                        >
                            <div
                                class="text-sm font-medium text-gray-900 dark:text-gray-100"
                            >
                                👥 Manajemen Pengguna
                            </div>
                            <div
                                class="mt-1 text-xs text-gray-500 dark:text-gray-400"
                            >
                                Permission: <code>manage_users</code>
                            </div>
                            <div class="mt-2 text-xs font-semibold">
                                <span
                                    v-if="can('manage_users')"
                                    class="text-emerald-700 dark:text-emerald-400"
                                >
                                    ✓ Diizinkan
                                </span>
                                <span v-else class="text-gray-400">
                                    ✕ Tidak Diizinkan
                                </span>
                            </div>
                        </div>

                        <div
                            class="rounded-md border p-4"
                            :class="
                                can('create_content')
                                    ? 'border-emerald-200 bg-emerald-50 dark:border-emerald-900 dark:bg-emerald-950/20'
                                    : 'border-gray-200 bg-gray-50 opacity-60 dark:border-gray-700 dark:bg-gray-800'
                            "
                        >
                            <div
                                class="text-sm font-medium text-gray-900 dark:text-gray-100"
                            >
                                📝 Buat & Kelola Konten
                            </div>
                            <div
                                class="mt-1 text-xs text-gray-500 dark:text-gray-400"
                            >
                                Permission: <code>create_content</code>
                            </div>
                            <div class="mt-2 text-xs font-semibold">
                                <span
                                    v-if="can('create_content')"
                                    class="text-emerald-700 dark:text-emerald-400"
                                >
                                    ✓ Diizinkan
                                </span>
                                <span v-else class="text-gray-400">
                                    ✕ Tidak Diizinkan
                                </span>
                            </div>
                        </div>

                        <div
                            class="rounded-md border p-4"
                            :class="
                                can('manage_settings')
                                    ? 'border-emerald-200 bg-emerald-50 dark:border-emerald-900 dark:bg-emerald-950/20'
                                    : 'border-gray-200 bg-gray-50 opacity-60 dark:border-gray-700 dark:bg-gray-800'
                            "
                        >
                            <div
                                class="text-sm font-medium text-gray-900 dark:text-gray-100"
                            >
                                ⚙️ Pengaturan Sistem
                            </div>
                            <div
                                class="mt-1 text-xs text-gray-500 dark:text-gray-400"
                            >
                                Permission: <code>manage_settings</code>
                            </div>
                            <div class="mt-2 text-xs font-semibold">
                                <span
                                    v-if="can('manage_settings')"
                                    class="text-emerald-700 dark:text-emerald-400"
                                >
                                    ✓ Diizinkan
                                </span>
                                <span v-else class="text-gray-400">
                                    ✕ Tidak Diizinkan
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
