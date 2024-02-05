<template>
    <q-layout class="tw-bg-gray-100" view="lHh LpR fFf">
        <q-ajax-bar
            ref="bar"
            position="top"
            color="primary"
            size="2px"
            skip-hijack
        />
        <q-header elevated class="tw-bg-gray-700 text-white">
            <q-toolbar>
                <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />

                <div class="tw-w-full">
                    <q-btn class="tw-float-right" no-caps flat :label="$p.props.auth?.user?.name" :ripple="false">
                        <q-menu>
                            <q-list style="min-width: 150px">
                                <q-item clickable @click="profile">
                                    <q-item-section>{{ $t('nav.profile') }}</q-item-section>
                                </q-item>
                                <q-separator />
                                <q-item clickable @click="logout">
                                    <q-item-section>{{ $t('actions.logout') }}</q-item-section>
                                </q-item>
                            </q-list>
                        </q-menu>
                    </q-btn>
                </div>
            </q-toolbar>
        </q-header>

        <q-drawer show-if-above v-model="leftDrawerOpen" elevated>

            <Link :href="route('home')">
            <q-toolbar-title class="tw-ml-3">
                <ApplicationLogo class="tw-w-auto tw-h-12  tw-fill-current tw-text-gray-500" />
            </q-toolbar-title>
            </Link>

            <q-separator style="margin-top: 1px" />

            <q-list>

                <template v-for="item in menu">
                    <q-expansion-item :default-opened="isExpanded(item.children)" :label="item.title" :icon="item.icon"
                        :color="item.color" v-if="can(item.can, page.props) && item.children">
                        <template v-for="child in item.children">
                            <q-item :class="{ 'item-active': child.isActive() }" :active="child.isActive()" class="tw-ml-3"
                                clickable v-ripple @click="$inertia.get(route(child.route))"
                                v-if="can(child.can, page.props)">
                                <q-item-section avatar>
                                    <q-icon :name="child.icon" :color="child.isActive() ? 'primary' : child.color" />
                                </q-item-section>
                                <q-item-section>{{ child.title }}</q-item-section>
                            </q-item>
                        </template>
                    </q-expansion-item>
                    <q-item :class="{ 'item-active': item.isActive() }" :active="item.isActive()" clickable v-ripple
                        @click="$inertia.get(route(item.route))" v-if="can(item.can, page.props) && !item.children">
                        <q-item-section avatar>
                            <q-icon :name="item.icon" :color="item.isActive() ? 'primary' : item.color" />
                        </q-item-section>
                        <q-item-section>{{ item.title }}</q-item-section>
                    </q-item>
                </template>
            </q-list>

            <q-list>
                <q-expansion-item :default-opened="isExpanded(reports)" :label="$t('reports.title')" icon="download"
                v-if="can('create_reports', page.props)">
                    <template v-for="item in reports">
                        <q-item :class="{ 'item-active': item.isActive() }" :active="item.isActive()" class="tw-ml-3"
                            clickable v-ripple @click="$inertia.get(route(item.route))">
                            <q-item-section avatar>
                                <q-icon :name="item.icon" :color="item.isActive() ? 'primary' : item.color" />
                            </q-item-section>
                            <q-item-section>{{ item.title }}</q-item-section>
                        </q-item>
                    </template>
                </q-expansion-item>
            </q-list>

            <q-list>
                <q-expansion-item :default-opened="isExpanded(configs)" :label="$t('settings.title')"
                    icon="settings_applications" v-if="can('read_settings', page.props)">
                    <template v-for="item in configs">
                        <q-item :class="{ 'item-active': item.isActive() }" :active="item.isActive()" class="tw-ml-3"
                            clickable v-ripple @click="$inertia.get(route(item.route))" v-if="can(item.can, page.props)">
                            <q-item-section avatar>
                                <q-icon :name="item.icon" :color="item.isActive() ? 'primary' : item.color" />
                            </q-item-section>
                            <q-item-section>{{ item.title }}</q-item-section>
                        </q-item>
                    </template>
                </q-expansion-item>
            </q-list>

        </q-drawer>

        <q-page-container>
            <q-page>
                <div class="tw-bg-white tw-shadow tw-py-4 tw-px-5 tw-mb-3 tw-text-xl tw-font-bold text-primary"
                    v-if="$slots.header">
                    <slot name="header" />
                </div>

                <slot />
            </q-page>
        </q-page-container>

    </q-layout>
