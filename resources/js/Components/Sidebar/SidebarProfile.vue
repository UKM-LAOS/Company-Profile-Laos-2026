<script setup lang="ts">
import AppIcon from '@/Components/AppIcon.vue';
import { usePermission } from '@/lib/usePermission';
import { useTheme } from '@/lib/useTheme';
import { Link } from '@inertiajs/vue3';
import { computed, onMounted, onUnmounted, ref } from 'vue';

const { user } = usePermission();
const { theme, setTheme } = useTheme();
const isOpen = ref(false);
const dropdownRef = ref<HTMLElement | null>(null);

const initials = computed(() => {
    if (!user.value?.name) return 'LA';
    const parts = user.value.name.trim().split(/\s+/);
    if (parts.length === 1) {
        return parts[0].substring(0, 2).toUpperCase();
    }
    return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
});

function toggleDropdown() {
    isOpen.value = !isOpen.value;
}

function closeDropdown(e: MouseEvent) {
    if (dropdownRef.value && !dropdownRef.value.contains(e.target as Node)) {
        isOpen.value = false;
    }
}

onMounted(() => {
    document.addEventListener('click', closeDropdown);
});

onUnmounted(() => {
    document.removeEventListener('click', closeDropdown);
});
</script>

<template>
    <div ref="dropdownRef" class="relative">
        <!-- Profile Card Bar -->
        <div
            class="flex items-center justify-between rounded-xl p-2 transition hover:bg-slate-50 dark:hover:bg-slate-800/60"
        >
            <div class="flex items-center gap-3 overflow-hidden">
                <!-- Avatar with initials -->
                <div
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-sm font-bold text-emerald-700 dark:bg-emerald-900/60 dark:text-emerald-300"
                >
                    {{ initials }}
                </div>

                <!-- Info -->
                <div class="flex flex-col overflow-hidden text-left">
                    <span
                        class="truncate text-xs font-bold text-slate-800 dark:text-slate-100"
                        :title="user?.name"
                    >
                        {{ user?.name || 'User LAOS' }}
                    </span>
                    <span
                        class="truncate text-[11px] text-slate-400 dark:text-slate-500"
                        :title="user?.email"
                    >
                        {{ user?.email || 'admin@laos.unej.ac.id' }}
                    </span>
                </div>
            </div>

            <!-- Dots Button -->
            <button
                type="button"
                @click.stop="toggleDropdown"
                class="rounded-lg p-1.5 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 dark:text-slate-500 dark:hover:bg-slate-700 dark:hover:text-slate-200"
                aria-label="User menu"
            >
                <AppIcon name="dots-vertical" class="h-4 w-4" />
            </button>
        </div>

        <!-- Dropdown Menu -->
        <Transition
            enter-active-class="transition duration-100 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-in"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
        >
            <div
                v-if="isOpen"
                class="absolute bottom-full left-0 mb-2 w-full origin-bottom rounded-2xl border border-slate-100 bg-white p-2 shadow-xl ring-1 ring-black/5 dark:border-slate-700 dark:bg-slate-800"
            >
                <div
                    class="border-b border-slate-100 px-3 py-2 dark:border-slate-700"
                >
                    <div
                        class="text-xs font-semibold text-slate-700 dark:text-slate-200"
                    >
                        Masuk sebagai:
                    </div>
                    <div
                        class="truncate text-xs text-slate-500 dark:text-slate-400"
                    >
                        {{ user?.email }}
                    </div>
                    <div
                        class="truncate text-xs text-slate-500 dark:text-slate-400"
                    >
                        Role : {{ user?.roles?.values }}
                    </div>
                </div>

                <div class="mt-1 space-y-1">
                    <Link
                        :href="
                            typeof route === 'function'
                                ? route('profile.edit')
                                : '/profile'
                        "
                        class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-xs font-medium text-slate-700 transition hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-700/60"
                        @click="isOpen = false"
                    >
                        <AppIcon
                            name="profile"
                            class="h-4 w-4 text-slate-400"
                        />
                        Pengaturan Profil
                    </Link>

                    <Link
                        :href="
                            typeof route === 'function'
                                ? route('logout')
                                : '/logout'
                        "
                        method="post"
                        as="button"
                        class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-xs font-medium text-rose-600 transition hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-950/30"
                        @click="isOpen = false"
                    >
                        <AppIcon name="logout" class="h-4 w-4 text-rose-500" />
                        Keluar (Logout)
                    </Link>
                </div>
                <!-- Theme Selection Inside Dropdown -->
                <div
                    class="border-b border-slate-100 px-3 py-2 dark:border-slate-700"
                >
                    <div
                        class="mb-1.5 text-[10px] font-bold tracking-wider text-slate-400 uppercase dark:text-slate-500"
                    >
                        Tema Tampilan
                    </div>
                    <div
                        class="flex items-center justify-between rounded-xl bg-slate-100/70 p-1 dark:bg-slate-900"
                    >
                        <button
                            type="button"
                            @click="setTheme('light')"
                            :class="
                                theme === 'light'
                                    ? 'bg-white text-amber-600 shadow-xs dark:bg-slate-800'
                                    : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-200'
                            "
                            class="flex flex-1 items-center justify-center gap-1 rounded-lg py-1 text-[11px] font-semibold transition"
                            title="Mode Terang"
                        >
                            <AppIcon name="sun" class="h-3.5 w-3.5" />
                            <span>Terang</span>
                        </button>
                        <button
                            type="button"
                            @click="setTheme('dark')"
                            :class="
                                theme === 'dark'
                                    ? 'bg-white text-slate-900 shadow-xs dark:bg-slate-800 dark:text-white'
                                    : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-200'
                            "
                            class="flex flex-1 items-center justify-center gap-1 rounded-lg py-1 text-[11px] font-semibold transition"
                            title="Mode Gelap"
                        >
                            <AppIcon name="moon" class="h-3.5 w-3.5" />
                            <span>Gelap</span>
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>
