<script setup lang="ts">
import AppIcon from '@/Components/AppIcon.vue';
import { computed } from 'vue';

const props = withDefaults(
    defineProps<{
        title: string;
        value: string | number;
        icon:
            | 'user-group'
            | 'check-circle'
            | 'nodes'
            | 'book'
            | 'dashboard'
            | 'divisi'
            | 'proker';
        variant?: 'emerald' | 'amber' | 'blue' | 'purple';
    }>(),
    {
        variant: 'emerald',
    },
);

const badgeStyle = computed(() => {
    switch (props.variant) {
        case 'amber':
            return 'bg-amber-50 text-amber-500 dark:bg-amber-950/40 dark:text-amber-400';
        case 'blue':
            return 'bg-sky-50 text-sky-500 dark:bg-sky-950/40 dark:text-sky-400';
        case 'purple':
            return 'bg-purple-50 text-purple-500 dark:bg-purple-950/40 dark:text-purple-400';
        case 'emerald':
        default:
            return 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400';
    }
});
</script>

<template>
    <div
        class="flex flex-col justify-between rounded-3xl border border-slate-100 bg-white p-6 shadow-xs transition hover:shadow-md dark:border-slate-800 dark:bg-slate-900"
    >
        <!-- Header: Title & Circular Icon Badge -->
        <div class="flex items-start justify-between gap-4">
            <span
                class="text-xs font-extrabold tracking-wider text-slate-700 uppercase dark:text-slate-300"
            >
                {{ title }}
            </span>

            <div
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full"
                :class="badgeStyle"
            >
                <AppIcon :name="icon" class="h-5 w-5" />
            </div>
        </div>

        <!-- Big Metric Number -->
        <div class="mt-4">
            <span
                class="text-4xl font-extrabold tracking-tight text-slate-800 dark:text-white"
            >
                {{ value }}
            </span>
        </div>
    </div>
</template>
