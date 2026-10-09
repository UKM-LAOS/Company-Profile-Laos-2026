<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <section
        class="relative flex min-h-163.75 flex-col justify-center overflow-hidden"
    >
        <div
            class="pointer-events-none absolute inset-0 z-0"
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
            class="relative z-10 mx-auto grid w-full max-w-7xl grid-cols-1 items-center gap-10 px-5 py-14 sm:px-8 lg:grid-cols-[7fr_5fr] lg:py-20"
        >
            <div class="w-full max-w-2xl lg:-mt-8 lg:max-w-3xl lg:-ml-15">
                <h1
                    class="text-4xl leading-tight font-extrabold text-[#5D5D5D] sm:text-5xl lg:whitespace-nowrap lg:text-6xl"
                >
                    Mengenal Lebih Dekat
                    <br class="hidden lg:block" />
                    <span class="text-[#22c55e]">UKM LAOS</span>
                </h1>
                <p
                    class="mt-4 max-w-2xl text-lg font-medium text-gray-600 sm:mt-6 sm:text-xl lg:max-w-3xl lg:text-2xl"
                >
                    Tempat bagi kamu yang ingin belajar, berkreasi, dan
                    berkontribusi di dunia digital. Yuk, jadi bagian dari
                    gerakan teknologi yang inovatif!
                </p>
                <Link
                    href="/program-kerja"
                    class="mt-30 inline-flex items-center gap-2 rounded-full border-2 border-white bg-[#2DCC70] px-8 py-3.5 text-sm font-semibold text-white shadow transition hover:bg-[#176638]"
                >
                    Lihat Program Kerja Kami
                    <span aria-hidden="true">&rarr;</span>
                </Link>
            </div>
            <div
                class="mt-6 flex w-full justify-center lg:justify-end lg:-mr-25"
            >
                <img
                    src="/assets/Group 456480386.png"
                    alt="Ilustrasi komunitas UKM LAOS"
                    loading="lazy"
                    decoding="async"
                    width="451"
                    height="414"
                    class="mx-auto h-auto w-[80%] max-w-75 rounded-tl-none rounded-tr-none rounded-br-[70px] rounded-bl-[130px] object-cover sm:max-w-90 md:mx-0 md:h-103.5 md:w-112.75 md:max-w-none md:rounded-br-[110px] md:rounded-bl-[207px] lg:h-125 lg:w-136.25"
                />
            </div>
        </div>
    </section>
</template>
