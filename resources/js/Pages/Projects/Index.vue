<template>

    <Head :title="$t('projects.title')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="foundation" />
            </q-avatar>
            {{ $t('projects.title') }}
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
                    <PrimaryButton v-if="can('create_projects', $page.props)"  class="tw-ml-3" icon="add" to="projects.create"/>
                    <q-btn color="primary" v-if="can('import_projects', $page.props)" class="tw-ml-3" icon="publish" @click="open=!open"/>

                </template>
                <template #body-cell-actions="props">
                    <RowActions :actions="getActions(props.row)"/>
                </template>
            </q-table>
            <q-dialog v-model="open">
                <Import

                downloadurl="/download/8"
                @errors="onFinishImportErrors"
                @finish="onFinishImport" routeName="projects.import" title="Importar plantilla de proyectos"></Import>
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
import {Head, router, usePage} from "@inertiajs/vue3";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import {useI18n} from "vue-i18n";
import {getUpdatedAt, can} from "../../Common/helpers";
import {Paginator} from "../../Models/Paginator";
import {ref} from "vue";
import {RowAction} from "../../Models/RowAction";
import RowActions from "../../Components/RowActions.vue";
import {usePagination} from "../../Composables/pagination";

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
        field: 'name',
        classes: 'product-col'
    },
    {
        name: 'client',
        required: true,
        align: "left",
        sortable: false,
        filterable: true,
        label: i18n.t('fields.client'),
        field: row => row.client.name,
        data: 'client',
        classes: 'product-col'
    },
    {
        name: 'project_manager',
        required: true,
        align: "left",
        sortable: true,
        filterable: true,
        label: i18n.t('fields.project_manager'),
        field: 'project_manager'
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
        align: "left",
        required: true,
        sortable: false,
        filterable: false,
        label: i18n.t('fields.created_by'),
        field: row => row.created_by_name ?? ' '
    },
    {
        name: 'updated_at',
        align: "left",
        sortable: true,
        filterable: true,
        label: i18n.t('fields.updated_at'),
        field: row => getUpdatedAt(row)
    },
    {
        name: 'updated_by',
        align: "left",
        required: true,
        sortable: false,
        filterable: false,
        label: i18n.t('fields.updated_by'),
        field: row => row.updated_by_name ?? ' '
    },
    {
        name: 'actions',
        label: i18n.t('actions.title'),
        field: null
    }
]

const {pagination, filter, onRequest} = usePagination(page.props.projects, columns)

/**
 *
 * @param {{id, name, phone, active, created_at}} row
 * @return {RowAction[]}
 */
function getActions(row): RowAction[] {

    return RowAction.buildCommonsActions(
        'projects.edit',
        'projects.destroy',
        row.crud_permissions.edit,
        row.crud_permissions.delete,
        'edit',
        'delete',
        { project: row.id },
        i18n,
        () => {

            router.reload({
                only: ['projects'],
                preserveScroll: true,
                onSuccess: params => {
                    pagination.value.data = params.props.projects.pagination.data
                }
            })
        }
    )
}

function onFinishImport(){
    open.value = false
    router.reload({
        only: ['projects'],
        preserveScroll: true,
        onSuccess: params => {
            console.log(params)
            pagination.value.data = params.props.projects.pagination.data
        }
    })
}

function onFinishImportErrors(data){
    openErrors.value = true
    listerrors.value = data.errors
}
</script>
