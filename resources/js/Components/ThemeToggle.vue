<script setup lang="ts">
import AppIcon from '@/Components/AppIcon.vue';
import { useTheme } from '@/lib/useTheme';

const { theme, setTheme } = useTheme();
</script>

<template>
    <div
        class="inline-flex items-center rounded-2xl border border-slate-200/80 bg-white p-1 shadow-xs dark:border-slate-800 dark:bg-slate-900"
        role="group"
        aria-label="Pilihan Tema Tampilan"
    >
        <!-- Light Mode Button -->
        <button
            type="button"
            @click="setTheme('light')"
            class="flex items-center gap-1.5 rounded-xl px-3 py-1.5 text-xs font-semibold transition-all duration-150"
            :class="
                theme === 'light'
                    ? 'bg-amber-50 text-amber-700 shadow-xs dark:bg-amber-950/40 dark:text-amber-300'
                    : 'text-slate-400 hover:text-slate-700 dark:text-slate-500 dark:hover:text-slate-200'
            "
            title="Mode Terang"
        >
            <AppIcon name="sun" class="h-4 w-4" />
            <span class="hidden sm:inline">Terang</span>
        </button>

        <!-- Dark Mode Button -->
        <button
            type="button"
            @click="setTheme('dark')"
            class="flex items-center gap-1.5 rounded-xl px-3 py-1.5 text-xs font-semibold transition-all duration-150"
            :class="
                theme === 'dark'
                    ? 'bg-slate-800 text-slate-100 shadow-xs dark:bg-slate-700 dark:text-white'
                    : 'text-slate-400 hover:text-slate-700 dark:text-slate-500 dark:hover:text-slate-200'
            "
            title="Mode Gelap"
        >
            <AppIcon name="moon" class="h-4 w-4" />
            <span class="hidden sm:inline">Gelap</span>
        </button>

        <!-- System Mode Button -->
        <button
            type="button"
            @click="setTheme('system')"
            class="flex items-center gap-1.5 rounded-xl px-3 py-1.5 text-xs font-semibold transition-all duration-150"
            :class="
                theme === 'system'
                    ? 'bg-emerald-50 text-emerald-700 shadow-xs dark:bg-emerald-950/60 dark:text-emerald-300'
                    : 'text-slate-400 hover:text-slate-700 dark:text-slate-500 dark:hover:text-slate-200'
            "
            title="Sesuai Pengaturan Sistem OS"
        >
            <span class="text-xs">💻</span>
            <span class="hidden sm:inline">Sistem</span>
        </button>
    </div>
</template>
