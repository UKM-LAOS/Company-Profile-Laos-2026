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
    
    <div class="min-h-screen font-sans text-gray-900 bg-white">
        <PublicNavbar />

        <main>
            <!-- Hero Section -->
            <section class="relative pt-12 pb-20 lg:pt-24 lg:pb-32 overflow-hidden bg-emerald-50/40">
                <!-- Abstract Background Elements -->
                <div class="absolute top-0 right-0 -mr-20 -mt-20 w-[40rem] h-[40rem] bg-emerald-100/50 rounded-full blur-3xl opacity-50 z-0"></div>
                <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-[30rem] h-[30rem] bg-emerald-100/50 rounded-full blur-3xl opacity-50 z-0"></div>
                
                <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="lg:grid lg:grid-cols-12 lg:gap-8 items-center">
                        <div class="sm:text-center md:max-w-2xl md:mx-auto lg:col-span-6 lg:text-left">
                            <p class="text-sm font-semibold text-emerald-600 tracking-wide uppercase mb-3">
                                Tempatnya Anak IT Berkembang!
                            </p>
                            <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl lg:text-5xl xl:text-6xl">
                                <span class="block">Selamat <span class="text-emerald-500">Datang,</span></span>
                                <span class="block text-emerald-500">Laosars!</span>
                            </h1>
                            <p class="mt-4 text-base text-gray-600 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                                UKM LAOS adalah Unit Kegiatan Mahasiswa yang berfokus sebagai wadah untuk memajukan kreativitas dalam pengembangan Linux dan Open Source di Fakultas Ilmu Komputer Universitas Jember.
                            </p>
                            <div class="mt-8 sm:max-w-lg sm:mx-auto sm:text-center lg:text-left">
                                <Link href="#" class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-full shadow-sm text-white bg-emerald-500 hover:bg-emerald-600 transition-colors duration-200">
                                    Lihat Selengkapnya
                                    <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </Link>
                            </div>
                        </div>
                        <div class="mt-12 relative sm:max-w-lg sm:mx-auto lg:mt-0 lg:max-w-none lg:mx-0 lg:col-span-6 lg:flex lg:items-center">
                            <!-- 4 Grid Images Placeholder -->
                            <div class="grid grid-cols-2 gap-4 w-full">
                                <div class="bg-emerald-200 aspect-square rounded-2xl shadow-lg flex items-center justify-center overflow-hidden border-4 border-white">
                                    <span class="text-emerald-600 font-medium">3D Character 1</span>
                                </div>
                                <div class="bg-emerald-200 aspect-square rounded-2xl shadow-lg flex items-center justify-center overflow-hidden border-4 border-white mt-8">
                                    <span class="text-emerald-600 font-medium">3D Character 2</span>
                                </div>
                                <div class="bg-emerald-200 aspect-square rounded-2xl shadow-lg flex items-center justify-center overflow-hidden border-4 border-white -mt-8">
                                    <span class="text-emerald-600 font-medium">3D Character 3</span>
                                </div>
                                <div class="bg-emerald-200 aspect-square rounded-2xl shadow-lg flex items-center justify-center overflow-hidden border-4 border-white">
                                    <span class="text-emerald-600 font-medium">3D Character 4</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Manfaat Section -->
            <section class="py-16 bg-white">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Manfaat Laosars</h2>
                    <p class="text-lg text-gray-600 leading-relaxed">
                        Bergabung dengan UKM LAOS membuka berbagai peluang untuk pengembangan diri dan karir Anda
                    </p>
                </div>
            </section>

            <!-- Program Kerja Section -->
            <section class="py-16 bg-gray-50/50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Program Kerja</h2>
                        <p class="text-gray-600 max-w-2xl mx-auto">
                            Rangkaian program untuk mengembangkan potensi, menantang kreativitas, dan membangun skill nyata
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <template v-if="programs && programs.length > 0">
                            <div v-for="program in programs" :key="program.id" class="bg-white rounded-2xl p-8 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-gray-100 text-center hover:shadow-[0_8px_30px_-4px_rgba(16,185,129,0.15)] transition-all duration-300">
                                <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <span class="text-2xl text-emerald-500 font-bold uppercase">{{ program.judul_program ? program.judul_program.charAt(0) : '' }}</span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-3 break-words">{{ program.judul_program }}</h3>
                            </div>
                        </template>
                        <div v-else class="col-span-full text-center text-gray-500 py-10">
                            Belum ada data program kerja.
                        </div>
                    </div>
                </div>
            </section>

            <!-- Divisi Kami Section -->
            <section id="divisi" class="py-20 bg-emerald-50/30">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Divisi Kami</h2>
                        <p class="text-gray-600 max-w-2xl mx-auto">
                            Empat divisi unggulan yang bersinergi mengembangkan potensi dalam bidang teknologi
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                        <template v-if="divisis && divisis.length > 0">
                            <div v-for="divisi in divisis" :key="divisi.id" class="bg-white rounded-2xl p-8 flex items-start gap-6 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-gray-100 hover:shadow-[0_8px_30px_-4px_rgba(16,185,129,0.15)] transition-all duration-300">
                                <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center flex-shrink-0 overflow-hidden">
                                    <img v-if="divisi.logo" :src="divisi.logo.startsWith('http') ? divisi.logo : '/storage/' + divisi.logo" :alt="divisi.nama" class="w-full h-full object-cover" />
                                    <span v-else class="text-2xl text-emerald-500 font-bold uppercase">{{ divisi.nama ? divisi.nama.charAt(0) : '' }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-xl font-bold text-gray-900 mb-2 break-all md:break-words">{{ divisi.nama }}</h3>
                                    <p class="text-gray-600 text-sm leading-relaxed break-words">
                                        {{ divisi.deskripsi }}
                                    </p>
                                </div>
                            </div>
                        </template>
                        <div v-else class="col-span-full text-center text-gray-500 py-10">
                            Belum ada data divisi.
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <PublicFooter :divisis="divisis" />
    </div>
</template>
