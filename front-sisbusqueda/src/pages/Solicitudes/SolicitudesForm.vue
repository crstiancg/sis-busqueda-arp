<template>
    <!-- content -->
    <q-card class="my-card" style="width: 1400px; max-width: 80vw">
      <q-card-section class="bg-primary text-white row">
        <div class="text-h6">{{title}}</div>
        <q-space />

        <q-btn icon="close" color="negative" round  v-close-popup />
      </q-card-section>
      <q-card-section class="q-pa-none">
        <div class="col-xs-12 col-sm-8 q-pa-sm">
          <q-form @submit="onSubmit">
            <q-stepper v-model="step" ref="stepper" color="primary" header-nav animated flat bordered>
              <q-step :name="1" title="Registro Solicitante" icon="settings"
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
                          v-model="solicitudForm.celular" mask="### ### ###"/>
                        <div class="row full-width">
                          <SelectUbigeoPlus v-model="solicitudForm.ubigeo_cod" cod_departamento="21" Class="q-pa-sm col-12 col-md-6"/>
                        </div>
                      </div>
                    </div>
                  </q-tab-panel>
                </q-tab-panels>

                <div v-if="okSolicitante"  class="q-gutter-md">
                  <div class="row">
                    <q-input class="col-12 col-md-6 q-pa-sm" label="Correo Electronico" dense outlined clearable
                        v-model="solicitudForm.correo" :rules="['email']"/>
                    <q-input class="col-12 col-md-6 q-pa-sm" label="Direccion - Domicilio" dense outlined clearable
                        v-model="solicitudForm.direccion" />
                  </div>
                </div>

              </q-step>

              <q-step :name="2" title="Registrar Solicitud" icon="create_new_folder"
                :done="step > 2" :header-nav="step > 2" >
                <div class="q-gutter-md q-mb-md">
                  <div class="row">
                    <div class="row full-width">
                      <SelectUbigeoPlus v-model="solicitudForm.ubigeo_cod_soli" cod_departamento="21" Class="q-pa-sm col-12 col-md-6"/>
                    </div>
                    <!-- <SelectUbigeoPuno ref="solicitudUbigeoRef" :ubigeo_cod="solicitudForm.ubigeo_cod_soli"
                        @selectedItem="updateUbigeoSoli($event)" Class="col-12 col-md-6 q-pa-sm"/> -->
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
                <!-- CONTENIDO -->
              </q-step>
              <q-step :name="3" title="Registro Caja" icon="point_of_sale"
                :done="step > 3" :header-nav="step > 3" >
                  <div class="row">
                    <p>Seleccione el Tipo de Documento:</p> <q-space />
                    <GenerarPDFSolicitud :datosSolicitud="solicitudForm" label="Pre Generar PDF" class="q-ml-sm"/>
                  </div>
                  <div class="row full-width">
                    <q-toggle v-model="solicitudForm.tipo_copia" color="pink" true-value="Testimonio" label="Testimonio"/>
                    <q-toggle v-model="solicitudForm.tipo_copia" label="Copia Certificada" true-value="Copia Certificada" color="green" />
                    <q-toggle v-model="solicitudForm.tipo_copia" label="Copia Simple" true-value="Copia Simple" color="red" />
                    <q-space/>
                    <div class="row items-center">Precio por Copia: {{ formatNumberToSoles(precioVigente) }}</div>
                  </div>
                  <div class="row">
                    <div class="col-12 col-md-6">
                      <q-input class="q-pa-sm" type="number" suffix="Copia(s)" dense outlined clearable
                          v-model="solicitudForm.cantidad_copia" label="Cantidad de copias"/>
                      <q-input class="q-pa-sm" type="number" prefix="s/" dense outlined clearable
                          v-model="montoEntregado" label="Monto entregado"/>
                    </div>
                    <div class="col-12 col-md-6 row">
                      <div class="col-7">
                        <div class="q-ma-sm text-right text-weight-bold text-subtitle1">Subtotal :</div>
                        <div class="q-ma-sm text-right text-weight-bold text-subtitle1">Monto Entrgado :</div>
                        <div class="q-ma-sm text-right text-weight-bold text-subtitle1">Monto de Cambio (vuelto) :</div>
                      </div>
                      <div class="col-5">
                        <div class="q-ma-sm text-green-13 text-weight-bold text-subtitle1">{{ solicitudForm.cantidad_copia?formatNumberToSoles(precioVigente*solicitudForm.cantidad_copia):'' }}</div>
                        <div class="q-ma-sm text-subtitle1">{{ montoEntregado?formatNumberToSoles(montoEntregado):'' }}</div>
                        <div class="q-ma-sm text-yellow-6 text-subtitle1">{{ montoEntregado?formatNumberToSoles(montoEntregado-(precioVigente*solicitudForm.cantidad_copia)):'' }}</div>
                      </div>
                    </div>
                  </div>
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
                    v-if="step !== 3"
                    @click="$refs.stepper.next()"
                    color="primary"
                    label="Siguiente"
                    type="button"
                  />
                  <q-btn
                    v-if="step === 3"
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
import { ref, computed, watch, onMounted } from "vue";
import DniService from "src/services/DniService";
import SolicitudService from "src/services/SolicitudService";
import PrecioService from "src/services/PrecioService";
import SelectUbigeoPuno from "src/components/SelectUbigeoPuno.vue";
import SelectUbigeoPlus from "src/components/SelectUbigeoPlus.vue"
import SelectInput from "src/components/SelectInput.vue";
import GenerateListService from "src/services/arp_v1/GenerateListService"
import GenerarPDFSolicitud from "src/components/GenerarPDFSolicitud.vue";
import { formatNumberToSoles } from "src/utils/ConvertMoney"
import { useQuasar } from "quasar";

