<script setup lang="ts">
import AppIcon from '@/Components/AppIcon.vue';

withDefaults(
    defineProps<{
        loading?: boolean;
        title?: string;
    }>(),
    {
        loading: false,
        title: 'Muat ulang data',
    },
);

defineEmits<{
    (e: 'click'): void;
}>();
</script>

<template>
    <button
        type="button"
        @click="$emit('click')"
        :disabled="loading"
        :title="title"
        class="inline-flex h-[42px] w-[42px] shrink-0 cursor-pointer items-center justify-center rounded-2xl border border-slate-200/90 bg-white text-slate-500 shadow-xs transition-all duration-150 select-none hover:border-slate-300 hover:bg-slate-50 hover:text-slate-800 active:scale-95 disabled:pointer-events-none disabled:opacity-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:border-slate-700 dark:hover:bg-slate-800 dark:hover:text-slate-200"
    >
        <AppIcon
            name="refresh"
            :class-name="`h-4 w-4 transition-transform duration-500 ${loading ? 'animate-spin text-emerald-600' : ''}`"
        />
    </button>
</template>
