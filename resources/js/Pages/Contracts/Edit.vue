<template>
    <Head :title="$t('contracts.edit')"/>

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="description" />
            </q-avatar>
            {{ $t('contracts.edit') }}
        </template>

        <div class="container">
            <q-card class="tw-p-6">
                <q-card-section>
                    <div class="tw-flex tw-justify-end tw-items-center tw-gap-5">
                        <span v-show="selectedQuote">
                            {{ '(' + $t('fields.id') + ': ' + selectedQuote?.id + ') ' + $t('fields.client') + ': ' + selectedQuote?.project?.client?.name  }}
                        </span>
                        <PrimaryButton icon="request_quote" @click="openQuotes">{{ $t('quotes.title') }}</PrimaryButton>
                        <SecondaryButton icon="download" @click="download">{{ $t('actions.download') }}</SecondaryButton>
                    </div>

                    <div class="md:tw-grid md:tw-grid-cols-3 md:tw-gap-5">
                        <TextInput
                            v-model="form.date"
                            :label="$t('fields.start_date')"
                            v-model:errors="form.errors.date"
                            required
                            :disable="! contract.crud_permissions.update_date"
                        >
                            <template v-slot:append>
                                <q-icon name="event" class="cursor-pointer">
                                    <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                                        <DatePicker v-model="form.date" />
                                    </q-popup-proxy>
                                </q-icon>
                            </template>
                        </TextInput>
                        <Select :label="$t('fields.client')"
                        v-model="form.client_id"
                         :options="$page.props.clients"
                          option-label="name"
                       option-value="id"
                       v-model:errors="form.errors.client_id"
                       required @inputValues="clientChange" />
                        <div class="tw-flex tw-items-center tw-gap-3">
                            <Select
                                class="tw-w-full"
                                v-model="form.project_id"
                                :label="$t('fields.project')"
                                :options="projectsByClientId"
                                option-label="name"
                                option-value="id"
                                v-model:errors="form.errors.project_id"
                                required
                                :loading="loadingProjects"
                            />

                            <PrimaryButton flat icon="add" @click="addProject"/>
                        </div>

                        <Select
                            v-model="periodProxy"
                            :label="$t('fields.period')"
                            :options="periods"
                            option-label="label"
                            option-value="value"
                            v-model:errors="form.errors.period"
                            required
                        />

                        <Select
                            v-model="form.user_id"
                            :label="$t('fields.seller')"
                            :options="users"
                            option-label="name"
                            option-value="id"
                            @filter="onFilterUsers"
                            input-debounce="100"
                            use-input
                            v-model:errors="form.errors.user_id"
                            required
                        />

                        <TextInput
                            :label="$t('fields.warranty_deposit')"
                            v-model="form.warranty_deposit"
                            type="number"
                            step="any"
                            v-model:errors="form.errors.warranty_deposit"
                            required
                        />

                        <TextInput
                            :label="$t('fields.legal_representative')"
                            v-model="form.legal_representative"
                            v-model:errors="form.errors.legal_representative"
                            required
                        />

                        <TextInput
                            :label="$t('fields.legal_representative_id')"
                            v-model="form.legal_representative_id"
                            v-model:errors="form.errors.legal_representative_id"
                            required
                        />

                        <q-toggle
                            v-model="taxExemptProxy"
                            :label="$t('fields.tax_exempt')"
                            :disable="form.quote_id != null"
                        />
                    </div>

                    <ContractibleProductTable
                        class="tw-mt-3"
                        :form="form"
                        ref="table"
                        :products="products"
                    />

                    <div class="tw-flex tw-justify-end">
                        <ContractibleProductsSummary :products="form.products" />
                    </div>

                    <Creators
                        class="tw-mt-3"
                        :created_at="contract.created_at"
                        :created_by="contract.created_by.name"
                        :updated_at="contract.updated_at"
                        :updated_by="contract.updated_by?.name"
                    />
                </q-card-section>
                <q-card-actions align="right">
                    <SecondaryButton to="contracts.index">
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

