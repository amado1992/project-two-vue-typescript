<template>
    <div class="tw-bg-white tw-rounded-lg tw-shadow-lg tw-mb-2">

        <q-item>
            <!--  <q-item-label>
                 <span>
                 
                </span></q-item-label> -->
            <q-item-section>
                <div class="tw-flex tw-w-full tw-justify-between tw-gap-12">
                    <span>
                        <strong>{{ $t('fields.name') + ': ' }}</strong>
                        {{ product.name }}
                    </span>
                    <span>
                        <strong>{{ $t('reports.products.rented') + ': ' }}</strong>
                        {{ rentedProxy }}
                    </span>
                    <span>
                        <strong>{{ $t('fields.stock') + ': ' }}</strong>
                        {{ stockProxy }}
                    </span>
                    <span>
                        <!--  <q-btn icon="keyboard_arrow_down"  @click="open('bottom')" >
                            <q-tooltip class="bg-secondary" :offset="[10, 10]">
                                {{ $t('fields.details') }}
                           </q-tooltip>
                        </q-btn>   -->

                    </span>
                </div>
            </q-item-section>
        </q-item>
    </div>
</template>

<script setup lang="ts">

import { computed } from "vue";
import { ContractibleProduct } from "../Models/ContractibleProduct";

const props = defineProps<{
    product: ContractibleProduct,
    index: number,
    tax_exempt: boolean,
    errors: Record<string, string>
}>()


const rentedProxy = computed(() => {
    return props.product.inventory?.rented
})


const stockProxy = computed(() => {
    return props.product.inventory?.stock
})


function open() {

}
</script>
