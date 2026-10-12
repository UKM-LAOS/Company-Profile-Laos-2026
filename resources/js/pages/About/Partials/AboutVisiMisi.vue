<script setup lang="ts">
const missions = [
    {
        text: 'Menjadi relasi berbagai pihak yang mendukung dan mengimplementasikan Linux and Open Source.',
        icon: '/assets/relasi.svg',
    },
    {
        text: 'Mengenalkan Linux dan Open Source software kepada masyarakat khususnya pada instansi pendidikan.',
        icon: '/assets/edukasi.svg',
    },
    {
        text: 'Ikut serta dan aktif bersama aktivis Linux se-Indonesia mengenalkan OS Linux kepada masyarakat.',
        icon: '/assets/Aktivis.svg',
    },
];
</script>

<template>
    <!-- Visi -->
    <section class="mx-auto max-w-7xl px-5 py-16 sm:px-8 sm:py-20 lg:py-24">
        <h2 class="text-center text-3xl sm:text-4xl font-extrabold text-[#1E1E1E] dark:text-white tracking-tight">
            Visi UKM LAOS
        </h2>
        <div
            class="mt-10 rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-8 sm:p-12 text-center shadow-[0_4px_20px_rgba(0,0,0,0.05)] dark:shadow-none transition-colors duration-300"
        >
            <img
                src="/assets/visi.svg"
                alt="Ikon Visi UKM LAOS"
                class="mx-auto mb-5 h-12 w-12 sm:h-14 sm:w-14"
            />
            <p class="mx-auto max-w-4xl text-sm sm:text-[14px] leading-relaxed text-[#636363] dark:text-slate-300">
                Mendukung, mengenalkan dan mengembangkan Linux dan Open Source
                pada masyarakat luas khususnya Fakultas Ilmu Komputer.
            </p>
        </div>
    </section>

    <!-- Misi -->
    <section class="mx-auto max-w-7xl px-5 pb-16 sm:px-8 sm:pb-20 lg:pb-24">
        <h2 class="text-center text-3xl sm:text-4xl font-extrabold text-[#1E1E1E] dark:text-white tracking-tight">
            Misi UKM LAOS
        </h2>
        <div class="mt-10 grid gap-6 md:grid-cols-3">
            <article
                v-for="mission in missions"
                :key="mission.icon"
                class="flex flex-col items-center justify-center rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-6 sm:p-8 text-center shadow-[0_4px_20px_rgba(0,0,0,0.05)] dark:shadow-none transition-colors duration-300"
            >
                <img
                    :src="mission.icon"
                    alt="Ikon misi UKM LAOS"
                    class="mx-auto mb-4 h-12 w-12 sm:h-14 sm:w-14"
                />
                <p class="max-w-fit text-sm sm:text-[14px] leading-relaxed text-gray-600 dark:text-slate-300">
                    {{ mission.text }}
                </p>
            </article>
        </div>
    </section>
</template>
