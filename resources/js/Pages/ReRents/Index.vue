<template>
    <Head :title="$t('re_rents.title')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="shopping_bag" />
            </q-avatar>
            {{ $t('re_rents.title') }}
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
                    <PrimaryButton v-if="can('create_re_rents', $page.props)"  class="tw-ml-3" icon="add" to="re-rents.create"/>
                </template>
                <template #body-cell-id="props">
                    <q-td class="text-right">
                        <Link class="text-primary" :href="route('re-rents.edit', {re_rent: props.row.id})" v-if="props.row.crud_permissions.edit">
                            {{ props.row.id }}
                        </Link>
                        <span v-else>{{ props.row.id }}</span>
                    </q-td>
                </template>
                <template #body-cell-actions="props">
                    <RowActions :actions="getActions(props.row)" />
                </template>
            </q-table>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">

import {Head, Link, router} from "@inertiajs/vue3";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import RowActions from "../../Components/RowActions.vue";
import {useI18n} from "vue-i18n";
import {getUpdatedAt, money} from "../../Common/helpers";
import {RowAction} from "../../Models/RowAction";
import {can} from '../../Common/helpers';
import route from "ziggy-js";
import {useQuasar} from "quasar";
import {usePagination} from "laravel-quasar-table";

const props = defineProps<{
    reRents
}>()

//Services
const i18n = useI18n()
const $q = useQuasar()

const {pagination, filter, onRequest} = usePagination(props.reRents, [
    {
        name: 'id',
        required: true,
        sortable: true,
        filterable: true,
        label: i18n.t('fields.id'),
        field: 'id'
    },
    {
        name: 'provider',
        required: true,
        align: "left",
        sortable: false,
        filterable: true,
        label: i18n.t('fields.provider'),
        field: row => row.provider.name
    },
    {
        name: 'start',
        required: true,
        align: "left",
        sortable: true,
        filterable: true,
        label: i18n.t('fields.from'),
        field: 'start'
    },
    {
        name: 'finish',
        required: true,
        align: "left",
        sortable: false,
        filterable: false,
        label: i18n.t('fields.to'),
        field: 'finish'
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
        name: 'subtotal',
        required: true,
        sortable: false,
        filterable: false,
        label: i18n.t('fields.subtotal'),
        field: row => money(row.subtotal)
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
        name: 'discount',
        required: true,
        sortable: false,
        filterable: false,
        label: i18n.t('fields.discount_without_symbol'),
        field: row => money(row.discount)
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

function getActions(row): RowAction[] {

    const actions = [new RowAction().apply(action => {
        action.label = i18n.t('actions.details')
        action.route = 're-rents.show'
        action.icon = 'info'
        action.args = {re_rent: row.id}
    })]

    if (row.crud_permissions.edit && row.crud_permissions.can_edit) {

        actions.push(new RowAction().apply(action => {
            action.label = i18n.t('actions.edit')
            action.route = 're-rents.edit'
            action.icon = 'edit'
            action.args = {re_rent: row.id}
        }))
    }

    if (row.crud_permissions.returns) {

        actions.push(new RowAction().apply(action => {
            action.label = i18n.t('contracts.returns.title')
            action.route = 're-rents.returns'
            action.icon = 'home_work'
            action.args = {re_rent: row.id}
        }))
    }

    if (row.crud_permissions.cancel) {

        actions.push(new RowAction().apply(action => {
            action.label = i18n.t('actions.annular')
            action.icon = 'cancel'
            action.callback = () => {
                $q.dialog({
                    title: i18n.t('messages.annular_confirmation'),
                    message: i18n.t('messages.annular_confirmation_msg'),
                    cancel: true,
                    persistent: true,
                    prompt: {
                        label: i18n.t('fields.reason'),
                        model: ''
                    }
                }).onOk(payload => {

                    const url = route('re-rents.cancel', {
                        re_rent: row.id
                    })
                    router.put(url, {
                        reason: payload
                    })
                })
                return true
            }
        }))
    }

    if (row.crud_permissions.delete) {

        actions.push(new RowAction().apply(action => {
            action.label = i18n.t('actions.delete')
            action.route = 're-rents.destroy'
            action.icon = 'delete'
            action.method = 'delete'
            action.args = {re_rent: row.id}
        }))
    }

  if (row.crud_permissions.start) {

    actions.push(new RowAction().apply(action => {
      action.label = i18n.t('actions.start')
      action.route = 're-rents.start'
      action.icon = 'play_arrow'
      action.callback = () => {
        const url = route('re-rents.start', {
          re_rent: row.id
        })
        router.put(
            url,
            {},
            {
              onSuccess: params => {
                pagination.value.data = params.props.reRents.pagination.data;
              }
            }
        )

        return true
      }


    }))
  }


  return actions
}
</script>
