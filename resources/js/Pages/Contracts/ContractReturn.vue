<template>
    <q-card class="tw-p-6">
        <q-card-section>
            <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-3 tw-gap-5">
                <TextInput
                    v-model="form.return_date"
                    :label="$t('fields.return_date')"
                    v-model:errors="form.errors.return_date"
                    required
                >
                    <template v-slot:append>
                        <q-icon name="event" class="cursor-pointer">
                            <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                                <DatePicker v-model="form.return_date" v-model:errors="form.errors.return_date" />
                            </q-popup-proxy>
                        </q-icon>
                    </template>
                </TextInput>

                <TextInput
                    v-model="form.book"
                    type="number"
                    :label="$t('fields.no_book')"
                    v-model:errors="form.errors.book"
                    required
                />
            </div>

            <q-markup-table class="tw-shadow-none">
                <thead>
                <tr>
                    <th class="text-right">{{ $t('fields.id') }}</th>
                    <th class="text-right">{{ $t('fields.product') }}</th>
                    <th class="text-right">{{ $t('fields.quantity') }}</th>
                    <th class="text-right">
                        <div class="table-header">
                            {{ $t('fields.mesu_rented_delivered') }}
                        </div>
                    </th>
                    <th class="text-right">
                        <div class="table-header">
                            {{ $t('fields.mesu_rented_return') }}
                        </div>
                    </th>
                    <th class="text-right">
                        <div class="table-header">
                            {{ $t('fields.mesu_to_return') }}
                        </div>
                    </th>
                    <th class="text-right">
                        <div class="table-header">
                            {{ $t('fields.re_rent_to_return') }}
                        </div>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr class="tw-h-[90px]" v-for="(product, index) in form.products">
                    <td class="text-right">{{ product.id }}</td>
                    <td class="text-right">
                        <ProductName :name="product.name" />
                    </td>
                    <td class="text-right">{{ product.quantity }}</td>
                    <td class="text-right">
                        {{ product.mesu_delivered + '/' + product.re_rent_delivered }}
                    </td>
                    <td class="text-right">
                        {{ product.mesu_return + '/' + product.re_rent_return }}
                    </td>
                    <td class="text-right">
                        <TextInput
                            class="tw-min-w-[120px]"
                            dense
                            type="number"
                            v-model="product.mesu"
                            :max="product.mesu_max"
                            min="0"
                            v-model:errors="form.errors['products.' + index + '.mesu']"
                            bottom-slots
                        >
                            <template #error="props">
                                <InputError
                                    classes="tw-max-w-[150px] tw-overflow-hidden tw-whitespace-normal"
                                    :message="props.errors"
                                />
                            </template>
                        </TextInput>
                    </td>
                    <td class="text-right">
                        <TextInput
                            class="tw-min-w-[120px]"
                            dense
                            type="number"
                            v-model="product.rented"
                            :max="product.rented_max"
                            min="0"
                            v-model:errors="form.errors['products.' + index + '.rented']"
                            bottom-slots
                        >
                            <template #error="props">
                                <InputError
                                    classes="tw-max-w-[150px] tw-overflow-hidden tw-whitespace-normal"
                                    :message="props.errors"
                                />
                            </template>
                        </TextInput>
                    </td>
                </tr>
                </tbody>
            </q-markup-table>
            <InputError :message="form.errors.products" />
        </q-card-section>
        <q-card-actions align="right">
            <PrimaryButton @click="submit" :loading="form.processing">{{ $t('actions.ok') }}</PrimaryButton>
        </q-card-actions>
    </q-card>
</template>

<script setup lang="ts">

import TextInput from "../../Components/TextInput.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import {useForm} from "@inertiajs/vue3";
import route from "ziggy-js";
import DatePicker from "../../Components/DatePicker.vue";
import {date} from "quasar";
import InputError from "../../Components/InputError.vue";
import ProductName from "../../Components/ProductName.vue";


const props = defineProps<{
    contract: any
}>()

const emits = defineEmits<{
    (e: 'success'): void
}>()

defineExpose({
    reload
})

const form = useForm({
    return_date: date.formatDate(new Date(), 'YYYY-MM-DD'),
    book: null,
    products: buildProducts()
})

function reload() {

    form.return_date = date.formatDate(new Date(), 'YYYY-MM-DD')
    form.book = null
    form.products = buildProducts()
}

function buildProducts() {

    return (props.contract.products as any[]).filter(p => {
        return p.pivot.mesu_delivered > p.pivot.mesu_return || p.pivot.re_rent_delivered > p.pivot.re_rent_return
    }).map(p => {
        return {
            id: p.id,
            name: p.name,
            mesu: 0,
            rented: 0,
            quantity: p.pivot.quantity,
            mesu_delivered: p.pivot.mesu_delivered,
            re_rent_delivered: p.pivot.re_rent_delivered,
            mesu_return: p.pivot.mesu_return,
            re_rent_return: p.pivot.re_rent_return,
            mesu_max: p.pivot.mesu_delivered - p.pivot.mesu_return,
            rented_max: p.pivot.re_rent_delivered - p.pivot.re_rent_return
        }
    })
}

function submit() {

    form.post(route('contracts.returns.store', {contract: props.contract.id}), {
        preserveScroll: true,
        onSuccess: () => {
            emits('success')
        }
    })
}
</script>

<style scoped>

.table-header {

    @apply tw-float-right tw-w-[120px] tw-overflow-hidden tw-whitespace-normal tw-text-right
}

</style>
