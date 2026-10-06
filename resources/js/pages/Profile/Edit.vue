<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps<{
    mustVerifyEmail?: boolean;
    status?: string;
}>();
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl leading-tight font-semibold text-slate-800 dark:text-slate-200"
            >
                Profile
            </h2>
        </template>

        <div class="max-w-4xl space-y-6">
            <div
                class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs sm:p-8 dark:border-slate-800/80 dark:bg-slate-900"
            >
                <UpdateProfileInformationForm
                    :must-verify-email="mustVerifyEmail"
                    :status="status"
                    class="max-w-xl"
                />
            </div>

            <div
                class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs sm:p-8 dark:border-slate-800/80 dark:bg-slate-900"
            >
                <UpdatePasswordForm class="max-w-xl" />
            </div>

            <div
                class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs sm:p-8 dark:border-slate-800/80 dark:bg-slate-900"
            >
                <DeleteUserForm class="max-w-xl" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
