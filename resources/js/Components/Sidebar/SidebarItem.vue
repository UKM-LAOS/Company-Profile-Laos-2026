<script setup lang="ts">
import AppIcon from '@/Components/AppIcon.vue';
import { Link } from '@inertiajs/vue3';

defineProps<{
    title: string;
    href: string;
    icon: any;
    active?: boolean;
}>();
</script>

<template>
    <Link
        :href="href"
        class="group flex items-center gap-3.5 px-4 py-2.5 text-sm font-medium transition-all duration-150"
        :class="
            active
                ? 'rounded-2xl bg-emerald-50 font-semibold text-emerald-700 shadow-xs dark:bg-emerald-950/40 dark:text-emerald-300'
                : 'rounded-xl text-slate-500 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800/60 dark:hover:text-slate-200'
        "
    >
        <AppIcon
            :name="icon"
            class="h-5 w-5 shrink-0 transition-colors"
            :class="
                active
                    ? 'text-emerald-600 dark:text-emerald-400'
                    : 'text-slate-400 group-hover:text-slate-600 dark:text-slate-500 dark:group-hover:text-slate-300'
            "
        />
        <span class="truncate">{{ title }}</span>
    </Link>
</template>
