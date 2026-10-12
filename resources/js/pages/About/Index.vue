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
    <div class="min-h-screen scroll-smooth bg-white font-sans text-[#1E1E1E] dark:bg-slate-950 dark:text-slate-100 transition-colors duration-300">
        <!-- Navbar -->
        <PublicNavbar/> 

        <AboutHero />

        <!-- Tentang Kami -->
        <section class="mx-auto max-w-7xl px-5 py-14 text-center sm:px-8">
            <h2 class="text-3xl font-medium text-[#1E1E1E] dark:text-white">Tentang Kami</h2>
            <p class="mt-6 text-center text-xl leading-relaxed text-[#636363] dark:text-slate-300">
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
        <section class="bg-slate-50/60 dark:bg-slate-900/60 transition-colors duration-300">
            <div class="mx-auto max-w-7xl px-5 py-14 sm:px-8">
                <h2 class="text-center text-3xl font-medium text-[#1E1E1E] dark:text-white">
                    Makna Logo
                </h2>
                <p
                    class="mx-auto mt-3 max-w-fit text-center text-xl text-[#636363] dark:text-slate-300"
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
                            onerror="this.onerror = null;
                            this.src = '/assets/logo.png';"
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
                                class="leading-relaxed text-[#4A4A4A] dark:text-slate-200 sm:text-xl"
                            >
                                <strong class="font-semibold text-gray-900 dark:text-white"
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
        <PublicFooter/>
    </div>
</template>
