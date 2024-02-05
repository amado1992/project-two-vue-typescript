<template>
    <div class="flex flex-row">
        <div class="tw-basis-1/4">
            <h5 class="tw-mt-5 tw-mb-3 tw-font-medium">{{$t(('clients.contacts'))}}</h5>
        </div>
        <div class="tw-basis-1/4 tw-mt-3">

            <q-btn round color="primary" icon="person_add" @click="addField()">
                <q-tooltip class="dark text-body2" :offset="[10, 10]">
                    {{$t('clients.add_contact')}}
                </q-tooltip>
            </q-btn>
        </div>
    </div>
    <TransitionGroup name="contacts" tag="div">
        <div v-for="(element, index) in contacts" :key="index">
            <div class="flex tw-gap-3 tw-mb-2">
                <TextInput
                    :required="true"
                    v-model="element.name"
                    :label="$t('fields.name')"
                    class="tw-flex-1 tw-w-64 tw-pb-0"
                    v-model:errors="errors['contacts.' + index + '.name']"
                />
                <TextInput
                    :required="true"
                    v-model="element.position"
                    :label="$t('fields.position')"
                    class="tw-flex-1 tw-w-64 tw-pb-0"
                    v-model:errors="errors['contacts.' + index + '.position']"
                />
                <TextInput
                    :required="true"
                    type="email"
                    v-model="element.email"
                    :label="$t('fields.email')"
                    class="tw-flex-1 tw-w-64 tw-pb-0"
                    v-model:errors="errors['contacts.' + index + '.email']"
                />
                <div class="tw-flex-none tw-mt-4">
                    <DangerButton
                        color="white"
                        text-color="negative"
                        icon="person_remove"
                        class="tw-w-1/2 tw-px-5"
                        @click.prevent="lessField(index)"
                    />
                </div>
            </div>
            <div class="flex tw-gap-3 tw-mb-2 tw-mt-4">
                <TextInput
                    :required="true"
                    v-model="element.phone"
                    :label="$t('fields.phone')"
                    class="tw-flex-1 tw-w-64 tw-pb-0"
                    v-model:errors="errors['contacts.' + index + '.phone']"
                />
                <TextInput
                    :required="true"
                    v-model="element.mobile"
                    :label="$t('fields.mobile')"
                    class="tw-flex-1 tw-w-64 tw-pb-0"
                    v-model:errors="errors['contacts.' + index + '.mobile']"
                />
            </div>
        </div>
    </TransitionGroup>
</template>

<script setup lang="ts">

import TextInput from "./TextInput.vue";
import { showNotification } from '../Common/helpers'
import {useI18n} from "vue-i18n";
import DangerButton from "./DangerButton.vue";

const props = defineProps({
    /**
     * Contacts of a Provider
     */
    contacts: { required: false, type: Array },
    errors: {required: false}
})

const i18n = useI18n();

function addField() {

    if (validateInput()) {
        props.contacts.push({
            name: '',
            phone: '',
            position: '',
            mobile: '',
            email: ''
        })
    } else {
        showNotification('negative', i18n.t('clients.fill_contact_fields'))
    }
}

function lessField(index) {

    if (props.contacts.length > 0) {

        props.contacts.splice(index, 1)
    }
}

function validateInput() {

    if (props.contacts.length > 0) {

        for (let i in props.contacts) {

            if (props.contacts[i].name == "")
                return false
            if (props.contacts[i].phone == "")
                return false
            if (props.contacts[i].mobile == "")
                return false
            if (props.contacts[i].email == "") {
                return false
            } else if (!validateEmail(props.contacts[i].email)) {
                showNotification('negative', i18n.t('messages.email_invalid'))
                return false
            }

            if (props.contacts[i].position == "")
                return false
        }
    }

    return true
}

const validateEmail = (email) => {
    return String(email)
        .toLowerCase()
        .match(
            /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
        );
};

function getEmailError(vIndex): string|null {

    try {
        let field = "contacts." + vIndex + '.email'

        return <string> props.errors[field]
    } catch (e) {

        return null
    }
}

</script>

<style scoped>
.contacts-enter-active,
.contacts-leave-active {
    transition: all 0.5s ease;
}

.contacts-enter-from,
.contacts-leave-to {
    opacity: 0;
    translate: 0 -20px;
}
</style>
