<template>
  <q-card class="tw-mt-4">
    <q-card-section>
      <q-markup-table>
        <!--  <pre>{{ data['all_contracts'] }}</pre> -->
        <q-td class="text-left tw-font-bold" width="5%">Cliente:</q-td>
        <template v-if="data['all_clients'] == 1">
          <q-td class="text-left" width="95%"> Todos </q-td>
        </template>
        <template v-if="data['all_clients'] == 0">

          <q-td class="text-left">
            <span v-for="(client, index) in data['clients']" :key="index"> {{ client }} <br /></span>
          </q-td>
        </template>
      </q-markup-table>
    </q-card-section>

    <q-card-section>

      <template v-for="(contract) in data['all_contracts']">
        <q-markup-table class="tw-mt-12 tw-mb-3">
          <thead>
            <tr>
              <q-th class="text-right" width="10%">No Contrato</q-th>
              <q-th class="text-right" width="50%">Cliente</q-th>
              <q-th class="text-right" width="10%">Inicio</q-th>
              <q-th class="text-right" width="10%">Final</q-th>
              <q-th class="text-right" width="10%">Total</q-th>
              <q-th class="text-right" width="10%">Estado</q-th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <q-td class="text-right" width="10%">{{ contract.id }}</q-td>
              <q-td class="tw-max-w-[120px] tw-text-ellipsis tw-overflow-hidden text-right">
                {{ contract.project.client.name }} </q-td>
              <q-td class="text-right" width="10%">{{ contract.date }}</q-td>
              <q-td class="text-right" width="10%">{{ contract.expire_at }}</q-td>
              <q-td class="text-right" width="10%">{{ contract.total }}</q-td>
              <q-td class="text-right" width="10%">{{ status(contract.status, i18n)[0] }}</q-td>

            </tr>
          </tbody>
        </q-markup-table>

        <div class="tw-font-bold tw-mt-5">Dirección:</div>
        <div>{{ contract.project.client.address }}</div>

        <div class="tw-font-bold tw-mt-4 tw-mb-3">Productos alquilados:</div>
        <q-markup-table class="tw-mb-5">
          <tr>
            <q-th class="text-right" width="10%">#</q-th>
            <q-th class="text-right" width="60%">Producto</q-th>
            <q-th class="text-right" width="10%">Alquilado</q-th>
            <q-th class="text-right" width="10%">Retornado</q-th>
            <q-th class="text-right" width="10%">Por retornar</q-th>
          </tr>
          <tbody>
            <tr v-for="(product, index) in contract.products">
              <q-td class="text-right">{{ index + 1 }}</q-td>
              <q-td class="tw-max-w-[200px] tw-text-ellipsis tw-overflow-hidden text-right">{{ product.name }}</q-td>
              <q-td class="text-right">{{ product.pivot.quantity }}</q-td>
              <q-td class="text-right">{{ product.pivot.mesu_return + product.pivot.re_rent_return  }}</q-td>
              <q-td class="text-right">{{ product.pivot.quantity - product.pivot.mesu_return - product.pivot.re_rent_return }}</q-td>
            </tr>
          </tbody>
        </q-markup-table>

      </template>

    </q-card-section>
  </q-card>
</template>

<script setup lang="ts">

import { Head, InertiaForm, router, useForm, usePage } from '@inertiajs/vue3';
import { useI18n } from "vue-i18n";
import { useQuasar } from 'quasar';
import { status } from "../../../Common/helpers";

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
