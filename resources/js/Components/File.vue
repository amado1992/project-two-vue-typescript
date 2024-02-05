<template>
    <q-file
        v-model="proxy"
        color="primary"
        :error-message="errors"
        :error="errors !== undefined"
    >
        <template #append>
            <slot name="append" />
        </template>
        <template #prepend>
            <slot name="prepend" />
        </template>
    </q-file>
</template>

<script setup lang="ts">

import {computed} from "vue";

const props = defineProps<{
    modelValue,
    errors?: string
}>()

const emits = defineEmits<{
    (e: 'update:modelValue', v): void,
    (e: 'update:errors', v): void
}>()

const proxy = computed({
    get: () => props.modelValue,
    set: v => {
        emits('update:errors', undefined)
        emits('update:modelValue', v)
    }
})
</script>
