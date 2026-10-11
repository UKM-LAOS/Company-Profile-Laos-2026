<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import PublicNavbar from '@/Components/Navbar/PublicNavbar.vue';
import PublicFooter from '@/Components/Footer/PublicFooter.vue';

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
    laravelVersion?: string;
    phpVersion?: string;
    divisis?: Array<{
        id: number;
        nama: string;
        deskripsi: string;
        logo: string;
    }>;
    programs?: Array<{
        id: number;
        judul_program: string;
    }>;
}>();
</script>

<template>
    <Head title="Welcome to UKM LAOS">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <div class="min-h-screen bg-white font-sans text-gray-900">
        <PublicNavbar />

        <main>
            <!-- Hero Section -->
            <section
                class="relative overflow-hidden bg-emerald-50/40 pt-12 pb-20 lg:pt-24 lg:pb-32"
            >
                <!-- Abstract Background Elements -->
                <div
                    class="absolute top-0 right-0 z-0 -mt-20 -mr-20 h-[40rem] w-[40rem] rounded-full bg-emerald-100/50 opacity-50 blur-3xl"
                ></div>
                <div
                    class="absolute bottom-0 left-0 z-0 -mb-20 -ml-20 h-[30rem] w-[30rem] rounded-full bg-emerald-100/50 opacity-50 blur-3xl"
                ></div>

                <div
                    class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8"
                >
                    <div class="items-center lg:grid lg:grid-cols-12 lg:gap-8">
                        <div
                            class="sm:text-center md:mx-auto md:max-w-2xl lg:col-span-6 lg:text-left"
                        >
                            <p
                                class="mb-3 text-sm font-semibold tracking-wide text-emerald-600 uppercase"
                            >
                                Tempatnya Anak IT Berkembang!
                            </p>
                            <h1
                                class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl md:text-6xl lg:text-5xl xl:text-6xl"
                            >
                                <span class="block"
                                    >Selamat
                                    <span class="text-emerald-500"
                                        >Datang,</span
                                    ></span
                                >
                                <span class="block text-emerald-500"
                                    >Laosars!</span
                                >
                            </h1>
                            <p
                                class="mt-4 text-base text-gray-600 sm:mx-auto sm:mt-5 sm:max-w-xl sm:text-lg md:mt-5 md:text-xl lg:mx-0"
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
                                    href="#"
                                    class="inline-flex items-center rounded-full border border-transparent bg-emerald-500 px-8 py-3 text-base font-medium text-white shadow-sm transition-colors duration-200 hover:bg-emerald-600"
                                >
                                    Lihat Selengkapnya
                                    <svg
                                        class="-mr-1 ml-2 h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3"
                                        ></path>
                                    </svg>
                                </Link>
                            </div>
                        </div>
                        <div
                            class="relative mt-12 sm:mx-auto sm:max-w-lg lg:col-span-6 lg:mx-0 lg:mt-0 lg:flex lg:max-w-none lg:items-center"
                        >
                            <!-- 4 Grid Images Placeholder -->
                            <div class="grid w-full grid-cols-2 gap-4">
                                <div
                                    class="flex aspect-square items-center justify-center overflow-hidden rounded-2xl border-4 border-white bg-emerald-200 shadow-lg"
                                >
                                    <span class="font-medium text-emerald-600"
                                        >3D Character 1</span
                                    >
                                </div>
                                <div
                                    class="mt-8 flex aspect-square items-center justify-center overflow-hidden rounded-2xl border-4 border-white bg-emerald-200 shadow-lg"
                                >
                                    <span class="font-medium text-emerald-600"
                                        >3D Character 2</span
                                    >
                                </div>
                                <div
                                    class="-mt-8 flex aspect-square items-center justify-center overflow-hidden rounded-2xl border-4 border-white bg-emerald-200 shadow-lg"
                                >
                                    <span class="font-medium text-emerald-600"
                                        >3D Character 3</span
                                    >
                                </div>
                                <div
                                    class="flex aspect-square items-center justify-center overflow-hidden rounded-2xl border-4 border-white bg-emerald-200 shadow-lg"
                                >
                                    <span class="font-medium text-emerald-600"
                                        >3D Character 4</span
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Manfaat Section -->
            <section class="bg-white py-16">
                <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
                    <h2 class="mb-6 text-3xl font-bold text-gray-900">
                        Manfaat Laosars
                    </h2>
                    <p class="text-lg leading-relaxed text-gray-600">
                        Bergabung dengan UKM LAOS membuka berbagai peluang untuk
                        pengembangan diri dan karir Anda
                    </p>
                </div>
            </section>

            <!-- Program Kerja Section -->
            <section class="bg-gray-50/50 py-16">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="mb-16 text-center">
                        <h2 class="mb-4 text-3xl font-bold text-gray-900">
                            Program Kerja
                        </h2>
                        <p class="mx-auto max-w-2xl text-gray-600">
                            Rangkaian program untuk mengembangkan potensi,
                            menantang kreativitas, dan membangun skill nyata
                        </p>
                    </div>

                    <div
                        class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3"
                    >
                        <template v-if="programs && programs.length > 0">
                            <div
                                v-for="program in programs"
                                :key="program.id"
                                class="rounded-2xl border border-gray-100 bg-white p-8 text-center shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] transition-all duration-300 hover:shadow-[0_8px_30px_-4px_rgba(16,185,129,0.15)]"
                            >
                                <div
                                    class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100"
                                >
                                    <span
                                        class="text-2xl font-bold text-emerald-500 uppercase"
                                        >{{
                                            program.judul_program
                                                ? program.judul_program.charAt(
                                                      0,
                                                  )
                                                : ''
                                        }}</span
                                    >
                                </div>
                                <h3
                                    class="mb-3 text-xl font-bold break-words text-gray-900"
                                >
                                    {{ program.judul_program }}
                                </h3>
                            </div>
                        </template>
                        <div
                            v-else
                            class="col-span-full py-10 text-center text-gray-500"
                        >
                            Belum ada data program kerja.
                        </div>
                    </div>
                </div>
            </section>

            <!-- Divisi Kami Section -->
            <section id="divisi" class="bg-emerald-50/30 py-20">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="mb-16 text-center">
                        <h2 class="mb-4 text-3xl font-bold text-gray-900">
                            Divisi Kami
                        </h2>
                        <p class="mx-auto max-w-2xl text-gray-600">
                            Empat divisi unggulan yang bersinergi mengembangkan
                            potensi dalam bidang teknologi
                        </p>
                    </div>

                    <div
                        class="mx-auto grid max-w-5xl grid-cols-1 gap-8 md:grid-cols-2"
                    >
                        <template v-if="divisis && divisis.length > 0">
                            <div
                                v-for="divisi in divisis"
                                :key="divisi.id"
                                class="flex items-start gap-6 rounded-2xl border border-gray-100 bg-white p-8 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] transition-all duration-300 hover:shadow-[0_8px_30px_-4px_rgba(16,185,129,0.15)]"
                            >
                                <div
                                    class="flex h-16 w-16 flex-shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-emerald-100"
                                >
                                    <img
                                        v-if="divisi.logo"
                                        :src="
                                            divisi.logo.startsWith('http')
                                                ? divisi.logo
                                                : '/storage/' + divisi.logo
                                        "
                                        :alt="divisi.nama"
                                        class="h-full w-full object-cover"
                                    />
                                    <span
                                        v-else
                                        class="text-2xl font-bold text-emerald-500 uppercase"
                                        >{{
                                            divisi.nama
                                                ? divisi.nama.charAt(0)
                                                : ''
                                        }}</span
                                    >
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3
                                        class="mb-2 text-xl font-bold break-all text-gray-900 md:break-words"
                                    >
                                        {{ divisi.nama }}
                                    </h3>
                                    <p
                                        class="text-sm leading-relaxed break-words text-gray-600"
                                    >
                                        {{ divisi.deskripsi }}
                                    </p>
                                </div>
                            </div>
                        </template>
                        <div
                            v-else
                            class="col-span-full py-10 text-center text-gray-500"
                        >
                            Belum ada data divisi.
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <PublicFooter :divisis="divisis" />
    </div>
</template>
