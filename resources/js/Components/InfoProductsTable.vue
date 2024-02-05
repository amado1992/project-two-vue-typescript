<template>
   <div class="md:tw-grid md:tw-grid-cols-2 md:tw-gap-5">
    <Select
        class="tw-mb-3"
        v-model="selectedProducts"
        :options="productsProxy"
        @filter="onFilterProducts"
        option-label="name"
        option-value="id"
        :label="$t('reports.select_product')"
        input-debounce="100"
        use-input
        use-chips
        multiple
        :errors="form.errors.products"
    />

    <Select 
        v-model="form.clients" 
        :label="$t('fields.clients')" 
        :options="clientsProxy" 
        option-label="name"
        option-value="id" 
        @filter="onFilterClients" 
        multiple 
        use-chips 
        input-debounce="100" 
        use-input 
    />
   
                   
</div>
    
    <div class="md:tw-grid md:tw-grid-cols-1 md:tw-gap-5">
    <slot name="list" :products="form.products">
        <q-list separator>
        <InfoProductsRow
            v-for="(product, index) in form.products"
            :product="product"
            :index="index"
            :tax_exempt="form.tax_exempt"
            :errors="form.errors"
            @remove="remove"
        />
        </q-list>
    </slot> 
</div>
</template>

<script setup lang="ts">

import {ContractibleProduct} from "../Models/ContractibleProduct";
import {Product} from "../Models/Product";
import {InertiaForm, useForm, usePage} from "@inertiajs/vue3";
import Select from "./Select.vue";
import {computed, ref} from "vue";
import InfoProductsRow from "./InfoProductsRow.vue";
import {Client} from "../Models/Client";

const page = usePage() 
const props = defineProps<{
    products: Product[],
    clients: Client[], 
    form: InertiaForm<{
        products: ContractibleProduct[],
        tax_exempt: boolean,
        period?: number
       /*  clients: Client  */   
    }>
}>()


const allClients = props.clients as { id: number, name: string }[]
const clients = ref(allClients) 

defineExpose({
    add,
   /*  refresh */
})

const productsProxy = ref(props.products)
const clientsProxy = ref(page.props.clients) 

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
        inventory: {
            id: product.inventory.id,
            rented: product.inventory.rented,
            quantity: product.inventory.quantity,
            stock: product.inventory.stock
        }        
        
    }
}

function onFilterClients(val, update) {

    if (val == '') {

        update(() => {
            clients.value = allClients
        })
    } else {

        update(() => {
            const needle = val.toLowerCase()
            clients.value = allClients.filter(v => v.name.toLowerCase().indexOf(needle) > -1)
        })
    }
}

function details(product: ContractibleProduct) {

const index = props.form.products.findIndex(p => p.id == product.id)

if (index != -1) {

    
    }
}
</script>
