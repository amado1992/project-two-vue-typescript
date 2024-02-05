<template>
    <Head :title="$t('designs.title')" />
    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="draw" />
            </q-avatar>
            {{ $t('designs.title') }}
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
                    <PrimaryButton v-if="can('create_designs', $page.props)"  class="tw-ml-3" icon="add" to="designs.create"/>
                </template>
                <template #body-cell-id="props">
                    <q-td class="text-right">
                        <Link class="text-blue" :href="route('designs.edit', {design: props.row.id})" v-if="props.row.crud_permissions.edit">
                            {{ props.row.id }}
                        </Link>
                        <span v-else>{{ props.row.id }}</span>
                    </q-td>
                </template>
                <template #body-cell-approved="props">
                    <BooleanCell :value="props.row.quote.approved" />
                </template>
                <template #body-cell-actions="props">
                    <RowActions :actions="getActions(props.row)" />
                </template>
            </q-table>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">

import {Head, router, Link} from "@inertiajs/vue3";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import RowActions from "../../Components/RowActions.vue";
import {getUpdatedAt, money, can} from "../../Common/helpers";
import {useI18n} from "vue-i18n";
import {RowAction} from "../../Models/RowAction";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import BooleanCell from "../../Components/BooleanCell.vue";
import {usePagination} from "laravel-quasar-table";

const props = defineProps<{
    designs
}>()

const i18n = useI18n()

const {pagination, filter, onRequest} = usePagination(props.designs, [
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
        sortable: false,
        filterable: false,
        label: i18n.t('fields.date'),
        field: row => row.quote.date,
    },
    {
        name: 'project',
        required: true,
        align: "left",
        sortable: false,
        filterable: true,
        label: i18n.t('fields.project'),
        field: row => row.quote.project?.name || ""
    },
    /*{
        name: 'subtotal',
        required: true,
        sortable: false,
        filterable: false,
        label: i18n.t('fields.subtotal'),
        field: row => money(row.quote.subtotal)
    },
    {
        name: 'tax',
        required: true,
        sortable: false,
        filterable: false,
        label: i18n.t('fields.tax'),
        field: row => money(row.quote.tax)
    },
    {
        name: 'total',
        required: true,
        sortable: false,
        filterable: false,
        label: i18n.t('fields.total'),
        field: row => money(row.quote.total)
    },*/
    {
        name: 'commercial',
        required: true,
        align: "left",
        sortable: false,
        filterable: true,
        label: i18n.t('fields.commercial'),
        field: row => row.quote.commercial.name
    },
    {
        name: 'approved',
        required: true,
        sortable: false,
        filterable: false,
        label: i18n.t('fields.approved')
    },
    {
        name: 'contract',
        required: true,
        align: "center",
        sortable: false,
        filterable: false,
        label: i18n.t('fields.contract'),
        field: row => row.quote.no_contract
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
        field: row => getUpdatedAt(row)
    },
    {
        name: 'updated_by',
        required: false,
        align: "left",
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

function getActions(row) {

    const actions = []

    if (row.crud_permissions.approve) {

        actions.push(new RowAction().apply(action => {
            action.label = i18n.t('actions.approve')
            action.route = 'designs.approve'
            action.args = {design: row.id}
            action.icon = 'done'
        }))
    }

    actions.push(...RowAction.buildCommonsActions(
        'designs.edit',
        'designs.destroy',
        row.crud_permissions.edit,
        row.crud_permissions.delete,
        'edit',
        'delete',
        {design: row.id},
        i18n,
        () => {
            router.reload({
                //only: ['designs'],
                preserveScroll: false,
                onSuccess: params => {
                    pagination.value.data = params.props.designs.pagination.data;
                }
            })
        }
    ))

    return actions
}
</script>
