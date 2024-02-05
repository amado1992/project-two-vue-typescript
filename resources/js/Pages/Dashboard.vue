<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, usePage} from '@inertiajs/vue3';
import CardBox from "../Components/CardBox.vue";
import {useI18n} from "vue-i18n";
import {can} from "@/Common/helpers";
import route from 'ziggy-js';

const page = usePage()
const i18n = useI18n();

</script>

<template>
    <Head title="Dashboard"/>

    <AuthenticatedLayout>
        <template #header>
            Dashboard
        </template>

        <div class="container">
            <div class="md:tw-grid md:tw-grid-cols-3 md:tw-gap-5">
                <CardBox v-if="can('read_users', $page.props)"
                         :color="'primary'"
                         :icon="'group'"
                         :text="i18n.t('users.title')"
                         :value="page.props.users"
                         :route="route('users.index')"/>
                <CardBox v-if="can('read_clients', $page.props)"
                    :color="'light-green-8'"
                         :icon="'switch_account'"
                         :text="i18n.t('clients.title')"
                         :value="page.props.clients"
                         :route="route('clients.index')"/>
                <CardBox v-if="can('read_providers', $page.props)"
                    :color="'red-14'"
                         :icon="'warehouse'"
                         :text="i18n.t('providers.title')"
                         :value="page.props.providers"
                         :route="route('providers.index')"/>

                <CardBox v-if="can('read_products', $page.props)"
                    :color="'primary'"
                         :icon="'construction'"
                         :text="i18n.t('products.title')"
                         :value="page.props.products"
                         :route="route('products.index')"/>

                <CardBox
                    v-if="can('read_quotes', $page.props)"
                    :route="route('quotes.index')"
                    :text="$t('quotes.title')"
                    :value="$page.props.quotes"
                    icon="request_quote"
                    color="secondary"
                />

                <CardBox
                    v-if="can('read_contracts', $page.props)"
                    :route="route('contracts.index')"
                    :text="$t('contracts.title')"
                    :value="$page.props.contracts"
                    icon="description"
                    color="yellow"
                />

                <CardBox
                    v-if="can('read_projects', $page.props)"
                    :route="route('projects.index')"
                    :text="$t('projects.title')"
                    :value="$page.props.projects"
                    icon="foundation"
                    color="orange"
                />

                <CardBox
                    v-if="can('read_re_rents', $page.props)"
                    :route="route('re-rents.index')"
                    :text="$t('re_rents.title')"
                    :value="$page.props.re_rents"
                    icon="shopping_bag"
                    color="red"
                />

                <CardBox
                    v-if="can('read_designs', $page.props)"
                    :route="route('designs.index')"
                    :text="$t('designs.title')"
                    :value="$page.props.designs"
                    icon="draw"
                    color="blue"
                />

                <CardBox
                    v-if="can('read_inventory', $page.props)"
                    :route="route('movements.index')"
                    :text="$t('inventories.title')"
                    :value="$page.props.movements"
                    icon="iso"
                    color="purple"
                />

                <CardBox
                    v-if="can('read_bonos', $page.props)"
                    :route="route('bonos.index')"
                    :text="$t('bonos.title')"
                    :value="$page.props.bonos"
                    icon="attach_money"
                    color="amber"
                />

                <CardBox
                    class="text-green"
                    v-if="can('read_payments', $page.props)"
                    :route="route('payments.index')"
                    :text="$t('payments.title')"
                    :value="$page.props.payments"
                    icon="payments"
                    color="green"
                />
            </div>

        </div>
    </AuthenticatedLayout>
</template>
