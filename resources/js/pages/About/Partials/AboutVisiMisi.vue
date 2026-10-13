<script setup lang="ts">
const missions = [
    {
        id: 1,
        text: 'Menerapkan prinsip open-source dalam seluruh tahapan pengelolaan perangkat lunak: perancangan, pengembangan, dan pengamanan sistem.',
        icon: '/assets/icons/pemrograman.svg',
    },
    {
        id: 2,
        text: 'Mengembangkan kompetensi anggota dalam manajemen siklus perangkat lunak berbasis teknologi terbuka.',
        icon: '/assets/edukasi.svg',
    },
    {
        id: 3,
        text: 'Membangun budaya kolaboratif antaranggota melalui proyek nyata dan kontribusi pada komunitas open-source.',
        icon: '/assets/relasi.svg',
    },
    {
        id: 4,
        text: 'Menjadi wadah eksplorasi, riset, dan inovasi di bidang teknologi terbuka di lingkungan kampus.',
        icon: '/assets/Aktivis.svg',
    },
    {
        id: 5,
        text: 'Mengedukasi masyarakat akademik tentang pentingnya open-source dalam pengembangan teknologi yang transparan, efisien, dan berkelanjutan.',
        icon: '/assets/visi.svg',
    },
];
</script>

<template>
    <!-- Visi -->
    <section
        v-reveal
        class="mx-auto max-w-7xl px-5 py-16 sm:px-8 sm:py-20 lg:py-24"
    >
        <div class="mb-10 text-center">
            <h2
                class="text-3xl font-extrabold tracking-tight text-[#1E1E1E] sm:text-4xl dark:text-white"
            >
                Visi UKM LAOS
            </h2>
            <p
                class="mx-auto mt-3 max-w-2xl text-sm text-[#636363] sm:text-base dark:text-slate-400"
            >
                Arah dan cita-cita strategis UKM LAOS sebagai wadah pengembangan
                teknologi berbasis open-source
            </p>
        </div>

        <div
            class="relative mx-auto max-w-5xl overflow-hidden rounded-3xl border border-slate-100 bg-white p-8 text-center shadow-[0_4px_24px_rgba(0,0,0,0.05)] transition-all duration-300 sm:p-12 lg:p-14 dark:border-slate-800/80 dark:bg-slate-900 dark:shadow-none"
        >
            <div
                class="pointer-events-none absolute -top-20 left-1/2 h-44 w-80 -translate-x-1/2 rounded-full bg-emerald-400/10 blur-3xl dark:bg-emerald-500/5"
                aria-hidden="true"
            ></div>

            <div
                class="relative mb-6 inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-[#D5FFE7] shadow-xs sm:h-20 sm:w-20 dark:bg-emerald-950/70"
            >
                <img
                    src="/assets/visi.svg"
                    alt="Ikon Visi UKM LAOS"
                    class="h-8 w-8 object-contain sm:h-10 sm:w-10"
                />
            </div>

            <p
                class="relative mx-auto max-w-4xl text-base leading-relaxed font-medium text-[#3F3F46] sm:text-lg lg:text-xl dark:text-slate-200"
            >
                Mewujudkan UKM LAOS (Logicware And Open Source) sebagai
                pengembangan teknologi wadah yang menerapkan prinsip open-source
                dalam pengelolaan perangkat lunak secara utuh, mulai dari
                perancangan, pengembangan, hingga pengamanan sistem, untuk
                meningkatkan kemampuan, kolaborasi, dan kreativitas mahasiswa di
                bidang teknologi.
            </p>
        </div>
    </section>

    <!-- Misi -->
    <section
        v-reveal
        class="mx-auto max-w-7xl px-5 pb-16 sm:px-8 sm:pb-20 lg:pb-24"
    >
        <div class="mb-10 text-center">
            <h2
                class="text-3xl font-extrabold tracking-tight text-[#1E1E1E] sm:text-4xl dark:text-white"
            >
                Misi UKM LAOS
            </h2>
            <p
                class="mx-auto mt-3 max-w-2xl text-sm text-[#636363] sm:text-base dark:text-slate-400"
            >
                Komitmen nyata kami dalam mewujudkan visi organisasi melalui
                langkah-langkah strategis
            </p>
        </div>

        <!-- 5 Items Grid Layout: Row 1 (3 items), Row 2 (2 items centered) on lg -->
        <div
            class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-6 lg:gap-7"
        >
            <article
                v-for="(mission, idx) in missions"
                :key="mission.id"
                :class="[
                    'group relative flex flex-col items-center justify-start rounded-2xl border border-slate-100 bg-white p-6 text-center shadow-[0_4px_20px_rgba(0,0,0,0.05)] transition-all duration-300 hover:-translate-y-1.5 hover:border-emerald-200 hover:shadow-[0_14px_30px_rgba(45,204,112,0.12)] sm:p-8 dark:border-slate-800 dark:bg-slate-900 dark:shadow-none dark:hover:border-emerald-800/80',
                    'lg:col-span-2',
                    idx === 3 ? 'lg:col-start-2' : '',
                    idx === 4
                        ? 'sm:col-span-2 sm:mx-auto sm:max-w-md lg:col-span-2 lg:mx-0 lg:max-w-none'
                        : '',
                ]"
            >
                <!-- Icon with number badge -->
                <div
                    class="relative mb-5 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#D5FFE7] shadow-xs transition-transform duration-300 group-hover:scale-105 sm:h-16 sm:w-16 dark:bg-emerald-950/70"
                >
                    <img
                        :src="mission.icon"
                        alt="Ikon misi UKM LAOS"
                        class="h-7 w-7 object-contain sm:h-8 sm:w-8"
                    />
                    <span
                        class="absolute -top-1.5 -right-1.5 flex h-6 w-6 items-center justify-center rounded-full bg-[#2DCC70] text-[11px] font-bold text-white shadow-2xs"
                    >
                        0{{ mission.id }}
                    </span>
                </div>

                <p
                    class="text-sm leading-relaxed font-normal text-[#52525B] sm:text-[15px] dark:text-slate-300"
                >
                    {{ mission.text }}
                </p>
            </article>
        </div>
    </section>
</template>
