<template>
    <Head :title="$t('settings.clausules')" />
    <AuthenticatedLayout>
        <template #header>
            {{ $t('settings.clausules') }}
        </template>
        <div class="container tw-mb-2">
            <q-card class="tw-p-6">
              <q-tabs
                  v-model="tab"
                  dense
                  class="text-grey"
                  active-color="primary"
                  indicator-color="primary"
                  align="justify"
                  narrow-indicator
              >
                <q-tab name="d1" label="Datos del arrendador" />
                  <q-tab name="d2" label="Datos del arrendatario" />
              </q-tabs>
              <q-tab-panels v-model="tab" animated>
                <q-tab-panel name="d1">
                  <q-card-section class="row">
                    <div class="col-5  tw-mx-3">
                        <TextInput
                                type="text"
                                v-model="form.nombre_empresa_arrendador"
                                label="Nombre de la empresa"
                                v-model:errors="form.errors.nombre_empresa_arrendador"
                        >
                            <template v-slot:append>
                                <q-btn round dense flat icon="add" @click="addToTemplate(1)" />
                            </template>
                        </TextInput>
                    </div>
                    <div class="col-5  tw-mx-3">
                      <TextInput
                          type="text"
                          v-model="form.direccion_empresa_arrendador"
                          label="Dirección de la empresa"
                          v-model:errors="form.errors.direccion_empresa_arrendador"
                      >
                        <template v-slot:append>
                          <q-btn round dense flat icon="add" @click="addToTemplate(4)" />
                        </template>
                      </TextInput>

                    </div>
                    <div class="col-5  tw-mx-3">
                      <TextInput
                          type="text"
                          v-model="form.ficha_inscripcion_arrendador"
                          label="Ficha de inscripción"
                          v-model:errors="form.errors.ficha_inscripcion_arrendador"
                      >
                        <template v-slot:append>
                          <q-btn round dense flat icon="add" @click="addToTemplate(2)" />
                        </template>
                      </TextInput>
                    </div>
                    <div class="col-5  tw-mx-3">
                        <TextInput
                                type="text"
                                v-model="form.documento_redi_arrendador"
                                label="Documento REDI"
                                v-model:errors="form.errors.documento_redi_arrendador"
                        >
                            <template v-slot:append>
                                <q-btn round dense flat icon="add" @click="addToTemplate(3)" />
                            </template>
                        </TextInput>
                    </div>

                    <div class="col-5  tw-mx-3">
                        <TextInput
                                type="text"
                                v-model="form.nombre_representante_arrendador"
                                label="Nombre del representante"
                                v-model:errors="form.errors.nombre_representante_arrendador"
                        >
                            <template v-slot:append>
                                <q-btn round dense flat icon="add" @click="addToTemplate(5)" />
                            </template>
                        </TextInput>
                      </div>
                    <div class="col-5  tw-mx-3">
                        <TextInput
                                type="text"
                                v-model="form.cedula_representante_arrendador"
                                label="Cédula del representante"
                                v-model:errors="form.errors.cedula_representante_arrendador"
                        >
                            <template v-slot:append>
                                <q-btn round dense flat icon="add" @click="addToTemplate(6)" />
                            </template>
                        </TextInput>
                    </div>
                    <div class="col-5  tw-mx-3">
                        <TextInput
                                type="text"
                                v-model="form.correo_representante_arrendador"
                                label="Correo del representante"
                                v-model:errors="form.errors.correo_representante_arrendador"
                        >
                            <template v-slot:append>
                                <q-btn round dense flat icon="add" @click="addToTemplate(7)" />
                            </template>
                        </TextInput>
                      </div>
                    <div class="col-5 tw-mx-3">
                        <TextInput
                                type="text"
                                v-model="form.telefono_representante_arrendador"
                                label="Telefónos del representante"
                                v-model:errors="form.errors.telefono_representante_arrendador"
                        >
                            <template v-slot:append>
                                <q-btn round dense flat icon="add" @click="addToTemplate(8)" />
                            </template>
                        </TextInput>
                    </div>
                  </q-card-section>
                </q-tab-panel>

                  <q-tab-panel name="d2">
                      <q-card-section class="row">
                          <div class="col-5  tw-mx-3">
                              <TextInput
                                  type="text"
                                  disable
                                  label="Nombre de la empresa"

                              >
                                  <template v-slot:append>
                                      <q-btn round dense flat icon="add" @click="addToTemplate(9)" />
                                  </template>
                              </TextInput>
                          </div>
                          <div class="col-5  tw-mx-3">
                              <TextInput
                                  type="text"
                                 disable
                                  label="Dirección de la empresa"

                              >
                                  <template v-slot:append>
                                      <q-btn round dense flat icon="add" @click="addToTemplate(12)" />
                                  </template>
                              </TextInput>

                          </div>
                          <div class="col-5  tw-mx-3">
                              <TextInput
                                  type="text"
                                  disable
                                  label="Ficha de inscripción"
                              >
                                  <template v-slot:append>
                                      <q-btn round dense flat icon="add" @click="addToTemplate(10)" />
                                  </template>
                              </TextInput>
                          </div>
                          <div class="col-5  tw-mx-3">
                              <TextInput
                                  type="text"
                                  disable
                                  label="Documento REDI"

                              >
                                  <template v-slot:append>
                                      <q-btn round dense flat icon="add" @click="addToTemplate(11)" />
                                  </template>
                              </TextInput>
                          </div>

                          <div class="col-5  tw-mx-3">
                              <TextInput
                                  type="text"
                                  disable
                                  label="Nombre del representante"
                                  v-model:errors="form.errors.nombre_representante_arrendador"
                              >
                                  <template v-slot:append>
                                      <q-btn round dense flat icon="add" @click="addToTemplate(13)" />
                                  </template>
                              </TextInput>
                          </div>
                          <div class="col-5  tw-mx-3">
                              <TextInput
                                  type="text"
                                  disable
                                  label="Cédula del representante"

                              >
                                  <template v-slot:append>
                                      <q-btn round dense flat icon="add" @click="addToTemplate(14)" />
                                  </template>
                              </TextInput>
                          </div>
                          <div class="col-5  tw-mx-3">
                              <TextInput
                                  type="text"
                                  disable
                                  label="Correo del representante"

                              >
                                  <template v-slot:append>
                                      <q-btn round dense flat icon="add" @click="addToTemplate(15)" />
                                  </template>
                              </TextInput>
                          </div>
                          <div class="col-5 tw-mx-3">
                              <TextInput
                                  type="text"
                                  disable
                                  label="Telefónos del representante"

                              >
                                  <template v-slot:append>
                                      <q-btn round dense flat icon="add" @click="addToTemplate(16)" />
                                  </template>
                              </TextInput>
                          </div>
                      </q-card-section>
                  </q-tab-panel>
              </q-tab-panels>
            </q-card>
        </div>
        <div class="container">
            <q-card class="tw-p-6">
                <q-card-section>
                    <Editor v-model="form.clausules" />
                </q-card-section>
                <q-card-actions align="right">
                    <PrimaryButton @click="submit" :loading="form.processing">
                        {{ $t('actions.ok') }}
                    </PrimaryButton>
                </q-card-actions>
            </q-card>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">

