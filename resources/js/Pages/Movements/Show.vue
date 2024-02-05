<template>
    <Head :title="$t('inventories.movements.details')" />
    <AuthenticatedLayout>
        <template #header>
            {{ $t('inventories.movements.details') }}
        </template>

        <div class="container">
            <q-card>
                <q-card-section>
                    <div class="tw-grid tw-grid-cols-2 lg:tw-grid-cols-5 tw-gap-5 tw-text-[12px]">
                        <dl>
                            <dt class="tw-font-semibold">{{ $t('fields.id') }}</dt>
                            <dd>{{ movement.id }}</dd>
                            <dt class="tw-font-semibold">{{ $t('fields.date') }}</dt>
                            <dd>{{ movement.date }}</dd>
                        </dl>

                        <dl>
                            <dt class="tw-font-semibold">{{ $t('fields.type') }}</dt>
                            <dd>{{ movementsTypes(i18n).get(movement.type) }}</dd>
                            <dt class="tw-font-semibold">{{ $t('fields.total') }}</dt>
                            <dd>{{ money(movement.total) }}</dd>
                        </dl>
                        <dl class="lg:tw-col-span-3">
                            <dt class="tw-font-semibold">{{ $t('fields.observations') }}</dt>
                            <dd>{{ movement.observations }}</dd>
                        </dl>
                    </div>
                </q-card-section>
            </q-card>

            <q-markup-table class="tw-mt-5">
                <thead>
                <tr>
                    <th class="text-right">{{ $t('fields.id') }}</th>
                    <th class="text-right">{{ $t('fields.name') }}</th>
                    <th class="text-right">{{ $t('fields.quantity') }}</th>
                    <th class="text-right">{{ $t('fields.price') }}</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="product in movement.products">
                    <td class="text-right">{{ product.id }}</td>
                    <td class="text-right product-col">
                        {{ product.name }}
                        <q-tooltip>{{ product.name }}</q-tooltip>
                    </td>
                    <td class="text-right">{{ product.pivot.quantity }}</td>
                    <td class="text-right">{{ money(product.pivot.price) }}</td>
                </tr>
                </tbody>
            </q-markup-table>

            <q-card class="tw-mt-5">
                <q-card-section>
                    <Creators :created_at="movement.created_at" :created_by="movement.created_by.name" />
                </q-card-section>
            </q-card>

            <div class="tw-flex tw-justify-end tw-mt-5">
                <SecondaryButton to="movements.index">{{ $t('actions.back') }}</SecondaryButton>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<script setup lang="ts">

import {Head} from "@inertiajs/vue3";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import {movementsTypes, money} from "../../Common/helpers";
import {useI18n} from "vue-i18n";
import SecondaryButton from "../../Components/SecondaryButton.vue";
import Creators from "../../Components/Creators.vue";

defineProps<{
    movement: any
}>()

const i18n = useI18n()
</script>

<style scoped>
dl > dd {
    margin-left: 0;
}

dl > dt {
    margin-top: 10px;
}
</style>
