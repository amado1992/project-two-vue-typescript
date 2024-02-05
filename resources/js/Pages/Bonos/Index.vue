<template>
    <Head :title="$t('bonos.title')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="attach_money" />
            </q-avatar>
            {{ $t('bonos.title') }}
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
                    <PrimaryButton v-if="can('create_bonos', $page.props)"  class="tw-ml-3" icon="add" to="bonos.create"/>
                </template>
                <template #body-cell-actions="props">
                    <RowActions :actions="getActions(props.row)" />
                </template>
            </q-table>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">

import {Head, router} from "@inertiajs/vue3";
import {usePagination} from "../../Composables/pagination";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import RowActions from "../../Components/RowActions.vue";
import {can, getUpdatedAt, money} from '../../Common/helpers';
import {RowAction} from "../../Models/RowAction";
import {useI18n} from "vue-i18n";
import {useQuasar} from "quasar";
import route from "ziggy-js";

const props = defineProps<{
    bonos
}>()

const i18n = useI18n()
const $q = useQuasar()

const {pagination, filter, onRequest} = usePagination(props.bonos, [
    {
        name: 'client',
        required: true,
        align: "left",
        sortable: true,
        filterable: true,
        label: i18n.t('fields.client'),
        field: row => row.client.name,
        data: 'client.name'
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
        name: 'credit',
        required: true,
        sortable: true,
        filterable: true,
        label: i18n.t('fields.credit'),
        field: row => money(row.credit),
        data: 'credit'
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
        name: 'updated_at',
        required: true,
        align: "left",
        sortable: true,
        filterable: true,
        label: i18n.t('fields.updated_at'),
        field: row => getUpdatedAt(row),
        data: 'updated_at'
    },
    {
        name: 'updated_by',
        required: true,
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

    if (row.crud_permissions.edit) {

        actions.push(new RowAction().apply(a => {
            a.label = i18n.t('actions.edit')
            a.icon = 'edit'
            a.route = 'bonos.edit'
            a.args = {bono: row.id}
        }))
    }

    if (row.crud_permissions.delete) {

        actions.push(new RowAction().apply(a => {
            a.label = i18n.t('actions.delete')
            a.icon = 'delete'
            a.method = 'delete'
            a.callback = () => {

                $q.dialog({
                    title: i18n.t('messages.delete_confirmation'),
                    message: i18n.t('messages.delete_bono_confirmation_msg'),
                    cancel: true,
                    persistent: true
                }).onOk(() => {

                    router.delete(route('bonos.destroy', {bono: row.id}), {
                        preserveScroll: true,
                        onSuccess: () => {

                            router.reload({
                                only: ['bonos'],
                                onSuccess: params => {

                                    pagination.value.data = params.props.bonos.pagination.data
                                }
                            })
                        }
                    })
                })

                return true
            }
        }))
    }

    return actions
}
</script>
