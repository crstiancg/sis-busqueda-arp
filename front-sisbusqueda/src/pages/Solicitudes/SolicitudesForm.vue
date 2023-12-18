<template>
    <!-- content -->
    <q-card class="my-card" style="width: 1400px; max-width: 80vw">
      <q-card-section class="bg-primary text-white">
        <div class="text-h6">Nueva Solicitud {{ step }}</div>
        <!-- <div class="text-subtitle2">Usuario</div> -->
      </q-card-section>
      <q-card-section class="q-pa-none">
        <!-- <div class="row">
          <div class="col-xs-12 col-sm-4 q-pa-md">
            <div class="text-center text-uppercase">
              <span class="text-weight-bold text-overline" style="font-size: 15px"
                >Detalles de la Solicitud</span
              >
            </div>

            {{ solicitudForm }}
          </div> -->
        <div class="col-xs-12 col-sm-8 q-pa-sm">
          <q-form @submit="onSubmit">
            <q-stepper v-model="step" ref="stepper" color="primary" header-nav animated flat bordered>
              <q-step :name="1" title="Datos Generales" icon="settings"
                :done="step > 1" :header-nav="step > 1">
                <q-option-group
                  v-model="solicitudForm.tipo_documento" inline @update:model-value="onReset"
                  :options="[ { label: 'PERSONA', value: 'PERSONA' }, { label: 'EMPRESA', value: 'EMPRESA' },]"
                />
                <q-tab-panels v-model="solicitudForm.tipo_documento">
                  <q-tab-panel name="PERSONA" class="q-pa-none">
                    <div class="text-h6">Persona</div>
                    <div class="q-gutter-md q-mb-md">
                      <q-input outlined lazy-rules dense v-model="solicitudForm.num_documento" class="q-pa-sm"
                        :label="  solicitudForm.tipo_documento === 'EMPRESA' ? 'RUC' : 'DNI' "
                        :rules="[
                          (val) =>
                            (val && val.length > 0) || 'Please type something',
                        ]"
                      >
                        <template v-slot:after>
                          <q-btn
                            :label="$q.screen.lt.sm ? '' : 'Buscar'"
                            @click="getSolicitante"
                            color="primary"
                            icon-right="search"
                          />
                        </template>
                      </q-input>
                      <!-- <SelectUbigeoPuno :ubigeo_cod="solicitudForm.ubigeo_cod" > </SelectUbigeoPuno> -->
                      <div class="row">
                        <q-input class="col-12 col-md-6 q-pa-sm" label="Apellido Paterno" dense outlined
                          v-model="solicitudForm.apellido_paterno" :loading="loading" />
                        <q-input class="col-12 col-md-6 q-pa-sm" label="Apellido Materno" dense outlined
                          v-model="solicitudForm.apellido_materno" :loading="loading" />
                        <q-input class="col-12 col-md-6 q-pa-sm" label="Nombres" dense outlined
                          v-model="solicitudForm.nombres" :loading="loading" />
                        <q-input class="col-12 col-md-6 q-pa-sm" label="Celular" dense outlined
                          v-model="solicitudForm.celular" />
                      </div>
                    </div>
                  </q-tab-panel>
                </q-tab-panels>

                <div v-if="okSolicitante"  class="q-gutter-md">
                  <div class="row">
                    <q-input class="col-12 col-md-6 q-pa-sm" label="orreo Electronico" dense outlined
                        v-model="solicitudForm.correo" />
                    <q-input class="col-12 col-md-6 q-pa-sm" label="Direccion - Domicilio" dense outlined
                        v-model="solicitudForm.direccion" />
                  </div>
                 <ul>
                  <li>Ubigeo (delimitarlo a solo region de Puno)</li>
                 </ul>
                </div>

              </q-step>

              <q-step :name="2" title="Registrar Solicitud" caption="Opcional" icon="create_new_folder"
                :done="step > 2" :header-nav="step > 2" >
                <div class="q-gutter-md q-mb-md">
                  <li>Ubigeo (delimitarlo a Puno)</li>
                  <li>Select Notario</li>
                  <li>Select Subserie (lista de escrituras públicas)</li>
                  <div class="row">
                    <q-input
                      class="col q-mr-sm"
                      dense
                      v-model="solicitudForm.otorgantes"
                      label="Otorgante"
                      outlined
                    />
                    <q-input
                      class="col"
                      dense
                      v-model="solicitudForm.favorecidos"
                      label="Favorecido"
                      outlined
                    />
                  </div>
                  <q-input
                    class="col q-mr-sm"
                    dense
                    v-model="solicitudForm.bien"
                    label="Nombre del Bien"
                    outlined
                  />
                  <q-input
                    clearable
                    dense
                    type="textarea"
                    v-model="solicitudForm.masdatos"
                    label="Mas datos: Escritura -  Protocolo -  Folio"
                    filled
                    color="blue-12"
                    :shadow-text="textareaShadowText"
                    @keydown="processTextareaFill"
                    @focus="processTextareaFill"
                  />

              </div>
              <p>Seleccione el titulo de documento</p>
              <div>
                <q-toggle
                color="pink"
                false-value="Disagreed"
                true-value="Agreed"
                :label="`Model is number ${greenModel}`"
                v-model="solicitudForm.testimonio"
              />

              <q-toggle
                :false-value="13"
                :label="`Model is number ${greenModel}`"
                :true-value="42"
                color="green"
                v-model="solicitudForm.copiaCertificada"
              />

              <q-toggle
                :false-value="true"
                :label="`Model is ${redModel} (flipped boolean)`"
                :true-value="false"
                color="red"
                v-model="solicitudForm.copiaSimple"
              />
              </div>
                <!-- CONTENIDO -->
              </q-step>

              <template v-slot:navigation>
                <q-stepper-navigation>
                  <q-btn
                    v-if="step !== 2"
                    @click="$refs.stepper.next()"
                    color="primary"
                    label="Siguiente"
                    type="button"
                  />
                  <q-btn
                    v-if="step === 2"
                    @click="$refs.stepper.next()"
                    color="primary"
                    type="submit"
                    label="Enviar"
                  />
                  <q-btn
                    v-if="step > 1"
                    flat
                    color="primary"
                    @click="$refs.stepper.previous()"
                    label="Back"
                    class="q-ml-sm"
                  />
                </q-stepper-navigation>
              </template>
            </q-stepper>
          </q-form>
        </div>
        <!-- </div> -->
      </q-card-section>
    </q-card>
  </template>


  <script setup>
  import { ref, computed } from "vue";
  import DniService from "src/services/DniService";
  import SolicitudService from "src/services/SolicitudService";
  import NotarioService from "src/services/NotarioService";
  import SubSerieService from "src/services/SubSerieService";
  import SelectUbigeoPuno from "src/components/SelectUbigeoPuno.vue";
  import { useQuasar } from "quasar";

  const $q = useQuasar();

  const step = ref(1);

  const emit = defineEmits(["save"]);

  const solicitanteForm = ref({

  });

  const solicitudForm = ref({
    num_documento: "",
    tipo_documento: 'PERSONA',
    encontrado: false,
    nombres: "",
    apellido_paterno: "",
    apellido_materno: "",
    nombre_completo: null,
    correo: "",
    celular: "",
    direccion: "",
    ubigeo_cod:'',
    otorgantes: "",
    favorecidos: "",
    bien: "",
    masdatos: "",
    testimonio: "",
    copiaCertificada: "",
    copiaSimple: "",
  });


  const okSolicitante = computed(() => {
  if (
    solicitudForm.value.nombres !== "" &&
    solicitudForm.value.apellido_materno !== "" &&
    solicitudForm.value.apellido_paterno !== "" &&
    solicitudForm.value.num_documento !== ""
  ) {
    return true;
  } else {
    return false;
  }
});

  const nombreCompleto = computed(() => {
    return (
      solicitudForm.value.nombres +
      " " +
      solicitudForm.value.apellido_paterno +
      " " +
      solicitudForm.value.apellido_materno
    );
  });

  async function getData(){
    const res = await NotarioService.getData();
    const res2 = await SubSerieService.getData();
    console.log(res.data);
    console.log(res2.data);
  };

  getData();

  // async function subserie(){
  //   const res = await SubSerieService.getData();
  //   console.log(res);
  // };

  // subserie();

