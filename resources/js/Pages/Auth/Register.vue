<template>
    <GuestLayout>
        <Head :title="$t('register.title')" />

        <form @submit.prevent="submit" novalidate>
            <div>
                <TextInput
                    id="name"
                    type="text"
                    class="tw-mt-1 tw-block tw-w-full"
                    :label="$t('fields.name')"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    v-model:errors="form.errors.name"
                />
            </div>

            <div class="tw-mt-4">

                <TextInput
                    id="email"
                    type="email"
                    class="tw-mt-1 tw-block tw-w-full"
                    :label="$t('fields.email')"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    v-model:errors="form.errors.email"
                />
            </div>

            <div class="tw-mt-4">

                <TextInput
                    id="password"
                    type="password"
                    class="tw-mt-1 tw-block tw-w-full"
                    :label="$t('fields.password')"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                    v-model:errors="form.errors.password"
                />

            </div>

            <div class="tw-mt-4">

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="tw-mt-1 tw-block tw-w-full"
                    :label="$t('fields.password_confirmation')"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                    v-model:errors="form.errors.password_confirmation"
                />

            </div>

            <div class="tw-flex tw-items-center tw-justify-end tw-mt-4">
                <Link
                    :href="route('login')"
                    class="tw-text-sm tw-text-gray-600 hover:tw-text-gray-900 tw-rounded-md focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-indigo-500"
                >
                    {{ $t('messages.already_registered') }}
                </Link>

                <PrimaryButton class="tw-ml-4" :loading="form.processing" :disabled="form.processing">
                    {{ $t('actions.register') }}
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>

<script setup>

import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import route from "ziggy-js";

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>
