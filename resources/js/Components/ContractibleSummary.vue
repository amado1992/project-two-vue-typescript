<template>
    <div class="tw-grid tw-grid-cols-2 tw-gap-2">
        <strong class="tw-text-lg tw-text-right">{{ $t('fields.discount_without_symbol') + ': ' }}</strong>
        <span class="tw-text-lg tw-text-right">{{ money(summary.discount) }}</span>

        <strong class="tw-text-lg tw-text-right">{{ $t('fields.subtotal') + ': ' }}</strong>
        <span class="tw-text-lg tw-text-right">{{ money(summary.subtotal) }}</span>

        <strong class="tw-text-lg tw-text-right">{{ $t('fields.tax') + ': ' }}</strong>
        <span class="tw-text-lg tw-text-right">{{ money(summary.tax) }}</span>

        <strong class="tw-text-lg tw-text-right text-primary">{{ $t('fields.total') + ': ' }}</strong>
        <span class="tw-text-lg text-primary tw-text-right">{{ money(summary.discount+summary.subtotal+summary.tax) }}</span>
    </div>
</template>

<script setup lang="ts">

import {money} from "../Common/helpers";


defineProps<{
    summary: {
        discount: number,
        tax: number,
        subtotal: number,
        total: number
    }
}>()
</script>
