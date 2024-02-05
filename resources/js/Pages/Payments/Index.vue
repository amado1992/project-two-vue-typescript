<template>
    <Head :title="$t('payments.title')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="payments" />
            </q-avatar>
            {{ $t('payments.title') }}
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
            <template v-slot:header="props">
            <q-tr :props="props">
              <q-th
                v-for="col in props.cols"
                :key="col.name"
                :props="props"
                style="text-align: center !important;"
              >
                {{ col.label }}
              </q-th>
            </q-tr>
           </template>
           
                <template #top-right>
                    <q-input dense debounce="300" v-model="filter" :placeholder="$t('actions.search')">
                        <template v-slot:append>
                            <q-icon name="search" />
                        </template>
                    </q-input>
                    <PrimaryButton v-if="can('create_payments', $page.props)"  class="tw-ml-3" icon="add" to="payments.create"/>
                </template>
                <template #body-cell-actions="props">
                    <RowActions :actions="getActions(props.row)"/>
                </template>
            </q-table>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">

import {Head, router} from "@inertiajs/vue3";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import {usePagination} from "../../Composables/pagination";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import RowActions from "../../Components/RowActions.vue";
import {can, getUpdatedAt, money} from '../../Common/helpers';
import {RowAction} from "../../Models/RowAction";
import {useI18n} from "vue-i18n";

const props = defineProps<{
    payments
}>()

const i18n = useI18n()

const {pagination, filter, onRequest} = usePagination(props.payments, [
    {
        name: 'id',
        required: true,
        sortable: true,
        filterable: true,
        label: i18n.t('fields.id'),
        field: 'id'
    },
    {
        name: 'date',
        required: true,
        align: "left",
        sortable: true,
        filterable: true,
        label: i18n.t('fields.date'),
        field: 'date'
    },
    {
        name: 'client',
        required: true,
        align: "left",
        sortable: true,
        filterable: true,
        label: i18n.t('fields.client'),
        field: row => row.client.name,
        data: 'client.id',
        classes: 'product-col'
    },
    {
        name: 'credit',
        required: true,
        sortable: false,
        filterable: false,
        label: i18n.t('fields.credit'),
        field: row => money(row.credit)
    },
    {
        name: 'created_at',
        required: true,
        align: "left",
        sortable: true,
        filterable: true,
        label: i18n.t('fields.created_at'),
        field: 'created_at'
    },
    {
        name: 'created_by',
        required: true,
        align: "left",
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

function getActions(row: any) {
    
        return RowAction.buildCommonsActionsTwo(
            'payments.edit',
            'payments.destroy',
            'payments.show',
            row.crud_permissions.edit,
            row.crud_permissions.delete,
            row.crud_permissions.details,
            'edit',
            'delete',
            'info',
            {payment: row.id},
            i18n,
            () => {

                router.reload({
                    only: ['payments'],
                    onSuccess: params => {

                        pagination.value.data = params.props.payments.pagination.data
                    }
                })
            }
        )
}
</script>
