<script setup>

import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import route from 'ziggy-js'

const props = defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>
        <header>
            <h2 class="tw-text-lg tw-font-medium tw-text-gray-900">{{ $t('profile_info.title') }}</h2>

            <p class="tw-mt-1 tw-text-sm tw-text-gray-600">
                {{ $t('profile_info.subtitle') }}
            </p>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))" class="tw-mt-6 tw-space-y-6">

            <TextInput
                id="name"
                type="text"
                :label="$t('fields.name')"
                v-model="form.name"
                required
                autofocus
                autocomplete="name"
                v-model:errors="form.errors.name"
            />

            <TextInput
                id="email"
                type="email"
                :label="$t('fields.email')"
                v-model="form.email"
                required
                autocomplete="username"
                v-model:errors="form.errors.email"
            />

            <div v-if="props.mustVerifyEmail && user.email_verified_at === null">
                <p class="tw-text-sm tw-mt-2 tw-text-gray-800">
                    {{ $t('messages.email_unverified') }}
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="tw-underline tw-text-sm tw-text-gray-600 hover:tw-text-gray-900 tw-rounded-md focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-indigo-500"
                    >
                        {{ $t('actions.resend_verification_email') }}
                    </Link>
                </p>

                <div
                    v-show="props.status === 'verification-link-sent'"
                    class="tw-mt-2 tw-font-medium tw-text-sm tw-text-green-600"
                >
                    {{ $t('messages.verification_resent') }}
                </div>
            </div>

            <div class="tw-flex tw-items-center tw-gap-4">
                <PrimaryButton :disabled="form.processing" :loading="form.processing">
                    {{ $t('actions.save') }}
                </PrimaryButton>
            </div>
        </form>
    </section>
</template>
