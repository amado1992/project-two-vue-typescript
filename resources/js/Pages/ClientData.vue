<template>
    <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-5 lg:tw-gap-5 tw-mt-7">
        <div class="tw-col-span-2">
            <!--User card-->
            <UserData :user="user" />

            <!--Client card-->
            <q-card class="tw-mt-5">
                <q-card-section>
                    <div class="text-h6">{{ $t('fields.client') }}</div>

                    <q-separator />

                    <div class="tw-grid tw-grid-cols-2">
                        <strong>{{ $t('fields.id') + ':' }}</strong>
                        <span>{{ user.client.id }}</span>
                        <strong>{{ $t('fields.enterprise') + ':' }}</strong>
                        <span>{{ user.client.name }}</span>
                        <strong>{{ $t('fields.ruc') + '/' + $t('fields.cedula') + ':'}}</strong>
                        <span>{{ user.client.ruc + '/' + user.client.cedula}}</span>
                        <strong>{{ $t('fields.dv') + ':' }}</strong>
                        <span>{{ user.client.dv }}</span>
                        <strong>{{ $t('fields.legal_representative') + ':' }}</strong>
                        <span>{{ user.client.legal_representative }}</span>
                        <strong>{{ $t('fields.email') + ':' }}</strong>
                        <span>{{ user.client.email }}</span>
                        <strong>{{ $t('fields.mobile') + ':' }}</strong>
                        <span>{{ user.client.mobile }}</span>
                        <strong>{{ $t('fields.phone') + ':' }}</strong>
                        <span>{{ user.client.phone }}</span>
                        <strong>{{ $t('fields.address') + ':' }}</strong>
                        <span>{{ user.client.address }}</span>
                        <strong>{{ $t('messages.apply_taxes') + ':' }}</strong>
                        <span>
                            <q-icon size="25px" name="check_circle" v-show="! user.client.no_taxes" color="primary"/>
                            <q-icon size="25px" name="cancel" v-show="user.client.no_taxes" color="negative" />
                        </span>
                    </div>
                </q-card-section>
            </q-card>
        </div>

        <div class="tw-col-span-3 tw-mt-5 lg:tw-mt-0">
            <div class="tw-flex tw-justify-between tw-gap-8">
                <ClientDataCard
                    class="tw-bg-green-500"
                    :label="$t('fields.balance')"
                    :value="money(data.balance)"
                />

                <ClientDataCard
                    class="tw-bg-pink-500"
                    :label="$t('fields.invoiced')"
                    :value="money(data.invoice_total)"
                />

                <ClientDataCard
                    class="tw-bg-blue-500"
                    :label="$t('bonos.credit_notes')"
                    :value="money(user.client.credit)"
                />

                <ClientDataCard
                    class="tw-bg-yellow-500"
                    :label="$t('payments.title')"
                    :value="money(data.payment_total)"
                />
            </div>

            <!--Contracts-->
            <q-card class="tw-mt-5">
                <q-card-section>
                    <q-tabs v-model="tabContracts" indicator-color="primary" active-color="primary" align="left">
                        <q-tab
                            name="active_contracts"
                            :label="$t('contracts.actives', {total: data.active_contracts.pagination.total})"
                        />
                        <q-tab name="contracts" :label="$t('contracts.history')"/>
                    </q-tabs>
                    <q-tab-panels v-model="tabContracts" animated>
                        <q-tab-panel name="active_contracts">
                            <q-table
                                class="tw-shadow-none"
                                :columns="activeContractsPagination.pagination.value.columns"
                                :rows="activeContractsPagination.pagination.value.data"
                                :pagination="activeContractsPagination.pagination.value"
                                @request="activeContractsPagination.onRequest"
                            >
                                <template #top-right>
                                    <PrimaryButton
                                        icon="add"
                                        v-if="$can('create_contracts')"
                                        :to="route('contracts.create', {client_id: user.client.id})"
                                    />
                                </template>
                                <template #body-cell-id="props">
                                    <td class="text-right">
                                        <Link
                                            v-if="props.row.crud_permissions.edit"
                                            class="text-primary"
                                            :href="route('contracts.show', {contract: props.row.id})"
                                        >
                                            {{ props.row.id }}
                                        </Link>
                                        <span v-else>{{ props.row.id }}</span>
                                    </td>
                                </template>
                            </q-table>
                        </q-tab-panel>
                        <q-tab-panel name="contracts">
                            <q-table
                                class="tw-shadow-none"
                                :columns="contractsPagination.pagination.value.columns"
                                :rows="contractsPagination.pagination.value.data"
                                :pagination="contractsPagination.pagination.value"
                                @request="contractsPagination.onRequest"
                            >
                                <template #top-right>
                                    <PrimaryButton icon="add" v-if="$can('create_contracts')" to="contracts.create" />
                                </template>
                                <template #body-cell-id="props">
                                    <td class="text-right">
                                        <Link
                                            v-if="props.row.crud_permissions.edit"
                                            class="text-primary"
                                            :href="route('contracts.show', {contract: props.row.id})"
                                        >
                                            {{ props.row.id }}
                                        </Link>
                                        <span v-else>{{ props.row.id }}</span>
                                    </td>
                                </template>
                            </q-table>
                        </q-tab-panel>
                    </q-tab-panels>
                </q-card-section>
            </q-card>

            <!--Quotes-->
            <q-card class="tw-mt-5">
                <q-card-section>
                    <q-tabs v-model="tabQuotes" indicator-color="primary" active-color="primary" align="left">
                        <q-tab name="pending_quotes" :label="$t('quotes.pending', {total: data.pending_quotes.pagination.total})" />
                        <q-tab name="quotes" :label="$t('quotes.history')" />
                    </q-tabs>
                    <q-tab-panels v-model="tabQuotes" animated>
                        <q-tab-panel name="pending_quotes">
                            <q-table
                                class="tw-shadow-none"
                                :columns="pendingQuotesPagination.pagination.value.columns"
                                :rows="pendingQuotesPagination.pagination.value.data"
                                :pagination="pendingQuotesPagination.pagination.value"
                                @request="pendingQuotesPagination.onRequest"
                            >
                                <template #top-right>
                                    <PrimaryButton icon="add" v-if="$can('create_quotes')" to="quotes.create" />
                                </template>
                                <template #body-cell-id="props">
                                    <td class="text-right">
                                        <Link
                                            v-if="props.row.crud_permissions.edit"
                                            class="text-primary"
                                            :href="route('quotes.edit', {quote: props.row.id})"
                                        >
                                            {{ props.row.id }}
                                        </Link>
                                        <span v-else>{{ props.row.id }}</span>
                                    </td>
                                </template>
                            </q-table>
                        </q-tab-panel>
                        <q-tab-panel name="quotes">
                            <q-table
                                class="tw-shadow-none"
                                :columns="quotesPagination.pagination.value.columns"
                                :rows="quotesPagination.pagination.value.data"
                                :pagination="quotesPagination.pagination.value"
                                @request="quotesPagination.onRequest"
                            >
                                <template #top-right>
                                    <PrimaryButton icon="add" v-if="$can('create_quotes')" to="quotes.create"/>
                                </template>
                                <template #body-cell-id="props">
                                    <td class="text-right">
                                        <Link
                                            v-if="props.row.crud_permissions.edit"
                                            class="text-primary"
                                            :href="route('quotes.edit', {quote: props.row.id})"
                                        >
                                            {{ props.row.id }}
                                        </Link>
                                        <span v-else>{{ props.row.id }}</span>
                                    </td>
                                </template>
                            </q-table>
                        </q-tab-panel>
                    </q-tab-panels>
                </q-card-section>
            </q-card>

            <!--Invoices-->
            <q-card class="tw-mt-5">
                <q-card-section>
                    <q-tabs v-model="tabInvoices" indicator-color="primary" active-color="primary" align="left">
                        <q-tab name="pending_invoices" :label="$t('invoices.pending', {total: data.pending_invoices.pagination.total})" />
                        <q-tab name="invoices" :label="$t('invoices.history')" />
                    </q-tabs>
                    <q-tab-panels v-model="tabInvoices" animated>
                        <q-tab-panel name="pending_invoices">
                            <q-table
                                class="tw-shadow-none"
                                :columns="pendingInvoicesPagination.pagination.value.columns"
                                :rows="pendingInvoicesPagination.pagination.value.data"
                                :pagination="pendingInvoicesPagination.pagination.value"
                                @request="pendingInvoicesPagination.onRequest"
                            >
                                <template #body-cell-id="props">
                                    <td class="text-right">
                                        <Link
                                            v-if="props.row.crud_permissions.read"
                                            class="text-primary"
                                            :href="route('invoices.show', {invoice: props.row.id})"
                                        >
                                            {{ props.row.id }}
                                        </Link>
                                        <span v-else>{{ props.row.id }}</span>
                                    </td>
                                </template>
                            </q-table>
                        </q-tab-panel>
                        <q-tab-panel name="invoices">
                            <q-table
                                class="tw-shadow-none"
                                :columns="invoicesPagination.pagination.value.columns"
                                :rows="invoicesPagination.pagination.value.data"
                                :pagination="invoicesPagination.pagination.value"
                                @request="invoicesPagination.onRequest"
                            >
                                <template #body-cell-id="props">
                                    <td class="text-right">
                                        <Link
                                            v-if="props.row.crud_permissions.read"
                                            class="text-primary"
                                            :href="route('invoices.show', {invoice: props.row.id})"
                                        >
                                            {{ props.row.id }}
                                        </Link>
                                        <span v-else>{{ props.row.id }}</span>
                                    </td>
                                </template>
                            </q-table>
                        </q-tab-panel>
                    </q-tab-panels>
                </q-card-section>
            </q-card>
        </div>
    </div>
