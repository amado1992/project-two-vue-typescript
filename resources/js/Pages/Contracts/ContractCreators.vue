<template>
    <Creators
        :created_at="contract.created_at"
        :created_by="contract.created_by?.name"
        :updated_at="contract.updated_at"
        :updated_by="contract.updated_by?.name"
    />

    <div class="tw-mt-2" v-show="contract.active_at">
        <span class="bg-green tw-rounded tw-p-1 tw-text-white tw-text-sm creator-tag">
            <q-icon name="play_arrow" />{{ $t('fields.started') }}
        </span>
        {{ $t('messages.created_msg', {name: contract.active_by?.name, date: contract.active_at}) }}
    </div>

    <div class="tw-mt-2" v-show="contract.finished_at">
        <span class="bg-black tw-rounded tw-p-1 tw-text-white tw-text-sm creator-tag">
            <q-icon name="done" />{{ $t('fields.finished') }}
        </span>
        {{ $t('messages.created_msg', {name: contract.finished_by?.name, date: contract.finished_at}) }}
    </div>
</template>

<script setup lang="ts">

import Creators from "../../Components/Creators.vue";

defineProps({
    contract: {
        required: true
    }
})
</script>
