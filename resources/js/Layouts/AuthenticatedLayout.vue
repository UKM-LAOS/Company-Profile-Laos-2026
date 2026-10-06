<script setup lang="ts">
import AppNavbar from '@/Components/Navbar/AppNavbar.vue';
import AppSidebar from '@/Components/Sidebar/AppSidebar.vue';
import { router } from '@inertiajs/vue3';
import { onUnmounted, ref } from 'vue';

const sidebarOpen = ref(false);

function toggleSidebar() {
    sidebarOpen.value = !sidebarOpen.value;
}

function closeSidebar() {
    sidebarOpen.value = false;
}

// Automatically close drawer on Inertia navigation on mobile / tablet
const unregisterRouterHook = router.on('navigate', () => {
    sidebarOpen.value = false;
});

onUnmounted(() => {
    unregisterRouterHook();
});
</script>

<template>
    <div
        class="flex min-h-screen bg-[#FAFAF9] font-sans text-slate-800 antialiased dark:bg-slate-950 dark:text-slate-100"
    >
        <!-- Desktop Sidebar (Fixed Left for lg >= 1024px) -->
        <div
            class="hidden lg:fixed lg:inset-y-0 lg:z-40 lg:flex lg:w-64 lg:flex-col"
        >
            <AppSidebar />
        </div>

        <!-- Mobile & Tablet Drawer Sidebar (Slide-over + Backdrop for < 1024px) -->
        <Teleport to="body">
            <Transition
                enter-active-class="transition-opacity ease-out duration-200"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition-opacity ease-in duration-150"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div v-if="sidebarOpen" class="relative z-50 lg:hidden">
                    <!-- Backdrop -->
                    <div
                        class="fixed inset-0 cursor-pointer bg-slate-900/50 backdrop-blur-xs transition-opacity dark:bg-black/70"
                        @click="closeSidebar"
                    />

                    <!-- Slide-in Drawer Panel -->
                    <Transition
                        enter-active-class="transition ease-out duration-300"
                        enter-from-class="-translate-x-full"
                        enter-to-class="translate-x-0"
                        leave-active-class="transition ease-in duration-200"
                        leave-from-class="translate-x-0"
                        leave-to-class="-translate-x-full"
                    >
                        <div
                            class="fixed inset-y-0 left-0 flex w-72 max-w-[85vw] flex-col shadow-2xl"
                        >
                            <AppSidebar />
                        </div>
                    </Transition>
                </div>
            </Transition>
        </Teleport>

        <!-- Right Main Viewport Area -->
        <div class="flex min-w-0 flex-1 flex-col lg:pl-64">
            <!-- Mobile & Tablet Top Navbar -->
            <AppNavbar
                :sidebar-open="sidebarOpen"
                @toggle-sidebar="toggleSidebar"
            />

            <!-- Optional Header Slot with Mobile/Tablet/Desktop Spacing -->
            <header
                v-if="$slots.header"
                class="px-4 pt-6 pb-2 sm:px-6 sm:pt-8 sm:pb-3 lg:px-10"
            >
                <slot name="header" />
            </header>

            <!-- Main Content Slot with Responsive Padding -->
            <main
                :class="[
                    'min-w-0 flex-1 px-4 pb-8 sm:px-6 sm:pb-10 lg:px-10 lg:pb-12',
                    $slots.header ? 'pt-2' : 'pt-6 sm:pt-8 lg:pt-10',
                ]"
            >
                <slot />
            </main>
        </div>
    </div>
</template>
