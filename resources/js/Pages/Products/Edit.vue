<template>

    <Head :title="$t('products.edit')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="construction" />
            </q-avatar>
            {{ $t('products.edit') }}
        </template>

        <div class="container">
            <q-card class="tw-p-6">
                <q-card-section>
                    <div class="tw-flex tw-items-center tw-gap-5">
                        <div class="tw-w-full">
                            <div class="text-h6">{{ $t('inventories.mesu_title') }}</div>
                            <q-separator />
                            <div class="tw-grid tw-grid-cols-2 lg:tw-grid-cols-3 tw-mt-1">
                                <span>{{ $t('fields.total_inventory') + ': ' + $page.props.product.inventory.quantity }}</span>
                                <span>{{ $t('fields.stock') + ': ' + $page.props.product.inventory.stock }}</span>
                                <span>{{ $t('fields.re_rented') + ': ' + $page.props.product.inventory.rented }}</span>
                            </div>
                        </div>

                        <div>
                            <PrimaryButton icon="search" @click="fetchActiveContracts" :loading="fetchingActiveContracts">
                                <q-tooltip>
                                    {{ $t('products.active_contracts') }}
                                </q-tooltip>
                            </PrimaryButton>
                        </div>
                    </div>

                    <div class="tw-flex tw-items-center tw-gap-5 tw-mt-5">
                        <div class="tw-w-full">
                            <div class="text-h6">{{ $t('inventories.rented_title') }}</div>
                            <q-separator />
                            <div class="tw-grid tw-grid-cols-2 lg:tw-grid-cols-3 tw-mt-1">
                                <span>{{ $t('fields.total_re_rented') + ': ' + $page.props.product.inventory.re_quantity }}</span>
                                <span>{{ $t('fields.stock') + ': ' + $page.props.product.inventory.re_stock }}</span>
                                <span>{{ $t('fields.in_contract') + ': ' + $page.props.product.inventory.re_rented }}</span>
                            </div>
                        </div>

                        <div>
                            <PrimaryButton icon="search" @click="fetchReRents" :loading="fetchingReRents">
                                <q-tooltip>
                                    {{ $t('products.re_rents_history') }}
                                </q-tooltip>
                            </PrimaryButton>
                        </div>
                    </div>

                </q-card-section>
            </q-card>

            <q-card class="tw-p-6 tw-mt-5">
                <form @submit.prevent="submit" novalidate>
                    <q-card-section>
                        <div class="md:tw-grid md:tw-grid-cols-3 md:tw-gap-5">
                            <TextInput
                                v-model="form.name"
                                :label="$t('fields.name')"
                                v-model:errors="form.errors.name"
                                :required="true"
                            />

                            <Select
                                v-model="form.product_category_id"
                                :label="$t('fields.product_category')"
                                :options="productCategories"
                                option-label="name"
                                option-value="id"
                                v-model:errors="form.errors.product_category_id"
                                :required="true"
                            />

                            <Select
                                v-model="form.type"
                                :label="$t('fields.type')"
                                :options="types"
                                option-label="name"
                                option-value="id"
                                v-model:errors="form.errors.type"
                                :required="true"
                            />

                            <TextInput
                                v-model.number="form.cost_price"
                                type="number"
                                step="0.01"
                                min="0"
                                :label="$t('fields.cost_price')"
                                v-model:errors="form.errors.cost_price"
                                :required="true"
                            />

                            <TextInput
                                v-model.number="form.daily_price"
                                type="number"
                                step="0.01"
                                min="0"
                                :label="$t('fields.daily_price')"
                                v-model:errors="form.errors.daily_price"
                                :required="true"
                            />

                            <TextInput
                                v-model.number="form.weekly_price"
                                type="number"
                                step="0.01"
                                min="0"
                                :label="$t('fields.weekly_price')"
                                v-model:errors="form.errors.weekly_price"
                                :required="true"
                            />

                            <TextInput
                                v-model.number="form.biweekly_price"
                                type="number"
                                step="0.01"
                                min="0"
                                :label="$t('fields.biweekly_price')"
                                v-model:errors="form.errors.biweekly_price"
                                :required="true"
                            />

                            <TextInput
                                v-model.number="form.monthly_price"
                                type="number"
                                step="0.01"
                                min="0"
                                :label="$t('fields.monthly_price')"
                                v-model:errors="form.errors.monthly_price"
                                :required="true"
                            />

                            <TextInput
                                v-model.number="form.replacement_price"
                                type="number"
                                step="0.01"
                                min="0"
                                :label="$t('fields.replacement_price')"
                                v-model:errors="form.errors.replacement_price"
                                :required="true"
                            />

                            <TextInput
                                v-model.number="form.tax"
                                type="number"
                                step="0.01"
                                min="0"
                                :label="$t('fields.tax_percent')"
                                v-model:errors="form.errors.tax"
                                :required="true"
                            />


                            <q-toggle
                                v-model="form.active"
                                checked-icon="check"
                                unchecked-icon="clear"
                                :label="$t('fields.active')"
                                :errors="form.errors.active"
                            />

                        </div>

                    </q-card-section>
                    <q-card-actions align="right">
                        <SecondaryButton to="products.index">
                            {{ $t('actions.cancel') }}
                        </SecondaryButton>
                        <PrimaryButton type="submit" :loading="form.processing">
                            {{ $t('actions.ok') }}
                        </PrimaryButton>
                    </q-card-actions>
                </form>
            </q-card>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">

