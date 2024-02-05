<template>
    <Head :title="$t('quotes.create')"/>

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="request_quote" />
            </q-avatar>
            {{ $t('quotes.create') }}
        </template>

        <div class="container">
            <q-card class="tw-p-6">
                <q-card-section>
                    <div class="tw-flex tw-justify-end tw-mt-2">
                        <SecondaryButton
                            icon="download"
                            @click="exportDespiece2pdf()"
                            :disabled="form.products.length === 0"
                        >
                            Exportar lista de despiece
                        </SecondaryButton>
                    </div>
                    <QuoteForm
                        :form="form"
                        :projects="projects"
                        :products="products"
                        :clients="clients"
                        :show-approve="can('approve_quotes', $page.props)"
                    />
                </q-card-section>
                <q-card-actions align="right">
                    <SecondaryButton to="quotes.index">
                        {{ $t('actions.cancel') }}
                    </SecondaryButton>
                    <PrimaryButton @click="submit" :loading="form.processing">
                        {{ $t('actions.ok') }}
                    </PrimaryButton>
                </q-card-actions>
            </q-card>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">

import {Head, useForm} from "@inertiajs/vue3";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import SecondaryButton from "../../Components/SecondaryButton.vue";
import route from "ziggy-js";
import {date, useQuasar} from "quasar";
import {Product} from "../../Models/Product";
import QuoteForm from "./QuoteForm.vue";
import {can} from '../../Common/helpers';
import {exportPdf} from "../../Composables/dowload";

const props = defineProps<{
    projects: any[],
    products: Product[],
    clients: any[]
}>()
const $q = useQuasar()
const form = useForm({
    date: date.formatDate(new Date(), 'YYYY-MM-DD'),
    client_id: null,
    project_id: null,
    period: 1,
    tax_exempt: false,
    approved: false,
    products: [],
    observations: ''
})

function submit() {
    form.post(route('quotes.store'), {
        preserveScroll: true
    })
}

function exportDespiece2pdf(){

    $q.dialog({
        title:"Exportar lista de despiece",
        message:"Se exportará la lista de despiece." +
            " Los datos que se exporten dependerán de lo que usted tenga registrado en el formulario",
        cancel:true
    })
        .onOk(() => {

            exportPdf(route('quote.store.despiece'),{
                client_id: form.client_id,
                project_id: form.project_id,
                products: form.products
            })
        });
}
</script>
