<template>
    <td class="tw-flex tw-justify-end tw-gap-3 tw-pr-4 tw-items-center">
        <q-btn flat>
            <q-icon name="more_vert"/>
            <q-menu style="min-width: 150px" anchor="bottom left" auto-close>
                <q-list>
                    <q-item v-for="action in actions" :key="action.route" clickable @click="executeAction(action)">
                        <q-item-section v-if="action.icon" avatar :class="{
                            'text-negative': action.method === 'delete'
                        }">
                            <q-icon :name="action.icon" />
                        </q-item-section>
                        <q-item-section :class="{
                            'text-negative': action.method === 'delete'
                        }">
                            {{ action.label }}
                        </q-item-section>
                    </q-item>
                </q-list>
            </q-menu>
        </q-btn>
    </td>
</template>

<script setup lang="ts">

import {router} from '@inertiajs/vue3'
import {useQuasar} from "quasar";
import {useI18n} from "vue-i18n";
import route from "ziggy-js";
import {RowAction} from "../Models/RowAction";

const props = defineProps<{
    actions: RowAction[]
}>()

//Services
const $q = useQuasar()
const i18n = useI18n()

/**
 *
 * @param {RowAction} action
 */
function executeAction(action: RowAction) {

    if (action.callback) {

        if (action.callback()) {

            action.onSuccess(action)
        } else {

            action.onError()
        }

    } else {

        let url = null

        const options = {
            onSuccess: params => {

                if (action.onSuccess) {
                    action.onSuccess(params.props)
                }
            },
            onError: () => {

                if (action.onError) {
                    action.onError()
                }
            }
        }

        switch (action.method.toLowerCase()) {
            case 'get':
                url = route(action.route, action.args)
                router.get(url, undefined, options)
                break
            case 'post':
                url = route(action.route)
                router.post(url, action.args, options)
                break
            case 'put':
                url = route(action.route, action.args)
                router.put(url, undefined, options)
                break
            case 'delete':
                destroy(action, options)
                break
        }
    }
}

/**
 * Show destroy confirmation dialog.
 */
function destroy(action: RowAction, options) {

    $q.dialog({
        title: i18n.t('messages.delete_confirmation'),
        message: i18n.t('messages.delete_confirmation_msg'),
        cancel: true,
        persistent: true
    }).onOk(() => {

        const url = route(action.route, action.args)
        router.delete(url, options)
    })
}
</script>