</template>

<script setup lang="ts">

import {Link} from "@inertiajs/vue3";
import {Contract} from "../Models/Contract";
import {ServerPaginator} from "laravel-quasar-table/dist/types/src/ServerPaginator";
import {Quote} from "../Models/Quote";
import UserData from "./UserData.vue";
import {usePagination} from "laravel-quasar-table";
import {money} from '../Common/helpers';
import ClientDataCard from "../Components/ClientDataCard.vue";
import route from 'ziggy-js';
import {useI18n} from "vue-i18n";
import {onMounted, ref} from "vue";
import PrimaryButton from "../Components/PrimaryButton.vue";

const props = defineProps<{
    user: {
        id: number,
        name: string,
        lastname: string,
        email: string,
        client?: {
            id: number,
            name: string,
            ruc: string,
            dv: number,
            phone: string,
            mobile: string,
            email: string,
            address: string,
            legal_representative: string,
            cedula: string,
            credit: number,
            no_taxes: boolean
        }
    },
    data: {
        invoice_total: number,
        bono_total: number,
        payment_total: number,
        balance: number,
        active_contracts: ServerPaginator<Contract[]>,
        contracts: ServerPaginator<Contract[]>
        pending_quotes: ServerPaginator<Quote[]>,
        quotes: ServerPaginator<Quote[]>,
        pending_invoices: ServerPaginator<any[]>,
        invoices: ServerPaginator<any[]>
    }
}>()

