<template>

    <Head :title="$t('users.edit')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="group" />
            </q-avatar>
            {{ $t('users.edit') }}
        </template>

        <div class="container">
            <q-card class="tw-p-5">
                <form @submit.prevent="submit">
                    <q-card-section>
                        <div class="md:tw-grid md:tw-grid-cols-2 md:tw-gap-5">
                            <TextInput
                                v-model="form.name"
                                :label="$t('fields.name')"
                                v-model:errors="form.errors.name"
                                :required="true"
                            />

                            <TextInput
                                v-model="form.lastname"
                                :label="$t('fields.lastname')"
                                v-model:errors="form.errors.lastname"
                                :required="true"
                            />

                            <TextInput
                                v-model="form.email"
                                type="email"
                                :label="$t('fields.email')"
                                v-model:errors="form.errors.email"
                                :required="true"
                            />

                            <TextInput
                                v-model="form.password"
                                type="password"
                                :label="$t('fields.password')"
                                v-model:errors="form.errors.password"
                                :required="true"
                            />

                            <TextInput
                                v-model="form.password_confirmation"
                                type="password"
                                :label="$t('fields.password_confirmation')"
                                v-model:errors="form.errors.password_confirmation"
                                :required="true"
                            />

                            <Select
                                v-model="form.role"
                                :label="$t('fields.role')"
                                :options="roles"
                                option-label="name"
                                option-value="name"
                                v-model:errors="form.errors.role"
                                :required="true"
                            />

                            <Select
                                v-model="form.client"
                                :label="$t('fields.belongs_to')"
                                :options="clients"
                                option-label="name"
                                option-value="id"
                                v-model:errors="form.errors.client"
                            />

                            <q-toggle v-model="form.active" :label="$t('fields.active')" />
                        </div>
                    </q-card-section>
                    <q-card-actions align="right">
                        <SecondaryButton to="users.index">
                            {{ $t('actions.cancel') }}
                        </SecondaryButton>
                        <PrimaryButton type="submit" :loading="form.processing">
                            {{ $t('actions.update') }}
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
import Select from "@/Components/Select.vue";

const page = usePage()

const roles = page.props.roles
const clients = page.props.clients


const form = useForm({
    name: page.props.user.name,
    lastname: page.props.user.lastname,
    email: page.props.user.email,
    password: '',
    password_confirmation: '',
    role: page.props.user.role?.name,
    client: page.props.user.client_id,
    active: page.props.user.active
})

function submit() {

    form.put(route('users.update', {
        user: page.props.user.id
    }), {
        preserveScroll: true
    })
}
</script>
