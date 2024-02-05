import './bootstrap';
import '../scss/app.scss';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { Quasar, Dialog, Notify } from 'quasar';
import i18n, {getQuasarLang} from "@/Plugins/i18n";
import can from './Plugins/can';

/**
 * App name.
 *
 * @type {string}
 */
const appName = getAppName();

createInertiaApp( {
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(i18n)
            .use(ZiggyVue, Ziggy)
            .use(can)
            .use(Quasar, {
                lang: getQuasarLang(),
                plugins: {
                    Dialog,
                    Notify
                }
            })
            .mount(el);
    },
    progress: false
});

window.addEventListener('popstate', async (e) => {
    e.stopImmediatePropagation();
    try{
        // to check if application session is still valid
        const res = await fetch('/dashboard')
        if(res.ok){
            window.location.replace(e.state.url);
        }
    }catch(e){
        window.location.replace("/login");
    }
},)

/**
 * Get app name.
 *
 * @return {string}
 */
export function getAppName() {
    return window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';
}
