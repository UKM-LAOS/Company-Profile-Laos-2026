<script setup lang="ts">
import AppImage from '@/Components/Common/AppImage.vue';

const heroImages = {
    collab: 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=600&q=80',
    studio: 'https://images.unsplash.com/photo-1574717024653-61fd2cf4d44d?auto=format&fit=crop&w=600&q=80',
    coding: 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=600&q=80',
    seminar:
        'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=900&q=80',
};
</script>

<template>
    <section
        class="relative flex min-h-screen flex-col justify-center overflow-hidden bg-[#EEFAF4] pt-20 pb-12 transition-colors duration-300 lg:pt-20 lg:pb-0 dark:bg-[#070517]"
    >
        <!-- Vector Pastel Mint Architecture Arches & Flowing Ribbon Lines matching Figma -->
        <svg
            class="pointer-events-none absolute inset-0 h-full w-full transition-opacity duration-300 select-none dark:opacity-10"
            viewBox="0 0 1440 640"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            preserveAspectRatio="xMidYMid slice"
        >
            <!-- Vertical Pillar 1 (Behind "Penasaran") -->
            <rect
                x="170"
                y="-40"
                width="72"
                height="300"
                rx="36"
                fill="#D4F3E2"
                opacity="0.85"
            />

            <!-- Top Connecting Arch Bar -->

            <!-- Vertical Pillar 3 (Behind title right) -->
            <rect
                x="635"
                y="-40"
                width="72"
                height="450"
                rx="36"
                fill="#D4F3E2"
                opacity="0.85"
            />

            <!-- Behind Photos Pillar -->
            <rect
                x="1080"
                y="-40"
                width="74"
                height="680"
                rx="37"
                fill="#D4F3E2"
                opacity="0.75"
            />

            <!-- Smooth Flowing Golden/Orange Wave Ribbon -->
            <path
                d="M -30 380 C 120 440 230 320 370 340 C 510 360 650 440 820 380 C 970 330 1100 240 1260 260 C 1370 275 1420 250 1480 230"
                fill="none"
                stroke="#F5A623"
                stroke-width="1.8"
                stroke-linecap="round"
                opacity="0.85"
            />

            <!-- Smooth Flowing Cyan/Sky Blue Wave Ribbon -->
            <path
                d="M -30 290 C 90 230 210 370 360 330 C 510 290 640 240 780 310 C 920 380 1060 340 1210 280 C 1320 230 1410 270 1480 295"
                fill="none"
                stroke="#38BDF8"
                stroke-width="1.6"
                stroke-linecap="round"
                opacity="0.8"
            />

            <!-- Far Left Accent Yellow Dot -->
            <circle cx="10" cy="300" r="7" fill="#FACC15" />

            <!-- Bottom Center Accent White Circle Dot -->
            <circle
                cx="680"
                cy="510"
                r="10"
                fill="#FFFFFF"
                fill-opacity="0.9"
            />

            <!-- Far Right Accent Yellow Semicircle -->
            <circle cx="1440" cy="530" r="65" fill="#FACC15" />
        </svg>

        <!-- Main Content Grid -->
        <div
            class="relative z-10 mx-auto w-full max-w-7xl px-6 py-12 sm:px-12 md:px-16 lg:px-20 lg:py-16 xl:px-24"
        >
            <div
                class="grid grid-cols-1 items-center gap-12 lg:grid-cols-12 lg:gap-14 xl:gap-20"
            >
                <!-- Left Hero Content -->
                <div class="space-y-6 lg:col-span-6">
                    <h1
                        class="text-4xl leading-[1.15] font-extrabold tracking-tight text-[#3F3F46] sm:text-5xl lg:text-[54px] dark:text-white"
                    >
                        Penasaran <span class="text-[#2DCC70]">UKM</span><br />
                        <span class="text-[#2DCC70]">LAOS</span> ngapain aja?
                    </h1>

                    <p
                        class="max-w-lg text-base leading-relaxed font-normal text-[#52525B] sm:text-[17px] dark:text-slate-300"
                    >
                        UKM LAOS aktif dan inovatif dalam mengembangkan potensi
                        anggota dalam bidang ilmu komputer dengan
                        menyelenggarakan berbagai program kerja yang bermanfaat.
                    </p>

                    <div class="pt-2">
                        <a
                            href="#program-list"
                            class="inline-flex cursor-pointer items-center justify-center rounded-full bg-[#2DCC70] px-8 py-3.5 text-base font-semibold text-white shadow-md shadow-[#2DCC70]/20 transition-all duration-200 hover:bg-[#25B562] active:bg-[#1D9A50]"
                        >
                            Gabung Sekarang
                        </a>
                    </div>
                </div>

                <!-- Right Hero Collage matching Figma -->
                <div class="flex justify-center lg:col-span-6 lg:justify-end">
                    <div class="relative w-full max-w-[480px] sm:max-w-[500px]">
                        <!-- Chevron Arrows Decoration (Top Left) -->
                        <div
                            class="absolute -top-6 left-1 z-10 flex items-center space-x-0.5 select-none sm:left-2"
                        >
                            <svg
                                v-for="i in 5"
                                :key="i"
                                class="h-3.5 w-3.5 text-[#2DCC70]"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>

                        <!-- Target Iris Eye Lens Circular Badge (overlapping left edge between Card 1 & 2) -->
                        <div
                            class="absolute top-[145px] -left-6 z-20 -translate-y-1/2 select-none sm:top-[155px] sm:-left-7"
                        >
                            <svg
                                class="h-14 w-14 drop-shadow-md sm:h-16 sm:w-16"
                                viewBox="0 0 60 60"
                                fill="none"
                            >
                                <!-- Outer white border -->
                                <circle cx="30" cy="30" r="29" fill="white" />
                                <!-- Green outer ring -->
                                <circle cx="30" cy="30" r="26" fill="#2DCC70" />
                                <!-- White inner ring -->
                                <circle cx="30" cy="30" r="19" fill="white" />
                                <!-- Iris with hazel radial gradient -->
                                <defs>
                                    <radialGradient
                                        id="irisGrad"
                                        cx="38%"
                                        cy="38%"
                                        r="65%"
                                    >
                                        <stop
                                            offset="0%"
                                            stop-color="#8C503A"
                                        />
                                        <stop
                                            offset="60%"
                                            stop-color="#542817"
                                        />
                                        <stop
                                            offset="100%"
                                            stop-color="#2D150C"
                                        />
                                    </radialGradient>
                                </defs>
                                <circle
                                    cx="30"
                                    cy="30"
                                    r="14"
                                    fill="url(#irisGrad)"
                                />
                                <!-- Dark pupil -->
                                <circle
                                    cx="30"
                                    cy="30"
                                    r="7.5"
                                    fill="#18181B"
                                />
                                <!-- Specular gloss reflections -->
                                <circle
                                    cx="27"
                                    cy="27"
                                    r="2.4"
                                    fill="white"
                                    fill-opacity="0.95"
                                />
                                <circle
                                    cx="33"
                                    cy="33"
                                    r="1.2"
                                    fill="white"
                                    fill-opacity="0.6"
                                />
                            </svg>
                        </div>

                        <!-- Green Crosshair Reticle (Bottom Right) -->
                        <div
                            class="absolute -right-4 -bottom-2 z-10 text-[#2DCC70] select-none sm:-right-5"
                        >
                            <svg
                                class="h-5 w-5 sm:h-6 sm:w-6"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2.5"
                                stroke-linecap="round"
                            >
                                <path d="M12 3v3m0 12v3m-9-9h3m12 0h3" />
                            </svg>
                        </div>

                        <!-- Collage Photo Containers -->
                        <div>
                            <!-- Top 2-Column Section -->
                            <div
                                class="mb-3 grid grid-cols-12 items-stretch gap-3 sm:mb-3.5 sm:gap-3.5"
                            >
                                <!-- Left Sub-Column (Collab + Typing) -->
                                <div
                                    class="col-span-6 flex flex-col gap-3 sm:gap-3.5"
                                >
                                    <!-- Card 1: Collab -->
                                    <div
                                        class="h-[145px] overflow-hidden rounded-[22px] border border-white bg-slate-100 shadow-xs sm:h-[155px] sm:rounded-[24px] dark:border-slate-800 dark:bg-slate-800"
                                    >
                                        <AppImage
                                            :src="heroImages.collab"
                                            alt="Kolaborasi tim UKM LAOS"
                                            loading="eager"
                                            wrapper-class="h-full w-full"
                                            image-class="h-full w-full object-cover transition-transform duration-500 hover:scale-105"
                                        />
                                    </div>
                                    <!-- Card 2: Typing Hands -->
                                    <div
                                        class="h-[95px] overflow-hidden rounded-[22px] border border-white bg-slate-100 shadow-xs sm:h-[105px] sm:rounded-[24px] dark:border-slate-800 dark:bg-slate-800"
                                    >
                                        <AppImage
                                            :src="heroImages.coding"
                                            alt="Aktivitas hands-on laptop"
                                            loading="eager"
                                            wrapper-class="h-full w-full"
                                            image-class="h-full w-full object-cover transition-transform duration-500 hover:scale-105"
                                        />
                                    </div>
                                </div>

                                <!-- Right Sub-Column: Studio Control Room -->
                                <div class="col-span-6">
                                    <div
                                        class="h-[252px] overflow-hidden rounded-[22px] border border-white bg-slate-900 shadow-xs sm:h-[272px] sm:rounded-[24px] dark:border-slate-800"
                                    >
                                        <AppImage
                                            :src="heroImages.studio"
                                            alt="Studio lab teknologi UKM LAOS"
                                            loading="eager"
                                            wrapper-class="h-full w-full"
                                            image-class="h-full w-full object-cover transition-transform duration-500 hover:scale-105"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Bottom Wide Card: Hackathon / Auditorium -->
                            <div
                                class="h-[155px] w-full overflow-hidden rounded-[22px] border border-white bg-slate-900 shadow-xs sm:h-[170px] sm:rounded-[24px] dark:border-slate-800"
                            >
                                <AppImage
                                    :src="heroImages.seminar"
                                    alt="Kegiatan auditorium dan hackathon"
                                    loading="lazy"
                                    wrapper-class="h-full w-full"
                                    image-class="h-full w-full object-cover transition-transform duration-500 hover:scale-105"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
