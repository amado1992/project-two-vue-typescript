<template>
    <q-card>
        <q-card-section>
            <div class="text-h6">{{ $t('fields.user') }}</div>

            <q-separator />

            <div class="tw-grid tw-grid-cols-2">
                <strong>{{ $t('fields.name') + ':' }}</strong>
                <span>{{ user.name }}</span>
                <strong>{{ $t('fields.lastname') + ':' }}</strong>
                <span>{{ user.lastname }}</span>
                <strong>{{ $t('fields.email') + ':' }}</strong>
                <span>{{ user.email }}</span>
            </div>
        </q-card-section>
    </q-card>
</template>

<script setup lang="ts">

defineProps<{
    user: {
        id: number,
        name: string,
        lastname: string,
        email: string
    }
}>()
</script>
