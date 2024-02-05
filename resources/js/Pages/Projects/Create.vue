<template>
    <Head :title="$t('projects.create')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="foundation" />
            </q-avatar>
            {{ $t('projects.create') }}
        </template>

        <div class="container">
            <q-card class="tw-p-6">
                <q-card-section>
                    <ProjectForm
                        class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 xl:tw-grid-cols-3 tw-gap-5"
                        :form="form"
                        :clients="clients"
                    />
                </q-card-section>
                <q-card-actions align="right">
                    <SecondaryButton to="projects.index">
                        {{ $t('actions.cancel') }}
                    </SecondaryButton>
                    <PrimaryButton @click="submit" :loading="form.processing">
                        {{ $t('actions.ok') }}
                    </PrimaryButton>
                </q-card-actions>
            </q-card>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">

import {Head, useForm} from "@inertiajs/vue3";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import TextInput from "../../Components/TextInput.vue";
import SecondaryButton from "../../Components/SecondaryButton.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import Select from "../../Components/Select.vue";
import route from "ziggy-js";
import ProjectForm from "./ProjectForm.vue";

defineProps<{
    clients: []
}>()

const form = useForm({
    name: '',
    address: '',
    project_manager: '',
    project_manager_phone: '',
    construction_manager: '',
    construction_manager_phone: '',
    in_charge_to_pay: '',
    in_charge_to_pay_phone: '',
    client_id: null
})

function submit() {
    form.post(route('projects.store'))
}
</script>
