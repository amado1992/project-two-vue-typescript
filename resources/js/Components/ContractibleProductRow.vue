<template>
    <div class="tw-bg-white tw-rounded-lg tw-shadow-lg tw-mb-2">

        <q-item>
            <q-item-section>
                <div v-if="show_cant" class="tw-p-2 tw-m-2">{{ product.name }}</div>
                <div class="tw-grid tw-w-full tw-grid-col-1 sm:tw-grid-cols-2 lg:tw-grid-cols-5 tw-gap-5">
                    <span
                    v-if="!show_cant"
                    class="lg:tw-col-span-2"
                    >
                        <strong>{{ $t('fields.name') + ': ' }}</strong>
                        {{ product.name }}
                    </span>

                    <span v-if="!show_cant">
                        <strong>{{ $t('fields.subtotal') + ': ' }}</strong>
                        {{ money(subtotalProxy) }}
                    </span>
                    <span v-if="!show_cant">
                        <strong>{{ $t('fields.tax') + ': ' }}</strong>
                        {{ money(taxProxy) }}
                    </span>
                    <span v-if="!show_cant">
                        <strong>{{ $t('fields.total') + ': ' }}</strong>
                        {{ money(totalProxy) }}
                    </span>
                </div>

                <div class="tw-w-full tw-flex tw-items-center tw-gap-2 tw-m-2">
                    <div class="tw-w-full tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 xl:tw-grid-cols-3 tw-gap-5">
                        <TextInput
                            v-if="!show_cant"
                            :label="$t('fields.price')"
                            type="number"
                            step="any"
                            v-model="product.price"
                            dense
                            v-model:errors="errors['products.' + index + '.price']"
                            required
                            min="0"
                        />

                        <TextInput
                            :label="$t('fields.quantity')"
                            type="number"
                            v-model="product.quantity"
                            dense
                            v-model:errors="errors['products.' + index + '.quantity']"
                            required
                            min="0"
                        />

                        <TextInput
                            v-if="!show_cant"
                            :label="$t('fields.discount')"
                            type="number"
                            step="any"
                            v-model="discountProxy"
                            dense
                            v-model:errors="errors['products.' + index + '.discount']"
                            min="0"
                            max="100"
                        />
                    </div>
                    <div>
                        <q-btn icon="delete" color="negative" flat @click="$emit('remove', product)" />
                    </div>
                </div>
            </q-item-section>
        </q-item>
    </div>
</template>

<script setup lang="ts">

import TextInput from "./TextInput.vue";
import {computed} from "vue";
import {ContractibleProduct} from "../Models/ContractibleProduct";
import {money} from "../Common/helpers";

import { inject } from 'vue'

const show_cant = inject('show_cant')
console.log(show_cant)

const props = defineProps<{
    product: ContractibleProduct,
    index: number,
    tax_exempt: boolean,
    errors: Record<string, string>
}>()

const discountProxy = computed<number>({
    get: () => props.product.discount,
    set: v => {
        props.product.discount = v
        const subtotal = props.product.price * props.product.quantity
        props.product.discount_value = calculatePercentage(subtotal)
    }
})

const subtotalProxy = computed(() => {

    props.product.subtotal = props.product.price * props.product.quantity
    props.product.subtotal -= calculatePercentage(props.product.subtotal)

    return props.product.subtotal
})

const taxProxy = computed(() => {

    if (props.tax_exempt) {
        props.product.tax = 0
    } else {

        let tax = 0

        if (props.product.product_tax > 0) {

            tax = props.product.price * props.product.product_tax / 100
        }

        props.product.tax = tax * props.product.quantity
    }

    return props.product.tax
})

const totalProxy = computed(() => {
    props.product.total = subtotalProxy.value + taxProxy.value
    return props.product.total
})

function calculatePercentage(value: number): number {
    return value * props.product.discount / 100
}
</script>
