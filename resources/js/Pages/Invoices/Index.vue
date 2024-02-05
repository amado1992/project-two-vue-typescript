<template>
    <Head :title="$t('invoices.contract_title', {contract: contract_id})" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="receipt" />
            </q-avatar>
            {{ $t('invoices.contract_title', {contract: contract_id}) }}
        </template>

        <div class="q-pa-md">
            <q-table
                class="fixed-column-table"
                :columns="pagination.columns"
                :rows="pagination.data"
                :filter="filter"
                v-model:pagination="pagination"
                row-key="id"
                @request="onRequest"
            >
                <template #top-right>
                    <q-input dense debounce="300" v-model="filter" :placeholder="$t('actions.search')">
                        <template v-slot:append>
                            <q-icon name="search" />
                        </template>
                    </q-input>
                    <PrimaryButton
                        v-if="can('create_invoices', $page.props)"
                        class="tw-ml-3"
                        icon="add"
                        @click="createInvoice"
                    />
                </template>
                <template #body-cell-id="props">
                    <td class="text-right">
                        <Link class="text-blue" :href="route('invoices.show', {invoice: props.row.id})">
                            {{ props.row.id }}
                        </Link>
                    </td>
                </template>
                <template #body-cell-was_paid="props">
                    <BooleanCell :value="props.row.was_paid" />
                </template>
                <template #body-cell-actions="props"  v-if="can('delete_invoices', $page.props)" >
                    <RowActions :actions="getActions(props.row)" />
                </template>
            </q-table>

            <div class="tw-mt-5 tw-flex tw-justify-end">
                <SecondaryButton to="contracts.index">{{ $t('actions.back') }}</SecondaryButton>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">

import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import {usePagination} from "../../Composables/pagination";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import RowActions from "../../Components/RowActions.vue";
import {can, money} from '../../Common/helpers';
import {useI18n} from "vue-i18n";
import route from 'ziggy-js';
import {router, useForm, Head, Link} from "@inertiajs/vue3";
import {RowAction} from "../../Models/RowAction";
import SecondaryButton from "../../Components/SecondaryButton.vue";
import BooleanCell from "../../Components/BooleanCell.vue";

const props = defineProps<{
    contract_id: number,
    invoices: any[]
}>()

const i18n = useI18n()

const {pagination, filter, onRequest} = usePagination(props.invoices, [
    {
        name: 'id',
        required: true,
        sortable: true,
        filterable: true,
        label: i18n.t('fields.id'),
        field: 'id'
    },
  {
    name: 'discount',
    required: true,
    sortable: false,
    filterable: false,
    label: i18n.t('fields.discount_without_symbol'),
    field: row => money(row.discount)
  },
    {
        name: 'subtotal',
        required: true,
        sortable: false,
        filterable: false,
        label: i18n.t('fields.subtotal'),
        field: row => money(row.subtotal)
    },
    {
        name: 'tax',
        required: true,
        sortable: false,
        filterable: false,
        label: i18n.t('fields.tax'),
        field: row => money(row.taxes)
    },
    {
        name: 'total',
        required: true,
        sortable: false,
        filterable: false,
        label: i18n.t('fields.total'),
        field: row => money(row.total)
    },
    {
        name: 'was_paid',
        required: true,
        sortable: false,
        filterable: false,
        label: i18n.t('fields.was_paid')
    },
    {
        name: 'created_at',
        required: true,
        sortable: true,
        filterable: true,
        label: i18n.t('fields.created_at'),
        field: 'created_at'
    },
    {
        name: 'created_by',
        required: true,
        sortable: false,
        filterable: false,
        label: i18n.t('fields.created_by'),
        field: row => row.created_by.name
    },
    {
        name: 'actions',
        label: i18n.t('actions.title')
    }
])

function createInvoice() {

    const form = useForm({
        contract_id: props.contract_id
    })

    form.post(route('invoices.store'), {
        preserveScroll: true
    })
}

function getActions(row) {
    return [
        new RowAction().apply(action => {
            action.label = i18n.t('actions.details')
            action.icon = 'info'
            action.route = 'invoices.show'
            action.args = {invoice: row.id}
        }),
        new RowAction().apply(action => {
            action.label = i18n.t('actions.delete')
            action.icon = 'delete'
            action.method = 'delete'
            action.route = 'invoices.destroy'
            action.args = {invoice: row.id}
            action.onSuccess = () => {

                router.reload({
                    only: ['invoices'],
                    onSuccess: params => {

                        pagination.value.data = params.props.invoices.pagination.data
                    }
                })
            }
        })
    ]
}
</script>
