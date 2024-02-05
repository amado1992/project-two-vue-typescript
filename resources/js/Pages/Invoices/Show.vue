<template>
    <Head :title="$t('invoices.details', {invoice: invoice.id, contract: invoice.contract_id})" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="receipt" />
            </q-avatar>
            {{ $t('invoices.details', {invoice: invoice.id, contract: invoice.contract_id}) }}
        </template>

        <div class="q-pa-md">
            <div class="tw-w-full tw-flex tw-justify-end tw-items-center tw-gap-5">

                <Select
                    v-model="template"
                    class="tw-w-1/4"
                    :label="$t('invoices.templates')"
                    :options="props.templates"
                    option-label="name"
                    option-value="id"
                />

                <div>
                    <PrimaryButton icon="download" @click="download" :disabled="checkTemplate">
                        <q-tooltip>
                            {{ $t('actions.download') }}
                        </q-tooltip>
                    </PrimaryButton>
                </div>
            </div>
            <q-card class="tw-mt-5">
                <q-card-section>
                    <div class="tw-grid tw-grid-cols-4">
                        <div>
                            <div class="tw-font-semibold">{{ $t('fields.client') }}</div>
                            <div>{{ invoice.contract.project.client.name }}</div>
                        </div>

                        <div>
                            <div class="tw-font-semibold">{{ $t('fields.total') }}</div>
                            <div>{{ money(invoice.total) }}</div>
                        </div>

                        <div>
                            <div class="tw-font-semibold">{{ $t('fields.created_at') }}</div>
                            <div>{{ invoice.created_at }}</div>
                        </div>

                        <div>
                            <div class="tw-font-semibold">{{ $t('fields.created_by') }}</div>
                            <div>{{ invoice.created_by.name }}</div>
                        </div>
                    </div>

                    <q-markup-table class="tw-mt-5 tw-shadow-none">
                        <thead>
                        <tr>
                            <th class="text-right">{{ $t('fields.product') }}</th>
                            <th class="text-right">{{ $t('fields.price') }}</th>
                            <th class="text-right">{{ $t('fields.quantity') }}</th>
                          <th class="text-right">{{ $t('fields.discount_without_symbol') }}</th>
                            <th class="text-right">{{ $t('fields.subtotal') }}</th>
                            <th class="text-right">{{ $t('fields.tax') }}</th>
                            <th class="text-right">{{ $t('fields.total') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="product in invoice.products">
                            <td class="text-right">
                                <ProductName :name="product.name" />
                            </td>
                            <td class="text-right">{{ money(product.pivot.price) }}</td>
                            <td class="text-right">{{ product.pivot.quantity }}</td>
                          <td class="text-right">{{ money(product.pivot.discount) }}</td>
                            <td class="text-right">{{ money(product.pivot.subtotal) }}</td>
                            <td class="text-right">{{ money(product.pivot.tax) }}</td>
                            <td class="text-right">{{ money(product.pivot.total) }}</td>
                        </tr>
                        </tbody>
                    </q-markup-table>
                </q-card-section>
            </q-card>

            <div class="tw-mt-5 tw-flex tw-justify-end">
                <SecondaryButton :to="route('invoices.index', {contract: invoice.contract_id})" v-if="can('read_invoices', $page.props)">
                    {{ $t('actions.back') }}
                </SecondaryButton>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">

import {Head} from "@inertiajs/vue3";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import Select from "../../Components/Select.vue";
import SecondaryButton from "../../Components/SecondaryButton.vue";
import route from 'ziggy-js';
import {money, can} from '../../Common/helpers';
import {computed, ref} from "vue";
import ProductName from "../../Components/ProductName.vue";

const props = defineProps<{
    invoice,
    templates
}>()

const template = ref(null)

const checkTemplate = computed(() => {
    console.log(template.value === null)
    return template.value === null;
})

function download() {
    if (template.value){
        window.location.href = route('invoices.download', {invoice: props.invoice.id, template: template.value})
    }

}


</script>
