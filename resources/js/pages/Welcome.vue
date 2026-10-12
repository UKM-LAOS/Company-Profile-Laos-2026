<script setup lang="ts">
import { computed, ref, onMounted, onUnmounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PublicNavbar from '@/Components/Navbar/PublicNavbar.vue';
import PublicFooter from '@/Components/Footer/PublicFooter.vue';

const props = defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
    laravelVersion?: string;
    phpVersion?: string;
    divisis?: Array<{
        id: number;
        nama: string;
        deskripsi: string;
        logo: string | null;
    }>;
    programs?: Array<{
        id: number;
        divisi_id?: number;
        judul_program: string;
        slug?: string;
        deskripsi: string;
        foto?: string | null;
        foto_url?: string | null;
        location_name?: string;
        status_peserta?: string;
        divisi?: {
            id: number;
            nama: string;
        } | null;
    }>;
}>();

const manfaatList = [
    {
        title: 'Relasi',
        image: '/assets/manfaat-laos/relasi.png',
        deskripsi:
            'Menambah wawasan membebaskan dari permasalahan. Relasi yang tepat membuka ide, solusi, dan pengalaman berharga',
    },
    {
        title: 'Bakat',
        image: '/assets/manfaat-laos/bakat.png',
        deskripsi:
            'Meningkatkan dan mengembangkan potensi yang ada pada setiap mahasiswa secara optimal melalui berbagai kegiatan',
    },
    {
        title: 'Sertifikat',
        image: '/assets/manfaat-laos/sertifikat.png',
        deskripsi:
            'Dapatkan sertifikat keaktifan sebagai anggota UKM LAOS, dukung CV kamu agar jauh lebih baik lagi',
    },
];

// Fallback Program Kerja jika database belum memiliki data
const defaultPrograms = [
    {
        id: 1,
        judul_program: 'Pelatihan Linux',
        deskripsi:
            'Belajar sistem operasi Linux dari dasar untuk bekal teknis, instalasi distro, manajemen paket, terminal commands, konfigurasi server dasar, hingga optimasi hak akses pengguna.',
        foto_url: '/assets/program-default.png',
        location_name: 'Lab Komputer Gd. Fasilkom',
        divisi: { id: 1, nama: 'Cyber Security' },
    },
    {
        id: 2,
        judul_program: 'Pelatihan CTF',
        deskripsi:
            'Belajar keamanan siber lewat tantangan Capture The Flag (CTF), mendalami teknik cryptography, reverse engineering, web exploitation, dan analisis forensik digital.',
        foto_url: '/assets/program-default.png',
        location_name: 'Auditorium Fasilkom UNEJ',
        divisi: { id: 2, nama: 'Cyber Security' },
    },
    {
        id: 3,
        judul_program: 'Sarasehan',
        deskripsi:
            'Diskusi santai dan dialog terbuka seputar perkembangan teknologi, ekosistem open-source modern, serta sharing wawasan kolaboratif bersama UKM dan komunitas eksternal.',
        foto_url: '/assets/program-default.png',
        location_name: 'Ruang Bersama Gedung PKM',
        divisi: { id: 3, nama: 'Humas' },
    },
    {
        id: 4,
        judul_program: 'PILA (Program Internal LAOS)',
        deskripsi:
            'Program pengembangan internal UKM LAOS yang berfokus pada penguatan kapasitas kepemimpinan, teamwork, manajemen proyek, dan technical skill seluruh kader.',
        foto_url: '/assets/program-default.png',
        location_name: 'Ruang UKM LAOS',
        divisi: { id: 4, nama: 'HRM' },
    },
    {
        id: 5,
        judul_program: 'Laos Arena',
        deskripsi:
            'Kompetisi teknologi internal yang menantang kreativitas dan ketangkasan anggota dalam problem solving, live coding hackathon, dan inovasi solusi perangkat lunak.',
        foto_url: '/assets/program-default.png',
        location_name: 'Lab Pemrograman Fasilkom',
        divisi: { id: 5, nama: 'Pemrograman' },
    },
    {
        id: 6,
        judul_program: 'Pertemuan Rutin',
        deskripsi:
            'Pertemuan online dan offline terstruktur yang menyajikan materi pembelajaran dari para pengurus, evaluasi project divisi, serta wadah interaktif berbagi ide kreatif.',
        foto_url: '/assets/program-default.png',
        location_name: 'Hybrid Zoom & Fasilkom',
        divisi: { id: 6, nama: 'BPH' },
    },
];