</template>

<script setup lang="ts">

import { computed, onMounted, ref, watch } from 'vue'
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { usePage } from "@inertiajs/vue3";
import { router } from '@inertiajs/vue3'
import route from "ziggy-js";
import {QAjaxBar, useQuasar} from "quasar";
import {useI18n} from "vue-i18n";
import {can} from "../Common/helpers";
import {Link} from "@inertiajs/vue3";

const leftDrawerOpen = ref(false)

const appName = ref('Laravel')

const page = usePage()

const $p = computed(() => usePage())

const $q = useQuasar()
const i18n = useI18n()

const bar = ref<QAjaxBar|null>(null)

const configs = [
    {
        title: i18n.t('roles.title'),
        route: 'roles.index',
        icon: 'verified_user',
        color: 'grey-9',
        can: 'read_roles',
        isActive: function () {
            return route().current() === this.route || route().current(splitRoute(this.route) + '.*')
        }
    },
    {
        title: i18n.t('productCategories.title'),
        route: 'productCategories.index',
        icon: 'category',
        color: 'grey-9',
        can: 'read_productCategories',
        isActive: function () {
            return route().current() === this.route || route().current(splitRoute(this.route) + '.*')
        }
    },
    {
        title: i18n.t('companies.title'),
        route: 'companies.index',
        icon: 'store',
        color: 'grey-9',
        can: 'read_companies',
        isActive: function () {
            return route().current() === this.route || route().current(splitRoute(this.route) + '.*')
        }
    },
    {
        title: i18n.t('settings.general'),
        route: 'settings.index',
        icon: 'settings',
        color: 'grey-9',
        can: 'read_settings',
        isActive: function(){
            return route().current() === this.route
        }
    },
    {
        title: i18n.t('settings.clausules'),
        route: 'settings.clausules.index',
        icon: 'gavel',
        color: 'grey-9',
        can: 'read_settings',
        isActive: function(){
            return route().current() === this.route
        }
    }
]

//Reports menu
const reports = [
    {
        title: i18n.t('reports.products.title'),
        route: 'reports.products.filter',
       /*  icon: 'download', */
        color: 'grey-9',
        can: 'create_reports',
        isActive: function(){
            return route().current() === this.route
        }
    },
    {
        title: i18n.t('reports.contracts.title'),
        route: 'reports.contracts.filter',
        /* icon: 'download', */
        color: 'grey-9',
        can: 'create_reports',
        isActive: function(){
            return route().current() === this.route
        }
    },
    {
        title: i18n.t('reports.earning.title'),
        route: 'reports.earnings.filter',
       /*  icon: 'download', */
        color: 'grey-9',
        can: 'create_reports',
        isActive: function(){
            return route().current() === this.route
        }
    },
    {
        title: i18n.t('reports.productToBeDelivered.title'),
        route: 'reports.productsToBeDelivered.filter',
       /*  icon: 'download', */
        color: 'grey-9',
        can: 'create_reports',
        isActive: function(){
            return route().current() === this.route
        }
    },
    {
        title: i18n.t('reports.reRents.title'),
        route: 'reports.reRents.filter',
        color: 'grey-9',
        can: 'create_reports',
        isActive: function(){
            return route().current() === this.route
        }
    },
]

