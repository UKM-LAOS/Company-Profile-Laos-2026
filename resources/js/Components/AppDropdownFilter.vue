<script setup lang="ts">
import AppIcon from '@/Components/AppIcon.vue';
import { computed, nextTick, onMounted, onUnmounted, ref } from 'vue';

export interface DropdownFilterOption {
    value: string;
    label: string;
}

const props = withDefaults(
    defineProps<{
        modelValue?: string;
        options: Array<DropdownFilterOption | string>;
        placeholder?: string;
        searchable?: boolean;
        id?: string;
        ariaLabel?: string;
    }>(),
    {
        modelValue: '',
        placeholder: 'Semua',
        searchable: false,
        id: undefined,
        ariaLabel: undefined,
    },
);

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
    (e: 'change', value: string): void;
}>();

const isOpen = ref(false);
const searchQuery = ref('');
const rootRef = ref<HTMLElement | null>(null);
const searchInputRef = ref<HTMLInputElement | null>(null);

const normalizedOptions = computed<DropdownFilterOption[]>(() => {
    return props.options.map((opt) => {
        if (typeof opt === 'string') {
            return { value: opt, label: opt };
        }
        return opt;
    });
});

const isSearchable = computed(() => {
    return props.searchable || normalizedOptions.value.length > 7;
});

const filteredOptions = computed(() => {
    const query = searchQuery.value.trim().toLowerCase();
    if (!query) {
        return normalizedOptions.value;
    }
    return normalizedOptions.value.filter((opt) =>
        opt.label.toLowerCase().includes(query),
    );
});

const selectedLabel = computed(() => {
    if (!props.modelValue) return '';
    const match = normalizedOptions.value.find(
        (opt) => opt.value === props.modelValue,
    );
    return match ? match.label : props.modelValue;
});

function toggleDropdown() {
    isOpen.value = !isOpen.value;
    if (isOpen.value) {
        searchQuery.value = '';
        if (isSearchable.value) {
            nextTick(() => {
                searchInputRef.value?.focus();
            });
        }
    }
}

function closeDropdown() {
    isOpen.value = false;
    searchQuery.value = '';
}

function selectOption(value: string) {
    emit('update:modelValue', value);
    emit('change', value);
    closeDropdown();
}

function clearSelection(event?: MouseEvent) {
    if (event) event.stopPropagation();
    emit('update:modelValue', '');
    emit('change', '');
    closeDropdown();
}

function handleClickOutside(event: MouseEvent) {
    if (rootRef.value && !rootRef.value.contains(event.target as Node)) {
        closeDropdown();
    }
}

function handleKeydown(event: KeyboardEvent) {
    if (event.key === 'Escape' && isOpen.value) {
        event.stopPropagation();
        closeDropdown();
    }
}

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
    document.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
    document.removeEventListener('keydown', handleKeydown);
});
</script>

