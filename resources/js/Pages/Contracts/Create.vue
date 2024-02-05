<template>
    <Head :title="$t('contracts.create')"/>

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="description" />
            </q-avatar>
            {{ $t('contracts.create') }}
        </template>

        <div class="container">
            <q-card class="tw-p-6">
                <q-card-section>
                    <div class="tw-flex tw-justify-end tw-items-center tw-gap-5">
                        <PrimaryButton icon="request_quote" @click="openQuotes">{{ $t('quotes.title') }}</PrimaryButton>
                    </div>

                    <div class="tw-mt-2 md:tw-grid md:tw-grid-cols-2 xl:tw-grid-cols-3 md:tw-gap-5">
                        <TextInput
                            v-model="form.date"
                            :label="$t('fields.start_date')"
                            v-model:errors="form.errors.date"
                            required
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
                        :products="$page.props.products"
                    />

                    <div class="tw-flex tw-justify-end">
                        <ContractibleProductsSummary :products="form.products" />
                    </div>

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
import {date, useQuasar} from "quasar";
import {computed, ref} from "vue";
import ContractibleProductTable from "../../Components/ContractibleProductTable.vue";
import route from "ziggy-js";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import TextInput from "../../Components/TextInput.vue";
import Select from "../../Components/Select.vue";
import ContractibleProductsSummary from "../../Components/ContractibleProductsSummary.vue";
import SecondaryButton from "../../Components/SecondaryButton.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import QuotesDialog from "./QuotesDialog.vue";
import {Quote} from "../../Models/Quote";
import {buildContractibleProducts, periods as getPeriods, showNotification} from "../../Common/helpers";
import DatePicker from "../../Components/DatePicker.vue";
import CreateProjectDialog from '../Projects/CreateDialog.vue';

const page = usePage()
const i18n = useI18n()
const $q = useQuasar()

const form = useForm({
    date: date.formatDate(new Date(), 'YYYY-MM-DD'),
    user_id: page.props.auth?.user?.id,
    period: null,
    tax_exempt: false,
    warranty_deposit: 0,
    legal_representative: '',
    legal_representative_id: '',
    quote_id: null,
    project_id: null,
    client_id: null,
    products: []
})

const table = ref<ContractibleProductTable|null>()

const periods = getPeriods(i18n)

const loadingProjects = ref(false)

const allUsers = page.props.users as {id: number, name: string}[]
const users = ref(allUsers)

const allClients = page.props.clients as {id: number, name: string}[]
const clients = ref(allClients)

const selectedQuote = ref<Quote|null>(null)

const periodProxy = computed<number|null>({
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

function reloadProjects(onSuccess?: () => void, onFinish?: () => void) {

    let data = {}

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

function openQuotes() {

    $q.dialog({
        component: QuotesDialog,
        componentProps: {
            quotes: page.props.quotes
        }
    }).onOk((quote: Quote) => {

        selectedQuote.value = quote
        form.quote_id = quote.id
        form.date = quote.date
        form.period = quote.period
        form.user_id = quote.commercial.id
        form.project_id = quote.project.id
        form.tax_exempt = quote.tax_exempt
        form.products = buildContractibleProducts(quote.products)
        form.client_id = quote.client_id ?? quote.project?.client_id ?? ""

        reloadProjects()
    })
}

function addProject() {

    $q.dialog({
        component: CreateProjectDialog,
        componentProps: {
            clients: page.props.clients
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

function submit() {
    form.post(route('contracts.store'), {
        preserveScroll: true,
        onError: () => {
            showNotification('negative', i18n.t('messages.validation_error'), 'top-right')
        }
    })
}

function clientChange(e:any){
        form.project_id = null
    }

const projectsByClientId = computed(() => {

 if (form.client_id == null) return page.props.projects
 return page.props.projects.filter(project => project.client_id == form.client_id)
})
</script>
