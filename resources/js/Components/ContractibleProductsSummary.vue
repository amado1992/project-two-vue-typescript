<template>
    <ContractibleSummary :summary="summary" />
</template>

<script setup lang="ts">

import {ContractibleProduct} from "../Models/ContractibleProduct";
import {computed} from "vue";
import {money, percentage} from "../Common/helpers";
import ContractibleSummary from "./ContractibleSummary.vue";

const props = defineProps<{
    products: ContractibleProduct[]
}>()

const summary = computed(() => {
    return {
        discount: discount.value,
        tax: tax.value,
        subtotal: subtotal.value,
        total: total.value
    }
})

const discount = computed(() => {

    let discount = 0

    props.products.forEach(p => {
        discount += p.discount_value
    })

    return discount
})

const subtotal = computed(() => {

    let subtotal = 0

    props.products.forEach(p => {
        subtotal += p.subtotal
    })

    return subtotal
})

const tax = computed(() => {

    let tax = 0

    props.products.forEach(p => {
        tax += p.tax
    })

    return tax
})

const total = computed(() => {

    let total = 0

    props.products.forEach(p => {
        total += p.total
    })

    return total
})
</script>
