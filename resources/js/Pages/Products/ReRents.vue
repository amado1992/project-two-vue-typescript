<template>
    <q-dialog ref="dialogRef" @hide="onDialogHide" full-width>
        <q-card class="q-dialog-plugin">
            <q-card-section>
                <div class="text-h5">{{ $t('products.re_rents_history_2', {product: name}) }}</div>
                <q-markup-table class="tw-shadow-none">
                    <thead>
                    <tr>
                        <th class="text-right">{{ $t('re_rents.singular_title') }}</th>
                        <th class="text-right">{{ $t('fields.provider') }}</th>
                        <th class="text-right">{{ $t('fields.price') }}</th>
                        <th class="text-right">{{ $t('fields.quantity') }}</th>
                        <th class="text-right">{{ $t('fields.subtotal') }}</th>
                        <th class="text-right">{{ $t('fields.discount_without_symbol') }}</th>
                        <th class="text-right">{{ $t('fields.tax') }}</th>
                        <th class="text-right">{{ $t('fields.total') }}</th>
                        <th class="text-right">{{ $t('fields.status') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="productReRent in productReRents">
                        <td class="text-right">
                            <a class="text-blue" href="#" @click.prevent="goTo(route('re-rents.show', {re_rent: productReRent.re_rent.id}))">
                                {{ productReRent.re_rent.id }}
                            </a>
                        </td>
                        <td class="text-right">
                            <ProductName :name="productReRent.re_rent.provider.name" />
                        </td>
                        <td class="text-right">{{ money(productReRent.price) }}</td>
                        <td class="text-right">{{ productReRent.quantity }}</td>
                        <td class="text-right">{{ money(productReRent.subtotal) }}</td>
                        <td class="text-right">{{ money(productReRent.discount) }}</td>
                        <td class="text-right">{{ money(productReRent.tax) }}</td>
                        <td class="text-right">{{ money(productReRent.total) }}</td>
                        <td class="text-right">
                            <span class="tw-p-1 tw-rounded tw-text-white" :class="status(productReRent.re_rent.status, i18n)[1]">
                                {{ status(productReRent.re_rent.status, i18n)[0] }}
                            </span>
                        </td>
                    </tr>
                    </tbody>
                </q-markup-table>
            </q-card-section>

            <q-card-actions align="right">
                <PrimaryButton @click="onDialogOK">{{ $t('actions.close') }}</PrimaryButton>
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>

<script setup lang="ts">

import {useDialogPluginComponent} from "quasar";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import {money, status} from "../../Common/helpers";
import route from 'ziggy-js';
import {router} from "@inertiajs/vue3";
import {useI18n} from "vue-i18n";
import ProductName from "../../Components/ProductName.vue";

defineProps<{
    name: string,
    productReRents: []
}>()

defineEmits([
    ...useDialogPluginComponent.emits
])

const i18n = useI18n()

const { dialogRef, onDialogHide, onDialogOK } = useDialogPluginComponent()

function goTo(to) {

    router.get(to)
    onDialogOK()
}
</script>
