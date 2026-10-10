<script setup lang="ts">
import AppIcon from '@/Components/AppIcon.vue';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

export interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

const props = withDefaults(
    defineProps<{
        links: PaginationLink[];
        from?: number | null;
        to?: number | null;
        total?: number;
        itemName?: string;
        variant?: 'default' | 'circular';
    }>(),
    {
        from: 0,
        to: 0,
        total: 0,
        itemName: 'data',
        variant: 'default',
    },
);

// Filter out first and last link since we render custom < and > controls
const pageLinks = computed(() => {
    if (!props.links || props.links.length <= 1) return [];
    return props.links.slice(1, -1);
});

const prevLink = computed(() => {
    return props.links?.[0]?.url || null;
});

const nextLink = computed(() => {
    return props.links?.[props.links.length - 1]?.url || null;
});

const activePage = computed(() => {
    const active = props.links.find((l) => l.active);
    return active ? formatLabel(active.label) : '1';
});

const totalPages = computed(() => {
    if (pageLinks.value.length === 0) return 1;
    const last = pageLinks.value[pageLinks.value.length - 1];
    return last ? formatLabel(last.label) : '1';
});

function formatLabel(label: string): string {
    return label
        .replace('&laquo;', '')
        .replace('&raquo;', '')
        .replace('Previous', '')
        .replace('Next', '')
        .trim();
}
</script>

