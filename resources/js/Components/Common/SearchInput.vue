<script setup lang="ts">
import AppIcon from '@/Components/AppIcon.vue';
import { ref, watch } from 'vue';

const props = withDefaults(
    defineProps<{
        modelValue: string;
        placeholder?: string;
        debounce?: number;
    }>(),
    {
        placeholder: 'Cari data...',
        debounce: 300,
    },
);

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
    (e: 'search', value: string): void;
}>();

const localValue = ref(props.modelValue);
let timeout: ReturnType<typeof setTimeout> | null = null;

watch(
    () => props.modelValue,
    (newVal) => {
        localValue.value = newVal;
    },
);

function onInput(e: Event) {
    const val = (e.target as HTMLInputElement).value;
    localValue.value = val;
    emit('update:modelValue', val);

    if (timeout) clearTimeout(timeout);
    timeout = setTimeout(() => {
        emit('search', val);
    }, props.debounce);
}

function clear() {
    localValue.value = '';
    emit('update:modelValue', '');
    emit('search', '');
}
</script>

<template>
    <div class="relative w-full">
        <!-- Search Icon -->
        <div
            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 dark:text-slate-500"
        >
            <AppIcon name="search" class-name="h-4 w-4" />
        </div>

        <!-- Input Field -->
        <input
            type="text"
            :value="localValue"
            @input="onInput"
            :placeholder="placeholder"
            class="w-full rounded-2xl border border-slate-200/90 bg-white py-2.5 pr-10 pl-11 text-sm text-slate-800 placeholder-slate-400 shadow-xs transition-all duration-150 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none dark:border-slate-800 dark:bg-slate-900 dark:text-white dark:placeholder-slate-500 dark:focus:border-emerald-500 dark:focus:bg-slate-900 dark:focus:ring-emerald-500/20"
        />

        <!-- Clear Button -->
        <button
            v-if="localValue"
            type="button"
            @click="clear"
            class="absolute inset-y-0 right-0 flex cursor-pointer items-center pr-3.5 text-slate-400 transition-colors hover:text-slate-600 dark:text-slate-400 dark:hover:text-slate-200"
            title="Hapus pencarian"
        >
            <AppIcon name="close" class-name="h-4 w-4" />
        </button>
    </div>
</template>
