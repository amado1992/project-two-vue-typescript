<template>
    <q-btn :type="type" color="negative" :loading="loading" :icon="icon" @click="goTo" no-caps>
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
    }
});

function goTo() {

    if (props.to) {

        router.get(route(props.to))
    }
}
</script>
