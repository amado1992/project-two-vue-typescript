<template>
    <div>
        <TextInput
            v-model="form.name"
            :label="$t('fields.name')"
            v-model:errors="form.errors.name"
            required
        />

        <TextInput
            v-model="form.address"
            :label="$t('fields.address')"
            v-model:errors="form.errors.address"
            required
        />

        <Select
            :label="$t('fields.client')"
            v-model="form.client_id"
            :options="clients"
            option-label="name"
            option-value="id"
            v-model:errors="form.errors.client_id"
            required
        />

        <div class="tw-border tw-border-gray-300 tw-rounded tw-p-3 tw-relative">

            <label class="tw-bg-white tw-top-[-12px] tw-absolute tw-px-1 tw-text-gray-500">
                {{ $t('fields.project_manager') }}
            </label>

            <TextInput
                v-model="form.project_manager"
                :label="$t('fields.name')"
                v-model:errors="form.errors.project_manager"
                required
            />

            <TextInput
                v-model="form.project_manager_phone"
                :label="$t('fields.phone')"
                v-model:errors="form.errors.project_manager_phone"
                required
            />
        </div>

        <div class="tw-border tw-border-gray-300 tw-rounded tw-p-3 tw-relative">

            <label class="tw-bg-white tw-top-[-12px] tw-absolute tw-px-1 tw-text-gray-500">
                {{ $t('fields.construction_manager') }}
            </label>

            <TextInput
                v-model="form.construction_manager"
                :label="$t('fields.name')"
                v-model:errors="form.errors.construction_manager"
                required
            />

            <TextInput
                v-model="form.construction_manager_phone"
                :label="$t('fields.phone')"
                v-model:errors="form.errors.construction_manager_phone"
                required
            />
        </div>

        <div class="tw-border tw-border-gray-300 tw-rounded tw-p-3 tw-relative">

            <label class="tw-bg-white tw-top-[-12px] tw-absolute tw-px-1 tw-text-gray-500">
                {{ $t('fields.in_charge_to_pay') }}
            </label>

            <TextInput
                v-model="form.in_charge_to_pay"
                :label="$t('fields.name')"
                v-model:errors="form.errors.in_charge_to_pay"
                required
            />

            <TextInput
                v-model="form.in_charge_to_pay_phone"
                :label="$t('fields.phone')"
                v-model:errors="form.errors.in_charge_to_pay_phone"
                required
            />
        </div>
    </div>
</template>

<script setup lang="ts">

import {InertiaForm} from "@inertiajs/vue3";
import TextInput from "../../Components/TextInput.vue";
import Select from "../../Components/Select.vue";

defineProps<{
    form: InertiaForm<{
        name: string,
        address: string,
        project_manager: string,
        project_manager_phone: string,
        construction_manager: string,
        construction_manager_phone: string,
        in_charge_to_pay: string,
        in_charge_to_pay_phone: string,
        client_id: number|null
    }>,
    clients: []
}>()
</script>
