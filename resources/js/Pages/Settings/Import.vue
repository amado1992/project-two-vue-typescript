<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Import from "@/Components/Import.vue"
import { Head, usePage } from "@inertiajs/vue3";
import { useI18n } from "vue-i18n";
import { can } from "@/Common/helpers";
import { ref } from "vue";
import { router } from '@inertiajs/vue3'
import axios from "axios";

const page = usePage();
const i18n = useI18n();
const file = ref(null);
function fileChanged(e) {
    file.value = e.target.files[0];
    console.log(file);
}

async function submit() {
    let formData = new FormData();
    formData.append("file", file.value);
    router.post('/importsettings', formData, {
  forceFormData: true,
})

}
</script>

<template>
    <Head :title="$t('setting.import')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="construction" />
            </q-avatar>
            {{ $t("settings.import") }}
        </template>
        <Import title="Importar configuraciones generales"></Import>
    </AuthenticatedLayout>
</template>
