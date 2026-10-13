<script setup lang="ts">
import { computed, onMounted, ref, watch } from 'vue';

const props = withDefaults(
    defineProps<{
        src?: string | null;
        alt?: string;
        aspectRatio?: string;
        wrapperClass?: string;
        imageClass?: string;
        fallbackSrc?: string;
        loading?: 'lazy' | 'eager';
        decoding?: 'async' | 'sync' | 'auto';
        objectFit?: 'cover' | 'contain' | 'fill' | 'none' | 'scale-down';
    }>(),
    {
        src: '',
        alt: '',
        aspectRatio: '',
        wrapperClass: '',
        imageClass: '',
        fallbackSrc: '',
        loading: 'lazy',
        decoding: 'async',
        objectFit: 'cover',
    },
);

const emit = defineEmits<{
    (e: 'load', event: Event): void;
    (e: 'error', event: Event): void;
}>();

const isLoaded = ref(false);
const hasError = ref(false);
const imgRef = ref<HTMLImageElement | null>(null);

const resolvedSrc = computed(() => {
    if (hasError.value && props.fallbackSrc) {
        return props.fallbackSrc;
    }
    return props.src || props.fallbackSrc || '';
});

const objectFitClass = computed(() => {
    switch (props.objectFit) {
        case 'contain':
            return 'object-contain';
        case 'fill':
            return 'object-fill';
        case 'none':
            return 'object-none';
        case 'scale-down':
            return 'object-scale-down';
        default:
            return 'object-cover';
    }
});

function handleLoad(e: Event) {
    isLoaded.value = true;
    hasError.value = false;
    emit('load', e);
}

function handleError(e: Event) {
    if (
        !hasError.value &&
        props.fallbackSrc &&
        resolvedSrc.value !== props.fallbackSrc
    ) {
        hasError.value = true;
    } else {
        hasError.value = true;
        isLoaded.value = true; // Hentikan shimmer placeholder jika error
        emit('error', e);
    }
}

function checkCacheStatus() {
    if (!resolvedSrc.value) {
        isLoaded.value = true;
        hasError.value = true;
        return;
    }
    if (
        imgRef.value &&
        imgRef.value.complete &&
        imgRef.value.naturalWidth > 0
    ) {
        isLoaded.value = true;
    }
}

// Cek apakah gambar sudah ter-cache di memori browser saat komponen dimuat
onMounted(() => {
    checkCacheStatus();
});

watch(
    () => props.src,
    () => {
        isLoaded.value = false;
        hasError.value = false;
        setTimeout(() => {
            checkCacheStatus();
        }, 20);
    },
);
</script>

<template>
    <div
        :class="['relative overflow-hidden', wrapperClass]"
        :style="aspectRatio ? { aspectRatio } : undefined"
    >
        <!-- Skeleton Shimmer Placeholder Animasi saat sedang loading -->
        <div
            v-if="!isLoaded"
            aria-hidden="true"
            class="skeleton-shimmer absolute inset-0 z-0 h-full w-full"
        />

        <!-- Elemen Gambar Asli dengan Smooth Fade-in -->
        <img
            v-if="resolvedSrc"
            ref="imgRef"
            :src="resolvedSrc"
            :alt="alt"
            :loading="loading"
            :decoding="decoding"
            :class="[
                'relative z-1 h-full w-full transition-all duration-500 ease-out',
                objectFitClass,
                isLoaded
                    ? 'blur-0 scale-100 opacity-100'
                    : 'scale-[1.02] opacity-0 blur-xs',
                imageClass,
            ]"
            @load="handleLoad"
            @error="handleError"
        />

        <!-- Fallback Placeholder jika gambar gagal dimuat atau tidak tersedia -->
        <div
            v-else
            class="absolute inset-0 z-1 flex flex-col items-center justify-center bg-slate-100 p-2 text-center text-slate-400 dark:bg-slate-800 dark:text-slate-500"
        >
            <svg
                class="mb-1 h-8 w-8 opacity-60"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.5"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                />
            </svg>
            <span class="text-[11px] font-medium">Gambar tidak tersedia</span>
        </div>

        <!-- Slot untuk overlay, badge, atau button di atas gambar -->
        <slot />
    </div>
</template>
