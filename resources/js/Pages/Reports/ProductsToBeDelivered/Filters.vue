<template>
    <Head :title="$t('reports.productsToBeDelivered')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="report" />
            </q-avatar>

            {{ $t('reports.productsToBeDelivered') }}

        </template>

        <div class="container">

            <q-card class="tw-p-6">

                <ReportsHeader />

                <q-separator inset />

                <q-card-section class="tw-mt-4">

                    <div class="md:tw-grid md:tw-grid-cols-2 md:tw-gap-5">

                        <Select v-model="form.clients" :label="$t('fields.clients')" :options="clients" option-label="name"
                            option-value="id" @filter="onFilterClients" multiple use-chips input-debounce="100" use-input />

                    </div>
                </q-card-section>
                <q-card-actions align="right">

                    <div class="tw-flex tw-justify-end tw-items-center tw-gap-5 tw-mr-2">
                        <q-btn-dropdown color="secondary" class="tw-float-right tw-ml-3" no-caps
                            :label="$t('reports.export')" :loading="loading_export">
                            <q-list>
                                <q-item clickable v-close-popup @click="pdf">
                                    <q-item-section>
                                        <q-item-label>Pdf</q-item-label>
                                    </q-item-section>
                                </q-item>
                            </q-list>
                        </q-btn-dropdown>
                    </div>

                    <PrimaryButton @click="getContracts()" :loading="loading">
                        {{ $t('actions.preview') }}
                    </PrimaryButton>
                </q-card-actions>

            </q-card>

            <!-- General preview about products to be delivered - component -->
            <div v-if="data.length !== 0" class="tw-mt-5 tw-full">
                <Preview :form="form" :data="data" />
            </div>

        </div>

    </AuthenticatedLayout>
</template>

<script setup lang="ts">

import { Head } from "@inertiajs/vue3";
import { useForm, usePage } from "@inertiajs/vue3";
import { useI18n } from "vue-i18n";
import { useQuasar } from "quasar";
import { ref } from "vue";
import AuthenticatedLayout from "../../../Layouts/AuthenticatedLayout.vue";
import Select from "../../../Components/Select.vue";
import PrimaryButton from "../../../Components/PrimaryButton.vue";
import axios from "axios";
import ReportsHeader from "../../../Components/ReportsHeader.vue";
import Preview from "./Preview.vue";

const page = usePage()
const i18n = useI18n()
const $q = useQuasar()

const form = useForm({
    clients: [],

})

const title = i18n.t('reports.productsToBeDelivered')
const loading = ref(false)

const allClients = page.props.clients as { id: number, name: string }[]
const clients = ref(allClients)

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

const data = ref([]);

async function getContracts() {

    loading.value = true
    await axios.post("/reports/to-be-delivered/preview", {
        clients: form.clients,
        _token: page.props.csrf_token
    })
        .then(response => {
            data.value = response.data.data
            loading.value = false
            console.log(clients)
        })
        .catch(function (error) {
            console.log(error);
        });

    return data

} 
const loading_export = ref(false);
function pdf() {
    loading_export.value = true
    axios.post("/reports/to-be-delivered/download", {
        clients: form.clients,
        doctitle: title,
    }, {
        responseType: 'blob'
    }).then((response) => {
        const url = URL.createObjectURL(new Blob([response.data], {
            type: 'application/pdf'
        }))
        const link = document.createElement('a')
        link.href = url
        let fileName = `ReporteProductosFaltantesPorEntregar.pdf`
        link.setAttribute('download', fileName)
        document.body.appendChild(link)
        link.click()
        loading_export.value = false
    }).catch((error) => {
        console.log(error)
    })
} 

</script>
