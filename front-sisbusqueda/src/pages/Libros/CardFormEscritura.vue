<template>
  <q-card class="my-card">
    <q-card-section class="bg-primary text-white q-pa-none">
      <q-toolbar>
        <q-toolbar-title class="text-h5 text-center">{{ Libro.nombre }}</q-toolbar-title>
        <q-btn flat v-close-popup round dense icon="close" />
      </q-toolbar>
    </q-card-section>
    <q-separator />

    <q-form @submit="Submit()" @validation-error="ValidaError(step)"
      @validation-success="ValidaSuccess($refs.stepper, step)">
      <q-stepper v-model="step" ref="stepper" color="primary" flat animated header-nav bordered>
        <q-step :name="1" title="Datos de la Escritura" icon="settings" :done="step > 1" :header-nav="step > 1">
          <div class="row">
            <q-input label="N° de Escritura" dense class="col-12 col-sm-6 col-md-3 q-pa-sm" lazy-rules
              v-model="escritura.cod_escritura" mask="E-######" autofocus
              :rules="[val => (val !== '' && val !== null) || 'Ingrese un número de Escritura']" />
            <q-input label="Fecha de Escritura" mask="##/##/####" dense class="col-12 col-sm-6 col-md-3 q-pa-sm"
              lazy-rules v-model="escritura.fecha" :rules="[val => (val !== '' && val !== null) || 'Ingrese una Fecha']">
              <template v-slot:append>
                <q-icon icon name="event" class="cursor-pointer">
                  <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                    <q-date v-model="escritura.fecha" mask="DD/MM/YYYY" today-btn>
                      <div class="row items-center justify-end">
                        <q-btn v-close-popup label="Cerrar" color="primary" flat />
                      </div>
                    </q-date>
                  </q-popup-proxy>
                </q-icon>
              </template>
            </q-input>
            <q-input label="Folio" dense class="col-12 col-sm-6 col-md-3 q-pa-sm" lazy-rules
              v-model="escritura.cod_folioInicial" mask="F-######"
              :rules="[val => (val !== '' && val !== null) || 'Ingrese un número de Folio']" />
            <q-input label="Cantidad de folios" dense class="col-12 col-sm-6 col-md-3 q-pa-sm" lazy-rules
              v-model="escritura.n_folios" mask="##"
              :rules="[val => (val !== null && val >= 0 && val < 10) || 'Ingrese un número válido']" />
            <SelectInput label="Subserie" dense class="col-12 q-pa-sm" lazy-rules
              :rules="[val => (val !== '' && val !== null) || 'Selecione una Subserie']" v-model="escritura.subserie_id"
              :options="SubSerieService" OptionLabel="nombre" OptionValue="id" />
            <q-input label="Nombre de bien" dense class="col-12 q-pa-sm" lazy-rules v-model="escritura.bien"
              :rules="[val => (val !== '' && val !== null) || 'Ingrese el Bien']" />
            <q-input label="Observaciones de Escritura" autogrow dense class="col-12 q-pa-sm"
              v-model="escritura.observaciones" />
          </div>
        </q-step>
        <q-step :name="2" title="Otorgante(s)" caption="" icon="create_new_folder" :done="step > 2"
          :header-nav="step > 2">
          <q-option-group inline v-model="tipoPersonaOtorgante" :options="[
              { label: 'Natural', value: 'Natural' },
              { label: 'Jurico', value: 'Jurico' },
            ]" />
          <q-tab-panels v-model="tipoPersonaOtorgante">
            <q-tab-panel name="Natural">
              <div class="text-subtitle1">Persona Natural</div>
              <SelectInput label="Otorgante(s)" dense v-model="otorgante"
                :options="OtorganteService" OptionLabel="nombre_completo" OptionValue="id"
                :ValueMulti="['id', 'nombre_completo']" />
              <div class="row">
                <q-input label="Nombres" autogrow dense class="col-12 col-md-4 col-sm-6 q-pa-sm"
                  v-model="formOtorgante.nombre" />
                <q-input label="Primer Apellido" autogrow dense class="col-12 col-md-4 col-sm-6 q-pa-sm"
                  v-model="formOtorgante.apellido_paterno" />
                <q-input label="Segundo Apellido" autogrow dense class="col-12 col-md-4 col-sm-6 q-pa-sm"
                  v-model="formOtorgante.apellido_materno" />
              </div>
              <q-btn label="Agregar Otorgante" color="positive" @click="AñadirOtorgante"></q-btn>
            </q-tab-panel>

            <q-tab-panel name="Jurico">
              <div class="text-subtitle1">Persona Juridica</div>
              Lorem ipsum dolor sit amet consectetur adipisicing elit.
            </q-tab-panel>
          </q-tab-panels>
          <div> 
            <q-table :rows="escritura.otorgantes" :columns="columns" row-key="id" :rows-per-page-options="[0]">
              <template v-slot:body="props">
                <q-tr :props="props">
                  <q-td v-for="col in props.cols" :key="col.name" :props="props">
                    <template v-if="col.name === '#'"> {{ props.rowIndex + 1 }} </template>
                    <template v-else-if="col.name === 'acciones'">
                      <q-btn size="sm" outline color="red" round icon="delete" @click="removeOtorgante(props.rowIndex)" />
                    </template>
                    <template v-else> {{ col.value }} </template>
                  </q-td>
                </q-tr>
              </template>
            </q-table>
            <div v-if="errorOtorgante" class="text-red-10">Agregue Otorgante(s)</div>
          </div>
        </q-step>

        <q-step :name="3" title="Favorecidos(s)" caption="" icon="create_new_folder" :done="step > 3"
          :header-nav="step > 3">
          <q-option-group inline v-model="tipoPersonaFavorecido" :options="[
            { label: 'Natural', value: 'Natural' },
            { label: 'Jurico', value: 'Jurico' },
          ]" />
          <q-tab-panels v-model="tipoPersonaFavorecido">
            <q-tab-panel name="Natural">
              <div class="text-subtitle1">Persona Natural</div>
              <SelectInput label="Favorecidos" dense v-model="favorecido"
                :options="FavorecidoService" OptionLabel="nombre_completo" OptionValue="id"
                :ValueMulti="['id', 'nombre_completo']" />
              <div class="row">
                <q-input label="Nombres" autogrow dense class="col-12 col-md-4 col-sm-6 q-pa-sm"
                  v-model="formFavorecido.nombre" />
                <q-input label="Primer Apellido" autogrow dense class="col-12 col-md-4 col-sm-6 q-pa-sm"
                  v-model="formFavorecido.apellido_paterno" />
                <q-input label="Segundo Apellido" autogrow dense class="col-12 col-md-4 col-sm-6 q-pa-sm"
                  v-model="formFavorecido.apellido_materno" />
              </div>
              <q-btn label="Agregar Favorecido" color="positive" @click="AñadirFavorecido"></q-btn>
            </q-tab-panel>

            <q-tab-panel name="Jurico">
              <div class="text-subtitle1">Persona Juridica</div>
              Lorem ipsum dolor sit amet consectetur adipisicing elit.
            </q-tab-panel>
          </q-tab-panels>
          <div> 
            <q-table :rows="escritura.favorecidos" :columns="columns" row-key="id" :rows-per-page-options="[0]">
              <template v-slot:body="props">
                <q-tr :props="props">
                  <q-td v-for="col in props.cols" :key="col.name" :props="props">
                    <template v-if="col.name === '#'"> {{ props.rowIndex + 1 }} </template>
                    <template v-else-if="col.name === 'acciones'">
                      <q-btn size="sm" outline color="red" round icon="delete" @click="removeFavorecido(props.rowIndex)" />
                    </template>
                    <template v-else> {{ col.value }} </template>
                  </q-td>
                </q-tr>
              </template>
            </q-table>
            <div v-if="errorFavorecido" class="text-red-10">Agregue Favorecido(s)</div>
          </div>
        </q-step>
        <template v-slot:navigation>
          <q-stepper-navigation>
            <q-card-actions align="between">
              <q-btn v-if="step === 1" label="Cerrar" v-close-popup flat color="primary" class="q-ml-sm" />
              <q-btn v-if="step > 1" label="Anterior" flat color="primary" class="q-ml-sm"
                @click="$refs.stepper.previous()" />
              <q-btn :label="step < 3 ? 'Continuar' : 'Registrar'" type="submit"
                :color="step < 3 ? 'primary' : 'positive'" />
            </q-card-actions>
          </q-stepper-navigation>
        </template>
      </q-stepper>
    </q-form>
  </q-card>
