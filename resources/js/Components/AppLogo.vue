<script setup lang="ts">
defineProps<{
    collapsed?: boolean;
}>();
</script>

<template>
    <div class="flex items-center gap-3">
        <img
            src="/assets/logo.png"
            alt="UKM LAOS"
            class="h-10 w-10 shrink-0 object-contain"
        />

        <!-- Typography -->
        <div v-if="!collapsed" class="flex flex-col leading-tight">
            <span
                class="text-base font-extrabold tracking-tight text-slate-900 dark:text-white"
            >
                UKM LAOS
            </span>
            <span
                class="text-[11px] font-bold tracking-wider text-emerald-600 uppercase dark:text-emerald-400"
            >
                FASILKOM UNEJ
            </span>
        </div>
    </div>
</template>
