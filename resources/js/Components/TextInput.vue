<template>
    <q-input
        v-model="proxyValue"
        color="primary"
        :label="label"
        :readonly="disable"
        :error-message="bottomSlots ? null : errors"
        :error="errors !== undefined"
        :bottom-slots="bottomSlots"
    >
        <template v-slot:label>
            <div class="row items-center all-pointer-events">

                {{props.label}}
                <q-icon v-if="props.required" class="tw-mb-2 q-mr-xs" color="negative" size="8px" name="emergency" />

            </div>
        </template>
        <template #append>
            <slot name="append" />
        </template>
        <template #prepend>
            <slot name="prepend" />
        </template>
        <template #error>
            <slot name="error" :errors="errors" />
        </template>
    </q-input>

</template>

<script setup lang="ts">

import {computed} from 'vue';

const props = defineProps<{
    modelValue: string,
    label: string,
    errors?: string,
    required?: boolean,
    bottomSlots?: boolean,
    disable?: boolean,
}>();

const emits = defineEmits(['update:modelValue', 'update:errors']);

const proxyValue = computed({
    get() {
        return props.modelValue
    },
    set(val) {
        emits('update:errors', undefined)
        emits('update:modelValue', val)
    }
})

</script>