<template>
    <div
        v-if="total > 0"
        :class="[
            'flex flex-col gap-3 px-4 py-3 sm:flex-row sm:items-center sm:justify-between sm:px-6 sm:py-4',
            variant === 'circular'
                ? 'border-t border-slate-100/90 dark:border-slate-800'
                : 'border-t border-slate-100 dark:border-slate-800',
        ]"
    >
        <!-- Info Text -->
        <p class="text-xs text-slate-500 dark:text-slate-400">
            Menampilkan
            <span class="font-bold text-slate-800 dark:text-slate-200">
                {{ from ?? 0 }}–{{ to ?? 0 }}
            </span>
            dari
            <span class="font-bold text-slate-800 dark:text-slate-200">
                {{ total }}
            </span>
            {{ itemName }}
        </p>

        <!-- Pagination Controls -->
        <div
            v-if="links.length > 3"
            class="flex items-center justify-between gap-1.5 sm:justify-end"
        >
            <!-- Mobile Compact Indicator (visible on < sm) -->
            <div
                class="mr-2 flex items-center gap-2 text-xs font-medium text-slate-500 sm:hidden dark:text-slate-400"
            >
                Hal {{ activePage }} / {{ totalPages }}
            </div>

            <!-- Circular Controls (Matches mockup) -->
            <div
                v-if="variant === 'circular'"
                class="flex items-center gap-1.5"
            >
                <!-- Prev Button -->
                <Link
                    v-if="prevLink"
                    :href="prevLink"
                    preserve-scroll
                    preserve-state
                    class="inline-flex h-7 w-7 cursor-pointer items-center justify-center rounded-full text-slate-400 transition hover:text-slate-700 dark:text-slate-500 dark:hover:text-slate-300"
                    title="Halaman sebelumnya"
                >
                    <AppIcon name="chevron-left" class-name="h-3.5 w-3.5" />
                </Link>
                <span
                    v-else
                    class="inline-flex h-7 w-7 items-center justify-center text-slate-300 dark:text-slate-700 cursor-not-allowed"
                >
                    <AppIcon name="chevron-left" class-name="h-3.5 w-3.5" />
                </span>

                <!-- Numeric Page Links -->
                <div class="hidden sm:flex sm:items-center sm:gap-1">
                    <template v-for="(link, idx) in pageLinks" :key="idx">
                        <span
                            v-if="link.label === '...'"
                            class="inline-flex h-7 w-7 items-center justify-center text-xs text-slate-400 dark:text-slate-500"
                        >
                            ...
                        </span>

                        <Link
                            v-else-if="link.url && !link.active"
                            :href="link.url"
                            preserve-scroll
                            preserve-state
                            class="inline-flex h-7 w-7 cursor-pointer items-center justify-center rounded-full text-xs font-semibold text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200"
                        >
                            {{ formatLabel(link.label) }}
                        </Link>

                        <span
                            v-else-if="link.active"
                            class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-[#10b981] text-xs font-bold text-white shadow-2xs"
                        >
                            {{ formatLabel(link.label) }}
                        </span>
                    </template>
                </div>

                <!-- Next Button -->
                <Link
                    v-if="nextLink"
                    :href="nextLink"
                    preserve-scroll
                    preserve-state
                    class="inline-flex h-7 w-7 cursor-pointer items-center justify-center rounded-full text-slate-400 transition hover:text-slate-700 dark:text-slate-500 dark:hover:text-slate-300"
                    title="Halaman selanjutnya"
                >
                    <AppIcon name="chevron-right" class-name="h-3.5 w-3.5" />
                </Link>
                <span
                    v-else
                    class="inline-flex h-7 w-7 items-center justify-center text-slate-300 dark:text-slate-700 cursor-not-allowed"
                >
                    <AppIcon name="chevron-right" class-name="h-3.5 w-3.5" />
                </span>
            </div>

            <!-- Default Square Controls -->
            <div v-else class="flex items-center gap-1.5">
                <!-- Prev Button -->
                <Link
                    v-if="prevLink"
                    :href="prevLink"
                    preserve-scroll
                    preserve-state
                    class="inline-flex h-8 w-8 cursor-pointer items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-xs transition-colors hover:bg-slate-50 hover:text-slate-900 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800"
                    title="Halaman sebelumnya"
                >
                    <AppIcon name="chevron-left" class-name="h-4 w-4" />
                </Link>
                <span
                    v-else
                    class="inline-flex h-8 w-8 items-center justify-center rounded-xl border border-slate-100 bg-slate-50 text-slate-300 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-600"
                >
                    <AppIcon name="chevron-left" class-name="h-4 w-4" />
                </span>

                <!-- Numeric Page Links -->
                <div class="hidden sm:flex sm:items-center sm:gap-1.5">
                    <template v-for="(link, idx) in pageLinks" :key="idx">
                        <span
                            v-if="link.label === '...'"
                            class="inline-flex h-8 min-w-[32px] items-center justify-center px-1 text-xs text-slate-400 dark:text-slate-500"
                        >
                            ...
                        </span>

                        <Link
                            v-else-if="link.url && !link.active"
                            :href="link.url"
                            preserve-scroll
                            preserve-state
                            class="inline-flex h-8 min-w-[32px] cursor-pointer items-center justify-center rounded-xl border border-slate-200 bg-white px-2.5 text-xs font-semibold text-slate-700 shadow-xs transition-colors hover:bg-slate-50 hover:text-slate-900 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800"
                        >
                            {{ formatLabel(link.label) }}
                        </Link>

                        <span
                            v-else-if="link.active"
                            class="inline-flex h-8 min-w-[32px] items-center justify-center rounded-xl bg-emerald-600 px-2.5 text-xs font-bold text-white shadow-xs"
                        >
                            {{ formatLabel(link.label) }}
                        </span>
                    </template>
                </div>

                <!-- Next Button -->
                <Link
                    v-if="nextLink"
                    :href="nextLink"
                    preserve-scroll
                    preserve-state
                    class="inline-flex h-8 w-8 cursor-pointer items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-xs transition-colors hover:bg-slate-50 hover:text-slate-900 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800"
                    title="Halaman selanjutnya"
                >
                    <AppIcon name="chevron-right" class-name="h-4 w-4" />
                </Link>
                <span
                    v-else
                    class="inline-flex h-8 w-8 items-center justify-center rounded-xl border border-slate-100 bg-slate-50 text-slate-300 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-600"
                >
                    <AppIcon name="chevron-right" class-name="h-4 w-4" />
                </span>
            </div>
        </div>
    </div>
</template>
