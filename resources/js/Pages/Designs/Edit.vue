<template>
    <Head :title="$t('designs.edit')"/>

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="draw" />
            </q-avatar>
            {{ $t('designs.edit') }}
        </template>

        <div class="container">
            <q-card class="tw-p-6">
                <q-card-section>
                  <div class="tw-flex tw-justify-end tw-mt-2">
                  <SecondaryButton
                      icon="download"
                      @click="exportDespiece2pdf()"
                      :disabled="form.products.length === 0"
                  >
                    Exportar lista de despiece
                  </SecondaryButton>
                  </div>
                    <QuoteForm
                        :form="form"
                        :projects="projects"
                        :products="products"
                        :clients="clients"
                        :show-approve="can('approve_designs', $page.props)"
                        :showExento="true"
                    >
                        <template #fields>
                            <File
                                v-model="form.files"
                                multiple
                                use-chips
                                :label="$t('fields.files')"
                                v-model:errors="form.errors.files"
                            />
                        </template>
                    </QuoteForm>

                    <div class="text-h6 tw-mt-5">{{ $t('fields.files') }}</div>
                    <div class="tw-text-md" v-if="! design.media.length">{{ $t('messages.empty_files') }}</div>
                    <q-scroll-area class="tw-w-full tw-h-[150px]">
                        <div class="tw-flex tw-gap-5">
                            <div class="tw-border tw-border-gray-500 tw-rounded tw-p-3" v-for="media in design.media">
                                <div class="tw-flex tw-justify-end tw-gap-5">
                                    <SecondaryButton icon="download" flat dense @click="download(media)">
                                        <q-tooltip>
                                            {{ $t('actions.download') }}
                                        </q-tooltip>
                                    </SecondaryButton>
                                    <SecondaryButton icon="close" flat dense @click="addToRemove(media.id)">
                                        <q-tooltip>
                                            {{ $t('actions.delete') }}
                                        </q-tooltip>
                                    </SecondaryButton>
                                </div>
                                <q-icon
                                    :name="getIcon(media.mime_type)"
                                    size="50px"
                                    color="grey-10" />

                                <a class="tw-block tw-max-w-[82px] tw-text-ellipsis tw-overflow-hidden tw-whitespace-nowrap">
                                    {{ media.file_name }}
                                </a>
                            </div>
                        </div>
                    </q-scroll-area>

                    <div class="tw-mt-5">
                        <Creators
                            :created_at="design.created_at"
                            :created_by="design.created_by.name"
                            :updated_at="design.updated_at"
                            :updated_by="design.updated_by?.name"
                        />
                    </div>
                </q-card-section>
                <q-card-actions align="right">
                    <SecondaryButton to="designs.index">
                        {{ $t('actions.cancel') }}
                    </SecondaryButton>

                    <PrimaryButton  @click="submit" :loading="form.processing">
                        {{ $t('actions.ok') }}
                    </PrimaryButton>
                </q-card-actions>
            </q-card>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">

import {Head, useForm} from "@inertiajs/vue3";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import {Product} from "../../Models/Product";
import QuoteForm from "../Quotes/QuoteForm.vue";
import File from "../../Components/File.vue";
import SecondaryButton from "../../Components/SecondaryButton.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import {buildContractibleProducts} from "../../Common/helpers";
import {onMounted} from "vue";
import route from "ziggy-js";
import Creators from "../../Components/Creators.vue";
import {can} from '../../Common/helpers';
import { provide } from 'vue'
import {date} from "quasar";
import {exportPdf, exportPdfGet} from "../../Composables/dowload";


provide('show_cant',true)

const props = defineProps<{
    projects: any[],
    products: Product[],
    clients: any[],
    design
}>()

const form = useForm({
    date: props.design.quote.date,
    project_id: props.design.quote.project_id,
    client_id: props.design.quote.client_id,
    period: props.design.quote.period,
    tax_exempt: props.design.quote.tax_exempt,
    approved: props.design.quote.approved,
    products: buildContractibleProducts(props.design.quote.products),
    observations: props.design.quote.observations,
    files: [],
    files_to_remove: [],
    _method: 'PUT'
})

onMounted(() => {
    console.log(props.design)
})

function getIcon(type: string) {

    if (type.startsWith('image/')) {

        return 'image'
    }

    if (type.startsWith('video/')) {

        return 'movie'
    }

    if (type.startsWith('audio/')) {

        return 'music_note'
    }

    return 'description'
}

function download(media) {
    window.location.href = route('design.download-file', {media: media.id})
}

function addToRemove(id) {

    if (form.files_to_remove.findIndex(i => i === id) == -1) {

        form.files_to_remove.push(id)

        const i = props.design.media.findIndex(m => m.id == id)

        if (i != -1) {

            props.design.media.splice(i, 1)
        }
    }
}

function submit() {
    form.post(route('designs.update', {design: props.design.id}), {
        preserveScroll: true
    })
}

function exportDespiece2pdf(){
  const filename = date.formatDate(new Date(Date.now()),'YYYY/MM/DD')
  exportPdfGet(
      route('designs.despiece.pdf',{design:props.design.id}),
      `Lista de despiece.pdf`
  )
}
</script>
