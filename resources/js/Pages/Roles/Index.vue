<template>

    <Head :title="$t('roles.title')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="verified_user" />
            </q-avatar>
            {{ $t('roles.title') }}
        </template>

        <div class="q-pa-md">
            <q-table
                class="fixed-column-table"
                :columns="pagination.columns"
                :rows="pagination.data"
                :filter="filter"
                v-model:pagination="pagination"
                row-key="email"
                @request="onRequest"
            >
                <template #top-right>
                    <q-input dense debounce="300" v-model="filter" :placeholder="$t('actions.search')">
                        <template v-slot:append>
                            <q-icon name="search" />
                        </template>
                    </q-input>
                    <PrimaryButton v-if="can('create_roles', $page.props)" class="tw-ml-3" icon="add" to="roles.create"/>
                </template>
                <template #body-cell-actions="props">
                    <RowActions :actions="getActions(props.row)"/>
                </template>
            </q-table>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">

import {Head, router, usePage} from "@inertiajs/vue3";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import RowActions from "@/Components/RowActions.vue";
import {useI18n} from "vue-i18n";
import {Paginator} from "../../Models/Paginator";
import {ref} from "vue";
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
        name: 'created_at',
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
        field: row => row.created_by?.name
    },
    {
        name: 'updated_at',
        sortable: true,
        filterable: true,
        label: i18n.t('fields.updated_at'),
        field: row => getUpdatedAt(row),
    },
    {
        name: 'updated_by',
        required: true,
        sortable: false,
        filterable: false,
        label: i18n.t('fields.updated_by'),
        field: row => row.updated_by?.name
    },
    {
        name: 'actions',
        label: i18n.t('actions.title'),
        field: null
    }
]

const paginationService = new Paginator(page.props.roles, columns)

const pagination = ref(paginationService)

const filter = ref(paginationService.filter)

/**
 *
 * @param {{id, name, email}} row
 * @return {RowAction[]}
 */
function getActions(row): RowAction[] {

    return RowAction.buildCommonsActions(
        'roles.edit',
        'roles.destroy',
        row.crud_permissions.edit,
        row.crud_permissions.delete,
        'edit',
        'delete',
        { role: row.id },
        i18n,
        () => {

            router.reload({
                only: ['roles'],
                preserveScroll: true,
                onSuccess: params => {

                    pagination.value.data = params.props.roles.pagination.data
                }
            })
        }
    )
}

function onRequest(props) {

    paginationService.onRequest(props)
}
</script>
