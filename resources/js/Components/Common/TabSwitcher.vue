<script setup lang="ts">
import AppIcon from '@/Components/AppIcon.vue';

export interface TabItem {
    id: string;
    label: string;
    count?: number;
    icon?: any;
}

const props = defineProps<{
    tabs: TabItem[];
    modelValue: string;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
}>();

function selectTab(id: string) {
    emit('update:modelValue', id);
}
</script>

<template>
    <div
        class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200/80 bg-slate-100/90 p-1 dark:border-slate-700/60 dark:bg-slate-800/80"
        role="tablist"
    >
        <button
            v-for="tab in tabs"
            :key="tab.id"
            type="button"
            role="tab"
            :aria-selected="modelValue === tab.id"
            :class="[
                'inline-flex cursor-pointer items-center gap-2 rounded-lg px-3.5 py-2 text-sm transition-all duration-200 select-none',
                modelValue === tab.id
                    ? 'bg-white font-semibold text-slate-900 shadow-xs dark:bg-slate-900 dark:text-white'
                    : 'font-medium text-slate-600 hover:bg-white/50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-700/40 dark:hover:text-slate-200',
            ]"
            @click="selectTab(tab.id)"
        >
            <AppIcon
                v-if="tab.icon"
                :name="tab.icon"
                class-name="h-4 w-4"
                :class="
                    modelValue === tab.id
                        ? 'text-emerald-600 dark:text-emerald-400'
                        : 'text-slate-400 dark:text-slate-500'
                "
            />
            <span>{{ tab.label }}</span>
            <span
                v-if="tab.count !== undefined"
                :class="[
                    'inline-flex items-center justify-center rounded-full px-2 py-0.5 text-xs leading-none font-semibold transition-colors',
                    modelValue === tab.id
                        ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-500/20 dark:bg-emerald-950/60 dark:text-emerald-300'
                        : 'bg-slate-200/70 text-slate-600 dark:bg-slate-700 dark:text-slate-300',
                ]"
            >
                {{ tab.count }}
            </span>
        </button>
    </div>
</template>