import {Head, router} from "@inertiajs/vue3";
import {useForm, usePage} from "@inertiajs/vue3";
import {useI18n} from "vue-i18n";
import {useQuasar} from "quasar";
import {computed, ref} from "vue";
import ContractibleProductTable from "../../Components/ContractibleProductTable.vue";
import route from "ziggy-js";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import TextInput from "../../Components/TextInput.vue";
import Select from "../../Components/Select.vue";
import ContractibleProductsSummary from "../../Components/ContractibleProductsSummary.vue";
import SecondaryButton from "../../Components/SecondaryButton.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import {buildContractibleProducts, periods as getPeriods} from "../../Common/helpers";
import {Quote} from "../../Models/Quote";
import QuotesDialog from "./QuotesDialog.vue";
import Creators from "../../Components/Creators.vue";
import DatePicker from "../../Components/DatePicker.vue";
import CreateProjectDialog from "../Projects/CreateDialog.vue";
import {Contract} from "../../Models/Contract";
import {Product} from "../../Models/Product";

const props = defineProps<{
    contract: Contract,
    users: any[],
    clients: any[],
    quotes: any[],
    projects: any[],
    products: Product[]
}>()

const i18n = useI18n()
const $q = useQuasar()

const form = useForm({
    date: props.contract.date,
    client_id: props.contract.client?.id || props.contract.project?.client?.id || null,
    project_id: props.contract.project.id,
    user_id: props.contract.commercial.id,
    quote_id: props.contract.quote?.id,
    period: props.contract.period,
    tax_exempt: props.contract.tax_exempt,
    warranty_deposit: props.contract.warranty_deposit,
    legal_representative: props.contract.legal_representative,
    legal_representative_id: props.contract.legal_representative_id,
    products: buildContractibleProducts(props.contract.products)
})

const table = ref<ContractibleProductTable|null>()

const periods = getPeriods(i18n)

const loadingProjects = ref(false)

const allUsers = props.users as {id: number, name: string}[]
const users = ref(allUsers)

const allClients = props.clients as {id: number, name: string}[]
const clients = ref(allClients)

const selectedQuote = ref<Quote|null>(props.contract.quote)

const periodProxy = computed<number>({
    get: () => form.period,
    set: v => {
        form.period = v
        table.value?.refresh(form.period)
    }
})

const taxExemptProxy = computed<boolean>({
    get: () => form.tax_exempt,
    set: v => {
        form.tax_exempt = v
        table.value?.refresh(form.period)
    }
})

function onFilterUsers(val, update) {

    if (val == '') {

        update(() => {
            users.value = allUsers
        })
    } else {

        update(() => {
            const needle = val.toLowerCase()
            users.value = allUsers.filter(v => v.name.toLowerCase().indexOf(needle) > -1)
        })
    }
}

function openQuotes() {

    $q.dialog({
        component: QuotesDialog,
        componentProps: {
            quotes: props.quotes
        }
    }).onOk((quote: Quote) => {

        selectedQuote.value = quote
        form.quote_id = quote.id
        form.date = quote.date
        form.project_id = quote.project.id
        form.period = quote.period
        form.user_id = quote.commercial.id
        form.tax_exempt = quote.tax_exempt
        form.products = buildContractibleProducts(quote.products)

        reloadProjects()
    })
}

function addProject() {

    $q.dialog({
        component: CreateProjectDialog,
        componentProps: {
            clients: props.clients
        }
    }).onOk(payload => {

        loadingProjects.value = true

        reloadProjects(() => {
            form.project_id = payload.id
        }, () => {
            loadingProjects.value = false
        })
    })
}

function download() {

    window.location.href = route('contracts.download', {contract: props.contract.id})
}

function reloadProjects(onSuccess?: () => void, onFinish?: () => void) {

    let data = null

    if (selectedQuote.value) {
        data = {

            client_id: selectedQuote.value?.project?.client?.id
        }
    }

    router.reload({
        only: ['projects'],
        preserveScroll: true,
        data: data,
        onSuccess: () => {

            if (onSuccess) {

                onSuccess()
            }
        },
        onFinish: () => {

            if (onFinish) {

                onFinish()
            }
        }
    })
}

function submit() {
    form.put(route('contracts.update', {contract: props.contract.id}))
}

function clientChange(e:any){
        form.project_id = null
    }

const projectsByClientId = computed(() => {

 if (form.client_id == null) return page.props.projects
 return props.projects.filter(project => project.client_id == form.client_id)
})

</script>

