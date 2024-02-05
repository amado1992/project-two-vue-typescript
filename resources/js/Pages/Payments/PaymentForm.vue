<template>
    <div class="tw-mt-2 md:tw-grid xl:tw-grid-cols-3 md:tw-gap-5 tw-items-center">
        <TextInput
            v-model="form.date"
            :label="$t('fields.date')"
            v-model:errors="form.errors.date"
            required
        >
            <template v-slot:append>
                <q-icon name="event" class="cursor-pointer">
                    <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                        <DatePicker v-model="form.date" />
                    </q-popup-proxy>
                </q-icon>
            </template>
        </TextInput>

        <Select
            v-model="form.client_id"
            :label="$t('fields.client')"
            :options="clients"
            option-label="name"
            option-value="id"
            v-model:errors="form.errors.client_id"
            required
        />

        <span :class="{'text-negative': credit < 0}">
            <strong>{{ $t('fields.credit') + ': ' }}</strong>
            {{ money(credit) }}
        </span>
    </div>

   
    <Invoices :form="form" :credit="credit" required />
</template>

<script setup lang="ts">

import Invoices from "./Invoices.vue";
import Select from "../../Components/Select.vue";
import {InertiaForm} from "@inertiajs/vue3";
import {computed} from "vue";
import {money} from '../../Common/helpers';
import TextInput from "../../Components/TextInput.vue";
import DatePicker from "../../Components/DatePicker.vue";
import InputError from "../../Components/InputError.vue";

const props = defineProps<{
    clients: any[],
    form: InertiaForm<{
        date: string,
        client_id: number|null,
        invoices: any[]
    }>
}>()

const credit = computed(() => {

    const client = props.clients.find(c => c.id == props.form.client_id)

    if (client) {

        let _credit = client.credit

        props.form.invoices.forEach(i => {

            _credit -= i.credit
        })

        return _credit
    }

    return 0
});

const invalidCredit = computed(() => {

const client = props.clients.find(c => c.id == props.form.client_id)

if (client) {

    let _credit = client.credit

    props.form.invoices.forEach(i => {

        _credit -= i.credit
    })

    return _credit
}

return 0
})
</script>
