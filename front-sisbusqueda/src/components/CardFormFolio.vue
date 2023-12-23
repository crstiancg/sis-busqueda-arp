<template>
  <q-card class="my-card">
    <q-card-section class="bg-primary text-white q-pa-none">
      <q-toolbar>
        <q-toolbar-title class="text-h5 text-center">{{
          proyecto.nombre
        }}</q-toolbar-title>
        <q-btn flat v-close-popup round dense icon="close" />
      </q-toolbar>
    </q-card-section>
    <q-separator />

    <q-form @submit="onSubmit">
      <q-stepper
        v-model="step"
        ref="stepper"
        color="primary"
        flat
        animated
        header-nav
      >
        <q-step
          :name="1"
          title="Select campaign settings"
          icon="settings"
          :done="step > 1"
        >
          <q-input v-model="escritura.folio" label="Folio" dense> </q-input>
          <q-input
            v-model="escritura.cod_escritura"
            label="N° de Escritura"
            dense
          >
          </q-input>
          <!-- <q-select label="Sub-Series" dense></q-select> -->
          <SelectSubSerie
            v-model:sub-serie="subSerie"
            @update:sub-serie="(e) => (escritura.subserie_id = e.id)"
          ></SelectSubSerie>
          <q-input
            v-model="escritura.observacion"
            label="Observaciones de Escritura"
            autogrow
            dense
          ></q-input>
          <q-input
            v-model="escritura.n_folios"
            label="Cantidad de folios"
            type="number"
            dense
          ></q-input>
          <q-input
            v-model="escritura.bien"
            label="Nombre de bien"
            dense
          ></q-input>
          <q-input
            v-model="escritura.fecha"
            label="Fecha de Escritura"
            type="date"
          ></q-input>
        </q-step>
        <q-step
          :name="2"
          title="Create an ad group"
          caption="Optional"
          icon="create_new_folder"
          :done="step > 2"
        >
          <q-option-group
            v-model="tipoPersona"
            inline
            :options="[
              { label: 'Natural', value: 'Natural' },
              { label: 'Jurico', value: 'Jurico' },
            ]"
          />
          <q-tab-panels v-model="tipoPersona">
            <q-tab-panel name="Natural">
              <div class="text-h6">Persona Natural</div>
              <!-- <q-select label="Apellidos y Nombres" dense>
                <template v-slot:after>
                  <q-btn color="primary" label="Añadir"></q-btn>
                </template>
              </q-select> -->
              <SelectOtorgante v-model:otorgante="otorgante"></SelectOtorgante>
              <q-btn
                label="Añadir"
                @click="addOtorgante"
                color="primary"
              ></q-btn>
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
                      <q-btn
                        size="sm"
                        outline
                        color="red"
                        round
                        @click="removeOtorgante(i)"
                        icon="delete"
                      />
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

        <q-step
          :name="3"
          title="Create an ad group"
          caption="Optional"
          icon="create_new_folder"
          :done="step > 2"
        >
          <q-option-group
            v-model="tipoPersona"
            inline
            :options="[
              { label: 'Natural', value: 'Natural' },
              { label: 'Jurico', value: 'Jurico' },
            ]"
          />
          <q-tab-panels v-model="tipoPersona">
            <q-tab-panel name="Natural">
              <div class="text-h6">Persona Natural</div>
              <!-- <q-select label="Apellidos y Nombres" dense>
                <template v-slot:after>
                  <q-btn color="primary" label="Añadir"></q-btn>
                </template>
              </q-select> -->
              <SelectFavorecido
                v-model:favorecido="favorecido"
              ></SelectFavorecido>
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
                      <q-btn
                        size="sm"
                        outline
                        color="red"
                        round
                        @click="removeFavorecido(i)"
                        icon="delete"
                      />
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
              <q-btn
                v-if="step === 1"
                flat
                color="primary"
                v-close-popup
                label="Cerrar"
                class="q-ml-sm"
              />
              <q-btn
                v-if="step > 1"
                flat
                color="primary"
                @click="$refs.stepper.previous()"
                label="Anterior"
                class="q-ml-sm"
              />
              <q-btn
                v-if="step < 3"
                @click="$refs.stepper.next()"
                color="primary"
                label="Continuar"
              />
              <q-btn
                v-if="step === 3"
                type="submit"
                color="positive"
                label="Registrar"
              />
            </q-card-actions>
          </q-stepper-navigation>
        </template>
      </q-stepper>
    </q-form>
    <!-- <q-card-actions align="right">
      <q-btn>Editar</q-btn>
      <q-btn color="primary" icon="search">Consultar</q-btn>
    </q-card-actions> -->
  </q-card>
</template>

<script setup>
import { ref } from "vue";
import SelectSubSerie from "components/SelectSubSerie.vue";
import SelectOtorgante from "components/SelectOtorgante.vue";
import SelectFavorecido from "components/SelectFavorecido.vue";
import EscrituraService from "src/services/arp_v1/EscrituraService";
const props = defineProps({
  proyecto: Object,
});

const emits = defineEmits(["save"]);
const subSerie = ref(null);
const escritura = ref({
  libro_id: null,
  folio: null,
  cod_escritura: null,
  subserie_id: null,
  observacion: null,
  n_folios: 0,
  cod_folioInicial: null,
  cod_folioFinal: null,
  bien: null,
  fecha: null,
  otorgantes: [],
  favorecidos: [],
});

const otorgante = ref(null);
const favorecido = ref(null);

const step = ref(1);

const tipoPersona = ref("Natural");

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
    escritura.value.libro_id = props.proyecto.id;
    escritura.value.cod_folioInicial = escritura.value.folio;
    escritura.value.cod_folioFinal = escritura.value.folio;
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
