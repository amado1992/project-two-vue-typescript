<template>

    <Head :title="$t('clients.edit')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="switch_account" />
            </q-avatar>
            {{ $t('clients.edit') }}
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
                                v-model="form.ruc"
                                :label="$t('fields.ruc')"
                                v-model:errors="form.errors.ruc"
                                :required="true"
                            />

                            <TextInput
                                type="number"
                                step="1"
                                v-model="form.dv"
                                :label="$t('fields.dv')"
                                v-model:errors="form.errors.dv"
                                :required="true"
                            />
                        </div>

                      <div class="md:tw-grid md:tw-grid-cols-2 md:tw-gap-5">
                        <TextInput
                            v-model="form.ficha"
                            :label="$t('fields.ficha')"
                            v-model:errors="form.errors.ficha"
                            :required="false"
                        />
                        <TextInput
                            v-model="form.redi"
                            :label="$t('fields.redi')"
                            v-model:errors="form.errors.redi"
                            :required="false"
                        />
                      </div>

                        <div class="md:tw-grid md:tw-grid-cols-3 md:tw-gap-5">

                            <TextInput
                                v-model="form.address"
                                :label="$t('fields.address')"
                                v-model:errors="form.errors.address"
                            />

                            <q-toggle
                                v-model="form.active"
                                checked-icon="check"
                                unchecked-icon="clear"
                                :label="$t('fields.active')"
                                :errors="form.errors.active"
                            />

                            <q-toggle
                                v-model="form.no_taxes"
                                checked-icon="check"
                                unchecked-icon="clear"
                                :label="$t('fields.no_taxes')"
                                :errors="form.errors.no_taxes"
                            />
                        </div>

                        <h5 class="tw-mt-5 tw-mb-3 tw-font-medium">{{$t(('clients.contact_data'))}}</h5>
                        <div class="md:tw-grid md:tw-grid-cols-3 md:tw-gap-5">

                            <TextInput
                                type="email"
                                v-model="form.email"
                                :label="$t('fields.email')"
                                v-model:errors="form.errors.email"
                                :required="true"
                            />

                            <TextInput
                                v-model="form.phone"
                                :label="$t('fields.phone')"
                                v-model:errors="form.errors.phone"
                            />

                            <TextInput
                                v-model="form.mobile"
                                :label="$t('fields.mobile')"
                                v-model:errors="form.errors.mobile"
                            />
                        </div>

                        <h5 class="tw-mt-5 tw-mb-3 tw-font-medium">{{$t(('clients.legal_representative'))}}</h5>
                        <div class="md:tw-grid md:tw-grid-cols-3 md:tw-gap-5">

                            <TextInput
                                v-model="form.legal_representative"
                                :label="$t('fields.name')"
                                v-model:errors="form.errors.legal_representative"
                            />

                            <TextInput
                                v-model="form.cedula"
                                :label="$t('fields.cedula')"
                                v-model:errors="form.errors.cedula"
                            />

                        </div>

                        <div class="md:tw-grid md:tw-grid-cols-1 md:tw-gap-5">
                            <ContactInformation
                                :contacts="form.contacts"
                                :errors="form.errors"
                            />
                        </div>

                    </q-card-section>
                    <q-card-actions align="right">
                        <SecondaryButton to="clients.index">
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
import ContactInformation from "../../Components/ContactInformation.vue";
import {showNotification} from "../../Common/helpers";
import {useI18n} from "vue-i18n";
import {onMounted} from "vue";
import {lowerizeArray} from "../../Util/json";

const i18n = useI18n();

const page = usePage()

const form = useForm({
    name: page.props.client.name,
    active: page.props.client.active,
    no_taxes: page.props.client.no_taxes,
    ruc: page.props.client.ruc,
    dv: page.props.client.dv,
    phone: page.props.client.phone,
    mobile: page.props.client.mobile,
    email: page.props.client.email,
    address: page.props.client.address,
    legal_representative: page.props.client.legal_representative,
    cedula: page.props.client.cedula,
    contacts: page.props.client?.contacts ? lowerizeArray(JSON.parse(page.props.client?.contacts ?? [])) : [],
    ficha: page.props.client.ficha ?? " ",
    redi: page.props.client.redi ?? " ",
})

function submit() {

    form.put(route('clients.update', {
        client: page.props.client.id
    }), {
        preserveScroll: true,
        onError: () => {
            showNotification('negative', i18n.t('messages.validation_error'), 'top-right')
        }
    })
}
</script>
