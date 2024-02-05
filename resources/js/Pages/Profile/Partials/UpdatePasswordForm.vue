<script setup>

import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import route from "ziggy-js";

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {

    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
            }
            if (form.errors.current_password) {
                form.reset('current_password');
            }
        },
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="tw-text-lg tw-font-medium tw-text-gray-900">{{ $t('update_password.title') }}</h2>

            <p class="tw-mt-1 tw-text-sm tw-text-gray-600">
                {{ $t('update_password.subtitle') }}
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="tw-mt-6 tw-space-y-6">

            <TextInput
                id="current_password"
                v-model="form.current_password"
                :label="$t('fields.current_password')"
                type="password"
                autocomplete="current-password"
                v-model:errors="form.errors.current_password"
            />

            <TextInput
                id="password"
                v-model="form.password"
                :label="$t('fields.password')"
                type="password"
                autocomplete="new-password"
                v-model:errors="form.errors.password"
            />

            <TextInput
                id="password_confirmation"
                v-model="form.password_confirmation"
                :label="$t('fields.password_confirmation')"
                type="password"
                autocomplete="new-password"
                v-model:errors="form.errors.password_confirmation"
            />

            <div class="tw-flex tw-items-center gap-4">
                <PrimaryButton :loading="form.processing" :disabled="form.processing">
                    {{ $t('actions.save') }}
                </PrimaryButton>
            </div>
        </form>
    </section>
</template>
