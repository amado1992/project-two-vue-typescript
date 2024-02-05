<template>
    <q-card class="tw-p-6">
        <q-card-section>
            <!--<div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-3 tw-gap-5">
                <TextInput v-model="form.travel_date" :label="$t('fields.travel_date')"
                    v-model:errors="form.errors.travel_date" required>
                    <template v-slot:append>
                        <q-icon name="event" class="cursor-pointer">
                            <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                                <DatePicker v-model="form.travel_date" v-model:errors="form.errors.travel_date" />
                            </q-popup-proxy>
                        </q-icon>
                    </template>
                </TextInput>
            </div>-->

            <div class="row">
                <div v-if="form.products.length > 0 && isStatusContract">
                    <TextInput v-model="form.travel_date" :label="$t('fields.travel_date')"
                        v-model:errors="form.errors.travel_date" required>
                        <template v-slot:append>
                            <q-icon name="event" class="cursor-pointer">
                                <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                                    <DatePicker v-model="form.travel_date" v-model:errors="form.errors.travel_date" />
                                </q-popup-proxy>
                            </q-icon>
                        </template>
                    </TextInput>
                </div>

                <div class="q-ml-md q-gutter-sm q-mt-sm" v-if="form.products.length > 0 && isStatusContract">
                    <q-btn color="dark" icon="download" no-caps label="Plantilla de viaje" @click="exportTemplatePdf()" />
                </div>
                <div v-if="form.products.length == 0 || !isStatusContract">
                    <q-btn color="dark" icon="download" no-caps label="Plantilla de viaje" @click="exportTemplatePdf()" />
                </div>

                <div class="q-ml-md q-gutter-sm q-mt-sm" v-if="form.products.length > 0 && isStatusContract">
                    <q-btn color="dark" icon="download" no-caps label="Viajes" @click="exportTravelsAllPdf()" />
                </div>
                <div class="q-ml-sm" v-if="form.products.length == 0 || !isStatusContract">
                    <q-btn color="dark" icon="download" no-caps label="Viajes" @click="exportTravelsAllPdf()" />
                </div>

                <!--<div class="q-ml-md q-gutter-sm q-mt-sm">
                    <q-btn color="dark" icon="download" no-caps label="Exportar viajes" @click="exportTemplatePdf()" />
                </div>-->
            </div>

            <q-markup-table class="tw-shadow-none" v-if="form.products.length > 0 && isStatusContract">
                <thead>
                    <tr>
                        <th class="text-right">{{ $t('fields.id') }}</th>
                        <th class="text-right">{{ $t('fields.product') }}</th>
                        <th class="text-right">{{ $t('fields.quantity') }}</th>
                        <th class="text-right">{{ $t('fields.carried_by_client') }}</th>
                        <th class="text-right">{{ $t('fields.quantity_by_client') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="tw-h-[90px]" v-for="(product, index) in form.products" :key="product.id">
                        <td class="text-right">{{ product.id }}</td>
                        <td class="text-right">
                            <ProductName :name="product.name" />
                        </td>
                        <td class="text-right">{{ product.quantity }}</td>
                        <td class="text-right">
                            {{ product.product_carried_by_client }}
                        </td>
                        <td class="text-right">
                            <TextInput class="tw-min-w-[120px]" dense type="number" v-model="product.carried_by_client"
                                min="0" v-model:errors="form.errors['products.' + index + '.carried_by_client']"
                                bottom-slots>
                                <template #error="props">
                                    <InputError classes="tw-max-w-[150px] tw-overflow-hidden tw-whitespace-normal"
                                        :message="props.errors" />
                                </template>
                            </TextInput>
                        </td>
                    </tr>
                </tbody>
            </q-markup-table>
            <InputError :message="form.errors.products" />
        </q-card-section>
        <q-card-actions align="right" v-if="form.products.length > 0 && isStatusContract">
            <PrimaryButton @click="submit" :loading="form.processing">{{ $t('actions.ok') }}</PrimaryButton>
        </q-card-actions>
    </q-card>
</template>

<script setup lang="ts">

import TextInput from "../../Components/TextInput.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import { useForm } from "@inertiajs/vue3";
import route from "ziggy-js";
import DatePicker from "../../Components/DatePicker.vue";
import { date } from "quasar";
import InputError from "../../Components/InputError.vue";
import ProductName from "../../Components/ProductName.vue";
import SecondaryButton from "../../Components/SecondaryButton.vue";
import { exportPdf, exportPdfGet } from "../../Composables/dowload";
import { computed } from "vue";

const props = defineProps<{
    contract: any
}>()

var statusContract: string = "pending"; 

const isStatusContract = computed(() => {
    const data = props.contract;

    if (statusContract == data.status) {
        return true;
    }

    return false;
});

const emits = defineEmits<{
    (e: 'success'): void
}>()

defineExpose({
    reload
})

const form = useForm({
    travel_date: date.formatDate(new Date(), 'YYYY-MM-DD'),
    book: null,
    products: buildProducts()
})

function reload() {

    form.travel_date = date.formatDate(new Date(), 'YYYY-MM-DD')
    form.book = null
    form.products = buildProducts()
}

function buildProducts() {

    return (props.contract.products as any[]).filter(p => {
        return p.pivot.quantity > p.pivot.carried_by_client
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
            rented_max: p.pivot.re_rent_delivered - p.pivot.re_rent_return,
            carried_by_client: 0,
            product_carried_by_client: p.pivot.carried_by_client
        }
    })
}

function submit() {
    form.post(route('contracts.travel.store', { contract: props.contract.id }), {
        preserveScroll: true,
        onSuccess: () => {
            emits('success')
        }
    })
}

function exportTemplatePdf() {

    exportPdfGet(
        route('travels.template.pdf', { contract: props.contract.id }),
        `Plantilla de viaje.pdf`
    )
}

function exportTravelsAllPdf() {

exportPdfGet(
    route('travels.export_travels_all.pdf', { contract: props.contract.id }),
    `Viajes.pdf`
)
}

</script>

<style scoped>
.table-header {

    @apply tw-float-right tw-w-[120px] tw-overflow-hidden tw-whitespace-normal tw-text-right
}
</style>
