<template>
    <GuestLayout>
        <Head :title="$t('login.title')" />

        <div v-if="status" class="tw-mb-4 tw-font-medium tw-text-sm tw-text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit" novalidate>
            <div>
                <TextInput
                    id="email"
                    type="email"
                    class="tw-mt-1 tw-block tw-w-full"
                    :label="$t('fields.email')"
                    v-model="form.email"
                    autofocus
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
                    autocomplete="current-password"
                    v-model:errors="form.errors.password"
                />
            </div>

            <div class="tw-block tw-mt-4">
                <label class="tw-flex tw-items-center">
                    <q-checkbox v-model="form.remember" :label="$t('login.remember')" dense />
                </label>
            </div>

            <div class="tw-flex tw-items-center tw-justify-end tw-mt-4">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="tw-text-sm tw-text-gray-600 hover:tw-text-gray-900 tw-rounded-md focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-indigo-500"
                >
                    {{ $t('messages.forgot_password') }}
                </Link>

                <PrimaryButton class="tw-ml-4" :loading="form.processing" :disabled="form.processing">
                    {{ $t('actions.login') }}
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

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>
