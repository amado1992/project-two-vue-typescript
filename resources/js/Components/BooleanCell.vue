<template>
    <q-td :class="alignProxy">
        <q-icon :size="sizeProxy" name="check_circle" v-show="value" color="primary"/>
        <q-icon :size="sizeProxy" name="cancel" v-show="! value" color="negative" />
    </q-td>
</template>

<script setup lang="ts">

import {computed} from "vue";

const props = defineProps<{
    value: boolean,
    align?: 'left'|'center'|'right',
    size?: string
}>()

const alignProxy = computed(() => {

    //const align = props.align ?? 'right'
    const align = props.align ?? 'center'

    return 'text-' + align
})

const sizeProxy = computed(() => {
    return props.size ?? '25px'
})
</script>
