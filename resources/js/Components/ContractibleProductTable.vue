<template>
  <q-select
      v-if="useCustomSelect"
      class="tw-w-full tw-mb-3"
      v-model="selected"
      :options="productsProxy"
      @filter="onFilterProducts"
      use-input
      use-chips
      multiple
      option-value="id"
      option-label="name"
      @update:model-value="onSelected"

  >
    <template v-slot:option="scope">
      <q-item v-bind="scope.itemProps">
          <q-item-label>{{scope.opt.name}}<p class="text-weight-bold">Cantidad: {{scope.opt.inventory.stock}}</p></q-item-label>
      </q-item>
    </template>


  </q-select>




    <Select
        v-else
        class="tw-w-full tw-mb-3"
        v-model="selectedProducts"
        :options="productsProxy"
        @filter="onFilterProducts"
        option-label="name"
        option-value="id"
        :label="$t('quotes.select_product')"
        :hint="$t('quotes.select_product_hint')"
        use-input
        use-chips
        multiple
        v-model:errors="form.errors.products"
        :required="required"
    />

    <slot name="list" :products="form.products">
        <q-list separator>
            <ContractibleProductRow
                v-for="(product, index) in form.products"
                :product="product"
                :index="index"
                :tax_exempt="form.tax_exempt"
                :errors="form.errors"
                @remove="remove"
            />
        </q-list>
    </slot>
</template>

<script setup lang="ts">

import {ContractibleProduct} from "../Models/ContractibleProduct";
import {Product} from "../Models/Product";
import ContractibleProductRow from "./ContractibleProductRow.vue";
import {InertiaForm} from "@inertiajs/vue3";
import Select from "./Select.vue";
import {computed, ref} from "vue";

const props = defineProps<{
    products: Product[],
    form: InertiaForm<{
        products: ContractibleProduct[],
        tax_exempt: boolean,
        period?: number
    }>,
    required?: boolean,
    useCustomSelect?: boolean
}>()

defineExpose({
    add,
    refresh
})

const productsProxy = ref(props.products)
const selected = ref()

function onSelected(v:number[]){

        console.log(v)
        const products = props.form.products

        v.forEach(obj => {

            const product = productsProxy.value.find(p => p.id === obj.id)


            if (product && ! products.some(p => p.id == product.id)) {

                products.push(newContractible(product, props.form.period))
            }
        })

        for (let i = 0; i < products.length; i++) {

            if (! v.some(obj => obj.id == products[i].id)) {

                products.splice(i, 1)
            }
        }
    console.log(products)
    props.form.products = products


}

const selectedProducts = computed<number[]>({
    get: () => {

        return props.form.products.map(p => p.id)
    },
    set: v => {

        const products = props.form.products

        v.forEach(id => {

            const product = productsProxy.value.find(p => p.id === id)

            if (product && ! products.some(p => p.id == product.id)) {

                products.push(newContractible(product, props.form.period))
            }
        })

        for (let i = 0; i < products.length; i++) {

            if (! v.some(id => id == products[i].id)) {

                products.splice(i, 1)
            }
        }
        console.log(products)
        props.form.products = products
    }
})

function add(product: Product, period?: number) {

    if (props.form.products.findIndex(p => p.id == product.id) == -1) {

        props.form.products.push(newContractible(product, period))
    }
}

function refresh(period: number) {

    props.form.products.forEach(p => {
        p.price = getPrice(p.period_prices, period)
    })
}

function getPrice(period_prices: Record<number, number>, period?: number) {

    if (period === -1) {

        return 0
    }
    return period ? period_prices[period] : period_prices[1]
}

function remove(product: ContractibleProduct) {

    const index = props.form.products.findIndex(p => p.id == product.id)

    if (index != -1) {

        props.form.products.splice(index, 1)
    }
}

function onFilterProducts(val, update) {

    if (val == '') {

        update(() => {
            productsProxy.value = props.products
        })
    } else {

        update(() => {
            const needle = val.toLowerCase()
            productsProxy.value = props.products.filter(v => v.name.toLowerCase().indexOf(needle) > -1)
        })
    }
}

function newContractible(product: Product, period?: number): ContractibleProduct {

    return {
        id: product.id,
        name: product.name,
        price: getPrice(product.period_prices, period),
        period_prices: product.period_prices,
        quantity: 0,
        discount: 0,
        discount_value: 0,
        subtotal: 0,
        tax: 0,
        product_tax: product.tax,
        total: 0
    }
}
</script>
