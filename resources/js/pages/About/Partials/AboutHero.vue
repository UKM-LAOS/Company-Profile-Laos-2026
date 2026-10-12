<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <section
        class="relative flex min-h-screen flex-col justify-center overflow-hidden pt-20 pb-12 lg:pt-20 lg:pb-0"
    >
        <div
            class="pointer-events-none absolute inset-0 z-0 opacity-100 dark:opacity-30 transition-opacity duration-300"
            aria-hidden="true"
        >
            <img
                src="/assets/image19.png"
                alt=""
                aria-hidden="true"
                loading="lazy"
                decoding="async"
                width="1282"
                height="561"
                class="pointer-events-none absolute right-0 bottom-0 z-1 h-full w-full max-w-none object-cover object-bottom"
            />
            <img
                src="/assets/Group 456480385.png"
                alt=""
                aria-hidden="true"
                loading="lazy"
                decoding="async"
                width="1289"
                height="582"
                class="pointer-events-none absolute right-0 bottom-0 z-1 h-full w-full max-w-none object-cover object-bottom"
            />
        </div>
        <div
            class="relative z-10 mx-auto grid w-full max-w-7xl grid-cols-1 items-center gap-12 px-6 sm:px-12 md:px-16 lg:grid-cols-12 lg:gap-16 xl:gap-24 xl:px-20"
        >
            <div class="w-full lg:col-span-7">
                <h1
                    v-reveal="{ delay: 100 }"
                    class="text-4xl leading-tight font-extrabold text-[#5D5D5D] dark:text-white sm:text-5xl lg:text-5xl xl:text-6xl"
                >
                    Mengenal Lebih Dekat
                    <br class="hidden sm:block" />
                    <span class="text-[#22c55e]">UKM LAOS</span>
                </h1>
                <p
                    v-reveal="{ delay: 240 }"
                    class="mt-5 max-w-xl text-base font-medium leading-relaxed text-gray-600 dark:text-slate-300 sm:mt-6 sm:text-lg lg:text-xl"
                >
                    Tempat bagi kamu yang ingin belajar, berkreasi, dan
                    berkontribusi di dunia digital. Yuk, jadi bagian dari
                    gerakan teknologi yang inovatif!
                </p>
                <div v-reveal="{ delay: 380 }" class="mt-8">
                    <Link
                        href="/program-kerja"
                        class="inline-flex items-center gap-2 rounded-full border-2 border-white dark:border-slate-800 bg-[#2DCC70] px-8 py-3.5 text-sm font-semibold text-white shadow-md transition-all duration-300 hover:bg-[#176638] hover:shadow-lg"
                    >
                        Lihat Program Kerja Kami
                        <span aria-hidden="true">&rarr;</span>
                    </Link>
                </div>
            </div>
            <div
                v-reveal.right="{ delay: 260 }"
                class="flex w-full items-center justify-center lg:col-span-5 lg:justify-end"
            >
                <img
                    src="/assets/Group 456480386.png"
                    alt="Ilustrasi komunitas UKM LAOS"
                    loading="lazy"
                    decoding="async"
                    width="451"
                    height="414"
                    class="mx-auto h-auto w-[85%] max-w-[280px] rounded-br-[70px] rounded-bl-[130px] object-cover sm:max-w-[340px] md:max-w-[380px] md:rounded-br-[110px] md:rounded-bl-[207px] lg:mx-0 lg:w-full lg:max-w-[440px]"
                />
            </div>
        </div>
    </section>
</template>
