<template>
    <Head :title="$t('reports.product')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="report" />
            </q-avatar>
            {{ $t("reports.reRent") }}
        </template>

        <div class="container">
            <q-card class="tw-p-6">
                <ReportsHeader />

                <q-separator inset />

                <q-card-section class="tw-mt-4">
                    <div class="md:tw-grid md:tw-grid-cols-2 md:tw-gap-5">
                        <Select
                            multiple
                            v-model="form.provider"
                            :label="$t('providers.title')"
                            :options="providers"
                            option-label="name"
                            option-value="id"
                            input-debounce="100"
                            use-input
                            use-chips
                            @inputValues="inputSelect"
                        />

                        <Select
                            v-model="form.products"
                            :options="products"
                            option-label="name"
                            option-value="id"
                            multiple
                            :label="$t('reports.select_product')"
                            input-debounce="100"
                            use-input
                            use-chips
                            @inputValues="inputSelect"

                        />
                    </div>
                </q-card-section>
                <q-card-actions align="right">
                    <div
                        class="tw-flex tw-justify-end tw-items-center tw-gap-5 tw-mr-2"
                    >
                        <q-btn-dropdown
                            color="green"
                            class="tw-float-right tw-ml-3"
                            no-caps
                            :label="$t('reports.export')"
                        >
                            <q-list>
                                <q-item clickable v-close-popup @click="exportReport2excel">
                                    <q-item-section>
                                        <q-item-label>Excel</q-item-label>
                                    </q-item-section>
                                </q-item>

                                <q-item clickable v-close-popup @click="exportReport2pdf">
                                    <q-item-section>
                                        <q-item-label>Pdf</q-item-label>
                                    </q-item-section>
                                </q-item>
                            </q-list>
                        </q-btn-dropdown>
                    </div>

                    <PrimaryButton
                        @click="getReportResult()"
                        :loading="loading"
                    >
                        {{ $t("actions.preview") }}
                    </PrimaryButton>
                </q-card-actions>

                <q-table
                    v-if="reportProviders.length > 0"
                    class="tw-mt-10 tw-p-5"
                    flat
                    bordered
                    :rows="reportProviders"
                    :columns="columnsReport"
                    row-key="name"
                >
                    <template #body-cell-actions="props">
                        <q-td class="q-table--col-auto-width text-center">
                            <q-btn
                                flat
                                icon="info"
                                @click="getData(props.row)"
                                class="center"
                                color="primary"
                                ><q-tooltip>Detalles</q-tooltip>
                            </q-btn>
                        </q-td>
                    </template>
                </q-table>
            </q-card>
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
import axios from "axios";
import ReportsHeader from "../../../Components/ReportsHeader.vue";
import { columnsReport } from "./columns.ts";
import ProductProviderTable from "./ProductProviderTable.vue";
import {exportPdf,exportExcel} from "../../../Composables/dowload.ts"

const page = usePage();
const i18n = useI18n();
const $q = useQuasar();

const form = useForm({
    provider:  [],
    products: [],
});

const allProducts = page.props.products as { id: number; name: string }[];
const products = ref(allProducts);
const loading = ref(false);

const allProviders = page.props.providers as { id: number; name: string }[];
const providers = ref(allProviders);

const reportProviders = ref([]);

async function getProducts() {
    const url = route("products.rerents.providers", {
        providers: form.provider,
    });
    loading.value = true;
    await axios
        .get(url, {
            _token: page.props.csrf_token,
        })
        .then((response) => {
            products.value = response.data;
            loading.value = false;
            console.log(products);
        })
        .catch(function (error) {
            loading.value = false;
            products.value = [];
            console.log(error);
        });
}

async function getReportResult() {
    const url = route("reports.reRents.all");
    loading.value = true;
    await axios
        .post(url, {

                provider:form.provider,
                products:form.products,
            _token: page.props.csrf_token,
        })
        .then((response) => {
            console.log(response);
            reportProviders.value = response.data;
            loading.value = false;
        })
        .catch(function (error) {
            loading.value = false;
            reportProviders.value = [];
            console.log(error);
        });
}

async function getData(provider) {
    const url = route("reports.reRents.product.provider");
    loading.value = true;
    console.log(form.products);
    await axios
        .get(url, {
            params: {
                provider_id: provider.id,
                products:form.products
            },
            _token: page.props.csrf_token,
        })
        .then((response) => {

            $q.dialog({
                component: ProductProviderTable,
                componentProps: {
                    provider:provider.name,
                    products: response.data,
                },
            }).onOk(() => {

            });
            loading.value = false;
        })
        .catch(function (error) {
            loading.value = false;
            console.log(error);
        });
}

function exportReport2pdf(){
    const filename = date.formatDate(new Date(Date.now()),'YYYY/MM/DD')
    exportPdf(
        route('reports.reRents.pdf'),
        {provider:form.provider},
        `Reporte Equipos Realquilados[${filename}].pdf`

    )
}

function exportReport2excel(){
    const filename = date.formatDate(new Date(Date.now()),'YYYY/MM/DD')
    exportExcel(
        `Reporte Equipos Realquilados[${filename}].xlsx`,
        route('reports.reRents.excel'),
        {provider:form.provider}
    )
}

function inputSelect(){
    reportProviders.value = []
}
</script>
