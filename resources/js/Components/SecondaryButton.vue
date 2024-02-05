<template>
    <q-btn :type="type" color="secondary" :loading="loading" :icon="icon" @click="goTo" :disable="disabled" no-caps>
        <slot />
    </q-btn>
</template>

<script setup lang="ts">

import {router} from "@inertiajs/vue3";
import route from "ziggy-js";

const props = defineProps({
    type: {
        type: String,
        default: 'button',
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
    disabled:{
        required:false,
        default:false
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
