<template>
    <Head :title="$t('contracts.details')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="description" />
            </q-avatar>
            {{ $t('contracts.details') }}
        </template>

        <div class="tw-absolute tw-w-full tw-z-10" v-if="contract.cancelled_at">
            <div class="tw-flex tw-justify-center tw-mt-72">
                <img
                    class="cancelled tw-h-[120px] lg:tw-h-[200px]"
                    style="transform: rotate(45grad); opacity: 0.7" src="/img/anulado.png"
                    alt="Annulled logo"
                />
            </div>
        </div>

        <div class="container">
            <div class="tw-flex tw-gap-3 tw-flex-col lg:tw-flex-row">
                <PrimaryButton class="tw-z-20" icon="download" @click="download">
                    {{ $t('actions.download') }}
                </PrimaryButton>

                <PrimaryButton
                    class="tw-z-20"
                    icon="local_shipping"
                    :to="route('contracts.travels', {contract: contract.id})"
                >
                    {{ $t('contracts.travels.title') }}
                </PrimaryButton>

                <PrimaryButton class="tw-z-20" icon="play_arrow" @click="startContract" v-show="contract.crud_permissions.start && showStart">
                    {{ $t('actions.start') }}
                </PrimaryButton>
                <PrimaryButton class="tw-z-20" icon="done" @click="finishContract" v-show="contract.crud_permissions.finish">
                    {{ $t('actions.finish') }}
                </PrimaryButton>
                <PrimaryButton
                    class="tw-z-20"
                    icon="home_work"
                    :to="route('contracts.returns', {contract: contract.id})"
                >
                    {{ $t('contracts.returns.title') }}
                </PrimaryButton>
                <PrimaryButton
                    class="tw-z-20"
                    icon="receipt"
                    :to="route('invoices.index', {contract: contract.id})"
                    v-show="contract.crud_permissions.returns"
                >
                    {{ $t('invoices.title') }}
                </PrimaryButton>
                
                <PrimaryButton v-if="computedmakesNewRenovation && statusContract == statusDefeated"
                    class="tw-z-20"
                    icon="update"
                    :to="route('contracts.renovations', {contract: contract.id})"
                    v-show="contract.crud_permissions.renovations"
                >
                    {{ $t('contracts.renovations.title') }}
                </PrimaryButton>

                <PrimaryButton
                    class="tw-z-20"
                    icon="edit"
                    :to="route('contracts.edit', {contract: contract.id})"
                    v-show="contract.crud_permissions.edit"
                >
                    {{ $t('actions.edit') }}
                </PrimaryButton>
                <PrimaryButton
                    class="tw-z-20"
                    icon="cancel"
                    @click="cancel"
                    v-if="contract.crud_permissions.cancel"
                >
                    {{ $t('actions.annular') }}
                </PrimaryButton>
                <PrimaryButton
                    class="tw-z-20"
                    icon="delete"
                    @click="remove"
                    v-if="contract.crud_permissions.delete"
                >
                    {{ $t('actions.delete') }}
                </PrimaryButton>
            </div>

            <div class="tw-mt-3" v-if="contract.cancelled_at">
                <div class="text-negative">
                    <strong>{{ $t('fields.date_annulled') }}</strong>{{ ': ' + contract.cancelled_at }}
                </div>
                <div class="text-negative">
                    <strong>{{ $t('fields.annulled_by') }}</strong>{{ ': ' + contract.cancelled_by.name }}
                </div>
                <div class="text-negative" v-if="contract.cancelled_reason">
                    <strong>{{ $t('fields.reason') }}</strong>{{ ': ' + contract.cancelled_reason }}
                </div>
            </div>

            <q-card class="tw-mt-5">
                <q-card-section>
                    <div class="tw-grid tw-grid-cols-2 md:tw-grid-cols-3 xl:tw-grid-cols-5 tw-gap-5 tw-text-[12px]">
                        <dl>
                            <dt class="tw-font-semibold">{{ $t('fields.id') }}</dt>
                            <dd>{{ contract.id }}</dd>
                            <dt class="tw-font-semibold">{{ $t('fields.period') }}</dt>
                            <dd>{{ period }}</dd>
                            <dt class="tw-font-semibold">{{ $t('fields.person_in_charge') }}</dt>
                            <dd>{{ contract.person_in_charge }}</dd>
                            <dt class="tw-font-semibold">{{ $t('fields.quote_id') }}</dt>
                            <dd>{{ contract.quote_id ? contract.quote_id : 'na' }}</dd>
                        </dl>

                        <dl>
                            <dt class="tw-font-semibold">
                                {{ $t('fields.start_date') }}
                            </dt>
                            <dd>
                                {{ contract.date }}
                                <PrimaryButton
                                    class="tw-p-0 tw-m-0"
                                    flat
                                    dense
                                    icon="edit"
                                    v-if="contract.crud_permissions.update_date"
                                >
                                    <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                                        <DatePicker v-model="form.date" @ok="submit" />
                                    </q-popup-proxy>
                                </PrimaryButton>
                            </dd>
                            <dt
                                class="tw-font-semibold"
                                :class="{'margin-top-0': contract.crud_permissions.update_date}"
                            >{{ $t('fields.from') }}</dt>
                            <dd>{{ from }}</dd>
                            <dt class="tw-font-semibold">{{ $t('fields.person_in_charge_id') }}</dt>
                            <dd>{{ contract.person_in_charge_id }}</dd>
                            <dt class="tw-font-semibold">{{ $t('contracts.renovations.title') }}</dt>
                            <dd>{{ contract.renovations_count }}</dd>
                        </dl>

                        <dl>
                            <dt class="tw-font-semibold">{{ $t('fields.project') }}</dt>
                            <dd>{{ contract.project.name }}</dd>
                            <dt class="tw-font-semibold">{{ $t('fields.renovation_date') }}</dt>
                            <dd>{{ to }}</dd>
                            <dt class="tw-font-semibold">{{ $t('fields.project_address') }}</dt>
                            <dd>{{ contract.project.address }}</dd>
                        </dl>

                        <dl>
                            <dt class="tw-font-semibold">{{ $t('fields.tax_exempt') }}</dt>
                            <dd>
                                <BooleanCell :value="contract.tax_exempt" size="18px" />
                            </dd>
                            <dt class="tw-font-semibold">{{ $t('fields.legal_representative') }}</dt>
                            <dd>{{ contract.legal_representative }}</dd>
                            <dt class="tw-font-semibold">{{ $t('fields.warranty_deposit') }}</dt>
                            <dd>{{ money(contract.warranty_deposit) }}</dd>
                        </dl>

                        <dl>
                            <dt class="tw-font-semibold">{{ $t('fields.seller') }}</dt>
                            <dd>{{ contract.commercial.name }}</dd>
                            <dt class="tw-font-semibold">{{ $t('fields.legal_representative_id') }}</dt>
                            <dd>{{ contract.legal_representative_id }}</dd>
                            <dt class="tw-font-semibold">{{ $t('fields.status') }}</dt>
                            <dd>
                                <span class="tw-p-1 tw-rounded tw-text-white" :class="status(contract.status, i18n)[1]">
                                    {{ status(contract.status, i18n)[0] }}
                                </span>
                            </dd>
                        </dl>
                    </div>
                </q-card-section>
            </q-card>

            <q-markup-table class="tw-mt-5">
                <thead>
                <tr>
                    <th class="text-right">{{ $t('fields.id') }}</th>
                    <th class="text-right">{{ $t('fields.name') }}</th>
                    <th class="text-right">{{ $t('fields.price') }}</th>
                    <th class="text-right">{{ $t('fields.quantity') }}</th>
                    <th class="text-right">{{ $t('fields.mesu_rented_delivered') }}</th>
                    <th class="text-right">{{ $t('fields.mesu_rented_return') }}</th>
                    <th class="text-right">{{ $t('fields.discount_without_symbol') }}</th>
                    <th class="text-right">{{ $t('fields.subtotal') }}</th>
                    <th class="text-right">{{ $t('fields.tax') }}</th>
                    <th class="text-right">{{ $t('fields.total') }}</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="product in contract.products">
                    <td class="text-right">{{ product.id }}</td>
                    <td class="text-right">
                        <ProductName :name="product.name" />
                    </td>
                    <td class="text-right">{{ money(product.pivot.price) }}</td>
                    <td class="text-right">{{ product.pivot.quantity }}</td>
                    <td class="text-right">
                        {{ product.pivot.mesu_delivered + '/' + product.pivot.re_rent_delivered }}
                    </td>
                    <td class="text-right">
                        {{ product.pivot.mesu_return + '/' + product.pivot.re_rent_return }}
                    </td>
                    <td class="text-right">{{ money(product.pivot.discount) + ` (${percentage(product.pivot.percent_discount)})` }}</td>
                    <td class="text-right">{{ money(product.pivot.subtotal) }}</td>
                    <td class="text-right">{{ money(product.pivot.tax) }}</td>
                    <td class="text-right">{{ money(product.pivot.total) }}</td>
                </tr>
                </tbody>
            </q-markup-table>

            <q-card class="tw-mt-5">
                <q-card-section>
                    <div class="tw-flex tw-justify-end">
                        <ContractibleSummary
                            :summary="{
                                discount: contract.discount,
                                tax: totalP,
                                subtotal: contract.subtotal,
                                total: contract.total
                            }"
                        />
                    </div>

                    <ContractCreators :contract="contract" />
                </q-card-section>
            </q-card>

            <div class="tw-flex tw-justify-end tw-mt-5">
                <SecondaryButton to="contracts.index" v-if="can('read_contracts', $page.props)">
                    {{ $t('actions.back') }}
                </SecondaryButton>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">

