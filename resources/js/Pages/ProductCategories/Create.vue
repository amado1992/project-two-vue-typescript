<template>

    <Head :title="$t('productCategories.create')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="category" />
            </q-avatar>
            {{ $t('productCategories.create') }}
        </template>

        <div class="container">
            <q-card class="tw-p-6">
                <form @submit.prevent="submit">
                    <q-card-section>
                        <div class="md:tw-grid md:tw-grid-cols-3 md:tw-gap-5">
                            <TextInput
                                v-model="form.name"
                                :label="$t('fields.name')"
                                v-model:errors="form.errors.name"
                                :required="true"
                            />

                            <Select
                                v-model="form.product_category_id"
                                :label="$t('fields.father_category')"
                                :options="productCategories"
                                option-label="name"
                                option-value="id"
                                v-model:errors="form.errors.product_category_id"
                            />

                            <q-toggle
                                v-model="form.active"
                                checked-icon="check"
                                unchecked-icon="clear"
                                :label="$t('fields.active')"
                                :errors="form.errors.active"
                            />

                        </div>

                    </q-card-section>
                    <q-card-actions align="right">
                        <SecondaryButton to="productCategories.index">
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
import Select from "../../Components/Select.vue";
import route from "ziggy-js";
import {showNotification} from "../../Common/helpers";
import {useI18n} from "vue-i18n";

const page = usePage()

const i18n = useI18n();

const productCategories = page.props.productCategories

const form = useForm({
    name: '',
    active: true,
    product_category_id: null
})

function submit() {
    form.post(route('productCategories.store'), {
        preserveScroll: true,
        onError: () => {
            showNotification('negative', i18n.t('messages.validation_error'), 'top-right')
        }
    })
}
</script>
