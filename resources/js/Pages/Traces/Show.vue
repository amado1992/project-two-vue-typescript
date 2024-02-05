<template>
    <Head :title="$t('traces.details')" />

    <AuthenticatedLayout>
        <template #header>
            <q-avatar>
                <q-icon name="query_stats" />
            </q-avatar>
            {{ $t('traces.details') }}
        </template>

        <div class="container">
            <q-card>
                <q-card-section>
                    <div class="tw-grid tw-grid-cols-4">
                        <div>
                            <div class="tw-font-semibold">{{ $t('fields.action') }}</div>
                            <div>{{ trace.readable_action }}</div>
                        </div>

                        <div>
                            <div class="tw-font-semibold">{{ $t('fields.id') }}</div>
                            <div>{{ trace.model_id }}</div>
                        </div>

                        <div>
                            <div class="tw-font-semibold">{{ $t('fields.user') }}</div>
                            <div>{{ trace.user.name }}</div>
                        </div>
                        <div>
                            <div class="tw-font-semibold">{{ $t('fields.date') }}</div>
                            <div>{{ trace.created_at }}</div>
                        </div>
                    </div>

                    <div v-if="trace.action === 'updated'">

                        <div class="text-h6 tw-mt-3">{{ $t('fields.title') }}</div>
                        <q-markup-table class="tw-shadow-none">
                            <thead>
                                <tr>
                                    <th class="text-right">{{ $t('fields.field') }}</th>
                                    <th class="text-right">{{ $t('fields.new_value') }}</th>
                                    <th class="text-right">{{ $t('fields.old_value') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(field, index) in trace.merge_fields">

                                    <td class="text-right" v-if="field.value">{{ splitField(index.toString()) }}</td>

                                    <td class="text-right" v-if="field.value" v-html="formatField(field.value)"></td>
                                    <td class="text-right" v-if="field.value" v-html="formatField(field.old_value)"></td>
                                </tr>
                            </tbody>
                        </q-markup-table>
                    </div>
                </q-card-section>
            </q-card>

            <div class="tw-flex tw-justify-end tw-mt-5">
                <SecondaryButton :to="route('traces.index', { module: module })">{{ $t('actions.back') }}</SecondaryButton>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">

import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import SecondaryButton from "../../Components/SecondaryButton.vue";
import route from 'ziggy-js';
import { useI18n } from "vue-i18n";

defineProps<{
    trace: any,
    module?: string
}>()

const i18n = useI18n()

const rediText:string = "Documento Redi";
const redi:string = "redi";

const sheetText:string = "Ficha de inscripción";
const sheet:string = "ficha";

function formatField(field: any) {
    
    if (field != undefined && field != null) {

        if (typeof field === 'number') {
            return field
        }

        if (typeof field === 'boolean') {
            return field
        }

        if (typeof field === 'string') {
            if (isObject(field)) {
                return typeObject(field)
                }
            return field
        }
    }
    return field
}

function isObject(field:string) {
  try {
    const parsedObject = JSON.parse(field);
    return typeof parsedObject === 'object' && parsedObject !== null;
  } catch (error) {
    return false;
  }
}

function typeObject(field: any): string {
    field = JSON.parse(field)
    let result = ""

    for (const key in field) {

            var name = "Nombre: " + field[key].NAME;
            var phone = "Teléfono: " + field[key].PHONE
            result += name.concat(', ', phone + " ")
    }

    return result
}

function splitField(str: string): string {

    var text = str
    if (text != "") {
        var splitted = text.split(".");

        if (splitted[splitted.length - 1] == sheet) {
            text = sheetText;
        }

        if (splitted[splitted.length - 1] == redi) {
            text = rediText;
        }
    }
    return text;
}

/*
function formatField(field) {

    if (typeof field === 'number' ||  typeof Number(field) === 'number') {

        return field
    }

    try {

        return formatJson(JSON.parse(field))

    } catch (e) {

        return field
    }
}

function formatJson(json) {


    let result = ''

    for (const key in json) {

        // @ts-ignore
        if (! isNaN(key)) {

            if (typeof json[key] === "object") {

                result += formatJson(json[key]) + '<br>'
            } else {

                result += json[key] + '<br>'
            }
        } else {

            if (typeof json[key] === "object") {

                result += '<strong>' + i18n.t('fields.' + key) + '</strong>' + ': ' + formatJson(json[key]) + '<br>'
            } else {

                result += '<strong>' + i18n.t('fields.' + key) + '</strong>' + ': ' + json[key] + '<br>'
            }
        }
    }

    return result
}*/
</script>
