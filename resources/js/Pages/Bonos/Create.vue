<template>
    <Head :title="$t('bonos.create')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="attach_money" />
            </q-avatar>
            {{ $t('bonos.create') }}
        </template>

        <div class="container">
            <q-card class="tw-p-6">
                <q-card-section>
                    <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 xl:tw-grid-cols-3 tw-gap-5">

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
                            :options="clients"
                            option-label="name"
                            option-value="id"
                            v-model:errors="form.errors.client_id"
                            :label="$t('fields.client')"
                            required
                        />

                        <TextInput
                            v-model="form.credit"
                            :label="$t('fields.credit')"
                            v-model:errors="form.errors.credit"
                            type="number"
                            step="any"
                            min="1"
                            required
                        />
                    </div>
                </q-card-section>
                <q-card-actions align="right">
                    <SecondaryButton to="bonos.index">{{ $t('actions.cancel') }}</SecondaryButton>
                    <PrimaryButton @click="submit" :loading="form.processing">
                        {{ $t('actions.save') }}
                    </PrimaryButton>
                </q-card-actions>
            </q-card>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">

import {Head, useForm} from "@inertiajs/vue3";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import Select from "../../Components/Select.vue";
import TextInput from "../../Components/TextInput.vue";
import SecondaryButton from "../../Components/SecondaryButton.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import route from "ziggy-js";
import DatePicker from "../../Components/DatePicker.vue";
import {date} from "quasar";

const props = defineProps<{
    clients: any[]
}>()

const form = useForm({
    date: date.formatDate(new Date(), 'YYYY-MM-DD'),
    client_id: null,
    credit: 0
})

function submit() {
    form.post(route('bonos.store'), {
        preserveScroll: true
    })
}
</script>
