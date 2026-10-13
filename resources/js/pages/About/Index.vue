<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AboutHero from './Partials/AboutHero.vue';
import AboutVisiMisi from './Partials/AboutVisiMisi.vue';
import PengurusSection from './Partials/PengurusSection.vue';
import type { PengurusItem } from './types';
import PublicNavbar from '@/Components/Navbar/PublicNavbar.vue';
import PublicFooter from '@/Components/Footer/PublicFooter.vue';

defineProps<{
    pengurus: PengurusItem[];
}>();

const logoMeanings = [
    {
        title: 'Tipografi',
        text: 'Font yang digunakan tampak modern dan bersih, mencerminkan kesan sederhana, dan mudah dikenali.',
        icon: '/assets/tipografi.svg',
    },
    {
        title: 'Warna',
        text: '"Kuning" melambangkan dasar Universitas Jember yang berarti "Kejayaan".',
        icon: '/assets/warna.svg',
    },
    {
        title: 'Gambar',
        text: 'berwarna "Hijau" merupakan logo Open Source.',
        icon: '/assets/gambar.svg',
    },
];
</script>

<template>
    <Head title="Tentang Kami" />
    <div
        class="min-h-screen scroll-smooth bg-white font-sans text-[#1E1E1E] transition-colors duration-300 dark:bg-slate-950 dark:text-slate-100"
    >
        <!-- Navbar -->
        <PublicNavbar />

        <AboutHero />

        <!-- Tentang Kami -->
        <section
            v-reveal
            class="mx-auto max-w-7xl px-5 py-16 text-center sm:px-8 sm:py-20 lg:py-24"
        >
            <h2
                class="text-3xl font-extrabold tracking-tight text-[#1E1E1E] sm:text-4xl dark:text-white"
            >
                Tentang Kami
            </h2>
            <p
                class="mx-auto mt-6 max-w-4xl text-center text-base leading-relaxed text-[#636363] sm:text-lg dark:text-slate-300"
            >
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
        <section
            v-reveal
            class="bg-slate-50 py-16 sm:py-20 lg:py-24 dark:bg-slate-900/60"
        >
            <div class="mx-auto max-w-7xl px-5 sm:px-8">
                <h2
                    class="text-center text-3xl font-extrabold tracking-tight text-[#1E1E1E] sm:text-4xl dark:text-white"
                >
                    Makna Logo
                </h2>
                <p
                    class="mx-auto mt-3.5 max-w-fit text-center text-base text-[#636363] sm:text-lg dark:text-slate-300"
                >
                    Logo UKM LAOS memiliki dua warna utama dengan makna
                    simbolis:
                </p>
                <div
                    class="mt-12 grid items-center justify-items-center gap-10 lg:grid-cols-[240px_1fr] lg:gap-14"
                >
                    <div class="relative flex items-center justify-center">
                        <!-- Soft decorative ambient glow yang ringan (GPU-accelerated) -->
                        <div
                            class="pointer-events-none absolute -inset-2 rounded-full bg-amber-400/10 blur-xl dark:bg-amber-400/5"
                        ></div>
                        <img
                            src="/assets/logo-laos.webp"
                            alt="Logo UKM LAOS"
                            width="208"
                            height="208"
                            loading="eager"
                            decoding="async"
                            class="relative z-10 aspect-square h-auto w-40 transform-gpu object-contain transition-transform duration-300 hover:scale-105 sm:w-48 lg:w-52"
                            onerror="this.onerror = null;
                            this.src = '/assets/logo.png';"
                        />
                    </div>
                    <ul class="w-full space-y-6 sm:space-y-8">
                        <li
                            v-for="meaning in logoMeanings"
                            :key="meaning.title"
                            class="flex items-start gap-4 sm:gap-5"
                        >
                            <img
                                :src="meaning.icon"
                                :alt="`Ikon ${meaning.title}`"
                                width="36"
                                height="36"
                                loading="lazy"
                                decoding="async"
                                class="mt-0.5 h-8 w-8 shrink-0 object-contain sm:h-9 sm:w-9 dark:brightness-110"
                                :class="
                                    meaning.title === 'Tipografi'
                                        ? 'dark:invert'
                                        : ''
                                "
                            />
                            <div>
                                <h3
                                    class="mb-1 text-lg font-bold text-gray-900 sm:text-xl dark:text-white"
                                >
                                    {{ meaning.title }}
                                </h3>
                                <p
                                    class="text-sm leading-relaxed text-[#4A4A4A] sm:text-[14px] dark:text-slate-300"
                                >
                                    {{ meaning.text }}
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <AboutVisiMisi />

        <PengurusSection :pengurus="pengurus" />

        <!-- Footer -->
        <PublicFooter />
    </div>
</template>
