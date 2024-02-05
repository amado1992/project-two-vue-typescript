<template>
    <div>
        <span class="bg-primary tw-rounded tw-p-1 tw-text-white tw-text-sm creator-tag">
            <q-icon name="add" />{{ $t('fields.created') }}
        </span>
        {{ $t('messages.created_msg', {name: created_by, date: created_at}) }}
    </div>
    <div class="tw-mt-2" v-if="updated_by">
        <span class="bg-secondary tw-rounded tw-p-1 tw-text-white tw-text-sm creator-tag">
            <q-icon name="edit" />{{ $t('fields.updated') }}
        </span>
        {{ $t('messages.updated_msg', {name: updated_by, date: updated_at}) }}
    </div>
</template>

<script setup lang="ts">

defineProps({
    created_at: {
        required: true,
        type: String
    },
    created_by: {
        required: true,
        type: String
    },
    updated_at: {
        required: false,
        type: String
    },
    updated_by: {
        required: false,
        type: String
    }
})
</script>
