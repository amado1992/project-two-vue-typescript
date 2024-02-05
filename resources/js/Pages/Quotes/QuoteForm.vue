<template>
    <div class="md:tw-grid md:tw-grid-cols-3 md:tw-gap-5">
        <TextInput v-model="form.date" :label="$t('fields.date')" v-model:errors="form.errors.date" required>
            <template v-slot:append>
                <q-icon name="event" class="cursor-pointer">
                    <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                        <DatePicker v-model="form.date" />
                    </q-popup-proxy>
                </q-icon>
            </template>
        </TextInput>

        <Select :label="$t('fields.client')" v-model="form.client_id" :options="clients" option-label="name"
            option-value="id" v-model:errors="form.errors.client_id" required @inputValues="clientChange" />

        <div class="tw-flex tw-items-center tw-gap-3">
            <Select class="tw-w-full" v-model="form.project_id" :label="$t('fields.project')" :options="projectsByClientId"
                option-label="name" option-value="id" v-model:errors="form.errors.project_id" :loading="loadingProjects"
                :disable="getIsDisabledProject" />

            <PrimaryButton flat icon="add" @click="addProject" />
        </div>

        <Select v-model="periodProxy" :label="$t('fields.period')" :options="periods" option-label="label"
            option-value="value" v-model:errors="form.errors.period" required />

        <slot name="fields" :form="form" />

        <q-toggle v-if="!showExento" v-model="taxExemptProxy" :label="$t('fields.tax_exempt')" />

        <q-toggle v-model="form.approved" :label="$t('fields.approved')" v-if="showApprove" />
    </div>

    <ContractibleProductTable class="tw-mt-3" :form="form" ref="table" :products="products" required />

    <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 tw-gap-5">
        <TextInput :label="$t('fields.observations')" type="textarea" v-model="form.observations"
            v-model:errors="form.errors.observations" />

        <div class="tw-flex tw-justify-end" v-if="!show_cant">
            <ContractibleProductsSummary :products="form.products" />
        </div>
    </div>
</template>

<script setup lang="ts">

    import { InertiaForm, router } from "@inertiajs/vue3";
    import TextInput from "../../Components/TextInput.vue";
    import DatePicker from "../../Components/DatePicker.vue";
    import Select from "../../Components/Select.vue";
    import PrimaryButton from "../../Components/PrimaryButton.vue";
    import ContractibleProductTable from "../../Components/ContractibleProductTable.vue";
    import ContractibleProductsSummary from "../../Components/ContractibleProductsSummary.vue";
    import { Product } from "../../Models/Product";
    import { useI18n } from "vue-i18n";
    import { useQuasar } from "quasar";
    import { computed, ref } from "vue";
    import { can, periods as getPeriods } from "../../Common/helpers";
    import CreateProjectDialog from "../Projects/CreateDialog.vue";
    import { inject } from 'vue'

    const show_cant = inject('show_cant')

    const props = defineProps<{
        form: InertiaForm<{
            date: string,
            client_id: number | null,
            project_id: number | null,
            period: number,
            tax_exempt: boolean,
            approved: boolean,
            products: [],
            observations: string
        }>,
        products: Product[],
        projects: any[],
        clients: any[],
        showApprove: boolean,
        showExento?: boolean
    }>()

    const i18n = useI18n()
    const $q = useQuasar()

    const loadingProjects = ref(false)

    const table = ref<ContractibleProductTable | null>()

    const periods = getPeriods(i18n)

    const periodProxy = computed<number>({
        get: () => props.form.period,
        set: v => {
            props.form.period = v
            table.value?.refresh(props.form.period)
        }
    })

    const taxExemptProxy = computed<boolean>({
        get: () => props.form.tax_exempt,
        set: v => {
            props.form.tax_exempt = v
            table.value?.refresh(props.form.period)
        }
    })

    function addProject() {

        $q.dialog({
            component: CreateProjectDialog,
            componentProps: {
                clients: props.clients
            }
        }).onOk(payload => {

            loadingProjects.value = true

            reloadProjects(() => {
                props.form.project_id = payload.id
            }, () => {
                loadingProjects.value = false
            })
        })
    }

    function reloadProjects(onSuccess?: () => void, onFinish?: () => void) {

        router.reload({
            only: ['projects'],
            preserveScroll: true,
            onSuccess: () => {

                if (onSuccess) {

                    onSuccess()
                }
            },
            onFinish: () => {

                if (onFinish) {

                    onFinish()
                }
            }
        })
    }

    const getIsDisabledProject = computed(() => !Boolean(props.form.client_id))
    const projectsByClientId = computed(() => {

        if (props.form.client_id == null) return props.projects
        return props.projects.filter(project => project.client_id == props.form.client_id)
    })

    function clientChange(e:any){
        props.form.project_id = null
    }
</script>
