<template>
    <button
        class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-full bg-rose-600 px-5 py-2.5 text-sm font-medium tracking-normal text-white shadow-xs transition-all duration-150 select-none hover:bg-rose-700 hover:shadow-rose-600/20 focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 focus:outline-none active:scale-[0.98] active:bg-rose-800 disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-60 dark:focus:ring-offset-slate-900"
    >
        <slot />
    </button>
</template>
