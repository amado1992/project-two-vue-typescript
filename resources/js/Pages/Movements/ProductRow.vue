<template>
    <div class="tw-bg-white tw-rounded-lg tw-shadow-lg tw-mb-2">
        <q-item>
            <q-item-section>
                    <div class="tw-grid tw-w-full tw-grid-col-1 sm:tw-grid-cols-2 lg:tw-grid-cols-5 tw-gap-5 tw-p-2">
                        <span>
                            <strong>{{ $t('fields.id') + ': ' }}</strong>
                            {{ product.id }}
                        </span>
                        <span class="lg:tw-col-span-2">
                            <strong>{{ $t('fields.name') + ': ' }}</strong>
                            {{ product.name }}
                        </span>
                        <span>
                            <strong>{{ $t('fields.price') + ': ' }}</strong>
                            {{ money(product.price) }}
                        </span>
                        <span>
                            <strong>{{ $t('fields.total') + ': ' }}</strong>
                            {{ money(product.total) }}
                        </span>
                    </div>

                    <div class="tw-w-full tw-flex tw-justify-between tw-items-center tw-gap-2 tw-pb-2">
                        <TextInput
                            :label="$t('fields.quantity')"
                            type="number"
                            v-model="quantityProxy"
                            dense
                            v-model:errors="errors['products.' + index + '.quantity']"
                            required
                            min="0"
                        />
                        <div>
                            <q-btn icon="delete" color="negative" flat @click="$emit('remove', product)" />
                        </div>
                    </div>
                </q-item-section>
        </q-item>
    </div>
</template>

<script setup lang="ts">

import TextInput from "../../Components/TextInput.vue";
import {computed} from "vue";
import {money} from "../../Common/helpers";

const props = defineProps<{
    product: any,
    errors: any,
    index: number
}>()

const quantityProxy = computed({
    get: () => props.product.quantity,
    set: v => {

        props.product.quantity = v
        props.product.total = props.product.price * props.product.quantity
    }
})
</script>