const i18n = useI18n()

const tabContracts = ref('active_contracts')
const tabQuotes = ref('pending_quotes')
const tabInvoices = ref('pending_invoices')

const contractColumns = [
    {
        name: 'id',
        sortable: true,
        label: i18n.t('fields.id')
    },
    {
        name: 'date',
        sortable: true,
        label: i18n.t('fields.start_date'),
        field: 'date'
    },
    {
        name: 'project',
        label: i18n.t('fields.project'),
        field: row => row.project_name,
        classes: 'product-col'
    },
    {
        name: 'total',
        label: i18n.t('fields.total'),
        field: row => money(row.total)
    },
    {
        name: 'commercial',
        label: i18n.t('fields.commercial'),
        field: row => row.commercial_name
    }
]

const activeContractsPagination = usePagination(props.data.active_contracts, contractColumns)

const contractsPagination = usePagination(props.data.contracts, contractColumns)

const quoteColumns = [
    {
        name: "id",
        sortable: true,
        label: i18n.t('fields.id')
    },
    {
        name: "date",
        sortable: true,
        label: i18n.t('fields.date'),
        field: 'date'
    },
    {
        name: 'project',
        label: i18n.t('fields.project'),
        field: row => row.project.name,
        classes: 'product-col'
    },
    {
        name: 'subtotal',
        label: i18n.t('fields.subtotal'),
        field: row => money(row.subtotal)
    },
    {
        name: 'tax',
        label: i18n.t('fields.tax'),
        field: row => money(row.tax)
    },
    {
        name: 'total',
        label: i18n.t('fields.total'),
        field: row => money(row.total)
    },
    {
        name: 'commercial',
        label: i18n.t('fields.commercial'),
        field: row => row.commercial.name
    }
]

const pendingQuotesPagination = usePagination(props.data.pending_quotes, quoteColumns)

const quotesPagination = usePagination(props.data.quotes, quoteColumns)

const invoiceColumns = [
    {
        name: 'id',
        sortable: true,
        label: i18n.t('fields.id')
    },
    {
        name: 'subtotal',
        label: i18n.t('fields.subtotal'),
        field: row => money(row.subtotal)
    },
    {
        name: 'discount',
        label: i18n.t('fields.discount_without_symbol'),
        field: row => money(row.discount)
    },
    {
        name: 'tax',
        label: i18n.t('fields.tax'),
        field: row => money(row.taxes)
    },
    {
        name: 'total',
        label: i18n.t('fields.total'),
        field: row => money(row.total)
    }
]

const invoicesPagination = usePagination(props.data.invoices, invoiceColumns)

const pendingInvoicesPagination = usePagination(props.data.pending_invoices, invoiceColumns)

onMounted(() => {
    console.log(props.data.active_contracts)
})
</script>
