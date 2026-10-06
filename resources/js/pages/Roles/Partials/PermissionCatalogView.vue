<script setup lang="ts">
import AppIcon from '@/Components/AppIcon.vue';
import SearchInput from '@/Components/Common/SearchInput.vue';
import { computed, ref } from 'vue';
import type { PermissionCatalogItem } from './RolePermissionModal.vue';

const props = defineProps<{
    groupedCatalog: Record<string, PermissionCatalogItem[]>;
    totalPermissions: number;
}>();

const searchQuery = ref('');

const filteredGroupedCatalog = computed(() => {
    const query = searchQuery.value.trim().toLowerCase();
    if (!query) return props.groupedCatalog;

    const result: Record<string, PermissionCatalogItem[]> = {};

    Object.entries(props.groupedCatalog).forEach(([moduleName, perms]) => {
        const matchesModule = moduleName.toLowerCase().includes(query);
        const matchedPerms = perms.filter(
            (p) =>
                matchesModule ||
                p.name.toLowerCase().includes(query) ||
                p.label.toLowerCase().includes(query) ||
                p.description.toLowerCase().includes(query),
        );

        if (matchedPerms.length > 0) {
            result[moduleName] = matchedPerms;
        }
    });

    return result;
});

const filteredTotalCount = computed(() => {
    let count = 0;
    Object.values(filteredGroupedCatalog.value).forEach((perms) => {
        count += perms.length;
    });
    return count;
});
</script>

<template>
    <div class="space-y-6">
        <!-- Search and Info Bar -->
        <div
            class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
        >
            <div class="w-full sm:max-w-md">
                <SearchInput
                    v-model="searchQuery"
                    placeholder="Cari izin berdasarkan nama atau kode..."
                />
            </div>

            <div
                class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400"
            >
                <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500" />
                <span>
                    Menampilkan
                    <strong class="text-slate-900 dark:text-white">{{
                        filteredTotalCount
                    }}</strong>
                    dari {{ totalPermissions }} izin master
                </span>
            </div>
        </div>

        <!-- Empty Search State -->
        <div
            v-if="Object.keys(filteredGroupedCatalog).length === 0"
            class="rounded-2xl border border-dashed border-slate-200 py-16 text-center dark:border-slate-800"
        >
            <div
                class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500"
            >
                <AppIcon name="search" class-name="h-6 w-6" />
            </div>
            <h3
                class="mt-3 text-sm font-semibold text-slate-900 dark:text-white"
            >
                Izin tidak ditemukan
            </h3>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                Tidak ada hak akses yang cocok dengan pencarian "{{
                    searchQuery
                }}".
            </p>
        </div>

        <!-- Modules Grid -->
        <div
            v-for="(permissions, moduleName) in filteredGroupedCatalog"
            :key="moduleName"
            class="space-y-3.5"
        >
            <!-- Module Title Header -->
            <div
                class="flex items-center gap-2.5 border-b border-slate-200/80 pb-2 dark:border-slate-800"
            >
                <div
                    class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400"
                >
                    <AppIcon name="nodes" class-name="h-4 w-4" />
                </div>
                <h3
                    class="text-sm font-bold tracking-tight text-slate-900 dark:text-white"
                >
                    {{ moduleName }}
                </h3>
                <span
                    class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-400"
                >
                    {{ permissions.length }} Izin
                </span>
            </div>

            <!-- Permission Cards -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="perm in permissions"
                    :key="perm.name"
                    class="flex flex-col justify-between rounded-2xl border border-slate-200/80 bg-white p-4.5 shadow-xs transition-all hover:border-slate-300 hover:shadow-sm dark:border-slate-800 dark:bg-slate-900 dark:hover:border-slate-700"
                >
                    <div>
                        <div class="flex items-start justify-between gap-2.5">
                            <div class="flex items-center gap-2.5">
                                <div
                                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300"
                                >
                                    <AppIcon name="key" class-name="h-4 w-4" />
                                </div>
                                <h4
                                    class="text-sm font-bold text-slate-900 dark:text-white"
                                >
                                    {{ perm.label }}
                                </h4>
                            </div>
                        </div>

                        <div class="mt-3">
                            <span
                                class="inline-block rounded-md bg-slate-100 px-2 py-0.5 font-mono text-xs font-medium text-slate-700 dark:bg-slate-800 dark:text-slate-300"
                            >
                                {{ perm.name }}
                            </span>
                        </div>

                        <p
                            class="mt-2 text-xs leading-relaxed text-slate-500 dark:text-slate-400"
                        >
                            {{ perm.description }}
                        </p>
                    </div>

                    <div
                        class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3 text-[11px] text-slate-400 dark:border-slate-800/80 dark:text-slate-500"
                    >
                        <span>Spatie Permission</span>
                        <span
                            class="font-mono font-medium text-emerald-600 dark:text-emerald-400"
                            >web</span
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