</template>

<script setup>
import { onBeforeMount, ref, watch } from "vue";
import SelectInput from "src/components/SelectInput.vue";
import InputTextSelect from "src/components/InputTextSelect.vue";
import SubSerieService from "src/services/SubSerieService";
import OtorganteService from "src/services/OtorganteService";
import FavorecidoService from "src/services/FavorecidoService";
import EscrituraService from "src/services/EscrituraService";
import { convertDate } from "src/utils/ConvertDate";
const props = defineProps({
  Libro: { type: Object, default: null },
  Editar: { type: Object, default: null },
});

const emits = defineEmits(["save"]);
const escritura = ref({
  id: null,
  bien: null,
  subserie_id: null,
  fecha: null,
  cod_escritura: null,
  cod_folioInicial: null,
  cod_folioFinal: null,
  libro_id: null,
  observaciones: null, // colocar en la migración (ojo)
  /**** ************************************ */
  n_folios: 1,
  otorgantes: [],
  favorecidos: [],
});

const formOtorgante = ref({
  nombre: "",
  apellido_paterno: "",
  apellido_materno: "",
  nombre_completo: "",
});
const formFavorecido = ref({
  nombre: "",
  apellido_paterno: "",
  apellido_materno: "",
  nombre_completo: "",
});

const otorgante = ref(null);
const favorecido = ref(null);
const step = ref(1);

