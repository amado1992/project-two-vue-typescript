<template>
    <q-select
        v-model="proxyValue"
        color="primary"
        :label="label"
        :error-message="errors"
        :error="errors != null"
        :options="options"
        :option-label="optionLabel"
        :option-value="optionValue"
        :map-options="optionValue != null && optionLabel != null"
        :emit-value="optionValue != null && optionLabel != null"
        :disable="disable"
        @update:model-value="(event)=>emits('inputValues',event)"
    >
        <template v-slot:label>
            <div class="row items-center all-pointer-events">

                {{props.label}}
                <q-icon v-if="props.required" class="tw-mb-2 q-mr-xs" color="negative" size="8px" name="emergency" />
            </div>
        </template>

        <template #prepend>
            <slot name="prepend" />
        </template>

        <template #append>
            <slot name="append" />
        </template>
    </q-select>
</template>

<script setup lang="ts">

import {computed} from "vue";

const props = defineProps<{
    modelValue: any,
    label: string,
    options: [],
    optionLabel?: string,
    optionValue?: string,
    errors?: string,
    required?: boolean,
    disable?: boolean,
    inputValue?: Function
}>();

const emits = defineEmits(['update:modelValue', 'update:errors','inputValues']);

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
