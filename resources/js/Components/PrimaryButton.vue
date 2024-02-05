<template>
    <q-btn :type="type"
     color="primary"
    :loading="loading"
    :icon="icon"
    @click="goTo"
    :label="label"
    no-caps>
        <slot />
    </q-btn>
</template>

<script setup lang="ts">

import {router} from "@inertiajs/vue3";
import route from "ziggy-js";

const props = defineProps({
    type: {
        type: String,
        default: 'submit',
    },
    loading: {
        type: Boolean,
        default: false
    },
    icon: {
        required: false,
        type: String
    },
    to: {
        required: false,
        type: String
    },
    label:{
        required:false,
        type:String
    }
});

function goTo() {

    if (props.to) {

        if (route().has(props.to)) {

            router.get(route(props.to))
        } else {

            router.get(props.to)
        }
    }
}
</script>
