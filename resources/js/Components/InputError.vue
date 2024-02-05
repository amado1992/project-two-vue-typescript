<script setup>
defineProps(['message', 'classes']);
</script>

<template>
    <Transition>
        <div v-show="message" :class="classes">
            <p class="tw-text-[12px] text-negative">
                {{ message }}
            </p>
        </div>
    </Transition>
</template>

<style scoped>
.v-enter-active,
.v-leave-active {
    transition: all 0.5s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
    translate: 0 -20px;
}
</style>
