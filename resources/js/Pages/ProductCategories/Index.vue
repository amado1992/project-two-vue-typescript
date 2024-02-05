<template>
    <Head :title="$t('productCategories.title')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="category" />
            </q-avatar>
            {{ $t('productCategories.title') }}
        </template>

        <div class="q-pa-md">

            <div class="lg:tw-grid lg:tw-grid-cols-4 tw-gap-5">

                <q-card class="tw-hidden lg:tw-block">
                    <q-tree :nodes="productCategories" node-key="label" :no-nodes-label="$t('messages.no_data')" />
                </q-card>

                <div class="tw-col-span-3">
                    <q-card>
                        <q-table class="fixed-column-table" :columns="pagination.columns" :rows="pagination.data"
                            :filter="filter" v-model:pagination="pagination" row-key="name" @request="onRequest">
                            <template #top-right>
                                <div class="tw-flex tw-gap-1">
                                    <q-input dense debounce="300" v-model="filter" :placeholder="$t('actions.search')">
                                        <template v-slot:append>
                                            <q-icon name="search" />
                                        </template>
                                    </q-input>
                                    <PrimaryButton v-if="can('create_productCategories', $page.props)" icon="add"
                                        to="productCategories.create" />
                                    <q-btn color="primary" v-if="can('import_productCategories', $page.props)"
                                        class="tw-ml-3" icon="publish" @click="open = !open" />


                                </div>
                            </template>
                            <template #body-cell-active="props">
                                <q-td class="q-table&#45;&#45;col-auto-width text-center">
                                    <q-icon size="25px" name="check_circle" v-show="props.row.active" color="primary" />
                                    <q-icon size="25px" name="cancel" v-show="!props.row.active" color="negative" />
                                </q-td>

                            </template>
                            <template #body-cell-actions="props">
                                <RowActions :actions="getActions(props.row)" />
                            </template>
                        </q-table>
                    </q-card>
                </div>
            </div>
        </div>
        <q-dialog v-model="open">
            <Import downloadurl="/download/1" @errors="onFinishImportErrors" @finish="onFinishImport"
                routeName="productCategories.import" title="Importar plantilla de categoría de productos"></Import>
        </q-dialog>
        <q-dialog v-model="openErrors">
            <div>
                <ImportErrors :list="listerrors"></ImportErrors>
            </div>
        </q-dialog>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">

import Import from "@/Components/Import.vue"
import ImportErrors from "@/Components/ImportErrors.vue"
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, usePage } from '@inertiajs/vue3';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { computed, onMounted, ref } from "vue";
import { useI18n } from "vue-i18n";
import RowActions from "@/Components/RowActions.vue";
import { Paginator } from "../../Models/Paginator";
import { RowAction } from "../../Models/RowAction";
import { can, getUpdatedAt } from "../../Common/helpers";
import { usePagination } from "../../Composables/pagination";

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
        sortable: true,
        filterable: true,
        label: i18n.t('fields.name'),
        field: 'name',
        classes: 'product-col'
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
        name: 'father_category',
        required: true,
        sortable: false,
        filterable: false,
        label: i18n.t('fields.father_category'),
        field: row => row.father?.name,
        classes: 'product-col'
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
        sortable: false,
        filterable: false,
        label: i18n.t('fields.created_by'),
        field: row => row.created_by?.name
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

const { pagination, filter, onRequest } = usePagination(page.props.data, columns)

const _productCategories = page.props.productCategories as Array<Category>;
    
let productCategories = computed(() => {

    var myarray = []
    try {
        for (var _i = 0; _i < _productCategories.length; _i++) {
            var result = new Category();
            result.children = _productCategories[_i].children;
            result.label = toUpperCaseFirstLetter(_productCategories[_i].label);
            myarray.push(result);
        }

    } catch (error) {
        console.error(error);
    }
    return myarray;
});

/**
 *
 * @param {{id, name, phone, active, created_at}} row
 * @return {RowAction[]}
 */
function getActions(row): RowAction[] {

    return RowAction.buildCommonsActions(
        'productCategories.edit',
        'productCategories.destroy',
        row.crud_permissions.edit,
        row.crud_permissions.delete,
        'edit',
        'delete',
        { productCategory: row.id },
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

function onFinishImport() {
    open.value = false
    router.reload({})
}

function onFinishImportErrors(data) {
    openErrors.value = true
    listerrors.value = data.errors
}

function toUpperCaseFirstLetter(str: string): string {
  return str.charAt(0).toUpperCase() + str.slice(1);
}

class Label {
    label: string = "";
}
class Category {
    label: string = "";
    children: Label[] = [];
}

</script>
