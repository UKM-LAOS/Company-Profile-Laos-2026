import { createInertiaApp } from '@inertiajs/vue3';
import { ZiggyVue } from 'ziggy-js';

import { vReveal } from './directives/reveal';

function getAppName(): string {
    if (typeof window !== 'undefined') {
        const win = window as Window & { __APP_NAME__?: string };
        if (win.__APP_NAME__) {
            return win.__APP_NAME__;
        }

        const appElement = document.getElementById('app');
        if (appElement?.dataset.page) {
            try {
                const page = JSON.parse(appElement.dataset.page);
                if (page.props?.name) {
                    return page.props.name;
                }
            } catch {}
        }
    }

    return import.meta.env.VITE_APP_NAME || 'UKM LAOS';
}

void createInertiaApp({
    title: (title) => {
        const appName = getAppName();
        return title ? `${title} - ${appName}` : appName;
    },
    withApp: (app) => {
        app.use(ZiggyVue);
        app.directive('focus', {
            mounted: (el: HTMLElement, shouldFocus) => {
                if (shouldFocus.value !== false) {
                    el.focus();
                }
            },
        });
        app.directive('reveal', vReveal);
    },
    progress: {
        color: '#4B5563',
    },
});
