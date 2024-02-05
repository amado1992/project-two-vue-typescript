<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import route from "ziggy-js";

const props = defineProps({
    status: String,
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <GuestLayout>
        <Head :title="$t('verify_email.title')" />

        <div class="tw-mb-4 tw-text-sm tw-text-gray-600">
            {{ $t('verify_email.subtitle') }}
        </div>

        <div class="tw-mb-4 tw-font-medium tw-text-sm tw-text-green-600" v-if="verificationLinkSent">
            {{ $t('verify_email.verification_resent') }}
        </div>

        <form @submit.prevent="submit" novalidate>
            <div class="tw-mt-4 tw-flex tw-items-center tw-justify-between">
                <PrimaryButton :loading="form.processing" :disabled="form.processing">
                    {{ $t('actions.resend_verification') }}
                </PrimaryButton>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="tw-text-sm tw-text-gray-600 hover:tw-text-gray-900 tw-rounded-md focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-indigo-500"
                    >{{ $t('actions.logout') }}</Link
                >
            </div>
        </form>
    </GuestLayout>
</template>
