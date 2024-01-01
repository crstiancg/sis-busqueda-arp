<template>
  {{ modeldate }} - {{ modelAnioMes }}
  <div>15 de mayo, 2023 {{ mostrar }}</div>
  <q-input label="Fecha de Escritura" mask="##/##/####" dense class="col-12 col-sm-6 col-md-3 q-pa-sm" lazy-rules
    :rules="[val => (val !== '' && val !== null) || 'Ingrese una Fecha']" @click="AbrirProxy()">
    <template v-slot:append>
      <q-icon icon name="event" class="cursor-pointer">
        <q-popup-proxy :model-value="mostrar" cover transition-show="scale" transition-hide="scale">
          <q-date v-model="modeldate" mask="DD/MM/YYYY" today-btn>
            <div class="row items-center justify-end">
              <q-btn v-close-popup label="Cerrar" color="primary" flat />
            </div>
          </q-date>
        </q-popup-proxy>
      </q-icon>
    </template>
  </q-input>
  <q-btn icon="event" round color="primary">
    <q-popup-proxy v-if="verProxy" cover transition-show="scale" transition-hide="scale">
      <q-date v-model="modeldate" minimal default-year-month="1980/01" default-view="Years" @navigation="hola($event)">
        <div class="row items-center justify-end q-gutter-sm">
          <q-btn label="Cancelar" color="primary" flat v-close-popup />
        </div>
      </q-date>
    </q-popup-proxy>
  </q-btn>
  <q-btn v-if="modelAnioMes" icon="event" round color="primary">
    <q-popup-proxy v-if="verProxy" cover transition-show="scale" transition-hide="scale">
      <q-date v-model="modeldate" minimal :default-year-month="modelAnioMes.year ? modelAnioMes?.year + '/01' : '1980/01'"
        default-view="Months" @navigation="hola($event)">
        <div class="row items-center justify-end q-gutter-sm">
          <q-btn label="Cancelar" color="primary" flat v-close-popup />
        </div>
      </q-date>
    </q-popup-proxy>
  </q-btn>
</template>

<script setup>
import { ref } from 'vue';

const modeldate = ref();
const modelAnioMes = ref();

const mostrar = ref(false);


const verProxy = ref(true);
function hola(event) {
  verProxy.value = false;
  modelAnioMes.value = event;
  setTimeout(() => {
    verProxy.value = true;
  }, 500);
}

function AbrirProxy() {
  mostrar.value = false;
}

</script>

<style lang="scss" scoped></style>