//Drawer menu
const menu = ref([
    {
        title: i18n.t('nav.home'),
        route: 'home',
        icon: 'home',
        color: 'grey-9',
        isActive: function () {
            return route().current() === this.route || route().current(splitRoute(this.route) + '.*')
        }
    },
    {
        title: i18n.t('nav.dashboard'),
        route: 'dashboard',
        icon: 'dashboard',
        color: 'grey-9',
        can: 'read_dashboard',
        isActive: function () {
            return route().current() === this.route || route().current(splitRoute(this.route) + '.*')
        }
    },
    {
        title: i18n.t('users.title'),
        route: 'users.index',
        icon: 'group',
        color: 'grey-9',
        can: 'read_users',
        isActive: function () {
            return route().current() === this.route || route().current(splitRoute(this.route) + '.*')
        }
    },

    {
        title: i18n.t('products.title'),
        route: 'products.index',
        icon: 'construction',
        color: 'grey-9',
        can: 'read_products',
        isActive: function () {
            return route().current() === this.route || route().current(splitRoute(this.route) + '.*')
        }
    },
    {
        title: i18n.t('inventories.title'),
        icon: 'iso',
        color: 'grey-9',
        can: 'read_inventory',
        children: [
            {
                title: i18n.t('inventories.reasons.title'),
                route: 'reasons.index',
                can: 'read_inventory',
                isActive: function () {
                    return route().current() === this.route || route().current(splitRoute(this.route) + '.*')
                }
            },
            {
                title: i18n.t('inventories.manage'),
                route: 'movements.index',
                can: 'read_inventory',
                isActive: function () {
                    return route().current() === this.route || route().current(splitRoute(this.route) + '.*')
                }
            }
        ],
        isActive: function () {
            return false
        }
    },
    {
        title: i18n.t('clients.title'),
        route: 'clients.index',
        icon: 'switch_account',
        color: 'grey-9',
        can: 'read_clients',
        isActive: function () {
            return route().current() === this.route || route().current(splitRoute(this.route) + '.*')
        }
    },
    {
        title: i18n.t('projects.title'),
        route: 'projects.index',
        icon: 'foundation',
        color: 'grey-9',
        can: 'read_projects',
        isActive: function(){
            return route().current() === this.route || route().current(splitRoute(this.route) + '.*')
        }
    },
    {
        title: i18n.t('providers.title'),
        route: 'providers.index',
        icon: 'warehouse',
        color: 'grey-9',
        can: 'read_providers',
        isActive: function () {
            return route().current() === this.route || route().current(splitRoute(this.route) + '.*')
        }
    },
    {
        title: i18n.t('quotes.title'),
        route: 'quotes.index',
        icon: 'request_quote',
        color: 'grey-9',
        can: 'read_quotes',
        isActive: function () {
            return route().current() === this.route || route().current(splitRoute(this.route) + '.*')
        }
    },
    {
        title: i18n.t('designs.title'),
        route: 'designs.index',
        icon: 'draw',
        color: 'grey-9',
        can: 'read_designs',
        isActive: function () {
            return route().current() === this.route || route().current(splitRoute(this.route) + '.*')
        }
    },
    {
        title: i18n.t('contracts.title'),
        route: 'contracts.index',
        icon: 'description',
        color: 'grey-9',
        can: 'read_contracts',
        isActive: function () {
            return route().current() === this.route || route().current(splitRoute(this.route) + '.*')
        }
    },
    {
        title: i18n.t('re_rents.title'),
        route: 're-rents.index',
        icon: 'shopping_bag',
        color: 'grey-9',
        can: 'read_re_rents',
        isActive: function () {
            return route().current() === this.route || route().current(splitRoute(this.route) + '.*')
        }
    },
    {
        title: i18n.t('bonos.title'),
        route: 'bonos.index',
        icon: 'attach_money',
        color: 'grey-9',
        can: 'read_bonos',
        isActive: function () {
            return route().current() === this.route || route().current(splitRoute(this.route) + '.*')
        }
    },
    {
        title: i18n.t('payments.title'),
        route: 'payments.index',
        icon: 'payments',
        color: 'grey-9',
        can: 'read_payments',
        isActive: function () {
            return route().current() === this.route || route().current(splitRoute(this.route) + '.*')
        }
    },
    {
        title: i18n.t('traces.title'),
        route: 'traces.index',
        icon: 'query_stats',
        color: 'grey-9',
        can: 'read_traces',
        isActive: function () {
            return route().current() === this.route || route().current(splitRoute(this.route) + '.*')
        }
    }
])


//Notifications
watch(() => $p.value.props.flash.notification, value => {

    if (value) {
        $q.notify({
            type: value.type,
            message: value.msg,
            position: 'top-right'
        })

        $p.value.props.flash.notification = null

    }

}, { deep: true })

onMounted(() => {

    const notification = $p.value.props.flash.notification

    if (notification) {

        $q.notify({
            type: notification.type,
            message: notification.msg,
            position: 'top-right'
        })
    }

    router.on('start', () => {
        bar.value?.start()
    })

    router.on('finish', () => {
        bar.value?.stop()
    })
})

const isExpanded = (children: []) => children.some(config => route().current() === config.route || route().current(splitRoute(config.route) + '.*'))


function splitRoute(route) {
    return route.split('.')[0]
}

function toggleLeftDrawer() {
    leftDrawerOpen.value = !leftDrawerOpen.value
}

function profile() {
    router.get(route('profile.edit'))
}

function logout() {
    router.post(route('logout'))
}
</script>
