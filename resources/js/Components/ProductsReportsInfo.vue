<template>
  
    <q-table class="fixed-column-table" :columns="columns" :rows="productos" row-key="name">
        <template #body-cell-actions="props">
            <q-td class="q-table--col-auto-width text-center">
                <q-btn flat icon="info" @click="getData(props.row)" class="center" color="primary"><q-tooltip>Detalles</q-tooltip>
                </q-btn>
            </q-td>
        </template>
    </q-table>
    
</template>

<script setup lang="ts">

import { Head, InertiaForm, router, useForm, usePage } from '@inertiajs/vue3';
import { ref } from "vue";
import { useI18n } from "vue-i18n";
import DetailsDialog from '../Pages/Reports/Products/DetailsDialog.vue';
import axios from 'axios';
import { useQuasar } from 'quasar';

//Services
const i18n = useI18n()
const page = usePage()
const $q = useQuasar()

const props = defineProps<{
    clients: Array,
    productos: Array
    form: InertiaForm<{
        clients: [],
        productos: []
    }>
}>()

const columns = [
    {
        name: 'name',
        required: true,
        sortable: true,
        filterable: true,
        label: i18n.t('fields.name'),
        field: 'name',
        classes: 'tw-max-w-sm tw-text-ellipsis tw-overflow-hidden'
    },

    {
        name: 'quantity',
        required: true,
        sortable: true,
        filterable: true,
        label: i18n.t('reports.products.rented'),
        field: 'quantity',
    },

    {
        name: 'stock',
        required: true,
        sortable: true,
        filterable: true,
        label: i18n.t('fields.stock'),
        field: 'stock'
    },

    {
        name: 'actions',
        label: i18n.t('actions.title')
    }

]


const payload = ref([])

function getData(row) {

    axios.post("/reports/products/details", {
        product: row.id,
        clients: props.form.clients
    
    })
        .then(response =>
            response.data.data)

        .then(data => {
            payload.value = data
        })

        .then(() =>
            getDetails(payload)
        )

        .catch(function (error) {
            console.log(error);
        });

    return payload
}

async function getDetails(payload) {

    const data = payload.value.values as []

    if (!data.length) {

        $q.dialog({
            title: i18n.t('messages.no_data'),
            message: i18n.t('messages.no_data_msg')
        })
    } else {

        $q.dialog({
            component: DetailsDialog,
            componentProps: {
                values: payload.value.values,
                product: payload.value.product,
            }
        }).onOk(() => {

            console.log('OK')

        })
    }
}

</script>
