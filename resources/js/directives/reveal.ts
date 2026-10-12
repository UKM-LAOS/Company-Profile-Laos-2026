import type { Directive } from 'vue';

type RevealValue = number | { delay?: number } | undefined;

export const vReveal: Directive<HTMLElement, RevealValue> = {
    mounted(el, binding) {
        if (typeof window === 'undefined') return;

        // Hormati preferensi aksesibilitas pengguna
        if (
            window.matchMedia &&
            window.matchMedia('(prefers-reduced-motion: reduce)').matches
        ) {
            el.classList.add('is-revealed');
            return;
        }

        el.classList.add('reveal-base');

        if (binding.modifiers.left) {
            el.classList.add('reveal-left');
        } else if (binding.modifiers.right) {
            el.classList.add('reveal-right');
        } else if (binding.modifiers.scale) {
            el.classList.add('reveal-scale');
        }

        const delay =
            typeof binding.value === 'number'
                ? binding.value
                : (binding.value?.delay ?? 0);

        if (!('IntersectionObserver' in window)) {
            el.classList.add('is-revealed');
            return;
        }

        let isTriggered = false;

        const triggerReveal = () => {
            if (isTriggered) return;
            isTriggered = true;

            if (delay > 0) {
                el.style.animationDelay = `${delay}ms`;
            }

            // Delay 60ms agar frame awal ter-commit di browser sebelum CSS animation dimulai
            setTimeout(() => {
                el.classList.add('is-revealed');
            }, 60);
        };

        const observer = new IntersectionObserver(
            (entries) => {
                for (const entry of entries) {
                    if (entry.isIntersecting) {
                        triggerReveal();
                        observer.unobserve(el);
                    }
                }
            },
            {
                threshold: 0.05,
                rootMargin: '0px 0px -20px 0px',
            },
        );

        observer.observe(el);
        (el as HTMLElement & { _revealObserver?: IntersectionObserver })._revealObserver = observer;
    },
    unmounted(el) {
        const target = el as HTMLElement & { _revealObserver?: IntersectionObserver };
        if (target._revealObserver) {
            target._revealObserver.disconnect();
            delete target._revealObserver;
        }
    },
};
