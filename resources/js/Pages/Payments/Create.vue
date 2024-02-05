<template>
    <Head :title="$t('payments.create')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="payments" />
            </q-avatar>
            {{ $t('payments.create') }}
        </template>

        <div class="container">
            <q-card class="tw-p-6">
                <q-card-section>
                    <PaymentForm :form="form" :clients="clients" />
                </q-card-section>
                <q-card-actions align="right">
                    <SecondaryButton to="payments.index">{{ $t('actions.cancel') }}</SecondaryButton>
                    <PrimaryButton @click="submit" :loading="form.processing">{{ $t('actions.save') }}</PrimaryButton>
                </q-card-actions>
            </q-card>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">

import {Head, useForm} from "@inertiajs/vue3";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import SecondaryButton from "../../Components/SecondaryButton.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import route from "ziggy-js";
import PaymentForm from "./PaymentForm.vue";
import {date, useQuasar} from "quasar";
import {useI18n} from "vue-i18n";

const props = defineProps<{
    clients: any[]
}>()

const i18n = useI18n()
const $q = useQuasar()

const form = useForm({
    date: date.formatDate(new Date(), 'YYYY-MM-DD'),
    client_id: null,
    invoices: []
})

function submit() {

    $q.dialog({
        title: i18n.t('messages.create_confirmation'),
        message: i18n.t('messages.create_confirmation_msg'),
        cancel: true,
        persistent: true
    }).onOk(() => {

        form.post(route('payments.store'), {
            preserveScroll: true
        })
    })
}
</script>
