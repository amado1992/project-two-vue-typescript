<template>
    <q-dialog ref="dialogRef" @hide="onDialogHide" full-width persistent>
        <q-card class="q-dialog-plugin">

            <q-card-section>
                <div class="text-h6">{{ $t('projects.create') }}</div>

                <ProjectForm
                    class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 xl:tw-grid-cols-3 tw-gap-5"
                    :form="form"
                    :clients="clients"
                />
            </q-card-section>

            <q-card-actions align="right">
                <q-btn color="primary" flat :label="$t('actions.cancel')" @click="onDialogCancel" />
                <q-btn color="primary" flat :label="$t('actions.ok')" @click="submit" :loading="form.processing" />
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>

<script setup lang="ts">

import {useDialogPluginComponent} from "quasar";
import {useForm} from "@inertiajs/vue3";
import ProjectForm from "./ProjectForm.vue";
import route from "ziggy-js";

defineProps<{
    clients: []
}>()

defineEmits([
    ...useDialogPluginComponent.emits
])

const { dialogRef, onDialogHide, onDialogOK, onDialogCancel } = useDialogPluginComponent()

const form = useForm({
    name: '',
    address: '',
    project_manager: '',
    project_manager_phone: '',
    construction_manager: '',
    construction_manager_phone: '',
    in_charge_to_pay: '',
    in_charge_to_pay_phone: '',
    client_id: null,
    redirect_to: window.location.href
})

function submit() {

    form.post(route('projects.store'), {
        preserveScroll: true,
        onSuccess: params => {

            onDialogOK(params.props.redirect_data)
        }
    })
}
</script>
