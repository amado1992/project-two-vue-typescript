<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    email: String,
    token: String,
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head :title="$t('reset_password.title')" />

        <form @submit.prevent="submit" novalidate>
            <div>

                <TextInput
                    id="email"
                    type="email"
                    class="tw-mt-1 tw-block tw-w-full"
                    :label="$t('fields.email')"
                    v-model="form.email"
                    required
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
                    required
                    autocomplete="new-password"
                    :errors="form.errors.password"
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
                    :errors="form.errors.password_confirmation"
                />

            </div>

            <div class="tw-flex tw-items-center tw-justify-end tw-mt-4">
                <PrimaryButton :loading="form.processing" :disabled="form.processing">
                    {{ $t('actions.reset_password') }}
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
