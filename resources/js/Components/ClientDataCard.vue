<template>
    <div class="tw-w-full tw-py-5 tw-rounded tw-shadow-lg tw-text-center tw-text-xl tw-text-white">
        <div>{{ value }}</div>
        {{ label }}
    </div>
</template>

<script setup lang="ts">

defineProps<{
    label: string,
    value: string
}>()
</script>
