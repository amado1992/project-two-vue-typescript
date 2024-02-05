<template>
    <Head :title="$t('inventories.reasons.create')" />
    <AuthenticatedLayout>
        <template #header>
            {{ $t('inventories.reasons.create') }}
        </template>

        <div class="container">
            <q-card class="tw-p-6">
                <q-card-section>
                    <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 xl:tw-grid-cols-3 tw-gap-5">
                        <TextInput
                            v-model="form.name"
                            :label="$t('fields.name')"
                            v-model:errors="form.errors.name"
                            :required="true"
                        />

                        <q-toggle v-model="form.active" :label="$t('fields.active')" />
                    </div>
                </q-card-section>
                <q-card-actions align="right">
                    <SecondaryButton to="reasons.index">
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
import PrimaryButton from "../../Components/PrimaryButton.vue";
import SecondaryButton from "../../Components/SecondaryButton.vue";
import route from "ziggy-js";

const form = useForm({
    name: '',
    active: true
})

function submit() {
    form.post(route('reasons.store'))
}
</script>
