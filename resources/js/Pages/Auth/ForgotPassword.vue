<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <div class="tw-mb-4 tw-text-sm tw-text-gray-600">
            {{  $t('messages.forgot_password_msg')}}
        </div>

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
                    required
                    autofocus
                    autocomplete="username"
                    v-model:errors="form.errors.email"
                />

            </div>

            <div class="tw-flex tw-items-center tw-justify-end tw-mt-4">
                <PrimaryButton :loading="form.processing" :disabled="form.processing">
                    {{ $t('actions.send_email')}}
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
