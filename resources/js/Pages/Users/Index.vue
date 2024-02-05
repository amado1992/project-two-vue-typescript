<template>

    <Head :title="$t('users.title')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="group" />
            </q-avatar>
            {{ $t('users.title') }}
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

            <template v-slot:header="props">
            <q-tr :props="props">
              <q-th
                v-for="col in props.cols"
                :key="col.name"
                :props="props"
                style="text-align: center;"
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
                    <PrimaryButton v-if="can('create_users', $page.props)" class="tw-ml-3" icon="add" to="users.create"/>
                    <q-btn color="primary" v-if="can('import_users', $page.props)" class="tw-ml-3" icon="publish" @click="open=!open"/>

                </template>
                <template #body-cell-actions="props">
                    <RowActions :actions="getActions(props.row)"/>
                </template>
            </q-table>
            <q-dialog v-model="open">
                <Import

                downloadurl="/download/9"
                @errors="onFinishImportErrors"
                @finish="onFinishImport" routeName="users.import" title="Importar plantilla de usuarios"></Import>
            </q-dialog>
            <q-dialog v-model="openErrors">
                <div>
                    <ImportErrors :list="listerrors"></ImportErrors>
                </div>
            </q-dialog>
        </div>

    </AuthenticatedLayout>
</template>

<script setup lang="ts">

import Import from "@/Components/Import.vue"
import ImportErrors from "@/Components/ImportErrors.vue"
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
const open = ref(false)
const openErrors = ref(false)
const listerrors = ref([])

const columns = [
    {
        name: 'name',
        required: true,
        align: "left",
        sortable: true,
        filterable: true,
        label: i18n.t('fields.name'),
        field: 'name'
    },
    {
        name: 'email',
        required: true,
        align: "left",
        sortable: true,
        filterable: true,
        label: i18n.t('fields.email'),
        field: 'email'
    },
    {
        name: 'role',
        required: true,
        align: "left",
        sortable: false,
        filterable: false,
        label: i18n.t('fields.role'),
        field: row => row.role?.name
    },
    {
        name: 'created_at',
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
        name: 'updated_at',
        align: "left",
        sortable: true,
        filterable: true,
        label: i18n.t('fields.updated_at'),
        field: row => getUpdatedAt(row),
    },
    {
        name: 'updated_by',
        align: "left",
        required: true,
        sortable: false,
        filterable: false,
        label: i18n.t('fields.updated_by'),
        field: row => row.updated_by?.name
    },
    {
        name: 'actions',
        label: i18n.t('actions.title')
    }
]

const paginationService = new Paginator(page.props.data, columns)

const pagination = ref(paginationService)

const filter = ref(paginationService.filter)

/**
 *
 * @param {{id, name, email}} row
 * @return {RowAction[]}
 */
function getActions(row): RowAction[] {

    return RowAction.buildCommonsActions(
        'users.edit',
        'users.destroy',
        row.crud_permissions.edit,
        row.crud_permissions.delete,
        'edit',
        'delete',
        { user: row.id },
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

function onFinishImport(){
    open.value = false
    router.reload({
        only:["data"]
    })
}

function onFinishImportErrors(data){
    openErrors.value = true
    listerrors.value = data.errors
}

</script>
