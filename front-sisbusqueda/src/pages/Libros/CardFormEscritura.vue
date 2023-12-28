<template>
  <q-card class="my-card">
    <q-card-section class="bg-primary text-white q-pa-none">
      <q-toolbar>
        <q-toolbar-title class="text-h5 text-center">{{ Libro.nombre }}</q-toolbar-title>
        <q-btn flat v-close-popup round dense icon="close" />
      </q-toolbar>
    </q-card-section>
    <q-separator />

    <q-form @submit="onSubmit">
      <q-stepper v-model="step" ref="stepper" color="primary" flat animated>
        <q-step :name="1" title="Select campaign settings" icon="settings" :done="step > 1">
          <div class="row">
            <q-input label="N° de Escritura" dense class="col-12 col-sm-6 col-md-3 q-pa-sm"
                v-model="escritura.cod_escritura"/>
            <q-input label="Fecha de Escritura" mask="##/##/####" dense class="col-12 col-sm-6 col-md-3 q-pa-sm"
                v-model="escritura.fecha">
                <template v-slot:append>
                  <q-icon icon name="event" class="cursor-pointer">
                    <q-popup-proxy  cover transition-show="scale" transition-hide="scale">
                      <q-date v-model="escritura.fecha" mask="DD/MM/YYYY" today-btn>
                        <div class="row items-center justify-end">
                          <q-btn v-close-popup label="Cerrar" color="primary" flat />
                        </div>
                      </q-date>
                    </q-popup-proxy>
                  </q-icon>
                </template>
            </q-input>
            <q-input  label="Folio" dense class="col-12 col-sm-6 col-md-3 q-pa-sm"
                v-model="escritura.folio" prefix="F-"/>
            <q-input label="Cantidad de folios" type="number" dense class="col-12 col-sm-6 col-md-3 q-pa-sm"
                v-model="escritura.n_folios" />
            <SelectInput label="Subserie" dense class="col-12 q-pa-sm"
                v-model="subSerie" :options="SubSerieService" OptionLabel="nombre" OptionValue="id" />
            <q-input label="Nombre de bien" dense class="col-12 q-pa-sm"
                v-model="escritura.bien" />
            <q-input label="Observaciones de Escritura" autogrow dense class="col-12 q-pa-sm"
                v-model="escritura.observacion"/>
          </div>
        </q-step>
        <q-step :name="2" title="Create an ad group" caption="Optional" icon="create_new_folder" :done="step > 2">
          <q-option-group inline
              v-model="tipoPersona"
              :options="[
                { label: 'Natural', value: 'Natural' },
                { label: 'Jurico', value: 'Jurico' },
              ]"/>
          <q-tab-panels v-model="tipoPersona">
            <q-tab-panel name="Natural">
              <div class="text-h6">Persona Natural</div>
              <!-- <q-select label="Apellidos y Nombres" dense>
                <template v-slot:after>
                  <q-btn color="primary" label="Añadir"></q-btn>
                </template>
              </q-select> -->
              <SelectInput label="Otorgantes" dense clearable
                  v-model="otorgante" :options="OtorganteService" OptionLabel="nombre_completo" OptionValue="id"
                  :ValueMulti="['id','nombre_completo']"/>
              <q-btn label="Añadir"
                @click="addOtorgante" color="primary"/>
              <q-btn label="Nuevo" color="positive"></q-btn>

              <q-markup-table flat class="q-mt-sm">
                <thead>
                  <tr>
                    <th class="text-left">id</th>
                    <th class="text-right">Apellidos y Nombres</th>
                    <th class="text-right">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(v, i) in escritura.otorgantes" :key="i">
                    <td>{{ i + 1 }}</td>
                    <td class="text-right">{{ v.nombre_completo }}</td>
                    <td class="text-right">
                      <q-btn size="sm" outline color="red" round icon="delete"
                        @click="removeOtorgante(i)"/>
                    </td>
                  </tr>
                </tbody>
              </q-markup-table>
            </q-tab-panel>

            <q-tab-panel name="Jurico">
              <div class="text-h6">Alarms</div>
              Lorem ipsum dolor sit amet consectetur adipisicing elit.
            </q-tab-panel>
          </q-tab-panels>
        </q-step>

        <q-step :name="3" title="Create an ad group" caption="Optional" icon="create_new_folder" :done="step > 2">
          <q-option-group inline
              v-model="tipoPersona"
              :options="[
                { label: 'Natural', value: 'Natural' },
                { label: 'Jurico', value: 'Jurico' },
              ]"/>
          <q-tab-panels v-model="tipoPersona">
            <q-tab-panel name="Natural">
              <div class="text-h6">Persona Natural</div>
              <!-- <q-select label="Apellidos y Nombres" dense>
                <template v-slot:after>
                  <q-btn color="primary" label="Añadir"></q-btn>
                </template>
              </q-select> -->
              <SelectInput label="Favorecidos" dense clearable
                  v-model="favorecido" :options="FavorecidoService" OptionLabel="nombre_completo" OptionValue="id"
                  :ValueMulti="['id','nombre_completo']"/>
              <q-btn
                label="Añadir"
                @click="addFavorecido"
                color="primary"
              ></q-btn>
              <q-btn label="Nuevo" color="positive"></q-btn>

              <q-markup-table flat class="q-mt-sm">
                <thead>
                  <tr>
                    <th class="text-left">#</th>
                    <th class="text-right">Apellidos y Nombres</th>
                    <th class="text-right">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(v, i) in escritura.favorecidos" :key="i">
                    <td>{{ i + 1 }}</td>
                    <td class="text-right">{{ v.nombre_completo }}</td>
                    <td class="text-right">
                      <q-btn size="sm" outline color="red" round icon="delete"
                        @click="removeFavorecido(i)" />
                    </td>
                  </tr>
                </tbody>
              </q-markup-table>
            </q-tab-panel>

            <q-tab-panel name="Jurico">
              <div class="text-h6">Alarms</div>
              Lorem ipsum dolor sit amet consectetur adipisicing elit.
            </q-tab-panel>
          </q-tab-panels>
        </q-step>
        <template v-slot:navigation>
          <q-stepper-navigation>
            <q-card-actions align="between">
              <q-btn v-if="step === 1"
                  label="Cerrar" v-close-popup flat color="primary" class="q-ml-sm" />
              <q-btn v-if="step > 1"
                  label="Anterior" flat color="primary" class="q-ml-sm"
                  @click="$refs.stepper.previous()" />
              <q-btn v-if="step < 3"
                  label="Continuar" color="primary"
                  @click="$refs.stepper.next()" />
              <q-btn v-if="step === 3"
                  label="Registrar" type="submit" color="positive" />
            </q-card-actions>
          </q-stepper-navigation>
        </template>
      </q-stepper>
    </q-form>
  </q-card>
