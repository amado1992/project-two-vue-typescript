<template>
    
    <q-dialog ref="dialogRef">
        
        <q-card style="width: 800px; max-width: 100vw;">
      
            <q-card-section>
                <h6 class="tw-mt-5 tw-ml-3 tw-mb-3 tw-font-medium"> {{ $t('fields.product') }}: {{ props.product.name }} </h6>
                <q-markup-table class="tw-shadow-none">
                    <thead>
                        <tr>
                            <th class="text-right">{{ $t('fields.client') }}</th>
                            <th class="text-right">{{ $t('reports.products.rented') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(value) in values">
                            <q-td class="tw-max-w-sm tw-text-ellipsis tw-overflow-hidden text-right"> {{ value['name'] }}
                            </q-td>
                            <q-td class="text-right">
                                {{ value['quantity'] }}
                            </q-td>
                        </tr>
                    </tbody>
                </q-markup-table>
            </q-card-section>

            <q-card-actions align="right">
                <q-btn color="primary" flat :label="$t('actions.close')" @click="onDialogCancel" />
            </q-card-actions>

        </q-card>
    </q-dialog>
</template>

<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { useDialogPluginComponent } from 'quasar';
import { useI18n } from "vue-i18n";
import route from "ziggy-js";

const i18n = useI18n()
const page = usePage()

const props = defineProps<{
    product: any,
    values: Array

}>()

defineEmits([
    ...useDialogPluginComponent.emits
])

const { dialogRef, onDialogHide, onDialogOK, onDialogCancel } = useDialogPluginComponent()

</script>