const tipoPersonaOtorgante = ref("Natural");
const tipoPersonaFavorecido = ref("Natural");
const errorOtorgante = ref(false);
const errorFavorecido = ref(false);

const columns = [
  { field: '', name: "#", label: "#", align: "center"},
  { field: (row) => row.id, name: "id", label: "ID", align: "center"},
  { field: (row) => row.nombre_completo, name: "nombre_completo", label: "Nombres y Apellidos", align: "center"},
  { field: '', name: "acciones", label: "Acciones", align: "center"},
];

onBeforeMount(() => {
  if (props.Editar) {
    let patron = /\d+/;
    let folioIni = props.Editar.cod_folioInicial.match(patron);
    let folioFin = props.Editar.cod_folioFinal.match(patron);

    escritura.value.id = props.Editar.id;
    escritura.value.bien = props.Editar.bien;
    escritura.value.subserie_id = props.Editar.subserie_id;
    escritura.value.fecha = convertDate(props.Editar.fecha, "dd/MM/yyyy");
    escritura.value.cod_escritura = props.Editar.cod_escritura;
    escritura.value.cod_folioInicial = props.Editar.cod_folioInicial;
    escritura.value.cod_folioFinal = props.Editar.cod_folioFinal;
    escritura.value.libro_id = props.Editar.libro_id;
    escritura.value.observaciones = props.Editar.observaciones;
    escritura.value.n_folios = folioIni && folioFin ? parseInt(folioFin[0], 10) - parseInt(folioIni[0], 10) + 1 : 1;
    props.Editar.otorgantes.map(item => {
      otorgante.value = item;
      addOtorgante();
    });
    props.Editar.favorecidos.map(item => {
      favorecido.value = item;
      addFavorecido();
    });
  }
});
const addOtorgante = () => {
  let permitir = escritura.value.otorgantes && escritura.value.otorgantes.length !== 0 ?
    !escritura.value.otorgantes.some(item => {
      return item.id === otorgante.value?.id;
    }) : true;
  if (permitir && otorgante.value){
    escritura.value.otorgantes.push({ ...otorgante.value });
    errorOtorgante.value = false;
  }
};
const removeOtorgante = (i) => {
  escritura.value.otorgantes.splice(i, 1);
  otorgante.value = null;
};

