<script setup lang="ts">
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import {Link, router} from '@inertiajs/vue3';
import {onMounted, ref} from "vue";
import {QAjaxBar} from "quasar";

const bar = ref<QAjaxBar|null>(null)

onMounted(() => {
    router.on('start', () => {
        bar.value?.start()
    })

    router.on('finish', () => {
        bar.value?.stop()
    })
})
</script>

<template>
    <q-ajax-bar
        ref="bar"
        position="top"
        color="primary"
        size="2px"
        skip-hijack
    />
    <div class="tw-min-h-screen tw-flex tw-flex-col sm:tw-justify-center tw-items-center tw-pt-6 sm:tw-pt-0 tw-bg-gray-100">
        <div>
            <Link href="/">
                <ApplicationLogo class="tw-w-auto tw-h-36 tw-fill-current tw-text-gray-500" />
            </Link>
        </div>

        <div class="tw-w-full sm:tw-max-w-md tw-mt-6 tw-px-6 tw-py-4 tw-bg-white tw-shadow-md tw-overflow-hidden sm:tw-rounded-lg">
            <slot />
        </div>
    </div>
</template>