import {Head, router, useForm} from "@inertiajs/vue3";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import {useI18n} from "vue-i18n";
import {periods, can} from "../../Common/helpers";
import {money, status, percentage} from "../../Common/helpers";
import {useQuasar} from "quasar";
import StartContractDialog from './StartContractDialog.vue';
import route from "ziggy-js";
import {computed, onMounted} from "vue";
import SecondaryButton from "../../Components/SecondaryButton.vue";
import ContractibleSummary from "../../Components/ContractibleSummary.vue";
import ContractCreators from "./ContractCreators.vue";
import DatePicker from "../../Components/DatePicker.vue";
import ProductName from "../../Components/ProductName.vue";
import BooleanCell from "../../Components/BooleanCell.vue";

const props = defineProps<{
    contract
}>()

const i18n = useI18n()
const $q = useQuasar()

const from = computed(() => props.contract.active_at || i18n.t('messages.without_init'))
const to = computed(() => props.contract.expire_at || i18n.t('messages.without_init'))
const period = computed(() => periods(i18n).find(p => p.value == props.contract.period).label)

var statusDefeated: string = "defeated"
var statusContract: string = "";

const computedmakesNewRenovation = computed(() => {
    const data = props.contract;
    let sumReturn: number = 0;
    let sumQuantity: number = 0;
    statusContract = data.status;

    for (let product of data.products) {
        sumReturn += product.pivot.mesu_return + product.pivot.re_rent_return;
        sumQuantity += product.pivot.quantity;
    }

    if (sumReturn < sumQuantity) {
        return true;
    }

    return false;
});

