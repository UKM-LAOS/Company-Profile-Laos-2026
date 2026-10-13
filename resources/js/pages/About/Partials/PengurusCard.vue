<script setup lang="ts">
import { computed } from 'vue';
import AppImage from '@/Components/Common/AppImage.vue';
import {
    divisionLabel,
    initials,
    photoUrl,
    sosmedLinks,
    type PengurusItem,
} from '../types';

const props = defineProps<{
    item: PengurusItem;
}>();

const activeSosmed = computed(() =>
    sosmedLinks(props.item).filter((link) => link.active),
);
</script>

<template>
    <article
        class="flex h-[255px] w-full max-w-[245px] flex-col overflow-hidden rounded-xl border border-slate-100 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md sm:h-[265px] sm:max-w-[255px] dark:border-slate-800 dark:bg-slate-900"
    >
        <div
            class="relative h-[155px] shrink-0 overflow-hidden bg-[#2DCC70] sm:h-[162px]"
        >
            <img
                src="/assets/image20.png"
                alt=""
                aria-hidden="true"
                loading="lazy"
                decoding="async"
                width="255"
                height="162"
                class="pointer-events-none absolute inset-0 h-full w-full rounded-t-xl object-cover"
            />
            <div
                class="relative z-10 flex items-center justify-center gap-1.5 pt-2"
            >
                <img
                    src="/assets/logo-laos.webp"
                    alt="Logo UKM LAOS"
                    loading="lazy"
                    decoding="async"
                    width="16"
                    height="16"
                    class="h-4 w-4 object-contain"
                    onerror="this.onerror = null;
                    this.src = '/assets/logo.png';"
                />
                <span class="text-xs font-semibold text-white drop-shadow-sm"
                    >UKM LAOS</span
                >
            </div>
            <div
                class="absolute bottom-0 left-1/2 z-10 h-28 w-28 -translate-x-1/2 overflow-hidden rounded-t-xl rounded-b-none border border-b-0 border-white bg-white sm:h-30 sm:w-30 dark:border-slate-900 dark:bg-slate-900"
            >
                <AppImage
                    v-if="photoUrl(item)"
                    :src="photoUrl(item)!"
                    :alt="`Foto ${item.nama}`"
                    loading="lazy"
                    wrapper-class="h-full w-full"
                    image-class="object-cover"
                />
                <div
                    v-else
                    class="flex h-full w-full items-center justify-center bg-[#176638] text-xl font-bold text-white"
                    aria-hidden="true"
                >
                    {{ initials(item.nama) }}
                </div>
            </div>
        </div>
        <div
            class="relative z-20 flex flex-1 flex-col items-center justify-between overflow-hidden bg-white px-2.5 pt-1.5 pb-2.5 text-center dark:bg-slate-900"
        >
            <div class="w-full">
                <div
                    data-marquee
                    class="marquee w-full-width m-0 overflow-hidden p-0"
                >
                    <h3
                        data-marquee-inner
                        class="marquee-inner m-0 gap-0 p-0 text-center text-[13px] leading-tight font-semibold whitespace-nowrap text-[#1E1E1E] sm:text-[14px] dark:text-white"
                    >
                        {{ item.nama }}
                    </h3>
                </div>
                <div
                    data-marquee
                    class="marquee m-0 mt-0.5 w-full overflow-hidden p-0"
                >
                    <p
                        data-marquee-inner
                        class="marquee-inner m-0 p-0 text-center text-[10.5px] leading-tight whitespace-nowrap text-[#636363] sm:text-[11.5px] dark:text-slate-400"
                    >
                        {{ divisionLabel(item) }}
                    </p>
                </div>
            </div>
            <div
                class="mt-1.5 flex min-h-[26px] items-center justify-center gap-2.5"
            >
                <a
                    v-for="link in activeSosmed"
                    :key="link.key"
                    :href="link.url!"
                    target="_blank"
                    rel="noopener noreferrer"
                    :aria-label="`${link.label} ${item.nama}`"
                    class="flex h-6 w-6 items-center justify-center rounded-full bg-[#2DCC70] text-white shadow-sm transition hover:scale-110 hover:opacity-90 sm:h-6.5 sm:w-6.5"
                >
                    <img
                        :src="link.icon"
                        :alt="link.label"
                        loading="lazy"
                        decoding="async"
                        width="14"
                        height="14"
                        class="h-3.5 w-3.5 object-contain sm:h-4 sm:w-4"
                    />
                </a>
            </div>
        </div>
    </article>
</template>

<style scoped>
.marquee-inner {
    display: inline-block;
    max-width: 100%;
    transform: translate3d(0, 0, 0);
}

.marquee.is-overflowing .marquee-inner {
    animation: about-marquee 6s linear infinite;
    will-change: transform;
}

.marquee.is-overflowing .marquee-inner:hover {
    animation-play-state: paused;
}

@keyframes about-marquee {
    0%,
    15% {
        transform: translate3d(0, 0, 0);
    }
    70%,
    85% {
        transform: translate3d(calc(-1 * var(--marquee-distance, 0px)), 0, 0);
    }
    100% {
        transform: translate3d(0, 0, 0);
    }
}

@media (prefers-reduced-motion: reduce) {
    .marquee.is-overflowing .marquee-inner {
        animation: none;
    }
}
</style>
