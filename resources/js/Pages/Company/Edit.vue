<template>

    <Head :title="$t('companies.edit')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="store" />
            </q-avatar>
            <div class="tw-inline" v-if="can('update_companies', $page.props)">{{ $t('companies.edit') }}</div>
            <div class="tw-inline" v-else>{{ $t('companies.company_data') }}</div>
        </template>

        <div class="container">
            <q-card class="tw-p-6">
                <form @submit.prevent="submit" novalidate>
                    <q-card-section>
                        <div class="tw-flex tw-justify-end">
                          <q-btn class="tw-m-5" color="primary" v-if="can('import_companies', $page.props)"  icon="publish" @click="open=!open"/>
                        </div>
                        <div class="md:tw-grid md:tw-grid-cols-3 md:tw-gap-5">
                            <TextInput
                                v-model="form.name"
                                :label="$t('fields.name')"
                                v-model:errors="form.errors.name"
                                :required="true"
                            />

                            <TextInput
                                v-model="form.social_reason"
                                :label="$t('fields.social_reason')"
                                v-model:errors="form.errors.social_reason"
                            />

                            <TextInput
                                v-model="form.ruc"
                                :label="$t('fields.ruc')"
                                v-model:errors="form.errors.ruc"
                            />

                            <TextInput
                                type="number"
                                step="1"
                                v-model="form.dv"
                                :label="$t('fields.dv')"
                                v-model:errors="form.errors.dv"
                            />

                           <TextInput
                                v-model="form.email"
                                type="email"
                                :label="$t('fields.email')"
                                v-model:errors="form.errors.email"
                            />

                            <TextInput
                                v-model="form.color"
                                :label="$t('fields.color')"
                            >
                                <template v-slot:append>
                                    <q-icon name="colorize" class="cursor-pointer">
                                        <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                                            <q-color v-model="form.color" />
                                        </q-popup-proxy>
                                    </q-icon>
                                </template>
                            </TextInput>

                        </div>

                        <TextInput
                            class="tw-w-full"
                            v-model="form.address"
                            :label="$t('fields.address')"
                            v-model:errors="form.errors.address"
                        />

                        <!--<h5 class="tw-mt-5 tw-mb-3 tw-font-medium">{{$t(('companies.logo'))}}</h5>
                        <div class="md:tw-grid md:tw-grid-cols-2 md:tw-gap-5">
                            <q-file
                                v-model="form.logo"
                                :label="$t('companies.pick')"
                                style="max-width: 300px"
                                accept="image/*"
                                ref="logoInput"
                            ></q-file>


                            <div class="tw-w-1/3" v-show="form.imageSrc">
                                <q-tooltip anchor="top middle" self="bottom middle" :offset="[10, 10]">
                                    <strong>{{$t(('fields.download'))}}</strong>
                                    <q-icon name="download"/>
                                </q-tooltip>
                                <q-img
                                    :src="form.imageSrc"
                                    spinner-color="white"
                                    @click="downloadLogo()"
                                    style="cursor: pointer;"

                                />
                            </div>


                        </div>-->

                        <div class="md:tw-grid md:tw-grid-cols-1 md:tw-gap-5 tw-mt-4">
                            <Phone
                                :contacts="form.contacts"
                                :errors="form.errors"
                                :can-add="can('update_companies', $page.props)"
                            />
                        </div>

                    </q-card-section>
                    <q-card-actions align="right">
                        <PrimaryButton v-if="can('update_companies', $page.props)" type="submit" :loading="form.processing">
                            {{ $t('actions.ok') }}
                        </PrimaryButton>
                    </q-card-actions>
                </form>
            </q-card>
            <q-dialog v-model="open">
                <Import

                downloadurl="/download/4"
                @errors="onFinishImportErrors"
                @finish="onFinishImport" routeName="companies.import" title="Importar plantilla de empresa"></Import>
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
import {Head, router, useForm, usePage} from "@inertiajs/vue3";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import TextInput from "../../Components/TextInput.vue";
import SecondaryButton from "../../Components/SecondaryButton.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import Phone from '../../Components/Phone.vue';
import route from "ziggy-js";
import {useI18n} from "vue-i18n";
import {can, showNotification} from "../../Common/helpers";
import {ref} from "vue";
import { lowerizeArray } from "../../Util/json";


const page = usePage()

const i18n = useI18n();
const open = ref(false)
const openErrors = ref(false)
const listerrors = ref([])

const form = useForm({
    name: page.props.company?.name,
    social_reason: page.props.company?.social_reason,
    ruc: page.props.company?.ruc,
    dv: page.props.company?.dv,
    email: page.props.company?.email,
    color: page.props.company?.color,
    address: page.props.company?.address,
    logo: null,
    downloadLink: page.props.company?.download_logo,
    imageSrc: page.props.company?.logo,
    //contacts: page.props.company?.contact_information == null ? [] : JSON.parse(page.props.company?.contact_information)
    contacts: page.props.company?.contact_information ? lowerizeArray(JSON.parse(page.props.company?.contact_information ?? [])) : [], 
})


function submit() {

    form.post(route('companies.store'), {
        preserveScroll: true,
        onError: () => {
            showNotification('negative', i18n.t('messages.validation_error'), 'top-right')
        },
        onSuccess: () => {
            router.reload({
                only : [
                    'company'
                ],
                onSuccess: (value) => {
                    form.imageSrc = value.props.company?.logo,
                    form.contacts = JSON.parse(value.props.company?.contact_information)
                }
            })
        }
    })
}

function downloadLogo() {
    window.open(route('companies.logo'), '_blank');
}

function onFinishImport(){
    open.value = false
    router.reload({
        only:["company"],
        onSuccess: params => {
          form.name = params.props.company?.name,
              form.social_reason = params.props.company?.social_reason,
              form.ruc = params.props.company?.ruc,
              form.dv = params.props.company?.dv,
              form.email = params.props.company?.email,
              form.color = params.props.company?.color,
              form.address = params.props.company?.address,
              form.downloadLink = params.props.company?.download_logo,
              form.imageSrc = params.props.company?.logo,
              form.contacts = params.props.company?.contact_information == null ? [] : JSON.parse(params.props.company?.contact_information)
        }
    })
}

function onFinishImportErrors(data){
    openErrors.value = true
    listerrors.value = data.errors
}

</script>
