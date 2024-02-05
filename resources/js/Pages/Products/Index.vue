<template>

    <Head :title="$t('products.title')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="construction" />
            </q-avatar>
            {{ $t('products.title') }}
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
                    <PrimaryButton v-if="can('create_products', $page.props)"  class="tw-ml-3" icon="add" to="products.create"/>
                    <q-btn color="primary" v-if="can('import_products', $page.props)" class="tw-ml-3" icon="publish" @click="open=!open"/>

                </template>
                <template #body-cell-name="props">
                    <!--<q-td class="text-right">
                        <ProductName :name="props.row.name" />
                    </q-td>-->
                    <q-td>
                        <ProductNameSpecification :name="props.row.name" />
                    </q-td>
                </template>
                <template #body-cell-active="props">
                    <BooleanCell :value="props.row.active" />
                </template>
                <template #body-cell-actions="props">
                    <RowActions :actions="getActions(props.row)"/>
                </template>
            </q-table>
            <q-dialog v-model="open">
                <Import

                downloadurl="/download/6"
                @errors="onFinishImportErrors"
                @finish="onFinishImport" routeName="products.import" title="Importar plantilla de productos"></Import>
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
import {onMounted, ref} from "vue";
import {useI18n} from "vue-i18n";
import RowActions from "@/Components/RowActions.vue";
import {Paginator} from "../../Models/Paginator";
import {RowAction} from "../../Models/RowAction";
import {can, getUpdatedAt, money} from "../../Common/helpers";
import {usePagination} from "../../Composables/pagination";
import BooleanCell from "../../Components/BooleanCell.vue";
import ProductNameSpecification from "../../Components/ProductNameSpecification.vue";

const props = defineProps<{
    data
}>()

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
        align: "center",
        sortable: true,
        filterable: true,
        label: i18n.t('fields.name'),
        field: 'name'
    },
    {
        name: 'productCategory',
        required: true,
        align: "left",
        sortable: true,
        filterable: true,
        label: i18n.t('fields.product_category'),
        field: row => row.product_category.name,
        data: 'product_category.name'
    },
    {
        name: 'active',
        required: true,
        align: "center",
        sortable: true,
        filterable: true,
        label: i18n.t('fields.active'),
        field: 'active'
    },
    {
        name: 'monthly_price',
        required: true,
        sortable: true,
        filterable: true,
        label: i18n.t('fields.monthly_price'),
        field: row => money(row.monthly_price),
        data: 'monthly_price'
    },
    {
        name: 'replacement_price',
        required: true,
        sortable: true,
        filterable: true,
        label: i18n.t('fields.replacement_price'),
        field: row => money(row.replacement_price),
        data: 'replacement_price'
    },
    {
        name: 'tax',
        required: true,
        sortable: true,
        filterable: true,
        label: i18n.t('fields.tax_percent'),
        field: row => row.tax,
        data: 'tax'
    },
    {
        name: 'sock',
        required: true,
        sortable: false,
        filterable: false,
        label: i18n.t('fields.stock'),
        field: row => row.inventory.stock
    },
    {
        name: 'rented',
        required: true,
        sortable: false,
        filterable: false,
        label: i18n.t('fields.re_rented'),
        field: row => row.inventory.rented
    },
    {
        name: 'created_at',
        align: "left",
        sortable: true,
        label: i18n.t('fields.created_at'),
        field: 'created_at'
    },
    {
        name: 'created_by',
        required: true,
        align: "left",
        sortable: false,
        filterable: true,
        label: i18n.t('fields.created_by'),
        field: row => row.created_by?.name,
        data: 'created_by'
    },
    {
        name: 'updated_at',
        align: "left",
        sortable: true,
        label: i18n.t('fields.updated_at'),
        field: row => getUpdatedAt(row),
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
        label: i18n.t('actions.title'),
        field: null
    }
]

const {pagination, filter, onRequest} = usePagination(props.data, columns)

/**
 *
 * @param {{id, name, phone, active, created_at}} row
 * @return {RowAction[]}
 */
function getActions(row): RowAction[] {

    return RowAction.buildCommonsActions(
        'products.edit',
        'products.destroy',
        row.crud_permissions.edit,
        row.crud_permissions.delete,
        'edit',
        'delete',
        { product: row.id },
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

<style scoped>
/*.q-table th {
    text-align: left !important;
}

.q-table td {
    text-align: left !important;
}*/
</style>