<template>

    <Head :title="$t('brands.title')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="polymer" />
            </q-avatar>
            {{ $t('brands.title') }}
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
                <template #top-right>
                    <q-input dense debounce="300" v-model="filter" :placeholder="$t('actions.search')">
                        <template v-slot:append>
                            <q-icon name="search" />
                        </template>
                    </q-input>
                    <PrimaryButton v-if="can('create_brands', $page.props)" class="tw-ml-3" icon="add" to="brands.create"/>
                </template>
                <template #body-cell-active="props">
                    <q-td class="q-table--col-auto-width text-center">
                        <q-icon size="25px" name="check_circle" v-show="props.row.active" color="primary"/>
                        <q-icon size="25px" name="cancel" v-show="! props.row.active" color="negative" />
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

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, router, usePage} from '@inertiajs/vue3';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {ref} from "vue";
import {useI18n} from "vue-i18n";
import RowActions from "@/Components/RowActions.vue";
import {Paginator} from "../../Models/Paginator";
import {RowAction} from "../../Models/RowAction";
import {can, getUpdatedAt} from "../../Common/helpers";

//Services
const i18n = useI18n()
const page = usePage()

const columns = [
    {
        name: 'name',
        required: true,
        sortable: true,
        filterable: true,
        label: i18n.t('fields.name'),
        field: 'name'
    },
    {
        name: 'active',
        required: true,
        sortable: true,
        filterable: true,
        label: i18n.t('fields.active'),
        field: 'active'
    },
    {
        name: 'created_at',
        sortable: true,
        label: i18n.t('fields.created_at'),
        field: 'created_at'
    },
    {
        name: 'created_by',
        required: true,
        sortable: true,
        filterable: true,
        label: i18n.t('fields.created_by'),
        field: row => row.created_by?.name,
        data: 'createdBy.name'
    },
    {
        name: 'updated_at',
        sortable: true,
        label: i18n.t('fields.updated_at'),
        field: row => getUpdatedAt(row),
    },
    {
        name: 'updated_by',
        required: true,
        sortable: true,
        filterable: true,
        label: i18n.t('fields.updated_by'),
        field: row => row.updated_by?.name,
        data: 'updated_by.name'
    },
    {
        name: 'actions',
        label: i18n.t('actions.title'),
        field: null
    }
]

const paginationService = new Paginator(page.props.data, columns)

const pagination = ref(paginationService)

const filter = ref(paginationService.filter)

/**
 *
 * @param {{id, name, phone, active, created_at}} row
 * @return {RowAction[]}
 */
function getActions(row): RowAction[] {

    return RowAction.buildCommonsActions(
        'brands.edit',
        'brands.destroy',
        row.crud_permissions.edit,
        row.crud_permissions.delete,
        'edit',
        'delete',
    { brand: row.id },
        i18n,
        () => {

            router.reload({
                only: ['data'],
                preserveScroll: true,
                onSuccess: params => {

                    pagination.value.data = params.props.data.pagination.data
                }
            })
        }
    )
}

function onRequest(props) {

    paginationService.onRequest(props)
}
</script>