const loading = ref(false);

  async function getSolicitante() {
    loading.value = true;
    try {
      const res = await DniService.getSolicitanteDni(
        solicitudForm.value.num_documento
      );
      if (res.message) {
        console.log(res.message);
        // console.log("object");
        onReset(false);
      } else {
        console.log(res);
        solicitudForm.value.encontrado = true;
        solicitudForm.value.nombres = res.nombres;
        solicitudForm.value.apellido_paterno = res.apellidoPaterno;
        solicitudForm.value.apellido_materno = res.apellidoMaterno;
      }
    } catch (error) {
      formDni.value.encontrado = false;
    }
    loading.value = false;
  }


  function onReset(n_documento = true) {
    solicitudForm.value.cargo = null;
    console.log(n_documento);
    if (n_documento) {
      solicitudForm.value.num_documento = "";
    }
    solicitudForm.value.encontrado = false;
    solicitudForm.value.nombres = "";
    solicitudForm.value.apellido_paterno = "";
    solicitudForm.value.apellido_materno = "";
    solicitudForm.value.nombre_completo = null;
    solicitudForm.value.correo = null;
    solicitudForm.value.celular = null;
  }

  const onSubmit = async () => {
    solicitudForm.value.nombre_completo = nombreCompleto.value;
    await SolicitudService.save(solicitudForm.value);
    console.log("enviado!");
    emit("save");
  };
  const save = () => {
    formTrabajadores.value = false;
    // tableRef.value.requestServerInteraction();
    $q.notify({
      type: "positive",
      message: "Guardado con Exito.",
      position: "top-right",
      progress: true,
      timeout: 1000,
    });
  };
  </script>
  <style></style>
