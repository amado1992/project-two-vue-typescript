<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, usePage} from '@inertiajs/vue3';
import {useI18n} from "vue-i18n";
import {can} from "@/Common/helpers";
import route from 'ziggy-js';

const page = usePage()
const i18n = useI18n();

</script>

<template>
<Head :title="$t('providers.import')" />

<AuthenticatedLayout>
    <template #header>
      <q-avatar>
                <q-icon name="warehouse" />
            </q-avatar>
        {{ $t('providers.import') }}
    </template>
    <div class="container">
            <q-card class="tw-p-6">
    <form @submit.prevent="submit" style="text-align: center;">
      <h4>{{ $t('providers.import') }}</h4>
      <input type="file" @change="fileChanged" />
      <button type="submit" :disabled="!file">Importar</button>
      
    </form>
    <a href="/download/7" style="display:grid;justify-content: center;color: darkblue;margin:10px 0px">Descargar plantilla</a>
  </q-card>
        </div>
  </AuthenticatedLayout>
  </template>

 
  <script>
  
  export default {
    data() {
      return {
        file: null
      };
    },
    methods: {
      fileChanged(e) {
        this.file = e.target.files[0];
      },
      submit() {
        let formData = new FormData();
        formData.append('file', this.file);
  
        // Usar Axios, Vue Resource, etc. para enviar una solicitud a tu servidor
        this.$inertia.post('/importprov', formData).then( /* manejar la respuesta */ );
      }
    }
  };
  </script>