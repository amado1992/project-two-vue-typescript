<template>
    <q-dialog ref="dialogRef" @hide="onDialogHide" full-width>
        <q-card>

            <q-card-section>
                <div class="text-h6">{{ $t('contracts.renovations.create') }}</div>
                <q-markup-table class="tw-shadow-sm">
                    <thead>
                    <tr>
                        <th class="tw-text-right">{{ $t('fields.id') }}</th>
                        <th class="tw-text-right">{{ $t('fields.name') }}</th>
                        <th class="tw-text-right">
                            {{ $t("fields.mesu_rented_delivered") }}
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="product in products">
                        <td class="tw-text-right">{{ product.id }}</td>
                        <td class="tw-text-right">
                            <ProductName :name="product.name" />
                        </td>
                        <td class="tw-text-right">
                        {{
                            product.pivot.mesu_delivered +
                            "/" +
                            product.pivot.re_rent_delivered
                        }}
                    </td>
                    </tr>
                    </tbody>
                </q-markup-table>
            </q-card-section>

            <q-card-actions align="right">
                <q-btn color="primary" flat :label="$t('actions.cancel')" @click="onDialogCancel" />
                <q-btn color="primary" flat :label="$t('actions.ok')" @click="submit" :loading="loading" />
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>

<script setup lang="ts">

import {useDialogPluginComponent} from "quasar";
import {computed, ref} from "vue";
import {router} from "@inertiajs/vue3";
import route from "ziggy-js";
import ProductName from "../../Components/ProductName.vue";

const props = defineProps<{
    contract
}>()
console.log(props.contract)
defineEmits([
    ...useDialogPluginComponent.emits
])

const { dialogRef, onDialogHide, onDialogOK, onDialogCancel } = useDialogPluginComponent()

const loading = ref(false)

const products = computed(() => {

    return (props.contract.products as any[]).filter(p => {
        return p.pivot.mesu_delivered > p.pivot.mesu_return || p.pivot.re_rent_delivered > p.pivot.re_rent_return
    })
})

function submit() {

    loading.value = true
    router.post(route('contracts.renovations.store', {contract: props.contract.id}), undefined, {
        preserveScroll: true,
        onSuccess: () => {
            onDialogOK()
        },
        onFinish: () => {

            loading.value = false
        }
    })
}
</script>
