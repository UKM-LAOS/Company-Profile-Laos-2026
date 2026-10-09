<script setup lang="ts">
import {
    divisionLabel,
    initials,
    photoUrl,
    sosmedLinks,
    type PengurusItem,
} from "../types";

defineProps<{
    item: PengurusItem;
}>();
</script>

<template>
    <article
        class="flex h-52.5 w-full max-w-54.5 flex-col overflow-hidden rounded-[10px] bg-white shadow-sm transition hover:shadow-md"
    >
        <div class="relative h-33.75 shrink-0 overflow-hidden bg-[#2DCC70]">
            <img
                src="/assets/image20.png"
                alt=""
                aria-hidden="true"
                loading="lazy"
                decoding="async"
                width="218"
                height="135"
                class="pointer-events-none absolute inset-0 h-full w-full rounded-t-[10px] object-cover"
            />
            <div
                class="relative z-10 flex items-center justify-center gap-1 pt-1.5"
            >
                <img
                    src="/assets/logo-laos.webp"
                    alt="Logo UKM LAOS"
                    loading="lazy"
                    decoding="async"
                    width="14"
                    height="14"
                    class="h-3.5 w-3.5 object-contain"
                    onerror="
                        this.onerror = null;
                        this.src = '/assets/logo.png';
                    "
                />
                <span class="text-[11px] font-semibold text-white"
                    >UKM LAOS</span
                >
            </div>
            <div
                class="absolute bottom-0 left-1/2 z-10 h-26.25 w-26.25 -translate-x-1/2 overflow-hidden rounded-t-[10px] rounded-b-none border border-b-0 border-white bg-white"
            >
                <img
                    v-if="photoUrl(item)"
                    :src="photoUrl(item)!"
                    :alt="`Foto ${item.nama}`"
                    loading="lazy"
                    decoding="async"
                    width="105"
                    height="105"
                    class="h-full w-full object-cover"
                />
                <div
                    v-else
                    class="flex h-full w-full items-center justify-center bg-[#176638] text-lg font-bold text-white"
                    aria-hidden="true"
                >
                    {{ initials(item.nama) }}
                </div>
            </div>
        </div>
        <div
            class="relative z-20 flex flex-1 flex-col items-center justify-start gap-0 overflow-hidden bg-white px-2 pt-1 pb-1.5 text-center"
        >
            <div class="w-full">
                <div
                    data-marquee
                    class="marquee m-0 w-full-width overflow-hidden p-0"
                >
                    <h3
                        data-marquee-inner
                        class="marquee-inner m-0 p-0 text-center text-[12.5px] gap-0 leading-tight font-medium whitespace-nowrap text-[#1E1E1E]"
                    >
                        {{ item.nama }}
                    </h3>
                </div>
                <div
                    data-marquee
                    class="marquee m-0 w-full overflow-hidden p-0"
                >
                    <p
                        data-marquee-inner
                        class="marquee-inner m-0 p-0 text-center text-[9.5px] leading-tight whitespace-nowrap text-[#636363]"
                    >
                        {{ divisionLabel(item) }}
                    </p>
                </div>
            </div>
            <div class="mt-0.5 flex items-center justify-center gap-4">
                <component
                    :is="link.active ? 'a' : 'span'"
                    v-for="link in sosmedLinks(item)"
                    :key="link.key"
                    :href="link.active ? link.url! : undefined"
                    :target="link.active ? '_blank' : undefined"
                    :rel="link.active ? 'noopener noreferrer' : undefined"
                    :aria-label="`${link.label} ${item.nama}`"
                    :aria-disabled="link.active ? undefined : 'true'"
                    :class="[
                        'flex h-4 w-4 items-center justify-center rounded-full transition',
                        link.active
                            ? 'cursor-pointer bg-[#2DCC70] hover:scale-110 hover:opacity-90'
                            : 'pointer-events-none cursor-not-allowed bg-[#E0E0E0] opacity-60',
                    ]"
                >
                    <img
                        :src="link.icon"
                        :alt="link.label"
                        loading="lazy"
                        decoding="async"
                        width="8"
                        height="8"
                        class="h-2 w-2"
                    />
                </component>
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
