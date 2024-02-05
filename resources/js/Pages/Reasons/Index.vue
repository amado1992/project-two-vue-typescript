<template>
    <Head :title="$t('inventories.reasons.title')" />
    <AuthenticatedLayout>
        <template #header>
            {{ $t('inventories.reasons.title') }}
        </template>
        <div class="q-pa-md">
            <q-card>
                <q-card-section>
                    <div class="tw-flex tw-justify-between">
                        <Select
                            class="tw-min-w-[95px]"
                            v-model="selectOption"
                            :label="$t('fields.filter')"
                            :options="filterOptions"
                            option-label="label"
                            option-value="value"
                        />

                        <div>
                            <PrimaryButton
                                icon="add"
                                v-if="can('create_reasons', $page.props)"
                                to="reasons.create"
                            />
                            <q-btn color="primary" v-if="can('import_reasons', $page.props)" class="tw-ml-3" icon="publish" @click="open=!open"/>

                        </div>
                    </div>
                    <q-markup-table class="tw-shadow-none tw-mt-2">
                        <thead>
                        <tr>
                            <th class="text-center">{{ $t('fields.name') }}</th>
                            <th class="text-center">{{ $t('fields.active') }}</th>
                            <th class="text-center">{{ $t('fields.created_at') }}</th>
                            <th class="text-center">{{ $t('fields.created_by') }}</th>
                            <th class="text-center">{{ $t('fields.updated_at') }}</th>
                            <th class="text-center">{{ $t('fields.updated_by') }}</th>
                            <th class="text-center">{{ $t('actions.title') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="reason in reasons" v-if="$page.props.reasons.length">
                            <q-td class="text-left">{{ reason.name }}</q-td>
                            <BooleanCell :value="reason.active" />
                            <q-td class="text-left">{{ reason.created_at }}</q-td>
                            <q-td class="text-left">{{ reason.created_by?.name }}</q-td>
                            <q-td class="text-left">{{ getUpdatedAt(reason) }}</q-td>
                            <q-td class="text-left">{{ reason.updated_by?.name }}</q-td>
                            <q-td>
                                <RowActions :actions="getActions(reason)" />
                            </q-td>
                        </tr>
                        </tbody>
                    </q-markup-table>
                    <EmptyData v-if="! reasons.length" />
                </q-card-section>
            </q-card>
            <q-dialog v-model="open">
                <Import

                downloadurl="/download/5"
                @errors="onFinishImportErrors"
                @finish="onFinishImport" routeName="reasons.import" title="Importar plantilla de motivos de ajustes"></Import>
            </q-dialog>
            <q-dialog v-model="openErrors">
                <div>
                    <ImportErrors :list="listerrors"></ImportErrors>
                </div>
            </q-dialog>
        </div>

    </AuthenticatedLayout>
</template>

<script setup lang="ts">

import Import from "@/Components/Import.vue"
import ImportErrors from "@/Components/ImportErrors.vue"
import {Head, router, usePage} from "@inertiajs/vue3";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import RowActions from "../../Components/RowActions.vue";
import {RowAction} from "../../Models/RowAction";
import {useI18n} from "vue-i18n";
import {computed, ref} from "vue";
import {can, getUpdatedAt} from '../../Common/helpers';
import BooleanCell from "../../Components/BooleanCell.vue";
import Select from "../../Components/Select.vue";
import EmptyData from "../../Components/EmptyData.vue";

const props = defineProps<{
    reasons: {
        name: string,
        active: boolean
    }[]
}>()

const i18n = useI18n()
const open = ref(false)
const openErrors = ref(false)
const listerrors = ref([])

const filterOptions = [
    {
        label: i18n.t('fields.all'),
        value: 'all'
    },
    {
        label: i18n.t('fields.actives'),
        value: 'actives'
    },
    {
        label: i18n.t('fields.inactives'),
        value: 'inactives'
    }
]

const _selectedOption = ref('actives')
const selectOption = computed({
    get: () => _selectedOption.value,
    set: v => {
        _selectedOption.value = v
        reloadReasons()
    }
})

function getActions(reason): RowAction[] {

    return RowAction.buildCommonsActions(
        'reasons.edit',
        'reasons.destroy',
        reason.crud_permissions.edit,
        reason.crud_permissions.delete,
        'edit',
        'delete',
        {reason: reason.id},
        i18n,
        () => {

            reloadReasons()
        }
    )
}

function reloadReasons() {

    router.reload({
        only: ['reasons'],
        data: {
            filter: selectOption.value
        }
    })
}

function onFinishImport(){
    open.value = false
    router.reload({})
}

function onFinishImportErrors(data){
    openErrors.value = true
    listerrors.value = data.errors
}
</script>
