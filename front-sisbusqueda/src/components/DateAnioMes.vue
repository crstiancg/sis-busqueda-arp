<template>
  {{ modeldate }} - {{ modelAnioMes }}
  <div>15 de mayo, {{ modelAño }} - {{ modelMes }}</div>
  <q-input v-model="modelAño" label="Año" mask="####" dense class="" lazy-rules
    :rules="[val => (val !== '' && val !== null && val > 1900 && val < 2024) || 'Ingrese una Año valido']">
    <template v-slot:append>
      <q-icon icon name="event" class="cursor-pointer">
        <q-popup-proxy v-if="verProxyAño" cover transition-show="scale" transition-hide="scale">
          <q-date v-model="modeldate" minimal default-view="Years" @navigation="eventSelectAño($event)"
            :options="RangoFechas">
            <div class="row items-center justify-end">
              <q-btn v-close-popup label="Cerrar" color="primary" flat />
            </div>
          </q-date>
        </q-popup-proxy>
      </q-icon>
    </template>
  </q-input>
  <q-input v-model="modelMes" label="Mes" mask="aaaaaaaaa" dense class="" lazy-rules
    :rules="[val => (val !== '' && val !== null && inMes(val)) || 'Ingrese una Mes valido']">
    <template v-slot:append>
      <q-icon icon name="event" class="cursor-pointer">
        <q-popup-proxy v-if="verProxyMes" cover transition-show="scale" transition-hide="scale">
          <q-date v-model="modeldate" minimal default-view="Months" @navigation="eventSelectMes($event)"
            :options="RangoFechas">
            <div class="row items-center justify-end">
              <q-btn v-close-popup label="Cerrar" color="primary" flat />
            </div>
          </q-date>
        </q-popup-proxy>
      </q-icon>
    </template>
  </q-input>
  <q-input v-model="modelDia" label="Día" mask="##" dense class="" lazy-rules
    :rules="[val => (val !== '' && val !== null && val >= 1 && val <= 31) || 'Ingrese un Día valido']">
    <template v-slot:append>
      <q-icon icon name="event" class="cursor-pointer">
        <q-popup-proxy cover transition-show="scale" transition-hide="scale">
          <q-date v-model="modeldate" minimal emit-immediately
            :default-year-month="modelAnioMes?.year && modelAnioMes?.month ? modelAnioMes.year + '/' + modelAnioMes.month : '1980/01'"
            @navigation="eventSelectDia($event)">
            <div class="row items-center justify-end">
              <q-btn v-close-popup label="Cerrar" color="primary" flat />
            </div>
          </q-date>
        </q-popup-proxy>
      </q-icon>
    </template>
  </q-input>
</template>

<script setup>
import { ref } from 'vue';

const modelAño = ref();
const modelMes = ref();
const modelDia = ref();
const modeldate = ref();
const listMes = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'setiembre', 'octubre', 'noviembre', 'diciembre'];
const modelAnioMes = ref({ year: null, month: null });


const verProxyAño = ref(true);
const verProxyMes = ref(true);

function eventSelectAño(event) {
  verProxyAño.value = false;
  modelAnioMes.value.year = event.year;
  modelAño.value = modelAnioMes.value.year;
  setTimeout(() => {
    verProxyAño.value = true;
  }, 500);
}
function eventSelectMes(event) {
  verProxyMes.value = false;
  modelAnioMes.value.month = event.month;
  modelMes.value = listMes[modelAnioMes.value.month - 1];
  setTimeout(() => {
    verProxyMes.value = true;
  }, 500);
}
function eventSelectDia(event) {
  modelAnioMes.value.year = event.year;
  modelAnioMes.value.month = event.month;
  modelAño.value = modelAnioMes.value.year;
  modelMes.value = listMes[modelAnioMes.value.month - 1];
  console.log(event);
}
function RangoFechas(date) {
  return date >= '2019/01/01' && date <= '2019/12/31';
}

function inMes(val) {
  return listMes.includes(val);
}
</script>

<style lang="scss" scoped></style>