import {Head, useForm, usePage} from "@inertiajs/vue3";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import Editor from "../../Components/Editor.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import route from "ziggy-js";
import TextInput from "@/Components/TextInput.vue";
import {ref} from "vue"

const tab = ref("d1")
const page = usePage()
const form = useForm({
    clausules: page.props.clausules,
    nombre_empresa_arrendador: page.props.settings.nombre_empresa_arrendador,
    ficha_inscripcion_arrendador: page.props.settings.ficha_inscripcion_arrendador,
    documento_redi_arrendador: page.props.settings.documento_redi_arrendador,
    direccion_empresa_arrendador: page.props.settings.direccion_empresa_arrendador,
    nombre_representante_arrendador: page.props.settings.nombre_representante_arrendador,
    cedula_representante_arrendador: page.props.settings.cedula_representante_arrendador,
    correo_representante_arrendador: page.props.settings.correo_representante_arrendador,
    telefono_representante_arrendador: page.props.settings.telefono_representante_arrendador,
})

function submit() {
    form.post(route('settings.clausules.store'), {
        preserveScroll: true
    })
}

function addToTemplate(placeholder:number){

  switch (placeholder) {
    case 1:
      form.clausules = form.clausules + ' ' + '[ARRENDADOR_NOMBREEMPRESA]'
      break;
    case 2:
      form.clausules = form.clausules + ' ' + '[ARRENDADOR_FICHAINSCRIPCION]'
      break;
    case 3:
      form.clausules = form.clausules + ' ' + '[ARRENDADOR_DOCUMENTOREDI]'
      break;
    case 4:
      form.clausules = form.clausules + ' ' + '[ARRENDADOR_DIRECCIONEMPRESA]'
      break;
    case 5:
      form.clausules = form.clausules + ' ' + '[ARRENDADOR_NOMBREREPRESENTANTE]'
      break;
    case 6:
      form.clausules = form.clausules + ' ' + '[ARRENDADOR_CEDULAREPRESENTANTE]'
      break;
    case 7:
      form.clausules = form.clausules + ' ' + '[ARRENDADOR_CORREOREPRESENTANTE]'
      break;
    case 8:
      form.clausules = form.clausules + ' ' + '[ARRENDADOR_TELEFONOREPRESENTANTE]'
      break;

    case 9:
      form.clausules = form.clausules + ' ' + '[ARRENDATARIO_NOMBREEMPRESA]'
      break;
    case 10:
      form.clausules = form.clausules + ' ' + '[ARRENDATARIO_FICHAINSCRIPCION]'
      break;
    case 11:
      form.clausules = form.clausules + ' ' + '[ARRENDATARIO_DOCUMENTOREDI]'
      break;
    case 12:
      form.clausules = form.clausules + ' ' + '[ARRENDATARIO_DIRECCIONEMPRESA]'
      break;
    case 13:
      form.clausules = form.clausules + ' ' + '[ARRENDATARIO_NOMBREREPRESENTANTE]'
      break;
    case 14:
      form.clausules = form.clausules + ' ' + '[ARRENDATARIO_CEDULAREPRESENTANTE]'
      break;
    case 15:
      form.clausules = form.clausules + ' ' + '[ARRENDATARIO_CORREOREPRESENTANTE]'
      break;
    case 16:
      form.clausules = form.clausules + ' ' + '[ARRENDATARIO_TELEFONOREPRESENTANTE]'
      break;
  }
}
</script>
