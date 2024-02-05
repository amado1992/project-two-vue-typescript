<template>

    <Head :title="$t('roles.edit')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="verified_user" />
            </q-avatar>
            {{ $t('roles.edit') }}
        </template>

        <div class="container">
            <q-card class="tw-p-5">
                <form @submit.prevent="submit">
                    <q-card-section>
                        <div class="tw-w-1 md:tw-w-1/2">
                            <TextInput
                                v-model="form.name"
                                :label="$t('fields.name')"
                                v-model:errors="form.errors.name"
                                :required="true"
                            />
                        </div>
                        <div class="sm:tw-grid md:tw-grid-cols-2 xl:tw-grid-cols-3">
                            <div v-for="module in form.modules">
                                <q-expansion-item default-opened header-class="tw-font-bold">
                                    <template #header>
                                        <q-checkbox
                                            class="tw-w-full"
                                            v-model="module.active"
                                            :label="module.name"
                                        />
                                    </template>

                                    <q-card>
                                        <q-card-section>
                                            <div class="tw-pl-2" v-for="permission in module.permissions">
                                                <q-checkbox v-model="permission.active" :label="permission.translate_name" />
                                            </div>
                                        </q-card-section>
                                    </q-card>
                                </q-expansion-item>
                            </div>
                        </div>
                    </q-card-section>
                    <q-card-actions align="right">
                        <SecondaryButton to="roles.index">{{ $t('actions.cancel') }}</SecondaryButton>
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

import {Head, router, useForm, usePage} from "@inertiajs/vue3";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import TextInput from "../../Components/TextInput.vue";
import SecondaryButton from "../../Components/SecondaryButton.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import route from "ziggy-js";
import {Module} from "../../Models/Module";

const page = usePage()

const form = useForm({
    name: page.props.name,
    modules: Module.fromDataServer(page.props.modules)
})

function submit() {

    form.put(route('roles.update', {
        role: page.props.id as number
    }), {
        preserveScroll: true,
        onSuccess: () => {
            //window.location.reload()
        }
    })
}
</script>
