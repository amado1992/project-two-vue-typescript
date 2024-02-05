<script setup>

import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import {useQuasar} from "quasar";
import route from "ziggy-js";
import {useI18n} from "vue-i18n";

const $q = useQuasar()
const i18n = useI18n()

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {

    $q.dialog({
        title: i18n.t('delete_account.question'),
        message: i18n.t('delete_account.subtitle'),
        prompt: {
            model: form.password,
            type: 'password',
            label: i18n.t('fields.password')
        },
        cancel: i18n.t('actions.cancel'),
        persistent: true,
        ok: i18n.t('actions.ok')
    }).onOk(() => {
        deleteUser()
    })
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>

<template>
    <section class="tw-space-y-6">
        <header>
            <h2 class="tw-text-lg tw-font-medium tw-text-gray-900">{{ $t('delete_account.title') }}</h2>

            <p class="tw-mt-1 tw-text-sm tw-text-gray-600">
                {{ $t('delete_account.subtitle') }}
            </p>
        </header>

        <DangerButton @click="confirmUserDeletion">{{ $t('actions.delete_account') }}</DangerButton>
    </section>
</template>
