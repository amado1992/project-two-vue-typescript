<template>
    <Head :title="$t('reports.product')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="report" />
            </q-avatar>
            {{ $t('reports.product') }}
        </template>

        <div class="container">

            <q-card class="tw-p-6">

                <ReportsHeader />

                <q-separator inset />

                <q-card-section class="tw-mt-4">

                    <div class="md:tw-grid md:tw-grid-cols-2 md:tw-gap-5">

                        <Select v-model="form.products" :options="products" @filter="onFilterProducts" option-label="name"
                            option-value="id" multiple :label="$t('reports.select_product')" input-debounce="100" use-input
                            use-chips />

                        <Select v-model="form.clients" :label="$t('fields.clients')" :options="clients" option-label="name"
                            option-value="id" @filter="onFilterClients" multiple use-chips input-debounce="100" use-input />

                    </div>
                </q-card-section>
                <q-card-actions align="right">

                    <div class="tw-flex tw-justify-end tw-items-center tw-gap-5 tw-mr-2">
                        <q-btn-dropdown color="secondary" class="tw-float-right tw-ml-3" no-caps
                            :label="$t('reports.export')">
                            <q-list>
                                <q-item clickable v-close-popup @click="excel">
                                    <q-item-section>
                                        <q-item-label>Excel</q-item-label>
                                    </q-item-section>
                                </q-item>

                                <q-item clickable v-close-popup @click="pdf">
                                    <q-item-section>
                                        <q-item-label>Pdf</q-item-label>
                                    </q-item-section>
                                </q-item>
                            </q-list>
                        </q-btn-dropdown>
                    </div>

                    <PrimaryButton @click="getProducts()" :loading="loading">
                        {{ $t('actions.preview') }}
                    </PrimaryButton>
                </q-card-actions>

            </q-card>

            <!-- General preview about products - component -->
            <div v-if="productos.length !== 0" class="tw-mt-5 tw-full">
                <ProductsReportsInfo :form="form" :productos="productos" :clients="$page.props.clients" />
            </div>

            <!--  <div v-show="!productos.length">No hay productos asociados a los datos de clientes filtrados.</div> -->

        </div>

    </AuthenticatedLayout>
</template>

<script setup lang="ts">

import { Head } from "@inertiajs/vue3";
import { useForm, usePage } from "@inertiajs/vue3";
import { useI18n } from "vue-i18n";
import { date, useQuasar } from "quasar";
import { computed, ref } from "vue";
import route from "ziggy-js";
import AuthenticatedLayout from "../../../Layouts/AuthenticatedLayout.vue";
import Select from "../../../Components/Select.vue";
import PrimaryButton from "../../../Components/PrimaryButton.vue";
import ProductsReportsInfo from "../../../Components/ProductsReportsInfo.vue";
import axios from "axios";
import ReportsHeader from "../../../Components/ReportsHeader.vue";
import { showNotification } from "../../../Common/helpers";

const page = usePage()
const i18n = useI18n()
const $q = useQuasar()

const form = useForm({
    clients: [],
    products: [],
})

const productos = ref([])
const loading = ref(false)

const allProducts = page.props.products as { id: number, name: string }[]
const products = ref(allProducts)

const allClients = page.props.clients as { id: number, name: string }[]
const clients = ref(allClients)

function onFilterProducts(val, update) {

    if (val == '') {

        update(() => {
            products.value = allProducts
        })
    } else {

        update(() => {
            const needle = val.toLowerCase()
            products.value = allProducts.filter(v => v.name.toLowerCase().indexOf(needle) > -1)
        })
    }
}

function onFilterClients(val, update) {

    if (val == '') {

        update(() => {
            clients.value = allClients
        })
    } else {

        update(() => {
            const needle = val.toLowerCase()
            clients.value = allClients.filter(v => v.name.toLowerCase().indexOf(needle) > -1)
        })
    }
}

async function getProducts() {

    loading.value = true
    await axios.post("/reports/products/preview", {
        products: form.products,
        clients: form.clients,
        _token: page.props.csrf_token
    })
        .then(response => {
            productos.value = response.data.data
            loading.value = false
            console.log(productos)
        })
        .catch(function (error) {
            console.log(error);
        });

    if (productos.value.length === 0) {
        loading.value = false
        showNotification('warning', i18n.t('messages.no_results'), 'top-right')
    }

    return productos

}

function excel() {
    axios.post("/reports/products/excel", {
        clients: form.clients,
        products: form.products,
    }, {
        responseType: 'blob'
    }).then((response) => {
        const url = URL.createObjectURL(new Blob([response.data], {
            type: 'application/vnd.ms-excel'
        }))
        const link = document.createElement('a')
        link.href = url
        let fileName = `ReporteProductos.xlsx`
        link.setAttribute('download', fileName)
        document.body.appendChild(link)
        link.click()
    }).catch((error) => {
        console.log(error)
    })
}

function pdf() {

    axios.post("/reports/products/download", {
        clients: form.clients,
        products: form.products,
        _token: page.props.csrf_token,
    }, {
        responseType: 'blob'
    }).then((response) => {
        const url = URL.createObjectURL(new Blob([response.data], {
            type: 'application/pdf'
        }))
        const link = document.createElement('a')
        link.href = url
        let fileName = `ReporteProductos.pdf`
        link.setAttribute('download', fileName)
        document.body.appendChild(link)
        link.click()
    }).catch((error) => {
        console.log(error)
    })
}

</script>