import {Head, useForm, usePage} from "@inertiajs/vue3";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import TextInput from "../../Components/TextInput.vue";
import SecondaryButton from "../../Components/SecondaryButton.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import route from "ziggy-js";
import {useI18n} from "vue-i18n";
import {showNotification} from "../../Common/helpers";
import Select from "@/Components/Select.vue";
import {useQuasar} from "quasar";
import {ref} from "vue";
import axios from "axios";
import ActiveContracts from "./ActiveContracts.vue";
import ReRents from './ReRents.vue';

const props = defineProps<{
    product
}>()
const page = usePage()
const i18n = useI18n()
const $q = useQuasar()

const productCategories = page.props.productCategories
const types = page.props.types

const form = useForm({
    name: props.product.name,
    active: props.product.active,
    product_category_id: props.product.product_category_id,
    type: props.product.type,
    cost_price: props.product.cost_price,
    daily_price: props.product.daily_price,
    weekly_price: props.product.weekly_price,
    biweekly_price: props.product.biweekly_price,
    monthly_price: props.product.monthly_price,
    replacement_price: props.product.replacement_price,
    tax: props.product.tax
})

const fetchingActiveContracts = ref(false)
const fetchingReRents = ref(false)

function submit() {

    form.put(route('products.update', {
        product: page.props.product.id
    }), {
        preserveScroll: true,
        onError: () => {
            showNotification('negative', i18n.t('messages.validation_error'), 'top-right')
        }
    })
}

function fetchActiveContracts() {

    fetchingActiveContracts.value = true

    axios.get(route('products.active-contracts', {product: props.product.id}))
        .then(response => {

            const data = response.data as []


            if (! data) {

                $q.dialog({
                    title: i18n.t('messages.no_data'),
                    message: i18n.t('messages.no_data_msg')
                })
            } else {

                $q.dialog({
                    component: ActiveContracts,
                    componentProps: {
                        product: props.product.name,
                        contractProducts: data,
                        product_tax:props.product.tax ?? 0
                    }
                })
            }
        })
        .finally(() => {
            fetchingActiveContracts.value = false
        })
}

function fetchReRents() {

    fetchingReRents.value = true

    axios.get(route('products.re-rents', {product: props.product.id}))
        .then(response => {

            const data = response.data as []

            console.log(data)

            if (! data.length) {

                $q.dialog({
                    title: i18n.t('messages.no_data'),
                    message: i18n.t('messages.no_data_msg')
                })
            } else {

                $q.dialog({
                    component: ReRents,
                    componentProps: {
                        name: props.product.name,
                        productReRents: data
                    }
                })
            }
        })
        .finally(() => {
            fetchingReRents.value = false
        })
}
</script>
