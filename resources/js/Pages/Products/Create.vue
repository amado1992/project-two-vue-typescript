<template>

    <Head :title="$t('products.create')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="construction" />
            </q-avatar>
            {{ $t('products.create') }}
        </template>

        <div class="container">
            <q-card class="tw-p-6">
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
import {showNotification} from "../../Common/helpers";
import {useI18n} from "vue-i18n";
import Select from "@/Components/Select.vue";

const page = usePage()

const i18n = useI18n();

const productCategories = page.props.productCategories
const types = page.props.types

const form = useForm({
    name: '',
    active: true,
    product_category_id: null,
    type: null,
    cost_price: 0,
    daily_price: 0,
    weekly_price: 0,
    biweekly_price: 0,
    monthly_price: 0,
    replacement_price: 0,
    tax: page.props.settings?.tax_itbms ?? 0
})

function submit() {
    form.post(route('products.store'), {
        preserveScroll: true,
        onError: () => {
            showNotification('negative', i18n.t('messages.validation_error'), 'top-right')
        }
    })
}
</script>
