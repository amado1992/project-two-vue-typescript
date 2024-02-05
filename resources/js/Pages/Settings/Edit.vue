<template>

    <Head :title="$t('settings.edit')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="settings" />
            </q-avatar>
            <div class="tw-inline" v-if="can('update_settings', $page.props)">{{ $t('settings.edit') }}</div>
            <div class="tw-inline" v-else>{{ $t('companies.title') }}</div>
        </template>

        <div class="container">

            <q-card class="tw-p-6">
                <form @submit.prevent="submit">
                    <q-card-section>
                       <div class="tw-flex tw-justify-end">
                         <q-btn
                             type="button"
                             color="primary"

                             class="tw-mt-5 tw-w-30 tw-self-end tw-place-self-end tw-justify-self-center"
                             @click="open=!open"
                             icon="publish">
                         </q-btn>
                       </div>
                        <div class="md:tw-grid md:tw-grid-cols-3 md:tw-gap-5 tw-justify-center">

                            <TextInput
                                type="number"
                                min="0"
                                step="0.1"
                                v-model="form.tax_itbms"
                                :label="$t('fields.tax_itbms')"
                                v-model:errors="form.errors.tax_itbms"
                            />

                            <TextInput
                                type="number"
                                min="0"
                                step="1"
                                v-model="form.expire_contract_notification"
                                :label="$t('fields.expire_contract_notification')"
                                v-model:errors="form.errors.expire_contract_notification"
                                :hint="$t('fields.days_before_contract_expire')"
                            />




                        </div>

                    </q-card-section>
                    <q-card-section>
                        <div class="md:tw-grid md:tw-grid-cols-1 md:tw-gap-5">

                            <Select
                                v-model="form.billers"
                                :label="$t('fields.billers')"
                                :options="users"
                                option-label="name"
                                option-value="id"
                                input-debounce="100"
                                multiple="multiple"
                                use-input
                                use-chips
                                v-model:errors="form.errors.billers"
                                :hint="$t('fields.select_billers')"
                            />

                        </div>

                    </q-card-section>
                    <q-card-actions align="right">
                        <SecondaryButton to="settings.index">
                            {{ $t('actions.cancel') }}
                        </SecondaryButton>
                        <PrimaryButton v-if="can('update_settings', $page.props)" type="submit" :loading="form.processing">
                            {{ $t('actions.ok') }}
                        </PrimaryButton>
                    </q-card-actions>
                </form>
                <q-dialog v-model="open">
                    <Import
                            downloadurl="/download/3"
                            @finish="onFinishImport"
                            @errors="onFinishImportErrors"
                            routeName="settings.import"
                            title="Importar configuraciones generales"

                    ></Import>
                </q-dialog>
              <q-dialog v-model="openErrors">
                <div>
                  <ImportErrors :list="listerrors"></ImportErrors>
                </div>
              </q-dialog>
            </q-card>

        </div>


    </AuthenticatedLayout>
</template>

<script setup lang="ts">

import Import from "@/Components/Import.vue"
import ImportErrors from "@/Components/ImportErrors.vue"
import {Head, router, useForm, usePage} from "@inertiajs/vue3";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import TextInput from "../../Components/TextInput.vue";
import SecondaryButton from "../../Components/SecondaryButton.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import Select from "../../Components/Select.vue";
import route from "ziggy-js";
import {useI18n} from "vue-i18n";
import {can, showNotification} from "../../Common/helpers";
import {ref} from "vue";


const page = usePage()

const i18n = useI18n();

const allUsers = page.props.users as {id: number, name: string}[]
const users = ref(allUsers)
const open = ref(false)
const openErrors = ref(false)
const listerrors = ref([])
const form = useForm({
    tax_itbms: page.props.settings?.tax_itbms,
    expire_contract_notification: page.props.settings?.expire_contract_notification,
    billers: page.props.billers,
})


function submit() {

    form.post(route('settings.store'), {
        preserveScroll: true,
        onError: () => {
            showNotification('negative', i18n.t('messages.validation_error'), 'top-right')
        }
    })
}
function onFinishImport(){
  open.value = false
  router.reload({
    preserveScroll: true,
    onSuccess: params => {
      console.log(params)
      form.tax_itbms = params.props.settings?.tax_itbms
      form.expire_contract_notification = params.props.settings?.expire_contract_notification
      form.billers = params.props.billers
    }
  })
}
function onFinishImportErrors(data){
  openErrors.value = true
  listerrors.value = data.errors
}

</script>
