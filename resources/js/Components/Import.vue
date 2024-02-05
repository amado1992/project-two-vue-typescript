<script setup lang="ts">

import { useI18n } from "vue-i18n";
import { ref } from "vue";
import { router } from '@inertiajs/vue3'
import axios from "axios";
import route from "ziggy-js";

const props = defineProps<{
    title:string,
    routeName:string
    downloadurl?: string
}>()

const emits = defineEmits<{
    (e: 'finish'): void,
    (e: 'errors',v): void
}>()

const i18n = useI18n();
const file = ref(null);
const loading = ref(false);
function fileChanged(e) {
    file.value = e.target.files[0];

}

async function submit() {
    loading.value = true
    let formData = new FormData();
    formData.append("file", file.value);
    axios({
        method: "post",
        url: route(props.routeName),
        data: formData,
        headers: { "Content-Type": "multipart/form-data" }
    }).then(()=>{
        emits('finish')
        loading.value = false
    }).catch((error)=>{
        emits('errors',error.response.data)
        loading.value = false
    })
}

async function download(){
    axios.get(props.downloadurl as string,{
        responseType: 'blob',
    })
        .then(response => {
            let blob = new Blob([response.data], {
                type: 'application/octet-stream',
            });

            if (typeof window.navigator.msSaveBlob !== 'undefined') {

                window.navigator.msSaveBlob(blob, filename);
            } else {
                let blobURL = window.URL.createObjectURL(blob);
                let tempLink = document.createElement('a');
                tempLink.style.display = 'none';
                tempLink.href = blobURL;
                tempLink.download = 'file.xlsx';
                tempLink.click();
                window.URL.revokeObjectURL(blobURL);
            }
        })
}


</script>

<template>
       <div class="container">
        <form style="text-align: center">
            <q-card class="tw-p-6 tw-flex tw-justify-center tw-items-center tw-flex-col" flat bordered>
                <q-card-section horizontal>
                  <q-card-section class="q-pt-xs">
                    <div class="text-h5 q-mt-sm q-mb-xs">{{title}}</div>
                    <div class="text-caption text-grey">
                        Seleccione el archivo que utilizará para importar sus datos
                    </div>
                    <q-file class="q-mt-md" accept=".xlsx" rounded outlined bottom-slots v-model="file" label="" counter max-files="1">
                        <template v-slot:before>
                          <q-icon color="primary" name="cloud_upload" />
                        </template>

                        <template v-slot:append>
                            <q-icon name="close" @click.stop.prevent="file = null" class="cursor-pointer" />
                        </template>

                      </q-file>
                  </q-card-section>


                </q-card-section>

                <q-separator />

                <q-card-actions>
                  <q-btn
                  :href="downloadurl"

                  v-if="downloadurl" class="tw-p-5" icon="attachment" >
                    Descargar plantilla

                  </q-btn>
                  <q-btn  :loading="loading" class="tw-p-5" color="primary" @click="submit" :disable="file == null"
                    >Importar</q-btn
                >
                </q-card-actions>
              </q-card>
            </form>
       </div>
</template>
