import { createI18n } from 'vue-i18n';
import es from '../Lang/es.json';
import quasarEsLang from 'quasar/lang/es';
import {QuasarLanguage} from "quasar";

const i18n = createI18n({
    locale: getLocale(), // set locale
    fallbackLocale: 'es', // set fallback locale
    messages: {
        es: es
    },
    legacy: false
})

export default i18n

/**
 * Get quasar lang.
 *
 * @return {QuasarLanguage}
 */
export function getQuasarLang(): QuasarLanguage {

    switch (i18n.global.locale) {
        case 'es':
            return quasarEsLang
        default:
            return quasarEsLang
    }
}

/**
 * Get application locale.
 *
 * @returns {string}
 */
export function getLocale(): string {
    return document.documentElement.lang
}
