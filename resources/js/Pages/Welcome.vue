<template>
    <Head title="Welcome" />

    <AuthenticatedLayout>
        <div class="q-pa-md">
            <div>
                <div class="text-h5 tw-py-3" v-html="getHeader()"></div>
            </div>

            <q-separator />

            <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-5 lg:tw-gap-5 tw-mt-7">
                <div class="tw-col-span-2">
                    <!--User card-->
                    <UserData :user="user" v-if="! user.client"/>
                </div>
            </div>

            <ClientData :user="user" :data="data" v-if="user.client" />
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">

import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from "../Layouts/AuthenticatedLayout.vue";
import {Contract} from "../Models/Contract";
import {useI18n} from "vue-i18n";
import {Quote} from "../Models/Quote";
import {ServerPaginator} from "laravel-quasar-table/dist/types/src/ServerPaginator";
import UserData from "./UserData.vue";
import ClientData from "./ClientData.vue";

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
        active_contracts: ServerPaginator<Contract[]>,
        contracts: ServerPaginator<Contract[]>
        pending_quotes: ServerPaginator<Quote[]>,
        quotes: ServerPaginator<Quote[]>,
        pending_invoices: ServerPaginator<any[]>,
        invoices: ServerPaginator<any[]>
    }
}>()

const i18n = useI18n()

function getHeader() {

    let id
    let name

    if (props.user.client) {

        id = props.user.client.id
        name = props.user.client.name
    } else {

        id = props.user.id
        name = props.user.name
    }

    return `<strong>${id}</strong> - ${name}`
}
</script>

