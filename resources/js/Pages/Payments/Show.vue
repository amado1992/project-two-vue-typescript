<template>
    <Head :title="$t('payments.details')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="description" />
            </q-avatar>
            {{ $t('payments.details') }}
        </template>

        <div class="container">

            <q-card class="tw-mt-5">
                <q-card-section>
                    <div class="tw-grid tw-grid-cols-2 md:tw-grid-cols-3 xl:tw-grid-cols-5 tw-gap-5 tw-text-[12px]">
                        <dl>
                            <dt class="tw-font-semibold">{{ $t('fields.id') }}</dt>
                            <dd>{{ payment.id }}</dd>
                        </dl>

                        <dl>
                            <dt class="tw-font-semibold">{{ $t('fields.date') }}</dt>
                            <dd>{{ payment.date }}</dd>
                        </dl>

                        <dl>
                            <dt class="tw-font-semibold">{{ $t('fields.client') }}</dt>
                            <dd>{{ payment.client.name }}</dd>
                        </dl>

                        <dl>
                            <dt class="tw-font-semibold">{{ $t('fields.created_at') }}</dt>
                            <dd>{{ payment.created_at }}</dd>
                        </dl>
                        <dl>
                            <dt class="tw-font-semibold">{{ $t('fields.created_by') }}</dt>
                            <dd>{{ payment.created_by.name }}</dd>
                        </dl>
                    </div>
                </q-card-section>
            </q-card>

            
            <q-markup-table class="tw-mt-5">
                <thead>
                <tr>
                    <th class="text-left">{{ $t('fields.invoice') }}</th>
                    <th class="text-right">{{ $t('fields.contract') }}</th>
                    <th class="text-left">{{ $t('fields.project') }}</th>
                    <th class="text-left">{{ $t('fields.amount_paid') }}</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item in payment.invoices" :key="item.id">
                    <td class="text-left">
                        {{ item.id }}
                    </td>
                    <td class="text-right">
                        {{ item.contract.id }}
                    </td>
                    <td class="text-left">
                        {{ item.contract.project.name }}
                    </td>
                    <td class="text-left">
                        {{ item.total }}
                    </td>
                </tr>
                </tbody>
            </q-markup-table>

            <div class="tw-flex tw-justify-end tw-mt-5">
                <SecondaryButton to="payments.index" v-if="can('read_payments', $page.props)">
                    {{ $t('actions.back') }}
                </SecondaryButton>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">

import {Head, router, useForm} from "@inertiajs/vue3";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import {useI18n} from "vue-i18n";
import {periods, can} from "../../Common/helpers";
import {money, status, percentage} from "../../Common/helpers";
import {useQuasar} from "quasar";
import StartContractDialog from './StartContractDialog.vue';
import route from "ziggy-js";
import {computed, onMounted} from "vue";
import SecondaryButton from "../../Components/SecondaryButton.vue";
import ContractibleSummary from "../../Components/ContractibleSummary.vue";
import ContractCreators from "./ContractCreators.vue";
import DatePicker from "../../Components/DatePicker.vue";
import ProductName from "../../Components/ProductName.vue";
import BooleanCell from "../../Components/BooleanCell.vue";

const props = defineProps<{
    payment: any
}>()

console.log("Payment ", props.payment)
const i18n = useI18n()
const $q = useQuasar()

function concatProduct(items: any): string {
    var sum = "";
    for (let item of items) {
        sum = sum.concat(', ', item.id);
    }
    return sum.replace(/^,\s*/, '');
}

</script>

<style scoped>
dl > dd {
    margin-left: 0;
}

dl > dt {
    margin-top: 10px;
}

.cancelled {
    animation: zoomIn 0.8s linear;
}

@keyframes zoomIn {
    0% {
        opacity: 0;
        transform: scale(5);
    }

    90% {
        opacity: 0.5;
    }

    100% {
        opacity: 0.7;
    }
}

.margin-top-0 {
    margin-top: 0 !important;
}
</style>
