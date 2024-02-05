<template>

    <Head :title="$t('productCategories.edit')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="category" />
            </q-avatar>
            {{ $t('productCategories.edit') }}
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
import {useI18n} from "vue-i18n";
import {showNotification} from "../../Common/helpers";
import {computed} from "vue";

const page = usePage()

const i18n = useI18n();

const productCategories = computed(() => {

    const list = []

    list.push({
        name: i18n.t('productCategories.none'),
        id: null
    })

    list.push(...page.props.productCategories)

    return list
})// = page.props.productCategories

const form = useForm({
    name: page.props.productCategory.name,
    active: page.props.productCategory.active,
    product_category_id: page.props.productCategory.product_category_id,
})

function submit() {

    form.put(route('productCategories.update', {
        productCategory: page.props.productCategory.id
    }), {
        preserveScroll: true,
        onError: () => {
            showNotification('negative', i18n.t('messages.validation_error'), 'top-right')
        }
    })
}
</script>