const showStart = computed(() => {
    const data = props.contract;
    let sumCarriedByClient: number = 0;
    let sumQuantity: number = 0;

    for (let product of data.products) {
        sumQuantity += product.pivot.quantity;
        sumCarriedByClient += product.pivot.carried_by_client;
    }

    if (sumQuantity == sumCarriedByClient) {
        return true;
    }

    return false;
});

const form = useForm({
    date: props.contract.date
})

function startContract() {

    $q.dialog({
        component: StartContractDialog,
        componentProps: {
            contract: props.contract
        }
    }).onOk(() => {
        reloadContract()
    })
}

function finishContract() {

    router.get(route('contracts.finish', {contract: props.contract.id}), undefined, {
        preserveScroll: true,
        onSuccess: () => {

            reloadContract()
        }
    })
}

function download() {
    window.location.href = route('contracts.download', {contract: props.contract.id})
}

function cancel() {

    $q.dialog({
        title: i18n.t('messages.annular_confirmation'),
        message: i18n.t('messages.annular_confirmation_msg'),
        cancel: true,
        persistent: true,
        prompt: {
            label: i18n.t('fields.reason'),
            model: ''
        }
    }).onOk(payload => {

        const url = route('contracts.cancel', {
            contract: props.contract.id
        })
        router.put(url, {
            reason: payload
        })
    })
}

function remove() {

    $q.dialog({
        title: i18n.t('messages.delete_confirmation'),
        message: i18n.t('messages.delete_confirmation_msg'),
        cancel: true,
        persistent: true
    }).onOk(() => {

        const url = route('contracts.destroy', {
            contract: props.contract.id,
            redirect_to: route('contracts.index')
        })
        router.delete(url)
    })
}

function reloadContract() {

    router.reload({
        only: ['contract']
    })
}

function submit() {

    form.put(route('contracts.update-date', {contract: props.contract.id}), {
        preserveScroll: true
    })
}

const totalP = computed(()=>{
    return props.contract.products.reduce((accumulator, currentValue) => accumulator + Number.parseFloat(currentValue.pivot.tax), 0);
})
</script>

<style scoped>
dl > dd {
    margin-left: 0;
}

dl > dt {
    margin-top: 10px;
}

.cancelled {
    animation: zoomIn 0.8s linear;
}

@keyframes zoomIn {
    0% {
        opacity: 0;
        transform: scale(5);
    }

    90% {
        opacity: 0.5;
    }

    100% {
        opacity: 0.7;
    }
}

.margin-top-0 {
    margin-top: 0 !important;
}
</style>
