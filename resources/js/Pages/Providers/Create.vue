<template>

    <Head :title="$t('providers.create')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="warehouse" />
            </q-avatar>
            {{ $t('providers.create') }}
        </template>

        <div class="container">
            <q-card class="tw-p-6">
                <form @submit.prevent="submit" novalidate>
                    <q-card-section>
                        <div class="md:tw-grid md:tw-grid-cols-3 md:tw-gap-5">
                            <TextInput
                                v-model="form.name"
                                :label="$t('fields.name')"
                                v-model:errors="form.errors.name"
                                :required="true"
                            />

                            <TextInput
                                v-model="form.phone"
                                :label="$t('fields.phone')"
                                v-model:errors="form.errors.phone"
                                :required="true"
                            />

                            <TextInput
                                v-model="form.ruc"
                                :label="$t('fields.ruc')"
                                v-model:errors="form.errors.ruc"
                                :required="true"
                            />

                            <TextInput
                                type="number"
                                step="1"
                                min="0"
                                max="99"
                                v-model="form.dv"
                                :label="$t('fields.dv')"
                                v-model:errors="form.errors.dv"
                                :required="true"
                            />

                            <q-toggle
                                v-model="form.active"
                                checked-icon="check"
                                unchecked-icon="clear"
                                :label="$t('fields.active')"
                                :errors="form.errors.active"
                            />

                        </div>

                        <div class="md:tw-grid md:tw-grid-cols-1 md:tw-gap-5">

                            <TextInput
                                v-model="form.address"
                                :label="$t('fields.address')"
                                v-model:errors="form.errors.address"
                                :required="true"
                            />
                        </div>

                        <div class="md:tw-grid md:tw-grid-cols-1 md:tw-gap-5">
                            <ProviderContact :contacts="form.contacts" :errors="form.errors"/>
                        </div>

                    </q-card-section>
                    <q-card-actions align="right" class="tw-mt-2">
                        <SecondaryButton to="providers.index">
                            {{ $t('actions.cancel') }}
                        </SecondaryButton>
                        <PrimaryButton type="submit" :loading="form.processing">
                            {{ $t('actions.ok') }}
                        </PrimaryButton>
                    </q-card-actions>
                </form>
            </q-card>
        </div>

    </AuthenticatedLayout>
</template>

<script setup lang="ts">

import {Head, useForm, usePage} from "@inertiajs/vue3";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import TextInput from "../../Components/TextInput.vue";
import SecondaryButton from "../../Components/SecondaryButton.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import route from "ziggy-js";
import {showNotification} from "../../Common/helpers";
import {useI18n} from "vue-i18n";
import ProviderContact from "../../Components/ProviderContact.vue";

const page = usePage()

const i18n = useI18n();

const form = useForm({
    name: '',
    phone: '',
    address: '',
    ruc: '',
    dv: '',
    contacts: [],
    active: true,
})




function submit() {

    form.post(route('providers.store'), {
        preserveScroll: true,
        onError: () => {
            showNotification('negative', i18n.t('messages.validation_error'), 'top-right')
        }
    })
}

</script>
