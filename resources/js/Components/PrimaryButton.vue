<template>
    <button
        class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-full bg-emerald-600 px-5 py-2.5 text-sm font-medium tracking-normal text-white shadow-xs transition-all duration-150 select-none hover:bg-emerald-700 hover:shadow-emerald-600/20 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:outline-none active:scale-[0.98] active:bg-emerald-800 disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-60 dark:focus:ring-offset-slate-900"
    >
        <slot />
    </button>
</template>