// Fallback Divisi jika database belum memiliki data
const defaultDivisis = [
    {
        id: 1,
        nama: 'Hubungan Masyarakat',
        deskripsi:
            'Divisi yang bertanggung jawab dalam penyebaran informasi di lingkup Fakultas Ilmu Komputer Universitas Jember serta menjalin relasi eksternal.',
        icon: '/assets/icons/humas.svg',
    },
    {
        id: 2,
        nama: 'Pemrograman',
        deskripsi:
            'Divisi yang berfokus pada pemrograman untuk mewujudkan tujuan UKM LAOS dalam menyebarkan open source serta pengembangan software inovatif.',
        icon: '/assets/icons/pemrograman.svg',
    },
    {
        id: 3,
        nama: 'Cyber Security',
        deskripsi:
            'Divisi yang memberikan wawasan mengenai Linux, jaringan komputer, dan keamanan siber untuk mendukung pemahaman teknologi pertahanan informasi.',
        icon: '/assets/icons/cyber-security.svg',
    },
    {
        id: 4,
        nama: 'Multimedia',
        deskripsi:
            'Divisi ini fokus pada desain UI/UX dan pengelolaan konten media sosial untuk memastikan komunikasi visual yang menarik, estetik, dan interaktif.',
        icon: '/assets/icons/multimedia.svg',
    },
    {
        id: 5,
        nama: 'Human Resource Management',
        deskripsi:
            'Divisi yang mendukung pengembangan soft skill dan produktivitas pengurus melalui pelatihan internal, serta program peningkatan kekompakan tim.',
        icon: '/assets/icons/hrm.svg',
    },
];

// Program yang diambil dari Database (dengan fallback jika kosong)
const computedPrograms = computed(() => {
    if (props.programs && props.programs.length > 0) {
        return props.programs.map((p, idx) => ({
            ...p,
            foto_url:
                p.foto_url ||
                (p.foto
                    ? p.foto.startsWith('http')
                        ? p.foto
                        : `/storage/${p.foto}`
                    : '/assets/program-default.png'),
            deskripsi:
                p.deskripsi ||
                'Rangkaian program untuk mengembangkan potensi, menantang kreativitas, dan membangun skill nyata.',
            divisi: p.divisi || defaultPrograms[idx % defaultPrograms.length].divisi,
        }));
    }
    return defaultPrograms;
});

// Divisi yang diambil dari Database (dengan fallback jika kosong)
const computedDivisis = computed(() => {
    if (props.divisis && props.divisis.length > 0) {
        return props.divisis.map((d, idx) => {
            const hasCustomLogo = Boolean(d.logo);
            let logoUrl = '';
            if (d.logo) {
                logoUrl = d.logo.startsWith('http') ? d.logo : `/storage/${d.logo}`;
            } else {
                logoUrl = defaultDivisis[idx % defaultDivisis.length].icon;
            }
            return {
                ...d,
                has_custom_logo: hasCustomLogo,
                logo_url: logoUrl,
            };
        });
    }
    return defaultDivisis.map((d) => ({
        ...d,
        has_custom_logo: false,
        logo_url: d.icon,
    }));
});

// Slider Carousel Logic untuk Program Kerja
const itemsPerView = ref(3);
const currentPage = ref(0);
const isPaused = ref(false);
let autoSlideTimer: ReturnType<typeof setInterval> | null = null;

const updateItemsPerView = () => {
    if (typeof window === 'undefined') return;
    if (window.innerWidth < 640) {
        itemsPerView.value = 1;
    } else if (window.innerWidth < 1024) {
        itemsPerView.value = 2;
    } else {
        itemsPerView.value = 3;
    }
};

const totalPages = computed(() => {
    return Math.ceil(computedPrograms.value.length / itemsPerView.value);
});

const getProgramsForPage = (pageIdx: number) => {
    const start = pageIdx * itemsPerView.value;
    return computedPrograms.value.slice(start, start + itemsPerView.value);
};

const nextSlide = () => {
    if (totalPages.value <= 1) return;
    currentPage.value = (currentPage.value + 1) % totalPages.value;
};

const prevSlide = () => {
    if (totalPages.value <= 1) return;
    currentPage.value =
        (currentPage.value - 1 + totalPages.value) % totalPages.value;
};

