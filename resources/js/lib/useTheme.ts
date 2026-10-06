import { onMounted, ref } from 'vue';

export type ThemeMode = 'light' | 'dark' | 'system';

const currentTheme = ref<ThemeMode>('system');
const isDark = ref(false);

function applyTheme(mode: ThemeMode) {
    currentTheme.value = mode;
    localStorage.setItem('theme', mode);

    let shouldBeDark = false;
    if (mode === 'dark') {
        shouldBeDark = true;
    } else if (mode === 'light') {
        shouldBeDark = false;
    } else {
        shouldBeDark = window.matchMedia(
            '(prefers-color-scheme: dark)',
        ).matches;
    }

    isDark.value = shouldBeDark;
    if (shouldBeDark) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
}

export function useTheme() {
    onMounted(() => {
        const saved = (localStorage.getItem('theme') as ThemeMode) || 'system';
        applyTheme(saved);

        const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        mediaQuery.addEventListener('change', () => {
            if (currentTheme.value === 'system') {
                applyTheme('system');
            }
        });
    });

    function setTheme(mode: ThemeMode) {
        applyTheme(mode);
    }

    function toggleTheme() {
        if (isDark.value) {
            setTheme('light');
        } else {
            setTheme('dark');
        }
    }

    return {
        theme: currentTheme,
        isDark,
        setTheme,
        toggleTheme,
    };
}
