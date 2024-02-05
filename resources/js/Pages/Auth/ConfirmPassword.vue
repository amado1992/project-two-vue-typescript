<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Confirm Password" />

        <div class="tw-mb-4 tw-text-sm tw-text-gray-600">
            {{ $t('messages.confirm_password') }}
        </div>

        <form @submit.prevent="submit" novalidate>
            <div>

                <TextInput
                    id="password"
                    type="password"
                    class="tw-mt-1 tw-block tw-w-full"
                    :label="$t('fields.password')"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    autofocus
                    v-model:errors="form.errors.password"
                />

            </div>

            <div class="tw-flex tw-justify-end tw-mt-4">
                <PrimaryButton class="tw-ml-4" :loading="form.processing" :disabled="form.processing">
                    {{ $t('actions.confirm') }}
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
