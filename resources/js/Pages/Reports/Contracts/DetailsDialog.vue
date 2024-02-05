<template>
    <q-dialog ref="dialogRef" @hide="onDialogHide" full-width>

        <q-card class="q-dialog-plugin">
            <q-card-section>

                <h6 class="tw-mt-5 tw-ml-3 tw-mb-3 tw-font-medium"> {{ $t('fields.contracts_client') }}: {{
                    props.newclient[0].name
                }} </h6>

                <div class="tw-mt-5 tw-ml-3 tw-mb-3 tw-font-bold"> {{$t('fields.quantity_by_month')}}: {{ props.newclient[0].total_month_price }} </div>

                <q-markup-table class="tw-shadow-none tw-mt-2 fixed-layout-table tw-full">

                    <thead>
                        <tr>
                            <q-th rowspan="2" class="text-center">{{ $t('fields.contract_id') }}</q-th>
                            <q-th rowspan="2" class="text-center">{{ $t('fields.projects') }}</q-th>
                            <q-th rowspan="2" class="tw-max-w-sm tw-text-ellipsis tw-overflow-hidden text-center">{{
                                $t('fields.projects_quantity_by_month') }}</q-th>
                       
                            <q-th colspan="3" class="text-center">{{ $t('fields.products') }} </q-th>
                        </tr>
                        <tr>

                            <q-th class="tw-max-w-sm tw-text-ellipsis tw-overflow-hidden text-center"
                                style="width:200px;">{{ $t('fields.name') }}</q-th>
                            <q-th class="text-center" style="width: 200px;">{{ $t('reports.products.rented') }}</q-th>
                            <q-th class="text-right" style="width: 200px;word-break: normal;">{{
                                $t('fields.products_quantity_by_month') }}</q-th>

                        </tr>
                    </thead>
                    <tbody>

                        <template v-for="(contract, index) in props.contracts" :key="index">
                            <tr>
                                <q-td class="text-center tw-mt-3" style="vertical-align: top;">
                                    {{ contract.id }}
                                </q-td>

                                <q-td class="text-center tw-mt-3" style="vertical-align: top;word-wrap: break-word;">
                                    {{ contract.project.name }}
                                </q-td>

                                <q-td class="text-center tw-mt-3" style="vertical-align: top;">
                                    {{ money(getContractibleProducts(contract.products, products)) }}
                                </q-td>
                                <q-td colspan="3">
                                    <template v-for="(product, i) in contract.products" :key="product.id">
                                        <template v-if="props.products.includes(product.id)">
                                            <q-markup-table class="fixed-layout-table tw-full"
                                                style="width:600px;table-layout: fixed">
                            <tr>
                                <q-td class="tw-max-w-[200px] tw-text-ellipsis tw-overflow-hidden text-center"
                                    style="word-wrap: break-word;"> {{ product.name }}
                                    <q-tooltip>{{  product.name }}</q-tooltip> 
                                </q-td>

                                <q-td class="text-center" style="width: 200px;"> {{
                                    product.pivot.quantity -
                                    product.pivot.mesu_return }} </q-td>

                                <q-td class="text-right" style="width:200px;border:hidden"> {{
                                    money(product.pivot.subtotal) }}
                                </q-td>
                            </tr>
                            </q-markup-table>

                            </template>
                            </template>
                            </q-td>

                            </tr>
                        </template>
                    </tbody>
                </q-markup-table>

            </q-card-section>

            <q-card-actions align="right">
                <q-btn color="primary" flat :label="$t('actions.close')" @click="onDialogCancel" />
            </q-card-actions>

        </q-card>

    </q-dialog>
</template>

<script setup lang="ts">

import { useDialogPluginComponent } from 'quasar'
import { getContractibleProducts } from '../../../Common/helpers';
import { money } from '../../../Common/helpers';


const props = defineProps<{
    newclient: any,
    products: Array,
    contracts: Array,
    projects: Array,
    /*     count: number, */

}>()

var count = 0;

defineEmits([
    ...useDialogPluginComponent.emits
])

const { dialogRef, onDialogHide, onDialogOK, onDialogCancel } = useDialogPluginComponent()

</script>