<template>
    <div ref="rootRef" class="relative inline-block text-left">
        <!-- Trigger button -->
        <button
            :id="id"
            type="button"
            :aria-label="ariaLabel || placeholder"
            :aria-expanded="isOpen"
            class="group inline-flex h-11 min-w-[145px] cursor-pointer items-center justify-between gap-2.5 rounded-xl border px-3.5 text-sm font-medium transition-all duration-200 select-none focus:outline-none"
            :class="[
                modelValue
                    ? 'border-emerald-300 bg-emerald-50/70 text-emerald-800 shadow-xs dark:border-emerald-800/80 dark:bg-emerald-950/40 dark:text-emerald-300'
                    : 'border-slate-200 bg-white text-slate-700 shadow-xs hover:border-slate-300 hover:bg-slate-50/50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-slate-600',
                isOpen
                    ? 'border-emerald-500 ring-2 ring-emerald-500/20 dark:border-emerald-500'
                    : '',
            ]"
            @click="toggleDropdown"
        >
            <span class="flex items-center gap-2 truncate">
                <!-- Dot indicator for active selection -->
                <span
                    v-if="modelValue"
                    class="h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-500 dark:bg-emerald-400"
                    aria-hidden="true"
                ></span>
                <span class="truncate">
                    {{ selectedLabel || placeholder }}
                </span>
            </span>

            <span class="flex items-center gap-1 shrink-0">
                <!-- Clear button when active -->
                <span
                    v-if="modelValue"
                    role="button"
                    tabindex="0"
                    title="Hapus filter"
                    class="rounded-md p-0.5 text-emerald-600 transition-colors hover:bg-emerald-100 hover:text-emerald-900 dark:text-emerald-400 dark:hover:bg-emerald-900/50 dark:hover:text-emerald-200"
                    @click="clearSelection"
                >
                    <AppIcon name="close" class-name="h-3.5 w-3.5" />
                </span>

                <!-- Smooth rotating chevron -->
                <AppIcon
                    name="chevron-down"
                    class-name="h-4 w-4 text-slate-400 transition-transform duration-200 ease-out dark:text-slate-500"
                    :class="isOpen ? 'rotate-180 text-emerald-600 dark:text-emerald-400' : ''"
                />
            </span>
        </button>

        <!-- Dropdown menu panel with smooth transition -->
        <Transition
            enter-active-class="transition duration-180 ease-out"
            enter-from-class="transform scale-95 opacity-0 -translate-y-1"
            enter-to-class="transform scale-100 opacity-100 translate-y-0"
            leave-active-class="transition duration-120 ease-in"
            leave-from-class="transform scale-100 opacity-100 translate-y-0"
            leave-to-class="transform scale-95 opacity-0 -translate-y-1"
        >
            <div
                v-if="isOpen"
                class="absolute left-0 z-40 mt-2 min-w-[200px] max-w-[320px] origin-top-left rounded-2xl border border-slate-200/90 bg-white/95 p-1.5 shadow-xl shadow-slate-900/10 backdrop-blur-md dark:border-slate-700/90 dark:bg-slate-900/95 dark:shadow-black/50"
            >
                <!-- Quick search input if list is long -->
                <div v-if="isSearchable" class="p-1 pb-1.5">
                    <div class="relative">
                        <AppIcon
                            name="search"
                            class-name="absolute left-2.5 top-2.5 h-3.5 w-3.5 text-slate-400"
                        />
                        <input
                            ref="searchInputRef"
                            v-model="searchQuery"
                            type="text"
                            placeholder="Cari opsi..."
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 py-1.5 pr-3 pl-8 text-xs text-slate-800 placeholder-slate-400 transition-colors focus:border-emerald-500 focus:bg-white focus:outline-none dark:border-slate-700 dark:bg-slate-800/80 dark:text-slate-100 dark:placeholder-slate-500 dark:focus:border-emerald-500 dark:focus:bg-slate-800"
                            @keydown.stop
                        />
                    </div>
                </div>

                <!-- Scrollable list of options -->
                <div
                    class="max-h-60 space-y-0.5 overflow-y-auto pr-0.5 text-sm overscroll-contain [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-slate-200 dark:[&::-webkit-scrollbar-thumb]:bg-slate-700"
                >
                    <!-- All / Reset option -->
                    <button
                        type="button"
                        class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-left text-xs font-medium transition-colors sm:text-sm"
                        :class="
                            !modelValue
                                ? 'bg-emerald-50 text-emerald-700 font-semibold dark:bg-emerald-950/60 dark:text-emerald-300'
                                : 'text-slate-700 hover:bg-slate-100/80 dark:text-slate-300 dark:hover:bg-slate-800/80'
                        "
                        @click="selectOption('')"
                    >
                        <span>{{ placeholder }}</span>
                        <AppIcon
                            v-if="!modelValue"
                            name="check"
                            class-name="h-4 w-4 text-emerald-600 dark:text-emerald-400"
                        />
                    </button>

                    <!-- Filtered options -->
                    <button
                        v-for="opt in filteredOptions"
                        :key="opt.value"
                        type="button"
                        class="flex w-full items-center justify-between gap-2 rounded-xl px-3 py-2 text-left text-xs transition-colors sm:text-sm"
                        :class="
                            modelValue === opt.value
                                ? 'bg-emerald-50 text-emerald-700 font-semibold dark:bg-emerald-950/60 dark:text-emerald-300'
                                : 'text-slate-700 hover:bg-slate-100/80 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800/80 dark:hover:text-white'
                        "
                        @click="selectOption(opt.value)"
                    >
                        <span class="truncate">{{ opt.label }}</span>
                        <AppIcon
                            v-if="modelValue === opt.value"
                            name="check"
                            class-name="h-4 w-4 shrink-0 text-emerald-600 dark:text-emerald-400"
                        />
                    </button>

                    <p
                        v-if="filteredOptions.length === 0"
                        class="py-3 text-center text-xs text-slate-400 dark:text-slate-500"
                    >
                        Tidak ada opsi yang cocok
                    </p>
                </div>
            </div>
        </Transition>
    </div>
</template>
