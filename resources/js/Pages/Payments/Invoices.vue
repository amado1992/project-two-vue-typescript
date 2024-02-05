<template>
    <Select
        class="tw-w-full tw-mb-3"
        v-model="selectedInvoices"
        :options="invoices"
        option-label="name"
        option-value="id"
        :label="$t('payments.select_invoices')"
        :hint="$t('payments.select_invoices_hint')"
        use-chips
        multiple
        :loading="loadingInvoices"
        :required="required">
        <template #append>
            <DangerButton icon="sync" flat @click="fetchInvoices" v-show="showRetry" />
        </template>
    </Select>
    
    <span v-if="props.credit < 0" style="color: #c41023;font-weight: bold;">
        {{ $t('payments.invalid_credit') }}
    </span>

    <TransitionGroup name="invoices" tag="div">
        <div
            class="tw-bg-white tw-rounded-lg tw-shadow-lg tw-mb-2 tw-p-3"
            v-for="(invoice, index) in form.invoices"
            :key="index"
        >
            <div class="tw-flex tw-w-full tw-justify-between tw-gap-5">
                <span>
                    <strong>{{ $t('fields.invoice') + ': ' }}</strong>
                    {{ invoice.id }}
                </span>
                <span>
                    <strong>{{ $t('fields.contract') + ': ' }}</strong>
                    {{ invoice.contract }}
                </span>
                <span>
                    <strong>{{ $t('fields.project') + ': ' }}</strong>
                    {{ invoice.project }}
                </span>
                <span :class="{'text-negative': invoice.per_to_pay - invoice.credit < 0}">
                    <strong>{{ $t('fields.per_to_pay') + ': ' }}</strong>
                    {{ money(invoice.per_to_pay - invoice.credit) }}
                </span>
                <span>
                    <strong>{{ $t('fields.total') + ': ' }}</strong>
                    {{ money(invoice.total) }}
                </span>
            </div>

            <div class="tw-w-full tw-flex tw-items-center tw-gap-2 tw-mt-2">
                <div class="tw-w-full tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 xl:tw-grid-cols-3 tw-gap-5">
                    <TextInput
                        :label="$t('fields.amount')"
                        type="number"
                        step="any"
                        v-model="invoice.credit"
                        dense
                        required
                        min="0"
                        :max="invoice.per_to_pay"
                        v-model:errors="form.errors['invoices.' + index + '.credit']"
                    />
                </div>
                <div>
                    <q-btn icon="delete" color="negative" flat @click="removeInvoice(invoice.id)" />
                </div>
            </div>
        </div>
    </TransitionGroup>
</template>

<script setup lang="ts">

import {InertiaForm} from "@inertiajs/vue3";
import Select from "../../Components/Select.vue";
import axios from "axios";
import route from "ziggy-js";
import {computed, ref} from "vue";
import {money} from '../../Common/helpers';
import TextInput from "../../Components/TextInput.vue";
import SecondaryButton from "../../Components/SecondaryButton.vue";
import DangerButton from "../../Components/DangerButton.vue";

const props = defineProps<{
    form: InertiaForm<{
        client_id: number|null,
        invoices: any[]
    }>,
    required?: boolean
    credit: number
}>()

const showRetry = ref(false)
const loadingInvoices = ref(false)
let lastClient = props.form.client_id
const _invoices = ref([])
const invoices = computed(() => {

    if (lastClient != props.form.client_id) {
        props.form.invoices = []
        fetchInvoices()
    }


    lastClient = props.form.client_id

    return _invoices.value
})

const selectedInvoices = computed<number[]>({
    get: () => {
        return props.form.invoices.map(i => i.id)
    },
    set: v => {

        const formInvoices = props.form.invoices

        v.forEach(id => {

            const invoice = invoices.value.find(i => i.id == id)

            if (invoice && ! formInvoices.some(i => i.id == invoice.id)) {

                formInvoices.push({
                    id: invoice.id,
                    contract: invoice.contract.id,
                    project: invoice.contract.project.name,
                    per_to_pay: invoice.per_to_pay,
                    total: invoice.total,
                    credit: 0
                })
            }
        })

        for (let i = 0; i < formInvoices.length; i++) {

            if (! v.some(id => id == formInvoices[i].id)) {

                formInvoices.splice(i, 1)
            }
        }

        props.form.invoices = formInvoices
    }
})

function fetchInvoices() {

    if (props.form.client_id) {

        showRetry.value = false
        loadingInvoices.value = true
        axios.get(route('payments.invoices', {client: props.form.client_id}))
            .then(response => {
                const responseData = response.data
                const options = []
                for (const key in responseData) {
                    if (Object.prototype.hasOwnProperty.call(responseData, key)) {
                        const element = responseData[key];
                        options.push(element)
                    }
                }
                _invoices.value = options as any
            })
            .catch(() => {
                showRetry.value = true
                _invoices.value = []
            })
            .finally(() => {
                loadingInvoices.value = false
            })
    }
}

function removeInvoice(id) {

    const index = props.form.invoices.findIndex(i => i.id == id)

    if (index != -1) {

        props.form.invoices.splice(index, 1)
    }
}
</script>

<style scoped>
.invoices-enter-active,
.invoices-leave-active {
    transition: all 0.5s ease;
}

.invoices-enter-from,
.invoices-leave-to {
    opacity: 0;
    translate: 0 -20px;
}
</style>
