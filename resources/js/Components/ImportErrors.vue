<template lang="">
        <q-card elevated class="tw-w-full tw-h-full">
            <q-list>
                <q-item class="tw-p-3 tw-m-3" v-for="item in list">
                    <q-item-section avatar>
                      <q-icon color="red" name="info" />
                    </q-item-section>
                    <q-item-section>{{ item }}</q-item-section>
                  </q-item>
            </q-list>
            <q-card-actions align="center">
                <q-btn v-close-popup color="red">Cerrar</q-btn>
            </q-card-actions>
        </q-card>

</template>
<script setup lang="ts">

  const props = defineProps<{
    list:string
}>()

</script>

