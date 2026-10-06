<script setup lang="ts">
withDefaults(
    defineProps<{
        type?: 'button' | 'submit' | 'reset';
    }>(),
    {
        type: 'button',
    },
);
</script>

<template>
    <button
        :type="type"
        class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-medium tracking-normal text-slate-700 shadow-xs transition-all duration-150 select-none hover:bg-slate-50 hover:text-slate-900 focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 focus:outline-none active:scale-[0.98] disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-60 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700/70 dark:hover:text-white dark:focus:ring-offset-slate-900"
    >
        <slot />
    </button>
</template>
