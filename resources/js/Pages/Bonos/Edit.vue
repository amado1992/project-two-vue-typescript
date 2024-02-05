<template>
    <Head :title="$t('bonos.edit')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="attach_money" />
            </q-avatar>
            {{ $t('bonos.edit') }}
        </template>

        <div class="container">
            <q-card class="tw-p-6">
                <q-card-section>
                    <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 xl:tw-grid-cols-3 tw-gap-5 tw-items-center">

                        <div>
                            <span>
                                <strong>{{ $t('fields.client') + ': ' }}</strong>
                                {{ bono.client.name }}
                            </span>
                        </div>

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
import TextInput from "../../Components/TextInput.vue";
import SecondaryButton from "../../Components/SecondaryButton.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import route from "ziggy-js";
import DatePicker from "../../Components/DatePicker.vue";

const props = defineProps<{
    bono: any
}>()

const form = useForm({
    date: props.bono.date,
    credit: props.bono.credit
})

function submit() {
    form.put(route('bonos.update', {bono: props.bono.id}), {
        preserveScroll: true
    })
}
</script>
