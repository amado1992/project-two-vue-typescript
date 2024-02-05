<template>
    <q-dialog ref="dialogRef" @hide="onDialogHide" persistent full-width>
        <q-card class="q-dialog-plugin">

            <q-card-section>
                <div class="text-h6">{{ $t('contracts.start') }}</div>
                <q-skeleton animation="pulse-x"  v-if="form.products.length == 0" height="200px" square />
                <q-markup-table class="tw-shadow-none" v-else>

                    <thead>
                    <tr>
                        <th class="text-right">{{ $t('fields.id') }}</th>
                        <th class="text-right">{{ $t('fields.name') }}</th>
                        <th class="text-right">{{ $t('fields.quantity') }}</th>
                        <th class="text-right">{{ $t('fields.stock') }}</th>
                        <th class="text-right">{{ $t('fields.mesu') }}</th>
                        <th class="text-right">{{ $t('fields.rented') }}</th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr v-for="(product, index) in form.products">
                        <td class="text-right">{{ product.id }}</td>
                        <td class="text-right">
                            <ProductName :name="product.name" />
                        </td>
                        <td class="text-right">{{ product.quantity }}</td>
                        <td class="text-right">{{ getStock(product.id) }}</td>
                        <td>
                            <TextInput
                                dense
                                type="number"
                                min="0"
                                v-model="product.mesu"
                                :max="Math.min(getMax(product, product.rented), getStock(product.id))"
                                v-model:errors="form.errors['products.' + index + '.mesu']"
                            />
                        </td>
                        <td>
                            <TextInput
                                dense
                                type="number"
                                min="0"
                                v-model="product.rented"
                                :max="Math.min(getMax(product, product.mesu), getReStock(product.id))"
                                v-model:errors="form.errors['products.' + index + '.rented']"
                            />
                        </td>
                    </tr>
                    </tbody>
                </q-markup-table>
            </q-card-section>

            <q-card-actions align="right">
                <q-btn color="primary" flat :label="$t('actions.cancel')" @click="onDialogCancel" />
                <q-btn color="primary" flat :label="$t('actions.ok')" @click="submit" :loading="form.processing" />
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>

<script setup lang="ts">

import {useDialogPluginComponent} from "quasar";
import TextInput from "../../Components/TextInput.vue";
import {useForm} from "@inertiajs/vue3";
import route from "ziggy-js";
import {computed,ref} from "vue";
import ProductName from "../../Components/ProductName.vue";
import axios from "axios";
import { onMounted } from 'vue'

const props = defineProps<{
    contract
}>()



const contractWithProducts = ref(null)

defineEmits([
    ...useDialogPluginComponent.emits
])

const { dialogRef, onDialogHide, onDialogOK, onDialogCancel } = useDialogPluginComponent()

const form = useForm({
    products: []
})

async function buildProducts() {
    axios.get(route("contracts.products",{
        contract:props.contract.id
     })).then((response)=>{

        const products = [];

        (response.data.contract.products as any[]).forEach(p => {

                products.push({
                    id: p.id,
                    name: p.name,
                    mesu: 0,
                    rented: 0,
                    quantity: p.pivot.quantity
                })
        })
        form.products = products
        console.log(form.products)
        contractWithProducts.value = response.data.contract

     })
}

onMounted(()=>{
    buildProducts()
})

function getMax(product, otherValue) {
    return product.quantity - otherValue
}

function getStock(id): number {

    if(contractWithProducts.value){
        const product = (contractWithProducts.value.products as any[]).find(p => p.id == id)

      if (product) {

        return product.inventory.stock
      }
    }

    return 0
}

function getReStock(id): number {

    if(contractWithProducts.value){
        const product = (contractWithProducts.value.products as any[]).find(p => p.id == id)

    if (product) {

        return product.inventory.re_stock
    }
    }

    return 0
}

function submit() {

    form.post(route('contracts.start', {contract: props.contract.id}), {
        preserveScroll: true,
        onSuccess: () => {
            onDialogOK()
        }
    })
}



</script>
