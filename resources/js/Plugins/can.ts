import {App} from "vue";
import {PageProps} from "@inertiajs/core";
import {can} from "../Common/helpers";
import {usePage} from "@inertiajs/vue3";

/**
 * @author Abel David.
 */
export default {
    install(app: App, ...options) {
        app.config.globalProperties.$can = (permission: string[] | string | ((props: PageProps) => boolean) | null) => {

            const page = usePage()

            return can(permission, page.props)
        }
    }
}
