<template>
  <q-card class="tw-mt-4">
    <q-card-section>

      <q-markup-table class="tw-shadow-none tw-mt-2 fixed-layout-table tw-full">
        <tr>
          <q-td class="text-right tw-font-bold" style="vertical-align: top;">Clientes:</q-td>
          <template v-if="data['all_clients'] == 1">
            <q-td class="text-right"> Todos </q-td>
          </template>
          <template v-if="data['all_clients'] == 0">

            <q-td class="text-right">
              <q-list padding v-for="(client, index) in data['clients']" :key="index">
                <q-item-section>
                  <q-item-label caption class="tw-font-bold"> {{ client }}
                    <br /></q-item-label>
                </q-item-section>
              </q-list>
            </q-td>
          </template>

        </tr>
        <tr>
          <q-td class="text-right tw-font-bold">Contratos activos a la fecha:</q-td>
          <q-td class="text-right">{{ data['qty_active_contracts'] }}</q-td>
        </tr>
        <tr>
          <q-td class="text-right tw-font-bold">Total descuentos de los contratos activos:</q-td>
          <q-td class="text-right">{{ data['discount_total'] }}</q-td>
        </tr>
        <tr>
          <q-td class="text-right tw-font-bold">Subtotal de los contratos activos:</q-td>
          <q-td class="text-right">{{ data['subtotal'] }}</q-td>
        </tr>
        <tr>
          <q-td class="text-right tw-font-bold">Total impuestos de los contratos activos:</q-td>
          <q-td class="text-right">{{ data['total_taxes'] }}</q-td>
        </tr>
        <tr>
          <q-td class="text-right tw-font-bold">Total:</q-td>
          <q-td class="text-right">{{ data['active_contracts_total'] }}</q-td>
        </tr>

      </q-markup-table>
    </q-card-section>
  </q-card>
</template>

<script setup lang="ts">

import { Head, InertiaForm, router, useForm, usePage } from '@inertiajs/vue3';
import { useI18n } from "vue-i18n";
import { useQuasar } from 'quasar';
import { money } from '../Common/helpers';

//Services
const i18n = useI18n()
const page = usePage()
const $q = useQuasar()

const props = defineProps<{
  data: Array,
  form: InertiaForm<{
    data: [],
  }>
}>()

</script>
