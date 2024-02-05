<template>
    <Head :title="$t('contracts.travels.title')" />

    <AuthenticatedLayout>
        <template #header>
            {{ $t('contracts.travels.title') }}
        </template>

        <div class="container">
            <ContractTravel
                class="tw-mt-5"
                v-show="showReturnForm"
                :contract="contract"
                @success="reloadContract"
                ref="form"
            />

            <q-card class="tw-mt-5" v-for="travel in contract.travels" :key="travel.id">

                <q-btn class="q-ma-sm" color="dark" icon="download" no-caps label="Viaje" @click="exportTravelOnePdf(travel)" />

                <q-card-section>

                    <div class="tw-grid tw-grid-cols-2 lg:tw-grid-cols-4 tw-gap-5">
                        <span>
                            <strong>{{ $t('fields.travel_date') + ': ' }}</strong>
                            {{ travel.travel_date }}
                        </span>
                        <span>
                            <strong>{{ $t('fields.no_book') + ': ' }}</strong>
                            {{ travel.book }}
                        </span>
                        <span>
                            <strong>{{ $t('fields.created_at') + ': ' }}</strong>
                            {{ travel.created_at }}
                        </span>
                        <span>
                            <strong>{{ $t('fields.created_by') + ': ' }}</strong>
                            {{ travel.created_by?.name }}
                        </span>
                    </div>

                    <q-markup-table class="tw-shadow-none">
                        <thead>
                        <tr>
                            <th class="text-right">{{ $t('fields.id') }}</th>
                            <th class="text-right">{{ $t('fields.product') }}</th>
                            <th class="text-right">{{ $t('fields.carried_client') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="product in travel.products" :key="product.id">
                            <td class="text-right" v-if="product.pivot.carried_by_client > 0">{{ product.id }}</td>
                            <td class="text-right" v-if="product.pivot.carried_by_client > 0">
                                <ProductName :name="product.name" />
                            </td>
                            <td class="text-right" v-if="product.pivot.carried_by_client > 0">{{ product.pivot.carried_by_client }}</td>
                        </tr>
                        </tbody>
                    </q-markup-table>
                </q-card-section>
            </q-card>

            <div v-if="! contract.returns.length && ! contract.crud_permissions.add_returns">
                <h6>{{ $t('messages.no_returns') }}</h6>
            </div>

            <div class="tw-flex tw-w-full tw-justify-end tw-mt-5">
                <SecondaryButton :to="route('contracts.show', {contract: contract.id})">{{ $t('actions.back') }}</SecondaryButton>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">

import {Head, router} from "@inertiajs/vue3";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import {computed, onMounted, ref} from "vue";
import ContractTravel from "./ContractTravel.vue";
import SecondaryButton from "../../Components/SecondaryButton.vue";
import route from "ziggy-js";
import ProductName from "../../Components/ProductName.vue";
import { exportPdfGet } from "../../Composables/dowload";

const props = defineProps<{
    contract: any
}>()
 console.log("HOLA ", props.contract)
const showReturnForm = computed(() => props.contract.crud_permissions.add_returns)
const form = ref<ContractTravel|null>(null)

function reloadContract() {

    router.reload({
        only: ['contract'],
        onSuccess: () => {

            form.value.reload()
        }
    })
}

function exportTravelOnePdf(travel: any) {
console.log("Viaje", travel)
exportPdfGet(
    route('travels.export_travel_one.pdf', {travel: travel.id}),
    `Viaje.pdf`
)
}

</script>
