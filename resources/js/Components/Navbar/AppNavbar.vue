<script setup lang="ts">
import AppIcon from '@/Components/AppIcon.vue';
import AppLogo from '@/Components/AppLogo.vue';
import { usePermission } from '@/lib/usePermission';
import { useTheme } from '@/lib/useTheme';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps<{
    sidebarOpen: boolean;
}>();

const emit = defineEmits(['toggle-sidebar']);

const { user } = usePermission();
const { isDark, toggleTheme } = useTheme();

const initials = computed(() => {
    if (!user.value?.name) return 'LA';
    const parts = user.value.name.trim().split(/\s+/);
    if (parts.length === 1) {
        return parts[0].substring(0, 2).toUpperCase();
    }
    return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
});
</script>

<template>
    <header
        class="sticky top-0 z-30 flex h-16 w-full items-center justify-between border-b border-slate-100 bg-white px-4 sm:px-6 lg:hidden dark:border-slate-800 dark:bg-slate-900"
    >
        <!-- Mobile hamburger toggle -->
        <button
            type="button"
            @click="emit('toggle-sidebar')"
            class="rounded-xl p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white"
            aria-label="Toggle menu"
        >
            <AppIcon v-if="!sidebarOpen" name="menu" class="h-6 w-6" />
            <AppIcon v-else name="close" class="h-6 w-6" />
        </button>

        <!-- Logo on mobile -->
        <Link :href="route('dashboard')">
            <AppLogo />
        </Link>

        <!-- Right actions: Theme toggle + User avatar on mobile -->
        <div class="flex items-center gap-2">
            <button
                type="button"
                @click="toggleTheme"
                class="rounded-xl p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white"
                :title="
                    isDark ? 'Beralih ke Mode Terang' : 'Beralih ke Mode Gelap'
                "
            >
                <AppIcon :name="isDark ? 'sun' : 'moon'" class="h-5 w-5" />
            </button>

            <Link
                :href="route('profile.edit')"
                class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-100 text-xs font-bold text-emerald-700 dark:bg-emerald-900/60 dark:text-emerald-300"
            >
                {{ initials }}
            </Link>
        </div>
    </header>
</template>
