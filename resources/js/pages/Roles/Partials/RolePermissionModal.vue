<script setup lang="ts">
import AppButton from '@/Components/AppButton.vue';
import AppIcon from '@/Components/AppIcon.vue';
import Modal from '@/Components/Modal.vue';
import { router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

export interface PermissionCatalogItem {
    name: string;
    label: string;
    description: string;
    module: string;
}

export interface RoleItem {
    id: number;
    name: string;
    guard_name: string;
    created_at: string;
    users_count: number;
    permissions: Array<{ id: number; name: string }>;
    [key: string]: any;
}

const props = defineProps<{
    show: boolean;
    role?: RoleItem | null;
    groupedCatalog: Record<string, PermissionCatalogItem[]>;
    totalPermissions: number;
}>();

const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'saved'): void;
}>();

const selectedPermissions = ref<string[]>([]);
const isSaving = ref(false);

const isSuperAdmin = computed(() => props.role?.name === 'super_admin');

const allCatalogPermissions = computed(() => {
    const list: string[] = [];
    Object.values(props.groupedCatalog).forEach((perms) => {
        perms.forEach((p) => list.push(p.name));
    });
    return list;
});

watch(
    () => [props.show, props.role],
    ([show]) => {
        if (show && props.role) {
            if (isSuperAdmin.value) {
                selectedPermissions.value = [...allCatalogPermissions.value];
            } else {
                selectedPermissions.value = (props.role.permissions || []).map(
                    (p) => p.name,
                );
            }
        }
    },
    { immediate: true },
);

const activeCount = computed(() => selectedPermissions.value.length);
const totalCount = computed(
    () => props.totalPermissions || allCatalogPermissions.value.length || 1,
);
const activePercentage = computed(() =>
    Math.round((activeCount.value / totalCount.value) * 100),
);

function formatRoleTitle(name?: string): string {
    if (!name) return '';
    switch (name) {
        case 'super_admin':
            return 'Super Admin';
        case 'admin':
            return 'Administrator';
        case 'member':
            return 'Anggota';
        default:
            return name
                .replace('_', ' ')
                .replace(/\b\w/g, (c) => c.toUpperCase());
    }
}

function togglePermission(permName: string) {
    if (isSuperAdmin.value) return;
    const index = selectedPermissions.value.indexOf(permName);
    if (index > -1) {
        selectedPermissions.value.splice(index, 1);
    } else {
        selectedPermissions.value.push(permName);
    }
}

function selectAll() {
    if (isSuperAdmin.value) return;
    selectedPermissions.value = [...allCatalogPermissions.value];
}

function clearAll() {
    if (isSuperAdmin.value) return;
    selectedPermissions.value = [];
}

function isModuleFullySelected(modulePerms: PermissionCatalogItem[]): boolean {
    if (!modulePerms.length) return false;
    return modulePerms.every((p) => selectedPermissions.value.includes(p.name));
}

function toggleModule(modulePerms: PermissionCatalogItem[]) {
    if (isSuperAdmin.value) return;
    const names = modulePerms.map((p) => p.name);
    const allSelected = isModuleFullySelected(modulePerms);

    if (allSelected) {
        selectedPermissions.value = selectedPermissions.value.filter(
            (p) => !names.includes(p),
        );
    } else {
        const set = new Set([...selectedPermissions.value, ...names]);
        selectedPermissions.value = Array.from(set);
    }
}

function countModuleActive(modulePerms: PermissionCatalogItem[]): number {
    return modulePerms.filter((p) => selectedPermissions.value.includes(p.name))
        .length;
}

function savePermissions() {
    if (!props.role || isSuperAdmin.value) {
        emit('close');
        return;
    }

    isSaving.value = true;
    router.put(
        route('roles.permissions.sync', props.role.id),
        {
            permissions: selectedPermissions.value,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                isSaving.value = false;
                emit('saved');
                emit('close');
            },
            onError: () => {
                isSaving.value = false;
            },
        },
    );
}
</script>