const startAutoSlide = () => {
    stopAutoSlide();
    autoSlideTimer = setInterval(() => {
        if (!isPaused.value && totalPages.value > 1) {
            nextSlide();
        }
    }, 4500);
};

const stopAutoSlide = () => {
    if (autoSlideTimer) {
        clearInterval(autoSlideTimer);
        autoSlideTimer = null;
    }
};

onMounted(() => {
    updateItemsPerView();
    window.addEventListener('resize', updateItemsPerView);
    startAutoSlide();
});

onUnmounted(() => {
    window.removeEventListener('resize', updateItemsPerView);
    stopAutoSlide();
});
</script>

<template>
    <Head title="Welcome to UKM LAOS">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <div class="min-h-screen bg-white font-sans text-gray-900 dark:bg-slate-950 dark:text-slate-100 transition-colors duration-300">
        <PublicNavbar />

        <main>
            <!-- Hero Section -->
            <section
                class="relative flex flex-col justify-center overflow-hidden bg-gradient-to-b from-[#EEFAF4] via-[#EEFAF4] to-white dark:from-[#070517] dark:via-[#092216] dark:to-slate-950 pt-24 pb-16 lg:pt-28 lg:pb-24 h-screen"
            >
                <!-- Vector Pastel Mint Architecture Arches & Flowing Ribbon Lines -->
                <svg
                    class="pointer-events-none absolute inset-0 w-full h-full select-none opacity-90 dark:opacity-10 transition-opacity duration-300"
                    viewBox="0 0 1440 640"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                    preserveAspectRatio="xMidYMid slice"
                >
                    <!-- Far left arch ring -->
                    <circle
                        cx="40"
                        cy="320"
                        r="190"
                        stroke="#D4F3E2"
                        stroke-width="65"
                        fill="none"
                        opacity="0.9"
                    />

                    <!-- Vertical Pillar 1 -->
                    <rect
                        x="170"
                        y="-40"
                        width="72"
                        height="300"
                        rx="36"
                        fill="#D4F3E2"
                        opacity="0.85"
                    />

                    <!-- Vertical Pillar 2 -->
                    <rect
                        x="345"
                        y="-40"
                        width="72"
                        height="530"
                        rx="36"
                        fill="#D4F3E2"
                        opacity="0.85"
                    />

                    <!-- Top Connecting Arch Bar -->
                    <rect
                        x="170"
                        y="-40"
                        width="540"
                        height="80"
                        rx="40"
                        fill="#D4F3E2"
                        opacity="0.85"
                    />

                    <!-- Vertical Pillar 3 -->
                    <rect
                        x="635"
                        y="-40"
                        width="72"
                        height="450"
                        rx="36"
                        fill="#D4F3E2"
                        opacity="0.85"
                    />

                    <!-- Behind Right Pillar -->
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
                </svg>

                <!-- Soft Bottom Fade to Background for Seamless Transition -->
                <div
                    class="pointer-events-none absolute bottom-0 left-0 right-0 h-28 sm:h-36 bg-gradient-to-t from-white via-white/70 to-transparent dark:from-slate-950 dark:via-slate-950/70 dark:to-transparent z-0 transition-colors duration-300"
                    aria-hidden="true"
                ></div>

                <div
                    class="relative z-10 mx-auto w-full max-w-7xl px-6 sm:px-12 md:px-16 lg:px-20 xl:px-24 py-12 lg:py-16"
                >
                    <div class="items-center lg:grid lg:grid-cols-12 lg:gap-12 xl:gap-16">
                        <div
                            class="sm:text-center md:mx-auto md:max-w-2xl lg:col-span-6 lg:text-left"
                        >
                            <p
                                class="mb-3 text-sm font-semibold tracking-normal text-[#52525B] dark:text-emerald-400 sm:text-base"
                            >
                                Tempatnya Anak TI Berkembang!
                            </p>
                            <h1
                                class="text-4xl font-extrabold tracking-tight text-[#3F3F46] dark:text-white leading-[1.15] sm:text-5xl md:text-6xl lg:text-[54px] xl:text-[58px]"
                            >
                                Selamat
                                <span class="text-[#2DCC70]">Datang,</span><br />
                                <span class="text-[#2DCC70]">Laosars!</span>
                            </h1>
                            <p
                                class="mt-4 text-base text-[#52525B] dark:text-slate-300 leading-relaxed sm:mx-auto sm:mt-5 sm:max-w-xl sm:text-lg md:text-lg lg:mx-0 font-normal"
                            >
                                UKM LAOS adalah Unit Kegiatan Mahasiswa yang
                                berfokus sebagai wadah untuk memajukan
                                kreativitas dalam pengembangan Linux dan Open
                                Source di Fakultas Ilmu Komputer Universitas
                                Jember.
                            </p>
                            <div
                                class="mt-8 sm:mx-auto sm:max-w-lg sm:text-center lg:text-left"
                            >
                                <Link
                                    :href="route('tentang-kami')"
                                    class="inline-flex items-center gap-2 rounded-full bg-[#2DCC70] px-7 py-3 text-sm font-semibold text-white shadow-md shadow-[#2DCC70]/20 transition-all duration-300 hover:bg-[#25B562] active:bg-[#1D9A50]"
                                >
                                    Lihat Selengkapnya
                                    <span aria-hidden="true">&rarr;</span>
                                </Link>
                            </div>
                        </div>

                        <!-- Right Hero Character Collage matching Figma & Hero Assets -->
                        <div
                            class="relative mt-12 sm:mx-auto sm:max-w-lg lg:col-span-6 lg:mx-0 lg:mt-0 lg:flex lg:max-w-none lg:items-center lg:justify-end"
                        >
                            <div
                                class="relative mx-auto w-full max-w-[420px] sm:max-w-[460px] lg:mx-0"
                            >
                                <!-- Floating Code Badge </> (Top-Left of kiri-atas) -->
                                <div
                                    class="absolute -top-3.5 -left-3.5 z-20 select-none sm:-top-4 sm:-left-4"
                                >
                                    <svg
                                        class="h-8 w-8 text-[#2DCC70] sm:h-9 sm:w-9"
                                        viewBox="0 0 36 36"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="3.5"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >
                                        <path d="M11 11L4 18L11 25" />
                                        <path d="M25 11L32 18L25 25" />
                                        <path d="M21 9L15 27" />
                                    </svg>
                                </div>

                                <!-- Floating Upward Trend Arrow (Bottom-Left of kiri-bawah) -->
                                <div
                                    class="absolute -bottom-3.5 -left-3.5 z-20 select-none sm:-bottom-4 sm:-left-4"
                                >
                                    <svg
                                        class="h-8 w-8 text-[#2DCC70] sm:h-9 sm:w-9"
                                        viewBox="0 0 36 36"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="3.5"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >
                                        <polyline points="6 26 14 18 20 23 28 12" />
                                        <polyline points="20 12 28 12 28 20" />
                                    </svg>
                                </div>

                                <!-- Floating Yellow Star (Right side between top and bottom cards) -->
                                <div
                                    class="absolute -right-3.5 top-1/2 z-20 -translate-y-1/2 select-none sm:-right-4"
                                >
                                    <svg
                                        class="h-7 w-7 fill-[#FACC15] text-[#FACC15] drop-shadow-sm sm:h-8 sm:w-8"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            d="M12 2l2.9 6.6 7.1.6-5.3 4.8 1.6 7-6.3-3.7-6.3 3.7 1.6-7L2 9.2l7.1-.6L12 2z"
                                        />
                                    </svg>
                                </div>

                                <!-- 4 Grid 3D Character Images from hero folder -->
                                <div class="grid grid-cols-2 gap-3.5 sm:gap-4">
                                    <!-- 1. Kiri Atas -->
                                    <div
                                        class="aspect-square overflow-hidden rounded-tl-[44px] rounded-tr-[20px] rounded-bl-[20px] rounded-br-[20px] border-4 border-white dark:border-slate-800 bg-[#2CC66D] shadow-md transition-transform duration-300 hover:shadow-lg sm:rounded-tl-[52px] sm:rounded-tr-[24px] sm:rounded-bl-[24px] sm:rounded-br-[24px]"
                                    >
                                        <img
                                            src="/assets/hero/kiri-atas.png"
                                            alt="Karakter 3D UKM LAOS Kiri Atas"
                                            class="h-full w-full object-cover transition-transform duration-500 hover:scale-105"
                                            loading="eager"
                                        />
                                    </div>

                                    <!-- 2. Kanan Atas -->
                                    <div
                                        class="aspect-square overflow-hidden rounded-[20px] border-4 border-white dark:border-slate-800 bg-[#2CC66D] shadow-md transition-transform duration-300 hover:shadow-lg sm:rounded-[24px]"
                                    >
                                        <img
                                            src="/assets/hero/kanan-atas.png"
                                            alt="Karakter 3D UKM LAOS Kanan Atas"
                                            class="h-full w-full object-cover transition-transform duration-500 hover:scale-105"
                                            loading="eager"
                                        />
                                    </div>

                                    <!-- 3. Kiri Bawah -->
                                    <div
                                        class="aspect-square overflow-hidden rounded-tl-[20px] rounded-tr-[20px] rounded-bl-[44px] rounded-br-[20px] border-4 border-white dark:border-slate-800 bg-[#2CC66D] shadow-md transition-transform duration-300 hover:shadow-lg sm:rounded-tl-[24px] sm:rounded-tr-[24px] sm:rounded-bl-[52px] sm:rounded-br-[24px]"
                                    >
                                        <img
                                            src="/assets/hero/kiri-bawah.png"
                                            alt="Karakter 3D UKM LAOS Kiri Bawah"
                                            class="h-full w-full object-cover transition-transform duration-500 hover:scale-105"
                                            loading="eager"
                                        />
                                    </div>

                                    <!-- 4. Kanan Bawah -->
                                    <div
                                        class="aspect-square overflow-hidden rounded-tl-[20px] rounded-tr-[20px] rounded-bl-[20px] rounded-br-[44px] border-4 border-white dark:border-slate-800 bg-[#2CC66D] shadow-md transition-transform duration-300 hover:shadow-lg sm:rounded-tl-[24px] sm:rounded-tr-[24px] sm:rounded-bl-[24px] sm:rounded-br-[52px]"
                                    >
                                        <img
                                            src="/assets/hero/kanan-bawah.png"
                                            alt="Karakter 3D UKM LAOS Kanan Bawah"
                                            class="h-full w-full object-cover transition-transform duration-500 hover:scale-105"
                                            loading="eager"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Manfaat Section -->
            <section class="relative overflow-hidden bg-white dark:bg-slate-950 pt-20 pb-28 lg:pt-28 lg:pb-36 transition-colors duration-300">
                <!-- Decorative subtle arches matching brand identity -->
                <div
                    class="pointer-events-none absolute inset-0 z-0 overflow-hidden"
                    aria-hidden="true"
                >
                    <div
                        class="absolute -bottom-16 -left-16 h-72 w-72 rounded-full bg-[#D4F3E2]/40 dark:bg-emerald-950/20 blur-2xl"
                    ></div>
                    <div
                        class="absolute -top-16 -right-16 h-72 w-72 rounded-full bg-[#D4F3E2]/40 dark:bg-emerald-950/20 blur-2xl"
                    ></div>
                </div>

                <!-- Slanted transition at the bottom -->
                <div
                    class="pointer-events-none absolute bottom-0 left-0 right-0 z-0 w-full overflow-hidden leading-none select-none"
                    aria-hidden="true"
                >
                    <svg
                        class="block h-20 w-full sm:h-28 md:h-36 lg:h-44 text-[#E3F7F2] dark:text-[#092216] transition-colors duration-300"
                        viewBox="0 0 1285 579"
                        fill="none"
                        preserveAspectRatio="none"
                    >
                        <path d="M0 466.696L1285 0V579H0V466.696Z" fill="currentColor" />
                    </svg>
                </div>

                <div
                    class="relative z-10 mx-auto max-w-7xl px-6 sm:px-12 md:px-16 lg:px-20 xl:px-24"
                >
                    <div class="mx-auto max-w-3xl text-center">
                        <h2
                            class="text-3xl font-extrabold tracking-tight text-[#1E1E1E] dark:text-white sm:text-4xl"
                        >
                            Manfaat Laosars
                        </h2>
                        <p
                            class="mt-4 text-base leading-relaxed text-[#636363] dark:text-slate-300 sm:text-lg"
                        >
                            Bergabung dengan UKM LAOS membuka berbagai peluang untuk
                            pengembangan diri dan karir Anda
                        </p>
                    </div>

                    <!-- 3 Cards Grid -->
                    <div
                        class="mt-12 sm:mt-16 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 items-stretch"
                    >
                        <div
                            v-for="(manfaat, idx) in manfaatList"
                            :key="idx"
                            class="group flex h-full flex-col justify-between rounded-xl border border-gray-100 bg-white dark:border-slate-800 dark:bg-slate-900 p-5 shadow-[0px_4px_16px_rgba(0,0,0,0.06)] dark:shadow-none transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0px_14px_30px_rgba(45,204,112,0.14)] sm:p-6"
                        >
                            <!-- Image Banner with subtle hover zoom -->
                            <div
                                class="w-full aspect-[354/214] overflow-hidden rounded-xl flex items-center justify-center bg-transparent"
                            >
                                <img
                                    :src="manfaat.image"
                                    :alt="manfaat.title"
                                    class="h-full w-full object-contain transition-transform duration-500 group-hover:scale-[1.03]"
                                    loading="lazy"
                                />
                            </div>

                            <!-- Description -->
                            <div class="mt-4 flex flex-1 items-center justify-center sm:mt-6">
                                <p
                                    class="text-center text-sm sm:text-[14px] font-normal leading-relaxed text-[#636363] dark:text-slate-300"
                                >
                                    {{ manfaat.deskripsi }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Program Kerja Section (Slider Carousel Otomatis) -->
            <section
                id="program-kerja"
                class="relative overflow-hidden bg-gradient-to-b from-[#E3F7F2] via-white to-[#E3F7F2] dark:from-[#092216] dark:via-slate-950 dark:to-[#070517] py-20 lg:py-28 transition-colors duration-300"
            >
                <div class="relative z-10 mx-auto max-w-7xl px-6 sm:px-12 md:px-16 lg:px-20 xl:px-24">
                    <!-- Section Header -->
                    <div class="mb-12 sm:mb-14 text-center">
                        <h2 class="text-3xl font-extrabold tracking-tight text-[#1E1E1E] dark:text-white sm:text-4xl">
                            Program Kerja
                        </h2>
                        <p class="mx-auto mt-3 max-w-2xl text-base sm:text-lg leading-relaxed text-[#636363] dark:text-slate-300">
                            Rangkaian program untuk mengembangkan potensi, menantang kreativitas, dan membangun skill nyata
                        </p>
                    </div>

                    <!-- Carousel Container -->
                    <div
                        class="overflow-hidden py-4 -my-4"
                        @mouseenter="isPaused = true"
                        @mouseleave="isPaused = false"
                    >
                        <div
                            class="flex transition-transform duration-700 ease-in-out"
                            :style="{ transform: `translateX(-${currentPage * 100}%)` }"
                        >
                            <div
                                v-for="pageIdx in totalPages"
                                :key="pageIdx"
                                class="w-full shrink-0 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-7 lg:gap-8 items-stretch"
                            >
                                <div
                                    v-for="program in getProgramsForPage(pageIdx - 1)"
                                    :key="program.id"
                                    class="group relative flex flex-col h-full rounded-lg border border-gray-100 bg-white dark:border-slate-800 dark:bg-slate-900 shadow-[0px_4px_20px_rgba(0,0,0,0.06)] dark:shadow-none transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0px_16px_36px_rgba(45,204,112,0.16)] overflow-hidden"
                                >
                                    <!-- Foto Kotak di Atas -->
                                    <div class="relative w-full aspect-[16/10] sm:aspect-[16/9] overflow-hidden bg-slate-900 shrink-0">
                                        <img
                                            :src="program.foto_url"
                                            :alt="program.judul_program"
                                            class="w-full h-full object-cover transition-transform duration-500"
                                            loading="lazy"
                                        />
                                    </div>

                                    <!-- Konten Tulisan -->
                                    <div class="p-6 sm:p-7 flex flex-col flex-1 justify-between bg-white dark:bg-slate-900">
                                        <div>
                                            <h3 class="text-lg sm:text-xl font-bold text-[#1E1E1E] dark:text-white group-hover:text-[#2DCC70] transition-colors line-clamp-2 mb-2.5 leading-snug">
                                                {{ program.judul_program }}
                                            </h3>
                                            <p class="text-sm sm:text-[14px] font-normal text-[#636363] dark:text-slate-300 leading-relaxed line-clamp-4 min-h-[5.5rem]">
                                                {{ program.deskripsi }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination Indicator Dots -->
                    <div v-if="totalPages > 1" class="mt-10 sm:mt-12 flex justify-center items-center gap-2.5">
                        <button
                            v-for="pageIndex in totalPages"
                            :key="pageIndex"
                            type="button"
                            @click="currentPage = pageIndex - 1"
                            :aria-label="`Pindah ke slide ${pageIndex}`"
                            :class="[
                                currentPage === pageIndex - 1
                                    ? 'w-8 bg-[#2DCC70]'
                                    : 'w-2.5 bg-gray-300 dark:bg-slate-700 hover:bg-gray-400 dark:hover:bg-slate-600',
                                'h-2.5 rounded-full transition-all duration-300 cursor-pointer'
                            ]"
                        />
                    </div>

                    <!-- Tombol Lihat Selengkapnya ke Route Program Kerja -->
                    <div class="mt-10 sm:mt-12 flex justify-center">
                        <Link
                            :href="route('program-kerja.index')"
                            class="inline-flex items-center gap-2.5 rounded-full bg-[#2DCC70] px-8 py-3.5 text-sm font-semibold text-white shadow-md shadow-[#2DCC70]/20 transition-all duration-300 hover:bg-[#25B562] active:bg-[#1D9A50] hover:shadow-lg hover:-translate-y-0.5"
                        >
                            <span>Lihat Selengkapnya</span>
                            <svg
                                class="h-4 w-4 transition-transform group-hover:translate-x-1"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </Link>
                    </div>
                </div>
            </section>

            <!-- Divisi Kami Section -->
            <section
                id="divisi"
                class="relative overflow-hidden bg-[#E3F7F2] dark:bg-[#070517] py-20 lg:py-28 transition-colors duration-300"
            >
                <div class="relative z-10 mx-auto max-w-7xl px-6 sm:px-12 md:px-16 lg:px-20 xl:px-24">
                    <div class="mb-14 sm:mb-16 text-center">
                        <h2 class="text-3xl font-extrabold tracking-tight text-[#1E1E1E] dark:text-white sm:text-4xl">
                            Divisi Kami
                        </h2>
                        <p class="mx-auto mt-4 max-w-2xl text-base sm:text-lg leading-relaxed text-[#636363] dark:text-slate-300">
                            Divisi unggulan yang bersinergi mengembangkan potensi dalam bidang teknologi
                        </p>
                    </div>

                    <!-- Divisi Cards Grid dengan foto dari database -->
                    <div
                        class="mx-auto grid max-w-6xl grid-cols-1 gap-4 md:grid-cols-2 lg:gap-5"
                    >
                        <div
                            v-for="(divisi, idx) in computedDivisis"
                            :key="divisi.id"
                            :class="[
                                'group relative flex flex-col sm:flex-row items-center gap-5 sm:gap-6 rounded-lg border border-white/60 bg-white dark:border-slate-800 dark:bg-slate-900 p-6 sm:p-7 shadow-[0px_4px_16px_rgba(0,0,0,0.06)] dark:shadow-none transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0px_14px_30px_rgba(45,204,112,0.14)]',
                                idx === computedDivisis.length - 1 && computedDivisis.length % 2 === 1
                                    ? 'md:col-span-2 md:mx-auto md:w-full md:max-w-xl'
                                    : ''
                            ]"
                        >
                            <!-- Wadah Logo / Foto Divisi dari Database -->
                            <div
                                class="flex h-20 w-20 sm:h-24 sm:w-24 flex-shrink-0 items-center justify-center rounded-full bg-[#D5FFE7] dark:bg-emerald-950/70 transition-transform duration-300 group-hover:scale-105 overflow-hidden shadow-xs"
                            >
                                <img
                                    v-if="divisi.has_custom_logo"
                                    :src="divisi.logo_url"
                                    :alt="divisi.nama"
                                    class="h-full w-full object-cover"
                                    loading="lazy"
                                />
                                <img
                                    v-else
                                    :src="divisi.logo_url"
                                    :alt="divisi.nama"
                                    class="h-11 w-11 sm:h-12 sm:w-12 object-contain"
                                    loading="lazy"
                                />
                            </div>
                            <div class="min-w-0 flex-1 text-center sm:text-left">
                                <h3
                                    class="mb-2 text-lg font-bold sm:text-xl text-[#1E1E1E] dark:text-white"
                                >
                                    {{ divisi.nama }}
                                </h3>
                                <p
                                    class="text-sm sm:text-[14px] font-normal leading-relaxed text-[#636363] dark:text-slate-300"
                                >
                                    {{ divisi.deskripsi }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <PublicFooter :divisis="divisis" />
    </div>
</template>
