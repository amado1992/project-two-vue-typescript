<template>
    <Head :title="$t('re_rents.edit')" />
    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="shopping_bag" />
            </q-avatar>
            {{ $t('re_rents.edit') }}
        </template>

        <div class="container">
            <q-card class="tw-p-6">
                <q-card-section>
                    <div class="md:tw-grid md:tw-grid-cols-3 md:tw-gap-5">
                        <Select
                            v-model="form.provider_id"
                            :label="$t('fields.provider')"
                            :options="$page.props.providers"
                            option-label="name"
                            option-value="id"
                            v-model:errors="form.errors.provider_id"
                            required
                        />
                        <TextInput
                            v-model="form.start"
                            :label="$t('fields.from')"
                            v-model:errors="form.errors.start"
                            required
                        >
                            <template v-slot:append>
                                <q-icon name="event" class="cursor-pointer">
                                    <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                                        <DatePicker v-model="form.start" v-model:errors="form.errors.start" />
                                    </q-popup-proxy>
                                </q-icon>
                            </template>
                        </TextInput>

                        <TextInput
                            v-model="form.finish"
                            :label="$t('fields.to')"
                            v-model:errors="form.errors.finish"
                            required
                        >
                            <template v-slot:append>
                                <q-icon name="event" class="cursor-pointer">
                                    <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                                        <DatePicker v-model="form.finish" v-model:errors="form.errors.finish" />
                                    </q-popup-proxy>
                                </q-icon>
                            </template>
                        </TextInput>

                        <q-toggle
                            v-model="form.tax_exempt"
                            :label="$t('fields.tax_exempt')"
                        />
                    </div>

                    <ContractibleProductTable
                        class="tw-mt-3"
                        :form="form"
                        :products="$page.props.products"
                        required
                    />

                    <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 tw-gap-5">
                        <TextInput
                            :label="$t('fields.observations')"
                            type="textarea"
                            v-model="form.observations"
                            v-model:errors="form.errors.observations"
                        />

                        <div class="tw-flex tw-justify-end">
                            <ContractibleProductsSummary :products="form.products" />
                        </div>
                    </div>
                </q-card-section>
                <q-card-actions align="right">
                    <SecondaryButton to="re-rents.index">
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

import {Head, useForm, usePage} from "@inertiajs/vue3";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import TextInput from "../../Components/TextInput.vue";
import Select from "../../Components/Select.vue";
import ContractibleProductTable from "../../Components/ContractibleProductTable.vue";
import {computed, ref} from "vue";
import SecondaryButton from "../../Components/SecondaryButton.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import route from "ziggy-js";
import ContractibleProductsSummary from "../../Components/ContractibleProductsSummary.vue";
import {buildContractibleProducts} from "../../Common/helpers";
import DatePicker from "../../Components/DatePicker.vue";

const page = usePage()

const form = useForm({
    provider_id: page.props.reRent.provider_id,
    start: page.props.reRent.start,
    finish: page.props.reRent.finish,
    tax_exempt: page.props.reRent.tax_exempt,
    observations: page.props.reRent.observations,
    products: buildContractibleProducts(page.props.reRent.products),
    period: -1
})

function submit() {
    form.put(route('re-rents.update', {re_rent: page.props.reRent.id}))
}
</script>