const $q = useQuasar();

const step = ref(1);

const emit = defineEmits(["save"]);
const regla_DNI = ref(false);

const montoEntregado = ref();
const precioVigente = ref();
const props = defineProps ({
  title: String,
});
const usuarioUbigeoRef = ref("");
const solicitudUbigeoRef = ref("");
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
    ubigeo_cod_soli: null,
    bien: "",
    mas_datos: "",
    tipo_copia:'Copia Simple',
    cantidad_copia:'',
    estado:'',
    area_id: 2,
    //datos para generar PDF **********
    notario:null,
    subserie:null,
    ubigeo_soli:'',
    ubigeo_pers:'',
    precio:'',
    /******************* */
    testimonio: "",
    copiaCertificada: "",
    copiaSimple: "",
  });

async function getPrecioVigente(){
  precioVigente.value = (await PrecioService.getData({params: {vigencia:1}}))[0].monto;
  solicitudForm.value.precio = precioVigente.value;
  // console.log(precioVigente.value);
}

onMounted(()=>{
  getPrecioVigente();
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

// function setValue(values) {
//       form.value = values;
//       usuarioUbigeoRef.value.getUbigeo(form.value.ubigeo_cod);
// }

function setValue(values) {
  // solicitudForm.value = values;
  solicitudForm.value.id = values.id;
  solicitudForm.value.nombres = values.solicitante.nombres;
  solicitudForm.value.apellido_paterno = values.solicitante.apellido_paterno;
  solicitudForm.value.apellido_materno = values.solicitante.apellido_materno;
  solicitudForm.value.num_documento = values.solicitante.num_documento;
  solicitudForm.value.celular = values.solicitante.celular;
  solicitudForm.value.correo = values.solicitante.correo;
  solicitudForm.value.direccion = values.solicitante.direccion;
  solicitudForm.value.ubigeo_cod = values.solicitante.ubigeo_cod;
  solicitudForm.value.otorgantes = values.otorgantes;
  solicitudForm.value.favorecidos = values.favorecidos;
  solicitudForm.value.ubigeo_cod_soli = values.ubigeo_cod;
  solicitudForm.value.bien = values.bien;
  solicitudForm.value.fecha = values.fecha;
  solicitudForm.value.mas_datos = values.mas_datos;
}

defineExpose({
    // setData,
    setValue,
  });
</script>
<style></style>