const addFavorecido = () => {
  let permitir = escritura.value.favorecidos && escritura.value.favorecidos.length !== 0 ?
    !escritura.value.favorecidos.some(item => {
      return item.id === favorecido.value?.id;
    }) : true;
  if (permitir && favorecido.value){
    escritura.value.favorecidos.push({ ...favorecido.value });
    errorFavorecido.value = false;
  } 
};
const removeFavorecido = (i) => {
  escritura.value.favorecidos.splice(i, 1);
  favorecido.value = null;
};
watch(() => otorgante.value, (newVal, oldVal) => {
  addOtorgante();
});
watch(() => favorecido.value, (newVal, oldVal) => {
  addFavorecido();
});
const Save = async () => {
  try {
    escritura.value.libro_id = props.Libro.id;
    escritura.value.fecha = convertDate(escritura.value.fecha, "yyyy-MM-dd");
    let patron = /\d+/;
    let resultado = escritura.value.cod_folioInicial.match(patron);
    let num = resultado ? parseInt(resultado[0], 10) + parseInt(escritura.value.n_folios) - 1 : null;
    escritura.value.cod_folioFinal = num ? 'F-' + num.toString() : escritura.value.cod_folioInicial;
    escritura.value.otorgantes = escritura.value.otorgantes.map((o) => {
      return o.id;
    });
    escritura.value.favorecidos = escritura.value.favorecidos.map((f) => {
      return f.id;
    });
    let res = await EscrituraService.save(escritura.value);
    emits("save"); //28/12/2023 23:11:01
  } catch (error) {
    console.log(error);
    window.alert(error);
  }
};
function Submit() {
  console.log('submit');
}
function ValidaError(step) {
  console.log('error stepper: ', step);
}
function ValidaSuccess(event, step) {
  if (step === 3) {
    if(escritura.value.otorgantes.length >0) Save();
    else errorFavorecido.value = true;
  } else if(step === 2) {
    if(escritura.value.otorgantes.length >0) event.next();
    else errorOtorgante.value = true;
  }else {
    event.next();
  }
}
async function AñadirOtorgante(){
  formOtorgante.value.nombre_completo= `${formOtorgante.value.nombre} ${formOtorgante.value.apellido_paterno} ${formOtorgante.value.apellido_materno}`;
  try {
      otorgante.value = await OtorganteService.save(formOtorgante.value);
      formOtorgante.value.nombre = "";
      formOtorgante.value.apellido_paterno = "";
      formOtorgante.value.apellido_materno = "";
    } catch (error) {
      console.log(error.response.data.errors);
    }
}
async function AñadirFavorecido(){
  formFavorecido.value.nombre_completo= `${formFavorecido.value.nombre} ${formFavorecido.value.apellido_paterno} ${formFavorecido.value.apellido_materno}`;
  try {
      favorecido.value = await FavorecidoService.save(formFavorecido.value);
      formFavorecido.value.nombre = "";
      formFavorecido.value.apellido_paterno = "";
      formFavorecido.value.apellido_materno = "";
    } catch (error) {
      console.log(error.response.data.errors);
    }
}
</script>
<style lang="sass" scoped>
.my-card
  width: 700px
  max-width: 80vw
</style>
