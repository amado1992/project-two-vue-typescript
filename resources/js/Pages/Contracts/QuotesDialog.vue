<template>
    <q-dialog ref="dialogRef" @hide="onDialogHide">
        <q-card class="q-dialog-plugin" style="width: 800px; max-width: 80vw;">

            <q-card-section>
                <q-table
                    :title="$t('quotes.title')"
                    class="tw-shadow-none"
                    :columns="pagination.columns"
                    :rows="pagination.data"
                    :filter="filter"
                    v-model:pagination="pagination"
                    row-key="name"
                    @request="onRequest"
                    @row-click="selectRow"
                />
            </q-card-section>

            <q-card-actions align="right">
                <q-btn color="primary" flat :label="$t('actions.cancel')" @click="onDialogCancel" />
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>

<script setup lang="ts">

import { useDialogPluginComponent } from 'quasar'
import {useI18n} from "vue-i18n";
import {money} from "../../Common/helpers";
import {usePagination} from "../../Composables/pagination";

const props = defineProps<{
    quotes: any
}>()

defineEmits([
    ...useDialogPluginComponent.emits
])

const i18n = useI18n()

const { dialogRef, onDialogHide, onDialogOK, onDialogCancel } = useDialogPluginComponent()

const {pagination, filter, onRequest} = usePagination(props.quotes, [
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
        sortable: true,
        filterable: true,
        label: i18n.t('fields.date'),
        field: 'date'
    },
    {
        name: 'client',
        required: true,
        sortable: true,
        filterable: true,
        label: i18n.t('fields.client'),
        field: row => row.project?.client_name ?? row.project?.client?.name ?? "",
        classes: 'product-col'
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
        name: 'commercial',
        required: true,
        sortable: true,
        filterable: true,
        label: i18n.t('fields.commercial'),
        field: row => row.commercial.name
    },
])

function selectRow(event, row, index) {
    onDialogOK(row)
}
</script>
