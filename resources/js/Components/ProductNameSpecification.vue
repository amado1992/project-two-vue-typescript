<template>
    <div class="product-col-specification">{{ name }}</div>
    <q-tooltip>{{ name }}</q-tooltip>
</template>

<script setup lang="ts">

defineProps<{
    name: string
}>()
</script>
