<template>

    <Head :title="$t('traces.title')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="query_stats" />
            </q-avatar>
            {{ $t('traces.title') }}
        </template>

        <div class="q-pa-md">

            <div class="tw-flex tw-justify-between tw-items-center tw-gap-3 tw-mt-5">
                <Select
                    class="tw-w-full lg:tw-w-1/2 xl:tw-w-1/4"
                    v-model="selectedModule"
                    :options="modules"
                    option-label="label"
                    option-value="value"
                    :label="$t('fields.module')"
                />

                <div>
                    <SecondaryButton @click="clear">
                        {{ $t('actions.clear') }}
                    </SecondaryButton>
                </div>
            </div>

            <q-table
                :columns="pagination.columns"
                :rows="pagination.data"
                v-model:pagination="pagination"
                @request="onRequest"
            >
                <template #body-cell-id="props">
                    <td class="text-right">
                        <span v-if="props.row.action === 'deleted'">{{ props.row.model_id }}</span>
                        <a target="_blank" v-else class="text-blue" :href="route('traces.model', {trace: props.row.id})">{{ props.row.model_id }}</a>
                    </td>
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
import {useI18n} from "vue-i18n";
import Select from "../../Components/Select.vue";
import {computed, onMounted, ref} from "vue";
import route from "ziggy-js";
import SecondaryButton from "../../Components/SecondaryButton.vue";
import {RowAction} from "../../Models/RowAction";
import RowActions from "../../Components/RowActions.vue";
import {useQuasar} from "quasar";
import {usePagination} from "laravel-quasar-table";
import {Column} from "laravel-quasar-table/dist/types/src/Column";

const props = defineProps<{
    traces,
    selectedModule
}>()

const i18n = useI18n()
const $q = useQuasar()

const modules = [
    {
        label: i18n.t('clients.title'),
        value: 'clients'
    },
    {
        label: i18n.t('contracts.title'),
        value: 'contracts'
    },
    {
        label: i18n.t('productCategories.title'),
        value: 'productCategories'
    },
    {
        label: i18n.t('providers.title'),
        value: 'providers'
    },
    {
        label: i18n.t('products.title'),
        value: 'products'
    },
    {
        label: i18n.t('projects.title'),
        value: 'projects'
    },
    {
        label: i18n.t('quotes.title'),
        value: 'quotes'
    },
    {
        label: i18n.t('re_rents.title'),
        value: 'reRents'
    },
    {
        label: i18n.t('inventories.title'),
        value: 'movements'
    },
    {
        label: i18n.t('inventories.reasons.title'),
        value: 'reasons'
    },
    {
        label: i18n.t('bonos.title'),
        value: 'bonos'
    },
    {
        label: i18n.t('payments.title'),
        value: 'payments'
    },
    {
        label: i18n.t('roles.title'),
        value: 'roles'
    },
    {
        label: i18n.t('users.title'),
        value: 'users'
    },
    {
        label: i18n.t('traces.title'),
        value: 'traces'
    },
    {
        label: i18n.t('designs.title'),
        value: 'designs'
    }
]

const _selectedModule = ref(props.selectedModule)
const selectedModule = computed<string>({
    get: () => _selectedModule.value,
    set: v => {
        _selectedModule.value = v

        router.get(route('traces.index', {module: _selectedModule.value}))
    }
})

const columns = computed<Column[]>(() => {

    const c: Column[] = [
        {
            name: 'action',
            required: true,
            sortable: true,
            filterable: true,
            label: i18n.t('fields.action'),
            field: 'readable_action'
        },
        {
            name: 'module',
            required: true,
            sortable: true,
            filterable: true,
            label: i18n.t('fields.module'),
            field: row => modules.find(m => m.value == row.module)?.label
        }
    ]

    if (selectedModule.value !== "traces") {

        c.push({
            name: 'id',
            required: true,
            sortable: true,
            filterable: true,
            label: i18n.t('fields.id'),
            field: 'id'
        })
    }

    c.push(...[
        {
            name: 'user',
            required: true,
            sortable: false,
            filterable: false,
            label: i18n.t('fields.user'),
            field: row => row.user.name
        },
        {
            name: 'created_at',
            sortable: true,
            filterable: true,
            label: i18n.t('fields.date'),
            field: 'created_at'
        },
        {
            name: 'actions',
            label: i18n.t('actions.title')
        }
    ])

    return c
})

const {pagination, onRequest} = usePagination(props.traces, columns.value)

function clear() {

    $q.dialog({
        title: i18n.t('messages.action_confirmation'),
        message: i18n.t('messages.delete_confirmation_msg'),
        cancel: true,
        persistent: true
    }).onOk(() => {

        router.delete(route('traces.clear', {module: selectedModule.value}), {
            onSuccess: () => {

                router.reload({
                    only: ['traces'],
                    onSuccess: params => {

                        pagination.value.data = params.props.traces.pagination.data
                    }
                })
            }
        })
    })
}

function getActions(row) {

    return [
        new RowAction().apply(action => {
            action.label = i18n.t('actions.details')
            action.icon = 'info'
            action.route = 'traces.show'
            action.args = {
                trace: row.id,
                module: selectedModule.value
            }
        })
    ]
}
</script>
