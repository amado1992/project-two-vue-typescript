<template>
    <Head :title="$t('re_rents.details')"/>
    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="shopping_bag" />
            </q-avatar>
            {{ $t('re_rents.details') }}
        </template>

        <div class="tw-absolute tw-w-full tw-z-10" v-if="reRent.cancelled_at">
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

                <PrimaryButton class="tw-z-20" icon="done" @click="finish" v-show="reRent.crud_permissions.finish">
                    {{ $t('actions.finish') }}
                </PrimaryButton>
                <PrimaryButton
                    class="tw-z-20"
                    icon="home_work"
                    :to="route('re-rents.returns', {re_rent: reRent.id})"
                >
                    {{ $t('contracts.returns.title') }}
                </PrimaryButton>
                <PrimaryButton
                    class="tw-z-20"
                    icon="edit"
                    :to="route('re-rents.edit', {re_rent: reRent.id})"
                    v-show="reRent.crud_permissions.edit"
                >
                    {{ $t('actions.edit') }}
                </PrimaryButton>
                <PrimaryButton
                    class="tw-z-20"
                    icon="cancel"
                    @click="cancel"
                    v-if="reRent.crud_permissions.cancel"
                >
                    {{ $t('actions.annular') }}
                </PrimaryButton>
                <PrimaryButton
                    class="tw-z-20"
                    icon="delete"
                    @click="remove"
                    v-if="reRent.crud_permissions.delete"
                >
                    {{ $t('actions.delete') }}
                </PrimaryButton>
            </div>

            <div class="tw-mt-3" v-if="reRent.cancelled_at">
                <div class="text-negative">
                    <strong>{{ $t('fields.date_annulled') }}</strong>{{ ': ' + reRent.cancelled_at }}
                </div>
                <div class="text-negative">
                    <strong>{{ $t('fields.annulled_by') }}</strong>{{ ': ' + reRent.cancelled_by.name }}
                </div>
                <div class="text-negative" v-if="reRent.cancelled_reason">
                    <strong>{{ $t('fields.reason') }}</strong>{{ ': ' + reRent.cancelled_reason }}
                </div>
            </div>

            <q-card class="tw-mt-5">
                <q-card-section>
                    <div class="tw-grid tw-grid-cols-2 tw-gap-5 tw-text-[12px]">
                        <dl>
                            <dt class="tw-font-semibold">{{ $t('fields.id') }}</dt>
                            <dd>{{ reRent.id }}</dd>
                            <dt class="tw-font-semibold">{{ $t('fields.from') }}</dt>
                            <dd>{{ reRent.start }}</dd>
                            <dt class="tw-font-semibold">{{ $t('fields.to') }}</dt>
                            <dd>{{ reRent.finish }}</dd>
                            <dt class="tw-font-semibold">{{ $t('fields.provider') }}</dt>
                            <dd>{{ reRent.provider.name }}</dd>
                        </dl>

                        <dl>
                            <dt class="tw-font-semibold">{{ $t('fields.tax_exempt') }}</dt>
                            <dd>
                                <q-toggle v-model="reRent.tax_exempt" disable />
                            </dd>
                            <dt class="tw-font-semibold">{{ $t('fields.status') }}</dt>
                            <dd>
                                <span class="tw-p-1 tw-rounded tw-text-white" :class="status(reRent.status, i18n)[1]">
                                    {{ status(reRent.status, i18n)[0] }}
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
                    <th class="text-right">{{ $t('fields.returned') }}</th>
                    <th class="text-right">{{ $t('fields.discount_without_symbol') }}</th>
                    <th class="text-right">{{ $t('fields.subtotal') }}</th>
                    <th class="text-right">{{ $t('fields.tax') }}</th>
                    <th class="text-right">{{ $t('fields.total') }}</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="product in reRent.products">
                    <td class="text-right">{{ product.id }}</td>
                    <td class="text-right product-col">{{ product.name }}</td>
                    <td class="text-right">{{ money(product.pivot.price) }}</td>
                    <td class="text-right">{{ product.pivot.quantity }}</td>
                    <td class="text-right">{{ product.pivot.returned }}</td>
                    <td class="text-right">{{ percentage(product.pivot.discount) }}</td>
                    <td class="text-right">{{ money(product.pivot.subtotal) }}</td>
                    <td class="text-right">{{ money(product.tax) }}</td>
                    <td class="text-right">{{ money(product.pivot.total) }}</td>
                </tr>
                </tbody>
            </q-markup-table>

            <q-card class="tw-mt-5">
                <q-card-section>
                    <div class="tw-flex tw-justify-between">

                        <p>{{ reRent.observations }}</p>

                        <ContractibleSummary
                            :summary="{
                                discount: reRent.discount,
                                tax: reRent.tax,
                                subtotal: reRent.subtotal,
                                total: reRent.total
                            }"
                        />
                    </div>

                    <Creators
                        :created_at="reRent.created_at"
                        :created_by="reRent.created_by?.name"
                        :updated_at="reRent.updated_at"
                        :updated_by="reRent.updated_by?.name"
                    />

                    <div class="tw-mt-2" v-show="reRent.finished_at">
                        <span class="bg-black tw-rounded tw-p-1 tw-text-white tw-text-sm creator-tag">
                            <q-icon name="done" />{{ $t('fields.finished') }}
                        </span>
                        {{ $t('messages.created_msg', {name: reRent.finished_by?.name, date: reRent.finished_at}) }}
                    </div>
                </q-card-section>
            </q-card>

            <div class="tw-flex tw-justify-end tw-mt-5">
                <SecondaryButton to="re-rents.index">{{ $t('actions.back') }}</SecondaryButton>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">

import {Head, router} from "@inertiajs/vue3";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import route from "ziggy-js";
import {status, money, percentage} from "../../Common/helpers";
import {useI18n} from "vue-i18n";
import ContractibleSummary from "../../Components/ContractibleSummary.vue";
import Creators from "../../Components/Creators.vue";
import SecondaryButton from "../../Components/SecondaryButton.vue";
import {useQuasar} from "quasar";

const props = defineProps<{
    reRent: any
}>()

const i18n = useI18n()
const $q = useQuasar()

function finish() {

    router.get(route('re-rents.finish', {re_rent: props.reRent.id}), undefined, {
        preserveScroll: true
    })
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

        const url = route('re-rents.cancel', {
            re_rent: props.reRent.id
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

        const url = route('re-rents.destroy', {
            re_rent: props.reRent.id,
            redirect_to: route('re-rents.index')
        })
        router.delete(url)
    })
}
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
</style>
