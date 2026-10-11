<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";
import AboutHero from "./Partials/AboutHero.vue";
import AboutVisiMisi from "./Partials/AboutVisiMisi.vue";
import PengurusSection from "./Partials/PengurusSection.vue";
import type { PengurusItem } from "./types";

defineProps<{
    pengurus: PengurusItem[];
}>();

const isNavOpen = ref(false);

const logoMeanings = [
    {
        title: "Tipografi",
        text: "Font yang digunakan tampak modern dan bersih, mencerminkan kesan sederhana, dan mudah dikenali.",
        icon: "/assets/tipografi.svg",
    },
    {
        title: "Warna",
        text: '"Kuning" melambangkan dasar Universitas Jember yang berarti "Kejayaan".',
        icon: "/assets/warna.svg",
    },
    {
        title: "Gambar",
        text: 'berwarna "Hijau" merupakan logo Open Source.',
        icon: "/assets/gambar.svg",
    },
];
</script>

<template>
    <Head title="Tentang Kami" />

    <div class="min-h-screen scroll-smooth bg-white font-sans text-[#1E1E1E]">
        <!-- Navbar -->
        <header class="sticky top-0 z-50 bg-white shadow-sm">
            <nav
                class="mx-auto flex h-[72px] max-w-7xl items-center justify-between gap-3 px-5 sm:px-8"
            >
                <Link href="/" class="flex shrink-0 items-center gap-2.5">
                    <img
                        src="/assets/Logo-Laos.svg"
                        alt="Logo UKM LAOS"
                        class="h-9 w-9"
                        onerror="
                            this.onerror = null;
                            this.src = '/assets/logo.png';
                        "
                    />
                    <span class="text-lg font-semibold text-[#2DCC70]"
                        >UKM LAOS</span
                    >
                </Link>
                <div
                    class="hidden items-center gap-7 text-sm font-semibold text-[#636363] md:flex"
                >
                    <Link href="/" class="transition hover:text-[#2DCC70]"
                        >Home</Link
                    >
                    <Link href="/blog" class="transition hover:text-[#2DCC70]"
                        >Blog</Link
                    >
                    <Link
                        href="/program-kerja"
                        class="transition hover:text-[#2DCC70]"
                        >Program Kerja</Link
                    >
                    <Link
                        href="/tentang-kami"
                        class="text-[#2DCC70]"
                        aria-current="page"
                        >Tentang Kami</Link
                    >
                    <a href="#pengurus" class="transition hover:text-[#2DCC70]"
                        >Pengurus</a
                    >
                </div>
                <div class="flex items-center gap-2 md:hidden">
                    <button
                        type="button"
                        class="flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 text-[#1E1E1E] transition hover:border-[#2DCC70] hover:text-[#2DCC70]"
                        :aria-expanded="isNavOpen"
                        aria-label="Buka menu navigasi"
                        @click="isNavOpen = !isNavOpen"
                    >
                        <svg
                            v-if="!isNavOpen"
                            class="h-5 w-5"
                            viewBox="0 0 20 20"
                            fill="none"
                            aria-hidden="true"
                        >
                            <path
                                d="M3 5h14M3 10h14M3 15h14"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                            />
                        </svg>
                        <svg
                            v-else
                            class="h-5 w-5"
                            viewBox="0 0 20 20"
                            fill="none"
                            aria-hidden="true"
                        >
                            <path
                                d="M5 5l10 10M15 5L5 15"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                            />
                        </svg>
                    </button>
                </div>
            </nav>
            <div
                v-if="isNavOpen"
                class="border-t border-slate-100 bg-white px-5 pt-2 pb-5 md:hidden"
            >
                <div
                    class="flex flex-col gap-1 text-sm font-semibold text-[#636363]"
                >
                    <Link
                        href="/"
                        class="rounded-lg px-3 py-2.5 transition hover:bg-[#2DCC70]/10 hover:text-[#2DCC70]"
                        @click="isNavOpen = false"
                        >Home</Link
                    >
                    <Link
                        href="/blog"
                        class="rounded-lg px-3 py-2.5 transition hover:bg-[#2DCC70]/10 hover:text-[#2DCC70]"
                        @click="isNavOpen = false"
                        >Blog</Link
                    >
                    <Link
                        href="/program-kerja"
                        class="rounded-lg px-3 py-2.5 transition hover:bg-[#2DCC70]/10 hover:text-[#2DCC70]"
                        @click="isNavOpen = false"
                        >Program Kerja</Link
                    >
                    <Link
                        href="/tentang-kami"
                        class="rounded-lg bg-[#2DCC70]/10 px-3 py-2.5 text-[#2DCC70]"
                        aria-current="page"
                        @click="isNavOpen = false"
                        >Tentang Kami</Link
                    >
                    <a
                        href="#pengurus"
                        class="rounded-lg px-3 py-2.5 transition hover:bg-[#2DCC70]/10 hover:text-[#2DCC70]"
                        @click="isNavOpen = false"
                        >Pengurus</a
                    >
                </div>
            </div>
        </header>

        <AboutHero />

        <!-- Tentang Kami -->
        <section class="mx-auto max-w-7xl px-5 py-14 text-center sm:px-8">
            <h2 class="text-3xl font-medium text-[#1E1E1E]">Tentang Kami</h2>
            <p class="mt-6 text-xl leading-relaxed text-[#636363] text-center">
                Unit Kegiatan Mahasiswa Linux and Open Source atau disebut juga
                UKM LAOS merupakan salah satu organisasi yang berfokus sebagai
                wadah mahasiswa untuk memajukan kreatifitas dalam pengembangan
                Linux dan Open Source di Fakultas Ilmu Komputer Universitas
                Jember. UKM LAOS berkedudukan di bawah naungan BEM (Badan
                Eksekutif Mahasiswa) Fakultas Ilmu Komputer. UKM LAOS didirikan
                pada tanggal 19 desember 2009 yang berasaskan “Pancasila” dan
                bersifat “Kekeluargaan”.
            </p>
        </section>

        <!-- Makna Logo -->
        <section class="bg-slate-50/60">
            <div class="mx-auto max-w-7xl px-5 py-14 sm:px-8">
                <h2 class="text-center text-3xl font-medium text-[#1E1E1E]">
                    Makna Logo
                </h2>
                <p
                    class="mx-auto mt-3 text-xl max-w-fit text-center text-[#636363]"
                >
                    Logo UKM LAOS memiliki dua warna utama dengan makna
                    simbolis:
                </p>
                <div
                    class="mt-10 grid items-center gap-10 lg:grid-cols-[320px_1fr]"
                >
                    <div class="flex justify-center">
                        <img
                            src="/assets/logo-laos.webp"
                            alt="Logo UKM LAOS"
                            class="w-64 max-w-full drop-shadow-[0_12px_20px_rgba(234,179,8,0.25)] sm:w-80"
                            onerror="
                                this.onerror = null;
                                this.src = '/assets/logo.png';
                            "
                        />
                    </div>
                    <ul class="space-y-10 sm:space-y-12 lg:ml-12">
                        <li
                            v-for="meaning in logoMeanings"
                            :key="meaning.title"
                            class="flex items-start gap-4 sm:gap-5"
                        >
                            <img
                                :src="meaning.icon"
                                :alt="`Ikon ${meaning.title}`"
                                class="h-8 w-8 shrink-0 sm:h-10 sm:w-10"
                            />
                            <p
                                class="leading-relaxed text-[#4A4A4A] sm:text-xl"
                            >
                                <strong class="font-semibold"
                                    >{{ meaning.title }}:</strong
                                >
                                {{ meaning.text }}
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <AboutVisiMisi />

        <PengurusSection :pengurus="pengurus" />

        <!-- Footer -->
        <footer class="bg-[#176638] text-white">
            <div
                class="mx-auto grid max-w-7xl gap-10 px-5 py-12 sm:px-8 md:grid-cols-2 lg:grid-cols-4"
            >
                <div>
                    <div class="flex items-center gap-2.5">
                        <img
                            src="/assets/logo-laos.webp
                            "
                            alt="Logo UKM LAOS"
                            class="h-9 w-9"
                            onerror="
                                this.onerror = null;
                                this.src = '/assets/logo-laos.webp';
                            "
                        />
                        <span class="text-lg font-semibold">UKM LAOS</span>
                    </div>
                    <p class="mt-4 text-sm leading-relaxed text-white/85">
                        Memberdayakan siswa dengan pendidikan teknologi mutakhir
                        dan bimbingan dari pakar industri.
                    </p>
                </div>
                <nav aria-label="Quick Links">
                    <h3 class="font-semibold">Quick Links</h3>
                    <ul
                        class="mt-4 space-y-2.5 text-sm font-medium text-white/85"
                    >
                        <li>
                            <Link href="/" class="transition hover:text-white"
                                >Home</Link
                            >
                        </li>
                        <li>
                            <Link
                                href="/blog"
                                class="transition hover:text-white"
                                >Blog</Link
                            >
                        </li>
                        <li>
                            <Link
                                href="/program-kerja"
                                class="transition hover:text-white"
                                >Program Kerja</Link
                            >
                        </li>
                        <li>
                            <Link
                                href="/tentang-kami"
                                class="transition hover:text-white"
                                >Tentang Kami</Link
                            >
                        </li>
                        <li>
                            <a
                                href="#pengurus"
                                class="transition hover:text-white"
                                >Pengurus</a
                            >
                        </li>
                    </ul>
                </nav>
                <div>
                    <h3 class="font-semibold">Kursus Kami</h3>
                    <ul
                        class="mt-4 space-y-2.5 text-sm font-medium text-white/85"
                    >
                        <li>Programming</li>
                        <li>Cyber Security</li>
                        <li>UI/UX Design</li>
                        <li>Digital Marketing</li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold">Kontak</h3>
                    <ul class="mt-4 space-y-2.5 text-sm text-white/85">
                        <li>Malang, East Java, Indonesia</li>
                        <li>
                            <a
                                href="mailto:info@ukmlaos.com"
                                class="transition hover:text-white"
                                >info@ukmlaos.com</a
                            >
                        </li>
                        <li>
                            <a
                                href="tel:+628****7890"
                                class="transition hover:text-white"
                                >+62 812 3456 7890</a
                            >
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-white/15">
                <p
                    class="mx-auto max-w-7xl px-5 py-4 text-center text-sm text-white/85 sm:px-8"
                >
                    &copy; 2025 UKM LAOS. All rights reserved
                </p>
            </div>
        </footer>
    </div>
</template>
