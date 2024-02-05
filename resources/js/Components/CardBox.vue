<template>
    <q-card class="tw-p-1 tw-rounded-tr-2xl tw-rounded-bl-2xl tw-rounded-tl-none tw-rounded-br-none tw-mb-2 hover:tw-shadow-2xl tw-transition-all">
        <Link
            :href="route"
        >
            <q-card-section class="tw-flex-col tw-bg-white tw-flex">

                    <div class="tw-flex-1 tw-p-1">
                        <div class="tw-justify-between tw-items-center tw-flex">
                            <div class="tw-flex tw-items-center tw-justify-center">
                                <div class="tw-text-sm tw-text-gray-600 hover:tw-text-gray-900 tw-rounded-md">

                                          <h3 class="tw-m-0 tw-text-lg tw-leading-tight tw-text-gray-500 tw-dark:text-slate-400">
                                            {{ text }}
                                          </h3>

                                    <h1 class="tw-m-0 tw-text-3xl tw-leading-tight tw-font-semibold">
                                        {{valueProxy}}
                                    </h1>
                                </div>
                            </div>
                            <div class="tw-flex tw-items-center tw-justify-center">
                                <q-icon :name="icon" :color="color" class="tw-text-4xl"/>
                            </div>
                        </div>
                    </div>

            </q-card-section>
        </Link>
    </q-card>
</template>

<script setup lang="ts">

import {Link} from "@inertiajs/vue3";
import {computed, onMounted, ref} from "vue";

const props = defineProps({
    icon: {
        required: true,
        type: String,
        default: 'group'
    },
    value: {
        required: true,
        type: Number,
        default: 0
    },
    text: {
        required: true,
        type: String,
        default: ''
    },
    color: {
        required: true,
        type: String,
        default: 'grey-9'
    },
    route: {
        required: true,
        type: String,
        default: '/'
    }
})

let valueProxy = ref(0)

onMounted(() => {
    increment()
})


function increment() {

    setTimeout(() => {

        if (valueProxy.value < <number> props.value) {

            valueProxy.value++
            increment()
        } else {

            valueProxy.value = <number> props.value
        }
    }, 5)
}
</script>
