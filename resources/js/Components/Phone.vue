<template>
    <div class="flex flex-row">
        <div class="tw-basis-1/4 tw-relative">
            <h5 class="tw-mt-5 tw-mb-3 tw-font-medium">{{$t(('companies.phones'))}}</h5>
            <Transition>
                <span class="tw-absolute" v-show="!contacts.length">{{ $t('messages.no_data') }}</span>
            </Transition>
        </div>
        <div class="tw-basis-1/4 tw-mt-3">

            <q-btn round color="primary" icon="person_add" @click="addField()" v-if="canAdd">
                <q-tooltip class="dark text-body2" :offset="[10, 10]">
                    {{$t('companies.add_phone')}}
                </q-tooltip>
            </q-btn>
        </div>
    </div>
    <TransitionGroup name="contacts" tag="div">
        <div v-for="(element, index) in contacts" :key="index">
            <div class="flex tw-gap-3">
                <TextInput
                    v-model="element.phone"
                    :label="$t('fields.phone')"
                    class="tw-flex-1 tw-w-64"
                    v-model:errors="errors['contacts.' + index + '.phone']"
                    required
                />
                <div class="tw-flex-none tw-mt-4">
                    <q-btn text-color="negative" icon="remove" class="tw-w-1/2 tw-px-5" @click="lessField(index)">
                        <q-tooltip class="dark text-body2" :offset="[10, 10]">
                            {{$t('companies.add_phone')}}
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
     * Phones
     */
    contacts: { required: false, type: Array },
    errors: {required: false},
    canAdd: {required: false, type: Boolean, default: true}
})

const i18n = useI18n();

function addField() {


    if (validateInput()) {
        props.contacts.push({
            phone: ''
        })
    } else {
        showNotification('negative', i18n.t('companies.fill_phone_fields'))
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

.v-enter-active,
.v-leave-active {
    transition: all 0.5s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
    translate: -20px;
}
</style>
