<template>
    <Head :title="$t('quotes.title')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="request_quote" />
            </q-avatar>
            {{ $t('quotes.title') }}
        </template>

        <div class="q-pa-md">
            <q-table
                class="fixed-column-table"
                :columns="pagination.columns"
                :rows="pagination.data"
                :filter="filter"
                v-model:pagination="pagination"
                row-key="name"
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
                    <PrimaryButton v-if="can('create_quotes', $page.props)"  class="tw-ml-3" icon="add" to="quotes.create"/>
                </template>
                <template #body-cell-id="props">
                    <q-td class="text-right">
                        <Link class="text-primary" :href="route('quotes.edit', {quote: props.row.id})" v-if="props.row.crud_permissions.edit">
                            {{ props.row.id }}
                        </Link>
                        <span v-else>{{ props.row.id }}</span>
                    </q-td>
                </template>
                <template #body-cell-approved="props">
                    <BooleanCell :value="props.row.approved" />
                </template>
                <template #body-cell-active="props">
                    <q-td class="q-table--col-auto-width text-center">
                        <q-icon size="25px" name="check_circle" v-show="props.row.active" color="primary"/>
                        <q-icon size="25px" name="cancel" v-show="! props.row.active" color="negative" />
                    </q-td>
                </template>
                <template #body-cell-contract="props">
                    <q-td class="text-center">
                        <Link
                            v-if="props.row.contract_id"
                            class="text-primary"
                            :href="route('contracts.show', {contract: props.row.contract_id})"
                        >
                            {{ props.row.contract_id }}
                        </Link>
                    </q-td>
                </template>
                <template #body-cell-design="props">
                    <q-td class="text-center">
                        <Link
                            v-if="props.row.design_id && props.row.crud_permissions.edit"
                            class="text-primary"
                            :href="route('designs.edit', {design: props.row.design_id})"
                        >
                            {{ props.row.design_id }}
                        </Link>
                        <span v-else>{{ props.row.design_id }}</span>
                    </q-td>
                </template>
                <template #body-cell-actions="props">
                    <RowActions :actions="getActions(props.row)"/>
                </template>
            </q-table>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">

import {Head, router, usePage, Link} from "@inertiajs/vue3";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import {Paginator} from "../../Models/Paginator";
import {ref} from "vue";
import {useI18n} from "vue-i18n";
import {RowAction} from "../../Models/RowAction";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import RowActions from "../../Components/RowActions.vue";
import {can, getUpdatedAt, money} from "../../Common/helpers";
import route from "ziggy-js";
import {usePagination} from "../../Composables/pagination";
import BooleanCell from "../../Components/BooleanCell.vue";

const props = defineProps<{
    quotes: any
}>()

//Services
const i18n = useI18n()
const page = usePage()

const {pagination, filter, onRequest} = usePagination(props.quotes, [
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
    sortable: false,
    filterable: false,
    label: i18n.t('fields.client'),
    field: row => row.client?.name ?? '',
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
        field: row => money(row.tax)
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
        name: 'commercial',
        required: true,
        align: "left",
        sortable: false,
        filterable: false,
        label: i18n.t('fields.commercial'),
        field: row => row.commercial.name
    },
    {
        name: 'approved',
        required: true,
        sortable: false,
        filterable: false,
        label: i18n.t('fields.approved'),
        field: 'approved'
    },
    {
        name: 'contract',
        required: true,
        align: "center",
        sortable: false,
        filterable: false,
        label: i18n.t('fields.contract'),
        field: 'no_contract'
    },
    {
        name: 'design',
        required: true,
        align: "center",
        sortable: false,
        filterable: false,
        label: i18n.t('fields.design'),
        field: 'design_id'
    },
    {
        name: 'created_at',
        required: false,
        align: "left",
        sortable: true,
        filterable: true,
        label: i18n.t('fields.created_at'),
        field: 'created_at'
    },
    {
        name: 'created_by',
        required: false,
        align: "left",
        sortable: false,
        filterable: false,
        label: i18n.t('fields.created_by'),
        field: row => row.created_by.name
    },
    {
        name: 'updated_at',
        required: false,
        align: "left",
        sortable: true,
        filterable: true,
        label: i18n.t('fields.updated_at'),
        field: 'updated_at',
    },
    {
        name: 'updated_by',
        align: "left",
        required: false,
        sortable: false,
        filterable: false,
        label: i18n.t('fields.updated_by'),
        field: row => row.updated_by?.name
    },
    {
        name: 'actions',
        label: i18n.t('actions.title')
    }
])

/**
 *
 * @param {{id, name, phone, active, created_at}} row
 * @return {RowAction[]}
 */
function getActions(row): RowAction[] {

    const actions = [
        new RowAction().apply(action => {
            action.label = i18n.t('actions.download')
            action.icon = 'download'
            action.callback = () => {
                window.location.href = route('quotes.download', {quote: row.id})
                return true
            }
        })
    ]

    if (row.crud_permissions.approve) {

        actions.push(new RowAction().apply(action => {
            action.label = i18n.t('actions.approve')
            action.route = 'quotes.approve'
            action.args = {quote: row.id}
            action.icon = 'done'
        }))
    }

    if (row.crud_permissions.edit) {

        actions.push(new RowAction().apply(action => {
            action.label = i18n.t('actions.edit')
            action.args = {quote: row.id}
            action.route = 'quotes.edit'
            action.icon = 'edit'
        }))
    }

    if (row.crud_permissions.delete) {

        actions.push(new RowAction().apply(action => {
            action.label = i18n.t('actions.delete')
            action.args = {quote: row.id}
            action.route = 'quotes.destroy'
            action.method = 'delete'
            action.icon = 'delete'
            action.onSuccess = () => {
                reload()
            }
        }))
    }

    return actions
}

function reload() {

    router.reload({
        only: ['quotes'],
        preserveScroll: true,
        onSuccess: params => {

            pagination.value.data = params.props.quotes.pagination.data
        }
    })
}
</script>
