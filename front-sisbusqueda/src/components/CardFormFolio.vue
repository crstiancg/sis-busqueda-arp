<template>
  <q-card class="my-card">
    <q-card-section class="bg-primary text-white q-pa-none">
      <q-toolbar>
        <q-toolbar-title class="text-h5 text-center">{{ proyecto.nombre }}</q-toolbar-title>
        <q-btn flat v-close-popup round dense icon="close" />
      </q-toolbar>
    </q-card-section>
    <q-separator />

    <q-form>
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
          <q-input label="Folio" dense> </q-input>
          <q-input label="N° de Escritura" dense> </q-input>
          <q-select label="Sub-Series" dense></q-select>
          <q-input label="Observaciones de Escritura" autogrow dense></q-input>
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
              <q-select label="Apellidos y Nombres" dense>
                <template v-slot:after>
                  <q-btn color="primary" label="Añadir"></q-btn>
                </template>
              </q-select>

              <q-markup-table flat class="q-mt-sm">
                <thead>
                  <tr>
                    <th class="text-left">id</th>
                    <th class="text-right">Apellidos y Nombres</th>
                    <th class="text-right">Acciones</th>
                  </tr>
                </thead>
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
              <q-select label="Apellidos y Nombres" dense>
                <template v-slot:after>
                  <q-btn color="primary" label="Añadir"></q-btn>
                </template>
              </q-select>

              <q-markup-table flat class="q-mt-sm">
                <thead>
                  <tr>
                    <th class="text-left">id</th>
                    <th class="text-right">Apellidos y Nombres</th>
                    <th class="text-right">Acciones</th>
                  </tr>
                </thead>
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
            <q-btn
              @click="$refs.stepper.next()"
              color="primary"
              :label="step === 4 ? 'Finish' : 'Continue'"
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
    <q-card-actions align="right">
      <q-btn>Editar</q-btn>
      <q-btn color="primary" icon="search">Consultar</q-btn>
    </q-card-actions>
  </q-card>
</template>

<script setup>
import { ref } from "vue";

const props = defineProps({
  proyecto: Object,
});


const step = ref(1);

const tipoPersona = ref("Natural");
</script>
<style lang="sass" scoped>
.my-card
  width: 700px
  max-width: 60vw
</style>
