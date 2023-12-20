<template>
    <!-- content -->
    <q-card class="my-card" style="width: 1400px; max-width: 80vw">
      <q-card-section class="bg-primary text-white">
        <div class="text-h6">Nueva Solicitud</div>
      </q-card-section>
      <q-card-section class="q-pa-none">
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
                        :label="  solicitudForm.tipo_documento === 'EMPRESA' ? 'RUC' : 'DNI' " mask="########"
                        :rules="[
                          (val) => (val && val.length > 0) || 'Por favor ingrese el DNI',
                        ]"
                      >
                        <template v-slot:after>
                          <q-btn :label="$q.screen.lt.sm ? '' : 'Buscar'" @click="getSolicitante"
                            color="primary" icon-right="search"
                          />
                        </template>
                      </q-input>
                      <div v-if="NoEncontroDatosPersona"> No se encontraron datos</div>
                      <div class="row">
                        <q-input class="col-12 col-md-6 q-pa-sm" label="Apellido Paterno" dense outlined clearable
                          v-model="solicitudForm.apellido_paterno" :loading="loading" />
                        <q-input class="col-12 col-md-6 q-pa-sm" label="Apellido Materno" dense outlined clearable
                          v-model="solicitudForm.apellido_materno" :loading="loading" />
                        <q-input class="col-12 col-md-6 q-pa-sm" label="Nombres" dense outlined clearable
                          v-model="solicitudForm.nombres" :loading="loading" />
                        <q-input class="col-12 col-md-6 q-pa-sm" label="Celular" dense outlined clearable
                          v-model="solicitudForm.celular" />
                        <SelectUbigeoPuno :ubigeo_cod="solicitudForm.ubigeo_cod"
                          @selectedItem="updateUbigeo($event)" Class="col-12 col-md-6 q-pa-sm"/>
                      </div>
                    </div>
                  </q-tab-panel>
                </q-tab-panels>

                <div v-if="okSolicitante"  class="q-gutter-md">
                  <div class="row">
                    <q-input class="col-12 col-md-6 q-pa-sm" label="Correo Electronico" dense outlined clearable
                        v-model="solicitudForm.correo" />
                    <q-input class="col-12 col-md-6 q-pa-sm" label="Direccion - Domicilio" dense outlined clearable
                        v-model="solicitudForm.direccion" />
                  </div>
                </div>

              </q-step>

              <q-step :name="2" title="Registrar Solicitud" caption="Opcional" icon="create_new_folder"
                :done="step > 2" :header-nav="step > 2" >
                <div class="q-gutter-md q-mb-md">
                  <div class="row">
                    <SelectUbigeoPuno :ubigeo_cod="solicitudForm.ubigeo_cod_soli"
                        @selectedItem="updateUbigeoSoli($event)" Class="col-12 col-md-6 q-pa-sm"/>
                    <SelectInput class="col-12 col-md-6 q-pa-sm" label="Notarios" v-model="solicitudForm.notario" :options="GenerateListService"
                        :GenerateList="{ column: 'notario', table: 'all' }" />
                    <SelectInput class="col-12 col-md-6 q-pa-sm" label="Subserie" v-model="solicitudForm.subserie" :options="GenerateListService"
                        :GenerateList="{ column: 'subserie', table: 'all' }" />
                    <q-input class="col-12 col-md-6 q-pa-sm" dense outlined clearable
                        v-model="solicitudForm.otorgantes" label="Otorgante"/>
                    <q-input class="col-12 col-md-6 q-pa-sm" dense outlined clearable
                        v-model="solicitudForm.favorecidos" label="Favorecido"/>
                    <q-input class="col-12 col-md-6 q-pa-sm" dense outlined clearable
                        v-model="solicitudForm.bien" label="Nombre del Bien"/>
                    <q-input class="col-12 col-md-6 q-pa-sm" dense outlined clearable
                        v-model="solicitudForm.fecha" label="Fecha" mask="date" :rules="['date']">
                      <template v-slot:prepend>
                        <q-icon name="event" class="cursor-pointer">
                          <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                            <q-date v-model="solicitudForm.fecha">
                              <div class="row items-center justify-end">
                                <q-btn v-close-popup label="Close" color="primary" flat />
                              </div>
                            </q-date>
                          </q-popup-proxy>
                        </q-icon>
                      </template>
                    </q-input>
                  </div>
                  <q-input dense outlined clearable type="textarea" class="q-pa-sm"
                      v-model="solicitudForm.mas_datos" label="Mas datos: Escritura -  Protocolo -  Folio" />

              </div>
              <p>Seleccione el titulo de documento</p>
              <div>
                <q-toggle
                color="pink"
                false-value="Disagreed"
                true-value="Agreed"
                :label="`Model is number ${solicitudForm.testimonio}`"
                v-model="solicitudForm.testimonio"
              />

              <q-toggle
                :false-value="13"
                :label="`Model is number ${solicitudForm.copiaCertificada}`"
                :true-value="42"
                color="green"
                v-model="solicitudForm.copiaCertificada"
              />

              <q-toggle
                :false-value="true"
                :label="`Model is ${solicitudForm.copiaSimple} (flipped boolean)`"
                :true-value="false"
                color="red"
                v-model="solicitudForm.copiaSimple"
              />
              </div>
                <!-- CONTENIDO -->
              </q-step>

              <template v-slot:navigation>
                <q-stepper-navigation>
                  <!-- <q-btn
                    v-if="step !== 2"
                    @click="step !== 1 ? $refs.stepper.next(): okSolicitante ? SiguienteStepper($refs.stepper):regla_DNI=true"
                    color="primary"
                    label="Siguiente"
                    type="button"
                  /> -->
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
                    label="Regresar"
                    class="q-ml-sm"
                    />
                  <GenerarPDFSolicitud v-if="step === 2" :datosSolicitud="solicitudForm" class="q-ml-sm"/>
                  <span v-if="regla_DNI" class="text-red-5 q-mx-sm">Ingrese DNI</span>
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
  import { ref, computed, watch } from "vue";
  import DniService from "src/services/DniService";
  import SolicitudService from "src/services/SolicitudService";
  import NotarioService from "src/services/NotarioService";
  import SubSerieService from "src/services/SubSerieService";
  import SelectUbigeoPuno from "src/components/SelectUbigeoPuno.vue";
  import SelectInput from "src/components/SelectInput.vue";
  import GenerateListService from "src/services/arp_v1/GenerateListService"

  import GenerarPDFSolicitud from "src/components/GenerarPDFSolicitud.vue";
  import { useQuasar } from "quasar";

  const $q = useQuasar();

  const step = ref(1);

  const emit = defineEmits(["save"]);
  const regla_DNI = ref(false);

  const solicitudForm = ref({
    //parte de solicitante ************
    id:null,
    nombres: "",
    apellido_paterno: "",
    apellido_materno: "",
    nombre_completo: '',
    tipo_documento: 'PERSONA',
    num_documento: "",
    direccion: "",
    correo: "",
    celular: "",
    ubigeo_cod: null,
    //parte de solicitud **************
    notario_id:'',
    subserie_id:'',
    otorgantes: "",
    favorecidos: "",
    fecha:'',
    bien: "",
    mas_datos: "",
    tipo_copia:'',
    ubigeo_cod_soli: null,
    estado:'En Proceso de Busqueda...',
    //datos para generar PDF **********
    testimonio: "",
    copiaCertificada: "",
    copiaSimple: "",
    notario:null,
    subserie:null,
    ubigeo_soli:'',
    ubigeo_pers:'',
  });

