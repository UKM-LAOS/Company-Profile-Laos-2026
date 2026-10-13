<script setup lang="ts">
import { onMounted, onUnmounted, ref } from 'vue';
import { periodeDisplay } from '../types';

defineProps<{
    modelValue: string;
    options: string[];
}>();

const emit = defineEmits<{
    'update:modelValue': [value: string];
}>();

const isOpen = ref(false);
const dropdownRef = ref<HTMLElement | null>(null);

function select(option: string): void {
    emit('update:modelValue', option);
    isOpen.value = false;
}

function handleClickOutside(event: MouseEvent): void {
    if (
        dropdownRef.value &&
        !dropdownRef.value.contains(event.target as Node)
    ) {
        isOpen.value = false;
    }
}

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
    <div ref="dropdownRef" class="relative">
        <button
            type="button"
            aria-haspopup="listbox"
            :aria-expanded="isOpen"
            class="flex cursor-pointer items-center gap-2 rounded-full border border-[#176638] px-4 py-1.5 font-bold text-[#176638] transition-colors dark:border-[#2DCC70] dark:text-[#2DCC70]"
            @click="isOpen = !isOpen"
        >
            {{ periodeDisplay(modelValue) }}
            <svg
                class="h-4 w-4 transition-transform"
                :class="isOpen ? 'rotate-180' : ''"
                viewBox="0 0 20 20"
                fill="none"
                aria-hidden="true"
            >
                <path
                    d="M5 7.5L10 12.5L15 7.5"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
            </svg>
        </button>
        <div
            v-if="isOpen"
            role="listbox"
            class="absolute top-full right-0 z-50 mt-1 min-w-35 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-lg dark:border-slate-800 dark:bg-slate-900"
        >
            <div
                v-for="option in options"
                :key="option"
                role="option"
                :aria-selected="option === modelValue"
                class="cursor-pointer px-4 py-2 text-sm font-medium transition-colors hover:bg-[#2DCC70]/10 hover:text-[#2DCC70]"
                :class="
                    option === modelValue
                        ? 'bg-[#2DCC70]/10 font-bold text-[#2DCC70]'
                        : 'text-[#1E1E1E] dark:text-slate-200'
                "
                @click="select(option)"
            >
                {{ periodeDisplay(option) }}
            </div>
        </div>
    </div>
</template>
