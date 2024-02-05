<template>
    <q-date v-model="proxy" mask="YYYY-MM-DD">
        <div class="row items-center justify-end">
            <q-btn v-close-popup :label="$t('actions.close')" color="primary" flat />
            <q-btn
                v-close-popup
                :label="$t('actions.ok')"
                color="primary"
                flat
                @click="$emit('ok')"
                v-show="okBtn"
            />
        </div>
    </q-date>
</template>

<script setup lang="ts">

import {computed} from "vue";

const props = defineProps({
    modelValue: {
        required: true,
        type: String
    },
    okBtn: {
        required: false,
        type: Boolean
    },
    errors: {
        required: false
    }
})

const emits = defineEmits<{
    (e: 'update:modelValue', v: string): void,
    (e: 'update:errors', v: string|undefined): void
}>()

const proxy = computed({
    get: () => props.modelValue,
    set: v => {
        emits('update:errors', undefined)
        emits('update:modelValue', v)
    }
})
</script>
