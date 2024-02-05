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
            <div class="flex tw-gap-3">
                <TextInput
                    v-model="element.name"
                    :label="$t('fields.name')"
                    class="tw-flex-1 tw-w-64"
                    v-model:errors="errors['contacts.' + index + '.name']"
                />
                <TextInput
                    v-model="element.phone"
                    :label="$t('fields.phone')"
                    class="tw-flex-1 tw-w-64"
                    v-model:errors="errors['contacts.' + index + '.phone']"
                />
                <div class="tw-flex-none tw-mt-4">
                    <q-btn text-color="negative" icon="person_remove" class="tw-w-1/2 tw-px-5" @click="lessField(index)">
                        <q-tooltip class="dark text-body2" :offset="[10, 10]">
                            {{$t('clients.remove_contact')}}
                        </q-tooltip>
                    </q-btn>
                </div>
            </div>
        </div>
    </TransitionGroup>
</template>

<script setup lang="ts">

import TextInput from "./TextInput.vue";
import { showNotification } from '../Common/helpers'
import {useI18n} from "vue-i18n";

const props = defineProps({
    /**
     * Contacts of a Client
     */
     contacts: { required: true, type: Array },
    errors: {required: true}
})

const i18n = useI18n();

function addField() {


    if (validateInput()) {
        props.contacts.push({
            name: '',
            phone: ''
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
        }
    }

    return true
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
