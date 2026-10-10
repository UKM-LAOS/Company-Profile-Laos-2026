<script setup lang="ts">
const missions = [
    {
        text: "Menjadi relasi berbagai pihak yang mendukung dan mengimplementasikan Linux and Open Source.",
        icon: "/assets/relasi.svg",
    },
    {
        text: "Mengenalkan Linux dan Open Source software kepada masyarakat khususnya pada instansi pendidikan.",
        icon: "/assets/edukasi.svg",
    },
    {
        text: "Ikut serta dan aktif bersama aktivis Linux se-Indonesia mengenalkan OS Linux kepada masyarakat.",
        icon: "/assets/Aktivis.svg",
    },
];
</script>

<template>
    <!-- Visi -->
    <section class="mx-auto max-w-7xl px-5 py-14 sm:px-8">
        <h2 class="text-center text-3xl font-medium text-[#1E1E1E]">
            Visi UKM LAOS
        </h2>
        <div
            class="mt-8 rounded-xl bg-white p-8 text-center shadow-[0_0_6px_rgba(0,0,0,0.15)] sm:p-10"
        >
            <img
                src="/assets/visi.svg"
                alt="Ikon Visi UKM LAOS"
                class="mx-auto mb-4 h-10 w-10 sm:h-12 sm:w-12"
            />
            <p class="mx-auto max-w-7xl text-xl leading-relaxed text-[#636363]">
                Mendukung, mengenalkan dan mengembangkan Linux dan Open Source
                pada masyarakat luas khususnya Fakultas Ilmu Komputer.
            </p>
        </div>
    </section>

    <!-- Misi -->
    <section class="mx-auto max-w-7xl px-5 pb-14 sm:px-8">
        <h2 class="text-center text-3xl font-medium text-[#1E1E1E]">
            Misi UKM LAOS
        </h2>
        <div class="mt-8 grid gap-5 md:grid-cols-3">
            <article
                v-for="mission in missions"
                :key="mission.icon"
                class="flex flex-col items-center justify-center rounded-xl bg-white p-6 text-center shadow-[0_0_6px_rgba(0,0,0,0.15)] sm:p-8"
            >
                <img
                    :src="mission.icon"
                    alt="Ikon misi UKM LAOS"
                    class="mx-auto mb-4 h-10 w-10 sm:h-12 sm:w-12"
                />
                <p class="text-xl max-w-fit leading-relaxed text-gray-600">
                    {{ mission.text }}
                </p>
            </article>
        </div>
    </section>
</template>
