<template>
    <Head :title="$t('quotes.edit')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="request_quote" />
            </q-avatar>
            {{ $t('quotes.edit') }}
        </template>

        <div class="container">
            <q-card class="tw-p-6">
                <q-card-section>
                    <div class="tw-flex tw-justify-end">
                        <SecondaryButton icon="download" @click="download">
                            {{ $t('actions.download') }}
                        </SecondaryButton>
                        <div class="tw-mx-2"></div>
                        <SecondaryButton
                          icon="download"
                          @click="exportDespiece2pdf()"
                          :disabled="form.products.length === 0"
                      >
                        Exportar lista de despiece
                      </SecondaryButton>
                    </div>

                    <QuoteForm :form="form" :projects="projects" :products="products" :clients="clients"
                        :show-approve="can('approve_quotes', $page.props)" />

                  <div class="text-h6 tw-mt-5">{{ $t('fields.files') }}</div>
                  <div class="tw-text-md" v-if="media.length==0">{{ $t('messages.empty_files') }}</div>
                  <q-scroll-area class="tw-w-full tw-h-[150px]">
                    <div class="tw-flex tw-gap-5">
                      <div class="tw-border tw-border-gray-500 tw-rounded tw-p-3" v-for="m in media">
                        <div class="tw-flex tw-justify-end tw-gap-5">
                          <SecondaryButton icon="download" flat dense @click="downloadD(m)">
                            <q-tooltip>
                              {{ $t('actions.download') }}
                            </q-tooltip>
                          </SecondaryButton>
                        </div>
                        <q-icon
                            :name="getIcon(m.mime_type)"
                            size="50px"
                            color="grey-10" />

                        <a class="tw-block tw-max-w-[82px] tw-text-ellipsis tw-overflow-hidden tw-whitespace-nowrap">
                          {{ m.file_name }}
                        </a>
                      </div>
                    </div>
                  </q-scroll-area>

                    <Creators :created_at="$page.props.quote.created_at" :created_by="$page.props.quote.created_by.name"
                        :updated_at="$page.props.quote.updated_at" :updated_by="$page.props.quote.updated_by?.name" />

                </q-card-section>
                <q-card-actions align="right">
                    <SecondaryButton :to="can('read_quotes', $page.props) ? 'quotes.index' : 'home'">
                        {{ $t('actions.cancel') }}
                    </SecondaryButton>

                    <PrimaryButton @click="submit" :loading="form.processing">
                        {{ $t('actions.ok') }}
                    </PrimaryButton>
                </q-card-actions>
            </q-card>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">

import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import { useForm, Head } from "@inertiajs/vue3";
import SecondaryButton from "../../Components/SecondaryButton.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import route from "ziggy-js";
import { buildContractibleProducts } from "../../Common/helpers";
import Creators from "../../Components/Creators.vue";
import QuoteForm from "./QuoteForm.vue";
import { can } from '../../Common/helpers';
import {date} from "quasar";
import {exportPdfGet} from "../../Composables/dowload";
import {computed} from "vue";

const props = defineProps<{
    quote,
    products,
    projects,
    clients
}>()

const form = useForm({
    date: props.quote.date,
    client_id: props.quote.client_id,
    project_id: props.quote.project_id,
    period: props.quote.period,
    tax_exempt: props.quote.tax_exempt,
    approved: props.quote.approved,
    products: buildContractibleProducts(props.quote.products),
    observations: props.quote.observations
})

const media = computed(()=> props.quote.mediadesing)

function download() {
    window.location.href = route('quotes.download', { quote: props.quote.id })
}

function submit() {
    form.put(route('quotes.update', { quote: props.quote.id }), {
        preserveScroll: true
    })
}

function exportDespiece2pdf(){

  exportPdfGet(
      route('quote.despiece.pdf',{quote:props.quote.id}),
      `Lista de despiece.pdf`
  )
}

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

function downloadD(media) {
  window.location.href = route('design.download-file', {media: media.id})
}

</script>