</template>

<script setup>
import { onBeforeMount, ref } from "vue";
import SelectInput from "src/components/SelectInput.vue";
import InputTextSelect from "src/components/InputTextSelect.vue";
import SubSerieService from "src/services/SubSerieService";
import OtorganteService from "src/services/OtorganteService";
import FavorecidoService from "src/services/FavorecidoService";
import EscrituraService from "src/services/EscrituraService";
const props = defineProps({
  Libro: {type:Object,default:null},
  Editar: {type:Object,default:null},
});

const emits = defineEmits(["save"]);
const subSerie = ref(null);
const escritura = ref({
  libro_id: null,
  folio: null,
  cod_escritura: null,
  subserie_id: null,
  observacion: null,
  n_folios: 1,
  cod_folioInicial: null,
  cod_folioFinal: null,
  bien: null,
  fecha: null,
  otorgantes: [],
  favorecidos: [],
});

const otorgante = ref(null);
const favorecido = ref(null);

const step = ref(2);

const tipoPersona = ref("Natural");
onBeforeMount(()=>{
  console.log(props.Editar);
});

const addOtorgante = () => {
  escritura.value.otorgantes.push({ ...otorgante.value });
  otorgante.value = null;
};
const removeOtorgante = (i) => {
  if (escritura.value.otorgantes.length != 0) {
    escritura.value.otorgantes.splice(i, 1);
  }
};

const addFavorecido = () => {
  escritura.value.favorecidos.push({ ...favorecido.value });
  favorecido.value = null;
};
const removeFavorecido = (i) => {
  if (escritura.value.favorecidos.length != 0) {
    escritura.value.favorecidos.splice(i, 1);
  }
};
const onSubmit = async () => {
  console.log("enviando");
  try {
    escritura.value.libro_id = props.Libro.id;
    escritura.value.cod_folioInicial = 'F-'+escritura.value.folio;
    let patron = /\d+/;
    let resultado = escritura.value.folio.match(patron);
    let num = resultado ? parseInt(resultado[0], 10)+escritura.value.n_folios-1 : null;
    console.log(num);
    escritura.value.cod_folioFinal = num? 'F-'+num.toString():'F-'+escritura.value.folio;
    escritura.value.otorgantes = escritura.value.otorgantes.map((o) => {
      return o.id;
    });
    escritura.value.favorecidos = escritura.value.favorecidos.map((f) => {
      return f.id;
    });
    await EscrituraService.save(escritura.value);
    emits("save");
  } catch (error) {
    console.log(error);
    window.alert(error);
  }
};
</script>
<style lang="sass" scoped>
.my-card
  width: 700px
  max-width: 80vw
</style>
