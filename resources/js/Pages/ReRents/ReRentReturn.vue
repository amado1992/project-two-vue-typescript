<template>
    <q-card>
        <q-card-section>
            <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-3 tw-gap-5">
                <TextInput
                    v-model="form.date"
                    :label="$t('fields.return_date')"
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
            </div>

            <q-markup-table class="tw-shadow-none">
                <thead>
                <tr>
                    <th class="text-right">{{ $t('fields.id') }}</th>
                    <th class="text-right">{{ $t('fields.product') }}</th>
                    <th class="text-right">{{ $t('fields.quantity') }}</th>
                    <th class="text-right">{{ $t('fields.returned') }}</th>
                    <th class="text-right" colspan="2">{{ $t('fields.quantity_to_return') }}</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(product, index) in form.products">
                    <td class="text-right">{{ product.id }}</td>
                    <td class="text-right product-col">{{ product.name }}</td>
                    <td class="text-right">{{ product.rented }}</td>
                    <td class="text-right">{{ product.returned }}</td>
                    <td class="text-right" colspan="2">
                        <TextInput
                            v-model="product.quantity"
                            dense
                            type="number"
                            min="0"
                            :max="product.rented - product.returned"
                            :errors="getError(index, 'quantity')"
                        />
                    </td>
                </tr>
                </tbody>
            </q-markup-table>
        </q-card-section>
        <q-card-actions align="right">
            <PrimaryButton @click="submit" :loading="form.processing">{{ $t('actions.ok') }}</PrimaryButton>
        </q-card-actions>
    </q-card>
</template>

<script setup lang="ts">

import {useForm} from "@inertiajs/vue3";
import {date} from "quasar";
import TextInput from "../../Components/TextInput.vue";
import DatePicker from "../../Components/DatePicker.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import route from "ziggy-js";

const props = defineProps<{
    reRent
}>()

const emits = defineEmits<{
    (e: 'success'): void
}>()

const form = useForm({
    date: date.formatDate(new Date(), 'YYYY-MM-DD'),
    products: buildProducts()
})

defineExpose({
    refresh
})

function refresh() {
    form.products = buildProducts()
}

function buildProducts() {
    return (props.reRent.products as any[]).filter(p => {
        return p.pivot.quantity > p.pivot.returned
    }).map(p => {
        return {
            id: p.id,
            name: p.name,
            rented: p.pivot.quantity,
            re_rented: p.inventory.re_rented,
            returned: p.pivot.returned,
            quantity: 0
        }
    })
}

function submit() {
    form.post(route('re-rents.returns.store', {re_rent: props.reRent.id}), {
        preserveScroll: true,
        onSuccess: () => {

            emits('success')
        }
    })
}

function getError(index, field) {

    try {
        return form.errors['products.' + index + '.' + field]
    } catch (e) {
        return null
    }
}
</script>