<template>
    <Modal :show="show" max-width="4xl" @close="$emit('close')">
        <div class="flex max-h-[90vh] flex-col overflow-hidden">
            <!-- Modal Header -->
            <div
                class="border-b border-slate-200/80 px-6 py-5 sm:px-8 dark:border-slate-800"
            >
                <div class="flex items-start justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex flex-wrap items-center gap-2.5">
                            <h2
                                class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl dark:text-white"
                            >
                                Konfigurasi Hak Akses:
                                {{ formatRoleTitle(role?.name) }}
                            </h2>
                            <span
                                class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 font-mono text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-300"
                            >
                                {{ role?.name }}
                            </span>
                            <span
                                class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 font-mono text-xs font-medium text-slate-500 dark:bg-slate-800 dark:text-slate-400"
                            >
                                guard: {{ role?.guard_name || 'web' }}
                            </span>
                        </div>
                        <p
                            class="text-xs text-slate-500 sm:text-sm dark:text-slate-400"
                        >
                            Tentukan hak akses granular apa saja yang diberikan
                            secara langsung kepada peran ini.
                        </p>
                    </div>

                    <button
                        type="button"
                        class="cursor-pointer rounded-xl p-2 text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800 dark:hover:text-slate-300"
                        @click="$emit('close')"
                    >
                        <AppIcon name="close" class-name="h-5 w-5" />
                    </button>
                </div>

                <!-- Active Permissions Summary Bar -->
                <div
                    class="mt-5 rounded-2xl bg-slate-50/80 p-4 ring-1 ring-slate-200/80 dark:bg-slate-800/40 dark:ring-slate-700/60"
                >
                    <div
                        class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
                    >
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700 dark:bg-emerald-950/70 dark:text-emerald-300"
                            >
                                <AppIcon name="key" class-name="h-4.5 w-4.5" />
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="text-sm font-bold text-slate-900 dark:text-white"
                                    >
                                        {{ activeCount }} dari
                                        {{ totalCount }} Izin Aktif
                                    </span>
                                    <span
                                        class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300"
                                    >
                                        {{ activePercentage }}%
                                    </span>
                                </div>
                                <p
                                    class="text-xs text-slate-500 dark:text-slate-400"
                                >
                                    Tingkat kelengkapan otorisasi pada peran
                                </p>
                            </div>
                        </div>

                        <!-- Global Quick Selection Buttons -->
                        <div
                            v-if="!isSuperAdmin"
                            class="flex items-center gap-2"
                        >
                            <button
                                type="button"
                                class="inline-flex cursor-pointer items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-xs transition-colors hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700/70"
                                @click="selectAll"
                            >
                                <AppIcon
                                    name="check-check"
                                    class-name="h-3.5 w-3.5 text-emerald-600 dark:text-emerald-400"
                                />
                                <span>Pilih Semua Izin</span>
                            </button>
                            <button
                                type="button"
                                class="inline-flex cursor-pointer items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-rose-600 shadow-xs transition-colors hover:bg-rose-50 dark:border-slate-700 dark:bg-slate-800 dark:text-rose-400 dark:hover:bg-rose-950/30"
                                @click="clearAll"
                            >
                                <AppIcon
                                    name="close"
                                    class-name="h-3.5 w-3.5"
                                />
                                <span>Hapus Semua</span>
                            </button>
                        </div>
                    </div>

                    <!-- Visual Progress Bar -->
                    <div
                        class="mt-3.5 h-1.5 w-full overflow-hidden rounded-full bg-slate-200/80 dark:bg-slate-700/80"
                    >
                        <div
                            class="h-full rounded-full bg-emerald-500 transition-all duration-300 dark:bg-emerald-400"
                            :style="{ width: `${activePercentage}%` }"
                        />
                    </div>
                </div>

                <!-- Super Admin Bypass Notification -->
                <div
                    v-if="isSuperAdmin"
                    class="mt-4 flex items-start gap-3 rounded-xl border border-emerald-200/80 bg-emerald-50/80 p-3.5 text-emerald-900 dark:border-emerald-900/60 dark:bg-emerald-950/40 dark:text-emerald-200"
                >
                    <AppIcon
                        name="check-circle"
                        class-name="h-5 w-5 shrink-0 text-emerald-600 dark:text-emerald-400 mt-0.5"
                    />
                    <div class="text-xs leading-relaxed">
                        <p class="font-bold">
                            Peran Super Administrator Memiliki Akses Penuh
                            (Super Admin Bypass)
                        </p>
                        <p
                            class="mt-0.5 text-emerald-800 dark:text-emerald-300"
                        >
                            Semua otorisasi izin dalam sistem UKM LAOS secara
                            otomatis diizinkan untuk peran ini tanpa batasan
                            melalui gerbang otorisasi utama.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Modal Body (Grouped Modules) -->
            <div
                class="flex-1 space-y-7 overflow-y-auto px-6 py-6 sm:px-8 dark:divide-slate-800"
            >
                <div
                    v-for="(permissions, moduleName) in groupedCatalog"
                    :key="moduleName"
                    class="space-y-3"
                >
                    <!-- Module Group Header -->
                    <div
                        class="flex items-center justify-between rounded-xl bg-slate-100/70 px-4 py-2.5 dark:bg-slate-800/60"
                    >
                        <div class="flex items-center gap-2.5">
                            <h3
                                class="text-xs font-bold tracking-wider text-slate-800 uppercase dark:text-slate-200"
                            >
                                {{ moduleName }}
                            </h3>
                            <span
                                class="rounded-full bg-slate-200/80 px-2 py-0.5 text-[11px] font-semibold text-slate-700 dark:bg-slate-700 dark:text-slate-300"
                            >
                                {{ countModuleActive(permissions) }} /
                                {{ permissions.length }}
                            </span>
                        </div>

                        <button
                            v-if="!isSuperAdmin"
                            type="button"
                            class="cursor-pointer text-xs font-semibold text-emerald-700 transition-colors hover:text-emerald-800 hover:underline dark:text-emerald-400 dark:hover:text-emerald-300"
                            @click="toggleModule(permissions)"
                        >
                            {{
                                isModuleFullySelected(permissions)
                                    ? 'Hapus Modul Ini'
                                    : 'Pilih Semua Modul Ini'
                            }}
                        </button>
                    </div>

                    <!-- Permission Cards Grid -->
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div
                            v-for="perm in permissions"
                            :key="perm.name"
                            :class="[
                                'relative flex items-start gap-3.5 rounded-xl border p-3.5 transition-all select-none',
                                isSuperAdmin
                                    ? 'cursor-default border-slate-200 bg-slate-50/60 opacity-90 dark:border-slate-800 dark:bg-slate-800/40'
                                    : 'cursor-pointer hover:shadow-xs',
                                selectedPermissions.includes(perm.name)
                                    ? 'border-emerald-500/80 bg-emerald-50/40 ring-1 ring-emerald-500/20 dark:border-emerald-500/60 dark:bg-emerald-950/20'
                                    : 'border-slate-200/80 bg-white hover:border-slate-300 dark:border-slate-800 dark:bg-slate-900 dark:hover:border-slate-700',
                            ]"
                            @click="togglePermission(perm.name)"
                        >
                            <!-- Custom Checkbox -->
                            <div class="pt-0.5">
                                <div
                                    :class="[
                                        'flex h-5 w-5 items-center justify-center rounded-md border transition-colors',
                                        selectedPermissions.includes(perm.name)
                                            ? 'border-emerald-600 bg-emerald-600 text-white dark:border-emerald-500 dark:bg-emerald-500'
                                            : 'border-slate-300 bg-white dark:border-slate-600 dark:bg-slate-800',
                                    ]"
                                >
                                    <AppIcon
                                        v-if="
                                            selectedPermissions.includes(
                                                perm.name,
                                            )
                                        "
                                        name="check"
                                        class-name="h-3.5 w-3.5"
                                    />
                                </div>
                            </div>

                            <!-- Permission Content -->
                            <div class="min-w-0 flex-1">
                                <div
                                    class="mb-1 flex flex-wrap items-center gap-2"
                                >
                                    <span
                                        class="text-sm font-semibold text-slate-900 dark:text-white"
                                    >
                                        {{ perm.label }}
                                    </span>
                                    <span
                                        class="inline-block rounded-md bg-slate-100 px-1.5 py-0.5 font-mono text-[10px] font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-400"
                                    >
                                        {{ perm.name }}
                                    </span>
                                </div>
                                <p
                                    class="text-xs leading-relaxed text-slate-500 dark:text-slate-400"
                                >
                                    {{ perm.description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div
                class="flex flex-col-reverse gap-3 border-t border-slate-200/80 bg-slate-50/60 px-6 py-4 sm:flex-row sm:items-center sm:justify-end sm:px-8 dark:border-slate-800 dark:bg-slate-900/60"
            >
                <AppButton
                    type="button"
                    variant="secondary"
                    size="md"
                    class="w-full sm:w-auto"
                    @click="$emit('close')"
                >
                    Batal
                </AppButton>

                <AppButton
                    v-if="!isSuperAdmin"
                    type="button"
                    variant="primary"
                    size="md"
                    :loading="isSaving"
                    class="w-full sm:w-auto"
                    @click="savePermissions"
                >
                    Simpan Perubahan Hak Akses
                </AppButton>
            </div>
        </div>
    </Modal>
</template>
