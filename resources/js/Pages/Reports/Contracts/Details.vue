<template>
    <Head :title="$t('reports.contracts.details_contracts')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="description" />
            </q-avatar>
            {{ $t('reports.contracts.details_contracts') }}
        </template>

        <div class="container">
            <q-card class="tw-p-6">
                <q-card-section>
                    <div class="md:tw-grid md:tw-grid-cols-3 md:tw-gap-5">
                        <h5 class="tw-mt-5 tw-mb-3 tw-font-medium"> {{ $t('Cliente') }}: {{ page.props.client.name }}
                        </h5>
                    </div>

                    <q-markup-table class="tw-shadow-none tw-mt-2">
                        <thead>
                            <tr>
                                <th class="text-right">{{ $t('fields.client') }}</th>
                                <th class="text-right">{{ $t('fields.contract_id') }}</th>
                                <th class="text-right">{{ $t('fields.status') }}</th>
                                <th class="text-right">{{ $t('fields.projects') }}</th>
                                <th class="text-right">{{ $t('fields.details') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(contract) in page.props.contracts">

                                <q-td class="text-right tw-mt-3" style="vertical-align: top;">
                                    {{ page.props.client.name }}
                                </q-td>
                                <q-td class="text-right tw-mt-3" style="vertical-align: top;">
                                    {{ contract.id }}
                                </q-td>
                                <q-td class="text-right tw-mt-3" style="vertical-align: top;">
                                    <span class="tw-p-1 tw-rounded tw-text-white" :class="status(contract.status, i18n)[1]">
                                        {{ status(contract.status, i18n)[0] }}
                                    </span>
                                </q-td>
                                <q-td class="text-right tw-mt-3" style="vertical-align: top;">
                                    {{ contract.project.name }}
                                </q-td>
                                <q-td>
                                    <q-markup-table class="tw-shadow-none">
                                        <thead>
                                            <tr>
                                                <th class="text-right">{{ $t('fields.products') }}</th>
                                                <th class="text-right">{{ $t('fields.quantity') }}</th>
                                                <th class="text-right">{{ $t('fields.subtotal') }}</th>
                                            </tr>
                                        </thead>
                        <tbody>
                            <!-- <template v-for="(product, index) in contract.products" :key="product.id"> -->
                                <tr v-for="(product, index) in contract.products" :key="product.id">
                                  
                                    <template v-if="products.args.products.includes(product.id.toString())">
                                        <q-td class="tw-max-w-sm tw-text-ellipsis tw-overflow-hidden text-right"> {{ product.name }} </q-td>
                                        <q-td class="text-right"> {{ product.pivot.quantity }} </q-td>
                                        <q-td class="text-right"> {{ money(product.pivot.subtotal) }} </q-td>
                                    </template>
                                </tr>
                          <!--   </template> -->
                        </tbody>
                    </q-markup-table>
                    </q-td>

                    </tr>
                    </tbody>
                    </q-markup-table>
                </q-card-section>

                <q-card-actions align="right">

                    <AuxBtn to="reports.contracts.general">
                        {{ $t('actions.back') }}
                    </AuxBtn>

                </q-card-actions>
            </q-card>
        </div>

    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { useI18n } from "vue-i18n";
import route from "ziggy-js";
import { money, status } from "../../../Common/helpers";


const i18n = useI18n()
const page = usePage()

const client = page.props.client.name
const contracts = page.props.contracts
const products_filters = page.props.products_filters
const products = page.props.products


</script>

