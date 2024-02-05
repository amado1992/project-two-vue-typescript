<template>
    <Head :title="$t('inventories.create')" />

    <AuthenticatedLayout>
        <template #header>
            {{ $t('inventories.create') }}
        </template>

        <div class="container">
            <q-card class="tw-p-6">
                <q-card-section>
                    <div class="tw-mt-2 md:tw-grid xl:tw-grid-cols-4 md:tw-gap-5">
                        <TextInput
                            v-model="form.date"
                            :label="$t('fields.date')"
                            v-model:errors="form.errors.date"
                            required
                        >
                            <template v-slot:append>
                                <q-icon name="event" class="cursor-pointer">
                                    <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                                        <DatePicker v-model="form.date" />
                                    </q-popup-proxy>
                                </q-icon>
                            </template>
                        </TextInput>

                        <div class="tw-flex tw-items-center tw-gap-2 xl:tw-col-span-2">
                            <Select
                                class="tw-w-full"
                                :label="$t('fields.reason')"
                                v-model="form.reason_id"
                                :options="$page.props.reasons"
                                option-label="name"
                                option-value="id"
                                required
                                v-model:errors="form.errors.reason_id"
                            />

                            <div>
                                <PrimaryButton flat icon="add" color="primary" @click="createReason" />
                            </div>
                        </div>

                        <div>
                            <q-radio
                                class="tw-inline-block"
                                v-model="form.type"
                                val="increment"
                                :label="$t('inventories.movements.types.increment')"
                            />


                            <q-radio
                                class="tw-inline-block"
                                v-model="form.type"
                                val="decrement"
                                :label="$t('inventories.movements.types.decrement')"
                            />
                        </div>
                    </div>

                    <ContractibleProductTable
                        :form="form"
                        :products="$page.props.products"
                        useCustomSelect
                    >
                        <template #list="props">
                            <q-list separator>
                                <ProductRow
                                    v-for="(product, index) in props.products"
                                    :product="product"
                                    v-model:errors="form.errors"
                                    :index="index"
                                    @remove="removeProduct"
                                />
                            </q-list>
                        </template>
                    </ContractibleProductTable>

                    <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 tw-gap-5 tw-mt-5">
                        <TextInput
                            :label="$t('fields.observations')"
                            type="textarea"
                            v-model="form.observations"
                            v-model:errors="form.errors.observations"
                            dense
                        />

                        <div class="tw-flex tw-w-full tw-justify-end tw-items-start tw-pr-3">
                            <span
                                class="tw-text-lg"
                                :class="totalTextColor()"
                            >
                                {{ $t('fields.total') + ': ' + money(total) }}
                            </span>
                        </div>
                    </div>
                </q-card-section>
                <q-card-actions align="right">
                    <SecondaryButton to="movements.index">{{ $t('actions.cancel') }}</SecondaryButton>
                    <PrimaryButton @click="submit" :loading="form.processing">{{ $t('actions.ok') }}</PrimaryButton>
                </q-card-actions>
            </q-card>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">

import {Head, router, useForm, usePage} from "@inertiajs/vue3";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import SecondaryButton from "../../Components/SecondaryButton.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import {date, useQuasar} from "quasar";
import TextInput from "../../Components/TextInput.vue";
import Select from "../../Components/Select.vue";
import {useI18n} from "vue-i18n";
import route from "ziggy-js";
import {computed, ref} from "vue";
import ProductRow from "./ProductRow.vue";
import {money, showNotification} from "../../Common/helpers";
import InputError from "../../../../vendor/laravel/breeze/stubs/inertia-vue-ts/resources/js/Components/InputError.vue";
import DatePicker from "../../Components/DatePicker.vue";
import ContractibleProductTable from "../../Components/ContractibleProductTable.vue";

const $q = useQuasar()
const i18n = useI18n()
const page = usePage()

const form = useForm({
    date: date.formatDate(new Date(), 'YYYY-MM-DD'),
    reason_id: null,
    type: 'increment',
    observations: '',
    products: []
})

const reasonForm = useForm({
    name: '',
    redirect_to: route('movements.create')
})

const total = computed(() => {

    let total = 0

    form.products.forEach(product => {

        total += product.total
    })

    if (form.type === 'decrement') {

        total -= total * 2;
    }

    return total
})

function createReason() {

    $q.dialog({
        title: i18n.t('inventories.reasons.create'),
        prompt: {
            model: '',
            displayName: i18n.t('fields.name')
        },
        cancel: i18n.t('actions.cancel'),
        persistent: true,
        ok: i18n.t('actions.ok'),
    }).onOk(data => {

        reasonForm.name = data

        reasonForm.post(route('reasons.store'), {
            preserveScroll: true,
            onSuccess: () => {

                router.reload({
                    only: ['reasons'],
                    onSuccess: params => {

                        const reason = params.props.reasons.find(r => r.name == data)

                        if (reason) {

                            form.reason_id = reason.id
                        }
                    }
                })

                reasonForm.reset()
            },
            onError: params => {

                $q.notify({
                    message: params.name,
                    position: 'top-right',
                    type: 'negative'
                })
            }
        })
    })
}

function removeProduct(product) {

    const index = form.products.findIndex(p => p.id == product.id)

    if (index != -1) {

        form.products.splice(index, 1)
    }
}

function totalTextColor(): string {

    if (form.type === 'increment')
        return 'text-blue'
    return 'text-red'
}

function submit() {

    form.post(route('movements.store'), {
        preserveScroll: true,
        onError: () => {
            showNotification('negative', i18n.t('messages.validation_error'), 'top-right')
        }
    })
}
</script>