function updateUbigeo(event) {
  solicitudForm.value.ubigeo_cod = event.ubigeo_cod;
  solicitudForm.value.ubigeo_pers = event.nombre;
}
function updateUbigeoSoli(event) {
  solicitudForm.value.ubigeo_cod_soli = event.ubigeo_cod;
  solicitudForm.value.ubigeo_soli = event.nombre;
}
function SiguienteStepper(val){
  val.next();regla_DNI.value=false;
}

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
  console.log(res.data);
};

  // getData();

  // async function subserie(){
  //   const res = await SubSerieService.getData();
  //   console.log(res);
  // };

  // subserie();

const loading = ref(false);
const NoEncontroDatosPersona = ref(false);

async function getSolicitante() {
    loading.value = true;
    try {
      const res = await DniService.getSolicitanteDni(
        solicitudForm.value.num_documento
      );
      if (res.message) {
        console.log(res.message);
        // console.log("object");
        NoEncontroDatosPersona.value = true;
        onReset(false);
      } else {
        NoEncontroDatosPersona.value = false;
        console.log(res);
        solicitudForm.value.nombres = res.nombres;
        solicitudForm.value.apellido_paterno = res.apellidoPaterno;
        solicitudForm.value.apellido_materno = res.apellidoMaterno;
      }
    } catch (error) {
      NoEncontroDatosPersona.value = false;
    }
    loading.value = false;
}

function onReset(n_documento = true) {
    solicitudForm.value.cargo = null;
    console.log(n_documento);
    if (n_documento) {
      solicitudForm.value.num_documento = "";
    }
    solicitudForm.value.nombres = "";
    solicitudForm.value.apellido_paterno = "";
    solicitudForm.value.apellido_materno = "";
    solicitudForm.value.nombre_completo = null;
    solicitudForm.value.correo = null;
    solicitudForm.value.celular = null;
}

const onSubmit = async () => {
    solicitudForm.value.nombre_completo = nombreCompleto.value;
    const request = await SolicitudService.save(solicitudForm.value);
    console.log("enviado!",request);
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
