<script setup lang="ts">
import { computed, onMounted, onUnmounted, watch } from 'vue';

const props = withDefaults(
    defineProps<{
        show?: boolean;
        maxWidth?: 'sm' | 'md' | 'lg' | 'xl' | '2xl' | '3xl' | '4xl' | '5xl';
        closeable?: boolean;
    }>(),
    {
        show: false,
        maxWidth: '2xl',
        closeable: true,
    },
);

const emit = defineEmits<{
    (e: 'close'): void;
}>();

watch(
    () => props.show,
    (val) => {
        if (val) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    },
);

const close = () => {
    if (props.closeable) {
        emit('close');
    }
};

const closeOnEscape = (e: KeyboardEvent) => {
    if (e.key === 'Escape' && props.show) {
        e.preventDefault();
        close();
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => {
    document.removeEventListener('keydown', closeOnEscape);
    document.body.style.overflow = '';
});

const maxWidthClass = computed(() => {
    return {
        sm: 'sm:max-w-sm',
        md: 'sm:max-w-md',
        lg: 'sm:max-w-lg',
        xl: 'sm:max-w-xl',
        '2xl': 'sm:max-w-2xl',
        '3xl': 'sm:max-w-3xl',
        '4xl': 'sm:max-w-4xl',
        '5xl': 'sm:max-w-5xl',
    }[props.maxWidth];
});
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition-opacity ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="show"
                class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4 sm:p-6"
            >
                <!-- Soft backdrop with subtle blur (prevents pitch-black double overlay bug) -->
                <div
                    class="fixed inset-0 cursor-pointer bg-slate-900/40 backdrop-blur-xs transition-opacity dark:bg-black/60"
                    @click="close"
                />

                <!-- Modal Window Container -->
                <Transition
                    enter-active-class="transition-all ease-out duration-200"
                    enter-from-class="opacity-0 translate-y-3 sm:scale-95"
                    enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-active-class="transition-all ease-in duration-150"
                    leave-from-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-to-class="opacity-0 translate-y-3 sm:scale-95"
                >
                    <div
                        v-if="show"
                        class="relative z-10 w-full overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-2xl transition-all sm:rounded-3xl dark:border-slate-800 dark:bg-slate-900"
                        :class="maxWidthClass"
                    >
                        <slot />
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>
