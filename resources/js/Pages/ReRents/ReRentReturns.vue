<template>
    <Head :title="$t('re_rents.returns.title')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="shopping_bag" />
            </q-avatar>
            {{ $t('re_rents.returns.title') }}
        </template>

        <div class="container">
            <ReRentReturn
                class="tw-mt-5"
                v-show="showReturnForm"
                :re-rent="reRent"
                @success="reloadReRent"
                ref="form"
            />

            <q-card class="tw-mt-5" v-for="reRentReturn in reRent.returns">
                <q-card-section>
                    <div>
                        <strong>{{ $t('fields.date') + ': ' + reRentReturn.date }}</strong>
                    </div>

                    <q-markup-table class="tw-shadow-none">
                        <thead>
                        <tr>
                            <th class="text-right">{{ $t('fields.id') }}</th>
                            <th class="text-right">{{ $t('fields.product') }}</th>
                            <th class="text-right">{{ $t('fields.returned') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="product in reRentReturn.products">
                            <td class="text-right">{{ product.id }}</td>
                            <td class="text-right product-col">{{ product.name }}</td>
                            <td class="text-right">{{ product.pivot.quantity }}</td>
                        </tr>
                        </tbody>
                    </q-markup-table>
                </q-card-section>
            </q-card>

            <div class="tw-flex tw-justify-end tw-mt-5">
                <SecondaryButton :to="route('re-rents.show', {re_rent: reRent.id})">
                    {{ $t('actions.back') }}
                </SecondaryButton>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">

import {Head, router, usePage} from "@inertiajs/vue3";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import ReRentReturn from "./ReRentReturn.vue";
import {computed, ref} from "vue";
import SecondaryButton from "../../Components/SecondaryButton.vue";
import route from 'ziggy-js';

const props = defineProps<{
    reRent
}>()

const showReturnForm = computed(() => props.reRent.crud_permissions.add_returns as boolean)

const form = ref<ReRentReturn|null>(null)

function reloadReRent() {

    router.reload({
        only: ['reRent'],
        onSuccess: () => {
            form.value?.refresh()
        }
    })
}
</script>
