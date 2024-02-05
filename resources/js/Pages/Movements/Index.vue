<template>
    <Head :title="$t('inventories.manage')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="iso" />
            </q-avatar>
            {{ $t('inventories.manage') }}
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
                    <PrimaryButton v-if="can('create_quotes', $page.props)"  class="tw-ml-3" icon="add" to="movements.create"/>
                </template>
                <template #body-cell-actions="props">
                    <RowActions :actions="getActions(props.row)"/>
                </template>
            </q-table>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">

import {Head} from "@inertiajs/vue3";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import RowActions from "../../Components/RowActions.vue";
import {can, money} from '../../Common/helpers';
import {useI18n} from "vue-i18n";
import {RowAction} from "../../Models/RowAction";
import {usePagination} from "laravel-quasar-table";

const props = defineProps<{
    movements
}>()

//Services
const i18n = useI18n()

const {pagination, filter, onRequest} = usePagination(props.movements, [
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
        name: 'reason',
        required: true,
        align: "left",
        sortable: false,
        filterable: true,
        label: i18n.t('fields.reason'),
        field: row => row.reason.name
    },
    {
        name: 'type',
        required: true,
        align: "left",
        sortable: true,
        filterable: true,
        label: i18n.t('fields.type'),
        field: row => {
            switch (String(row.type).toLocaleLowerCase()) {
                case 'increment':
                    return i18n.t('inventories.movements.types.increment')
                case 'decrement':
                    return i18n.t('inventories.movements.types.decrement')
            }
        }
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
        field: row => row.created_by?.name
    },
    {
        name: 'actions',
        label: i18n.t('actions.title')
    }
])

function getActions(row): RowAction[] {

    return [
        new RowAction().apply(action => {
            action.label = i18n.t('actions.details')
            action.route = 'movements.show'
            action.args = {movement: row.id}
            action.icon = 'info'
        })
    ]
}
</script>
