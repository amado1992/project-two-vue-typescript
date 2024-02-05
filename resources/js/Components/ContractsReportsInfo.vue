<template>
    <q-table class="fixed-column-table" :columns="columns" :rows="clientes" row-key="name">

        <template #body-cell-actions="props">
            <q-td class="q-table--col-auto-width text-center">
                <PrimaryButton flat icon="info" @click="getData(props.row)" class="center"> <q-tooltip>Detalles</q-tooltip>
                </PrimaryButton>
            </q-td>
        </template>
    </q-table>
</template>

<script setup lang="ts">

import { InertiaForm, useForm, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import { useI18n } from "vue-i18n";
import { date, useDialogPluginComponent, useQuasar } from "quasar";
import PrimaryButton from "./PrimaryButton.vue";
import DetailsDialog from "../Pages/Reports/Contracts/DetailsDialog.vue";
import axios from "axios";
import { all } from "axios";



const page = usePage()
const i18n = useI18n()
const $q = useQuasar()

const props = defineProps<{
    clientes: Array,
    products: Array,
    projects: Array
    form: InertiaForm<{
        clientes: [],
        products: []
        projects: []
    }>
}>()

const allProducts = page.props.products
const products = ref(allProducts)

const loadingDetails = ref(false)

const columns = [
    {
        name: 'name',
        required: true,
        sortable: true,
        filterable: true,
        label: i18n.t('fields.client'),
        field: 'name'
    },
    {
        name: 'contracts_quantity',
        required: true,
        sortable: true,
        filterable: true,
        label: i18n.t('fields.contracts_qty'),
        field: 'contracts_quantity',
    },
    {
        name: 'quantity',
        required: true,
        sortable: true,
        filterable: true,
        label: i18n.t('fields.products_qty'),
        field: 'quantity',
    },
    {
        name: 'total_month_price',
        required: true,
        sortable: true,
        filterable: true,
        label: i18n.t('fields.price'),
        field: 'total_month_price',
        data: 'total_month_price'

    },
    {
        name: 'actions',
        label: i18n.t('actions.title'),
        field: null
    }

]

const payload = ref([])
const client = ref([])

function getData(row) {

    axios.post("/reports/contracts/details", {
        client: row.id, products: props.form.products, projects: props.form.projects
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

    const data = payload.value.contracts as []

    /*   if (!data.length) {
  
          $q.dialog({
              title: i18n.t('messages.no_data'),
              message: i18n.t('messages.no_data_msg')
          })
      } else { */
    console.log(payload.value.newclient[0])
    return $q.dialog({
        component: DetailsDialog,
        componentProps: {
            newclient: payload.value.newclient,
            products: payload.value.products,
            contracts: payload.value.contracts,
            count: 0,
        }
    }).onOk(() => {
        console.log('OK')

    })
    /*   } */
}

</script>
