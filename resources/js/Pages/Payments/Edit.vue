<template>
    <Head :title="$t('payments.edit')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="payments" />
            </q-avatar>
            {{ $t('payments.edit') }}
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
import PaymentForm from "./PaymentForm.vue";
import SecondaryButton from "../../Components/SecondaryButton.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import route from "ziggy-js";

const props = defineProps<{
    payment: any,
    clients: any[]
}>()

const form = useForm({
    date: props.payment.date,
    client_id: props.payment.client_id,
    invoices: props.payment.invoices.map(i => {
        return {
            id: i.id,
            contract: i.contract.id,
            project: i.contract.project.name,
            total: i.total,
            credit: i.pivot.credit
        }
    })
})

function submit() {
    form.put(route('payments.update', {payment: props.payment.id}), {
        preserveScroll: true
    })
}
</script>
