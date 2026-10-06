<script setup lang="ts">
import AppIcon from '@/Components/AppIcon.vue';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

type IconName = InstanceType<typeof AppIcon>['$props']['name'];

const props = withDefaults(
    defineProps<{
        variant?: 'primary' | 'secondary' | 'danger' | 'outline' | 'ghost';
        size?: 'sm' | 'md' | 'lg' | 'icon';
        rounded?: 'full' | 'xl' | 'lg';
        type?: 'button' | 'submit' | 'reset';
        as?: 'button' | 'a' | 'Link';
        href?: string;
        icon?: IconName;
        iconPosition?: 'left' | 'right';
        loading?: boolean;
        disabled?: boolean;
    }>(),
    {
        variant: 'primary',
        size: 'md',
        rounded: 'full',
        type: 'button',
        as: 'button',
        iconPosition: 'left',
        loading: false,
        disabled: false,
    },
);

const baseClasses =
    'inline-flex items-center justify-center font-medium tracking-normal transition-all duration-150 select-none focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-slate-900 cursor-pointer disabled:cursor-not-allowed disabled:opacity-60 disabled:pointer-events-none active:scale-[0.98]';

const roundedClasses = computed(() => {
    switch (props.rounded) {
        case 'xl':
            return 'rounded-xl';
        case 'lg':
            return 'rounded-lg';
        case 'full':
        default:
            return 'rounded-full';
    }
});

const sizeClasses = computed(() => {
    switch (props.size) {
        case 'sm':
            return 'px-3.5 py-1.5 text-xs gap-1.5';
        case 'lg':
            return 'px-6 py-3 text-base gap-2.5';
        case 'icon':
            return 'p-2.5 text-sm';
        case 'md':
        default:
            return 'px-4.5 py-2.5 text-sm gap-2';
    }
});

const variantClasses = computed(() => {
    switch (props.variant) {
        case 'secondary':
            return 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 shadow-xs hover:bg-slate-50 dark:hover:bg-slate-700/70 hover:text-slate-900 dark:hover:text-white focus:ring-slate-400';
        case 'danger':
            return 'bg-rose-600 text-white shadow-xs hover:bg-rose-700 active:bg-rose-800 focus:ring-rose-500 hover:shadow-rose-600/20';
        case 'outline':
            return 'bg-transparent border border-emerald-600 dark:border-emerald-500 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 focus:ring-emerald-500';
        case 'ghost':
            return 'bg-transparent text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white focus:ring-slate-400';
        case 'primary':
        default:
            return 'bg-emerald-600 text-white shadow-xs hover:bg-emerald-700 active:bg-emerald-800 focus:ring-emerald-500 hover:shadow-emerald-600/20';
    }
});

const iconSizeClass = computed(() => {
    switch (props.size) {
        case 'sm':
            return 'h-3.5 w-3.5';
        case 'lg':
            return 'h-5 w-5';
        case 'icon':
        case 'md':
        default:
            return 'h-4 w-4';
    }
});
</script>

<template>
    <Link
        v-if="as === 'Link' && href"
        :href="href"
        :class="[baseClasses, roundedClasses, sizeClasses, variantClasses]"
        :aria-disabled="disabled || loading"
    >
        <AppIcon v-if="loading" name="spinner" :class-name="iconSizeClass" />
        <AppIcon
            v-else-if="icon && iconPosition === 'left'"
            :name="icon"
            :class-name="iconSizeClass"
        />
        <slot />
        <AppIcon
            v-if="!loading && icon && iconPosition === 'right'"
            :name="icon"
            :class-name="iconSizeClass"
        />
    </Link>

    <a
        v-else-if="as === 'a' && href"
        :href="href"
        :class="[baseClasses, roundedClasses, sizeClasses, variantClasses]"
        :aria-disabled="disabled || loading"
    >
        <AppIcon v-if="loading" name="spinner" :class-name="iconSizeClass" />
        <AppIcon
            v-else-if="icon && iconPosition === 'left'"
            :name="icon"
            :class-name="iconSizeClass"
        />
        <slot />
        <AppIcon
            v-if="!loading && icon && iconPosition === 'right'"
            :name="icon"
            :class-name="iconSizeClass"
        />
    </a>

    <button
        v-else
        :type="type"
        :disabled="disabled || loading"
        :class="[baseClasses, roundedClasses, sizeClasses, variantClasses]"
    >
        <AppIcon v-if="loading" name="spinner" :class-name="iconSizeClass" />
        <AppIcon
            v-else-if="icon && iconPosition === 'left'"
            :name="icon"
            :class-name="iconSizeClass"
        />
        <slot />
        <AppIcon
            v-if="!loading && icon && iconPosition === 'right'"
            :name="icon"
            :class-name="iconSizeClass"
        />
    </button>
</template>
