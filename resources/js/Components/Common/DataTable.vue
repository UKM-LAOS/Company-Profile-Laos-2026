<script setup lang="ts" generic="T extends Record<string, any>">
import AppIcon from '@/Components/AppIcon.vue';

export interface TableColumn {
    key: string;
    label: string;
    class?: string;
    headerClass?: string;
    align?: 'left' | 'center' | 'right';
}

withDefaults(
    defineProps<{
        columns: TableColumn[];
        items: T[];
        loading?: boolean;
        emptyTitle?: string;
        emptyDescription?: string;
    }>(),
    {
        loading: false,
        emptyTitle: 'Tidak ada data ditemukan',
        emptyDescription:
            'Belum ada data yang sesuai dengan kriteria pencarian.',
    },
);
</script>

<template>
    <div
        class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs dark:border-slate-800/80 dark:bg-slate-900"
    >
        <!-- Table Scroll Container with smooth touch scrolling on mobile/tablet -->
        <div class="scrollbar-thin overflow-x-auto">
            <table
                class="w-full min-w-[640px] text-left text-sm text-slate-600 dark:text-slate-300"
            >
                <!-- Table Header -->
                <thead
                    class="border-b border-slate-100 bg-slate-50/70 text-[11px] font-bold tracking-wider text-slate-400 uppercase dark:border-slate-800 dark:bg-slate-800/50 dark:text-slate-500"
                >
                    <tr>
                        <th
                            v-for="col in columns"
                            :key="col.key"
                            scope="col"
                            :class="[
                                'px-4 py-3.5 select-none sm:px-6 sm:py-4',
                                col.align === 'center'
                                    ? 'text-center'
                                    : col.align === 'right'
                                      ? 'text-right'
                                      : 'text-left',
                                col.headerClass || '',
                            ]"
                        >
                            {{ col.label }}
                        </th>
                    </tr>
                </thead>

                <!-- Table Body -->
                <tbody
                    class="divide-y divide-slate-100 dark:divide-slate-800/70"
                >
                    <!-- Loading Skeleton / Overlay -->
                    <tr v-if="loading">
                        <td
                            :colspan="columns.length"
                            class="px-6 py-12 text-center"
                        >
                            <div
                                class="inline-flex items-center gap-2 text-xs font-medium text-slate-500 dark:text-slate-400"
                            >
                                <AppIcon
                                    name="spinner"
                                    class-name="h-5 w-5 text-emerald-600"
                                />
                                Memuat data...
                            </div>
                        </td>
                    </tr>

                    <!-- Empty State -->
                    <tr v-else-if="items.length === 0">
                        <td
                            :colspan="columns.length"
                            class="px-6 py-16 text-center"
                        >
                            <slot name="empty">
                                <div
                                    class="mx-auto flex max-w-sm flex-col items-center justify-center text-center"
                                >
                                    <div
                                        class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500"
                                    >
                                        <AppIcon
                                            name="search"
                                            class-name="h-6 w-6"
                                        />
                                    </div>
                                    <h3
                                        class="mt-3 text-sm font-semibold text-slate-800 dark:text-slate-200"
                                    >
                                        {{ emptyTitle }}
                                    </h3>
                                    <p
                                        class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                                    >
                                        {{ emptyDescription }}
                                    </p>
                                </div>
                            </slot>
                        </td>
                    </tr>

                    <!-- Data Rows -->
                    <tr
                        v-else
                        v-for="(item, index) in items"
                        :key="item.id ?? index"
                        class="transition-colors duration-100 hover:bg-slate-50/70 dark:hover:bg-slate-800/40"
                    >
                        <td
                            v-for="col in columns"
                            :key="col.key"
                            :class="[
                                'px-4 py-3.5 text-xs whitespace-nowrap sm:px-6 sm:py-4',
                                col.align === 'center'
                                    ? 'text-center'
                                    : col.align === 'right'
                                      ? 'text-right'
                                      : 'text-left',
                                col.class || '',
                            ]"
                        >
                            <slot
                                :name="`cell-${col.key}`"
                                :item="item"
                                :value="item[col.key]"
                                :index="index"
                            >
                                {{ item[col.key] }}
                            </slot>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Table Footer / Pagination Slot -->
        <slot name="footer" />
    </div>
</template>
