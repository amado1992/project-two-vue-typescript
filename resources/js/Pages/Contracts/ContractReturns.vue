<template>
    <Head :title="$t('contracts.returns.title')" />

    <AuthenticatedLayout>
        <template #header>
            {{ $t('contracts.returns.title') }}
        </template>

        <div class="container">
            <ContractReturn
                class="tw-mt-5"
                v-show="showReturnForm"
                :contract="contract"
                @success="reloadContract"
                ref="form"
            />

            <q-card class="tw-mt-5" v-for="contractReturn in contract.returns">
                <q-card-section>

                    <div class="tw-grid tw-grid-cols-2 lg:tw-grid-cols-4 tw-gap-5">
                        <span>
                            <strong>{{ $t('fields.return_date') + ': ' }}</strong>
                            {{ contractReturn.return_date }}
                        </span>
                        <span>
                            <strong>{{ $t('fields.no_book') + ': ' }}</strong>
                            {{ contractReturn.book }}
                        </span>
                        <span>
                            <strong>{{ $t('fields.created_at') + ': ' }}</strong>
                            {{ contractReturn.created_at }}
                        </span>
                        <span>
                            <strong>{{ $t('fields.created_by') + ': ' }}</strong>
                            {{ contractReturn.created_by?.name }}
                        </span>
                    </div>

                    <q-markup-table class="tw-shadow-none">
                        <thead>
                        <tr>
                            <th class="text-right">{{ $t('fields.id') }}</th>
                            <th class="text-right">{{ $t('fields.product') }}</th>
                            <th class="text-right">{{ $t('fields.mesu_return') }}</th>
                            <th class="text-right">{{ $t('fields.re_rent_return') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="product in contractReturn.products">
                            <td class="text-right">{{ product.id }}</td>
                            <td class="text-right">
                                <ProductName :name="product.name" />
                            </td>
                            <td class="text-right">{{ product.pivot.mesu_return }}</td>
                            <td class="text-right">{{ product.pivot.re_rent_return }}</td>
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
import ContractReturn from "./ContractReturn.vue";
import SecondaryButton from "../../Components/SecondaryButton.vue";
import route from "ziggy-js";
import ProductName from "../../Components/ProductName.vue";

const props = defineProps<{
    contract
}>()

const showReturnForm = computed(() => props.contract.crud_permissions.add_returns)
const form = ref<ContractReturn|null>(null)

function reloadContract() {

    router.reload({
        only: ['contract'],
        onSuccess: () => {

            form.value.reload()
        }
    })
}
</script>
