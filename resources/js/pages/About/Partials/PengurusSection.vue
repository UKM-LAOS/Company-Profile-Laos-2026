<script setup lang="ts">
import { computed, nextTick, onMounted, onUpdated, ref, watch } from "vue";
import PengurusCard from "./PengurusCard.vue";
import PeriodDropdown from "./PeriodDropdown.vue";
import { normalizePeriode, type PengurusItem } from "../types";

const props = defineProps<{
    pengurus: PengurusItem[];
}>();

const ITEMS_PER_PAGE = 15;
const currentPage = ref(1);

const committee = computed(() => props.pengurus ?? []);

const periodOptions = ["2024/2025", "2025/2026", "2026/2027"];
const selectedPeriode = ref("2025/2026");

const filteredPengurus = computed(() => {
    if (selectedPeriode.value === "") return committee.value;
    const target = normalizePeriode(selectedPeriode.value);
    return committee.value.filter(
        (item) => normalizePeriode(item.periode) === target,
    );
});

watch(selectedPeriode, () => {
    currentPage.value = 1;
});

const totalPages = computed(() =>
    Math.max(1, Math.ceil(filteredPengurus.value.length / ITEMS_PER_PAGE)),
);

const paginatedPengurus = computed(() => {
    const start = (currentPage.value - 1) * ITEMS_PER_PAGE;
    return filteredPengurus.value.slice(start, start + ITEMS_PER_PAGE);
});

function goToPage(page: number): void {
    currentPage.value = Math.min(Math.max(1, page), totalPages.value);
}

watch(totalPages, (pages) => {
    if (currentPage.value > pages) currentPage.value = pages;
});

function setupMarquees(): void {
    document.querySelectorAll<HTMLElement>("[data-marquee]").forEach((el) => {
        const inner = el.querySelector<HTMLElement>("[data-marquee-inner]");
        if (!inner) return;
        const overflowing = inner.scrollWidth > el.clientWidth + 1;
        el.classList.toggle("is-overflowing", overflowing);
        if (overflowing) {
            const distance = inner.scrollWidth - el.clientWidth;
            inner.style.setProperty("--marquee-distance", `${distance}px`);
        } else {
            inner.style.removeProperty("--marquee-distance");
        }
    });
}

onMounted(() => {
    void nextTick(setupMarquees);
});

onUpdated(() => {
    void nextTick(setupMarquees);
});
</script>

<template>
    <section
        id="pengurus"
        class="mx-auto max-w-7xl scroll-mt-24 px-5 py-14 sm:px-8"
    >
        <h2
            class="text-center text-5xl font-semibold tracking-wide text-[#1E1E1E]"
        >
            PENGURUS UKM LAOS
        </h2>
        <div class="mt-4 flex flex-wrap items-center justify-center gap-3">
            <p class="text-lg text-[#636363]">UKM LAOS Periode</p>
            <PeriodDropdown
                v-model="selectedPeriode"
                :options="periodOptions"
            />
        </div>

        <div
            v-if="filteredPengurus.length > 0"
            class="mt-10 grid grid-cols-2 justify-items-center gap-3 sm:grid-cols-3 sm:gap-4 lg:grid-cols-5"
        >
            <PengurusCard
                v-for="item in paginatedPengurus"
                :key="item.id"
                :item="item"
            />
        </div>
        <div
            v-if="filteredPengurus.length === 0"
            class="mx-auto mt-10 w-full max-w-xl rounded-2xl border border-slate-200 bg-white px-4 py-16 text-center shadow-sm"
        >
            <svg
                class="mx-auto h-14 w-14 text-slate-300"
                viewBox="0 0 24 24"
                fill="none"
                aria-hidden="true"
            >
                <path
                    d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7Z"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linejoin="round"
                />
                <path
                    d="M9.5 13.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"
                    stroke="currentColor"
                    stroke-width="1.8"
                />
                <path
                    d="m11.3 11.7 2.2 2.2"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                />
            </svg>
            <p class="mt-5 text-[18px] font-medium text-[#1E1E1E]">
                Belum ada data pengurus
            </p>
            <p class="mt-2 text-[14px] text-[#636363]">
                Data pengurus untuk periode ini belum ditambahkan.
            </p>
        </div>

        <div
            v-if="filteredPengurus.length > ITEMS_PER_PAGE"
            class="mt-8 flex flex-wrap items-center justify-center gap-2"
        >
            <button
                type="button"
                :disabled="currentPage === 1"
                class="rounded-full border border-slate-200 px-4 py-1.5 text-sm font-medium transition disabled:cursor-not-allowed disabled:opacity-40"
                :class="
                    currentPage === 1
                        ? 'bg-slate-100 text-slate-400'
                        : 'bg-white text-slate-600 hover:border-[#2DCC70] hover:text-[#2DCC70]'
                "
                @click="goToPage(currentPage - 1)"
            >
                &lt;
            </button>
            <button
                v-for="page in totalPages"
                :key="page"
                type="button"
                class="h-8 w-8 rounded-full text-sm font-semibold transition"
                :class="
                    page === currentPage
                        ? 'bg-[#2DCC70] text-white'
                        : 'bg-slate-100 text-slate-500 hover:bg-slate-200'
                "
                @click="goToPage(page)"
            >
                {{ page }}
            </button>
            <button
                type="button"
                :disabled="currentPage === totalPages"
                class="rounded-full border border-slate-200 px-4 py-1.5 text-sm font-medium transition disabled:cursor-not-allowed disabled:opacity-40"
                :class="
                    currentPage === totalPages
                        ? 'bg-slate-100 text-slate-400'
                        : 'bg-white text-slate-600 hover:border-[#2DCC70] hover:text-[#2DCC70]'
                "
                @click="goToPage(currentPage + 1)"
            >
                &gt;
            </button>
        </div>
    </section>
</template>
