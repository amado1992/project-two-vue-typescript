<template>
    <q-dialog ref="dialogRef" @hide="onDialogHide" full-width>
        <q-card class="q-dialog-plugin">

            <q-card-section>

                <div class="text-h6" v-html="$t('products.active_contracts_title', {product: product})"></div>

                <q-markup-table class="tw-shadow-none">
                    <thead>
                    <tr>
                        <th class="text-right">{{ $t('fields.contract') }}</th>
                        <th class="text-right">{{ $t('fields.project') }}</th>
                        <th class="text-right">{{ $t('fields.client') }}</th>
                        <th class="text-right">{{ $t('fields.price') }}</th>
                        <th class="text-right">{{ $t('fields.quantity') }}</th>
                        <th class="text-right">{{ $t('fields.discount_without_symbol') }}</th>
                        <th class="text-right">{{ $t('fields.subtotal') }}</th>
                        <th class="text-right">{{ $t('fields.tax') }}</th>
                        <th class="text-right">{{ $t('fields.total') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="contractProduct in contractProducts">
                        <td class="text-right">
                            <a class="text-blue" href="#" @click.prevent="goTo(route('contracts.show', {contract: contractProduct.contract.id}))">
                                {{ contractProduct.contract.id }}
                            </a>
                        </td>
                        <td class="text-right">{{ contractProduct.contract.project.name }}</td>
                        <td class="text-right">{{ contractProduct.contract.project.client.name }}</td>
                        <td class="text-right">{{ money(contractProduct.price) }}</td>
                        <td class="text-right">{{ contractProduct.quantity }}</td>
                        <td class="text-right">{{ money(discountQuantity(contractProduct) ) }}</td>
                        <td class="text-right">{{ money(subtotal(contractProduct)) }}</td>
                        <td class="text-right">{{ money(discountTax(contractProduct)) }}</td>
                        <td class="text-right">{{ money(total(contractProduct)) }}</td>
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
import {getUpdatedAt, money} from "../../Common/helpers";
import route from 'ziggy-js';
import {router} from "@inertiajs/vue3";

const props = defineProps<{
    product: string
    contractProducts: [],
    product_tax: 0
}>()

defineEmits([
    ...useDialogPluginComponent.emits
])

const { dialogRef, onDialogHide, onDialogOK } = useDialogPluginComponent()
console.log(props.contractProducts)
function goTo(to) {

    router.get(to)
    onDialogOK()
}

function discountQuantity(contract){
    return contract.quantity*((contract.percent_discount*contract.price)/100)
}
function discountTax(contract){
    return ((contract.price*props.product_tax)/100)*contract.quantity
}

function subtotal(contract){
    return contract.quantity*contract.price - discountQuantity(contract)
}

function total(contract){
    return discountTax(contract) + subtotal(contract)
}
</script>
