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
            <q-stepper
              v-model="step"
              ref="stepper"
              color="primary"
              header-nav
              animated
              flat
              bordered
            >
              <q-step
                :name="1"
                title="Datos Generales"
                icon="settings"
                :done="step > 1"
              >
                <q-option-group
                  v-model="solicitudForm.tipo_tramite"
                  inline
                  @update:model-value="onReset"
                  :options="[
                    { label: 'PERSONA', value: 'PERSONA' },
                    { label: 'EMPRESA', value: 'EMPRESA' },
                  ]"
                />
                <q-tab-panels v-model="solicitudForm.tipo_tramite">
                  <q-tab-panel name="PERSONA" class="q-pa-none">
                    <div class="text-h6">Persona</div>
                    <q-input
                      outlined
                      dense
                      v-model="solicitudForm.num_documento"
                      :label="
                        solicitudForm.tipo_tramite === 'EMPRESA' ? 'RUC' : 'DNI'
                      "
                      lazy-rules
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
                    <div class="q-gutter-md q-mb-md">
                      <div class="row">
                        <q-input
                          class="col q-mr-sm"
                          dense
                          v-model="solicitudForm.apellido_paterno"
                          label="Apellido Paterno"
                          outlined
                        />
                        <q-input
                          class="col"
                          dense
                          v-model="solicitudForm.apellido_materno"
                          label="Apellido Materno"
                          outlined
                        />
                      </div>
                      <q-input
                        class="col"
                        dense
                        v-model="solicitudForm.nombres"
                        label="Nombres"
                        outlined
                      />
                    </div>
                  </q-tab-panel>
                </q-tab-panels>

                <div v-if="okSolicitante"  class="q-gutter-md">
                  <div class="row">
                    <q-input
                      class="col q-mr-sm"
                      dense
                      v-model="solicitudForm.correo"
                      label="Correo Electronico"
                      outlined
                    />
                    <q-input
                      class="col"
                      dense
                      v-model="solicitudForm.celular"
                      label="Celular"
                      outlined
                    />
                  </div>
                  <q-input
                      class="col"
                      dense
                      v-model="solicitudForm.direccion"
                      label="Direccion - Domicilio"
                      outlined
                    />
                 <ul>
                  <li>Ubigeo (delimitarlo a solo region de Puno)</li>
                 </ul>
                </div>
                
              </q-step>
  
              <q-step
                :name="2"
                title="Registrar Solicitud"
                caption="Opcional"
                icon="create_new_folder"
                :done="step > 2"
              >
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
  import { useQuasar } from "quasar";
  
  const $q = useQuasar();

  const step = ref(1);
  
  const emit = defineEmits(["save"]);

  
  const solicitudForm = ref({
    num_documento: "",
    tipo_tramite: null,
    encontrado: false,
    nombres: "",
    apellido_paterno: "",
    apellido_materno: "",
    nombre_completo: null,
    correo: "",
    celular: "",
    direccion: "",
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
  

  async function getSolicitante() {
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
