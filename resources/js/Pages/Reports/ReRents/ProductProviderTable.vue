<template>
    <q-dialog ref="dialogRef" @hide="onDialogHide" full-width >
    <q-card style="width: 800px; max-width: 100vw;">
     <h6 class="tw-mt-10 tw-ml-3 tw-mb-3 tw-font-light"> PROVEEDOR: {{ provider }} </h6>
    <q-table
        class="tw-mt-10 tw-p-5"
        style="height: 400px"
        flat
        bordered
        :rows="products"
        :columns="columnsReportProducts"
        :rows-per-page-options="[0]"
        row-key="name"/>

        <q-card-actions align="right">
            <q-btn color="primary" flat :label="$t('actions.close')" @click="onDialogCancel" />
        </q-card-actions>
    </q-card>
</q-dialog>
</template>

<script setup lang="ts">
import { columnsReportProducts } from "./columns.ts";
import { useDialogPluginComponent } from 'quasar';

const props = defineProps<{
    products: any,
    provider: string
}>()
defineEmits([
    ...useDialogPluginComponent.emits
])

const { dialogRef, onDialogHide, onDialogOK, onDialogCancel } = useDialogPluginComponent()
</script>
