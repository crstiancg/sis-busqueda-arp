<template>
  {{ modeldate }}
  <div>15 de mayo, {{ modelAño }} - {{ modelMes }}</div>
  <div>{{ defaulYearMonth }}</div>
  <q-input v-model="modelAño" label="Año" mask="####" dense class="" lazy-rules
    :rules="[val => (validaAnio(val)) || 'Ingrese una Año valido']">
    <template v-slot:append>
      <q-icon icon name="event" class="cursor-pointer">
        <q-popup-proxy v-if="verProxyAño" cover transition-show="scale" transition-hide="scale">
          <q-carousel v-model="modelslide" transition-prev="scale" transition-next="scale" animated arrows padding
            style="max-width: 20em; max-height: 15em;" class="shadow-1 rounded-borders">
            <q-carousel-slide v-for="slideanio, index in anios" :key="index" :name="index" class="row">
              <div v-for="anio, ind in slideanio" :key="ind" class="col-3 justify-center items-center row">
                <p :class="parseInt(modelAño) ===anio?'selectAnio':''" class="Anio"
                    @click="enviarAnio(anio,index)">{{ anio }}</p>
              </div>
            </q-carousel-slide>
          </q-carousel>
          <div><q-btn v-close-popup label="Cerrar" color="primary" flat /></div>
        </q-popup-proxy>
      </q-icon>
    </template>
  </q-input>
  <q-input v-model="modelMes" label="Mes" mask="aaaaaaaaa" dense class="" lazy-rules
    :rules="[val => (val !== '' && val !== null && inMes(val)) || 'Ingrese una Mes valido']">
    <template v-slot:append>
      <q-icon icon name="event" class="cursor-pointer">
        <q-popup-proxy v-if="verProxyMes" cover transition-show="scale" transition-hide="scale">
          <q-date v-model="modeldate" minimal default-view="Months" :default-year-month="defaulYearMonth"
            @navigation="eventSelectMes($event)">
            <div class="row items-center justify-end">
              <q-btn v-close-popup label="Cerrar" color="primary" flat />
            </div>
          </q-date>
        </q-popup-proxy>
      </q-icon>
    </template>
  </q-input>
  <q-input v-model="modelDia" label="Día" mask="##" dense class="" lazy-rules
    :rules="[val => (val >= 1 && val <= 31) || 'Ingrese un Día valido']">
    <template v-slot:append>
      <q-icon icon name="event" class="cursor-pointer">
        <q-popup-proxy cover transition-show="scale" transition-hide="scale">
          <q-date v-model="modeldate" minimal emit-immediately :default-year-month="defaulYearMonth"
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
import { computed, ref, watch } from 'vue';

const props = defineProps({
  modelValue: { default: null },
  RangoAños: { default: [1900, 2024] },
});

const modelAnioMes = ref({ year: null, month: null });

const modelAño = ref(null);
const modelMes = ref(null);
const modelDia = ref(null);
const modeldate = ref();
const modelslide = ref(0);

const listMes = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'setiembre', 'octubre', 'noviembre', 'diciembre'];
const anios = computed(() => {
  const aniosArray = [];
  for (let year = props.RangoAños[0]; year <= props.RangoAños[1]; year++) {
    aniosArray.push(year);
  }
  const rows = Math.ceil(aniosArray.length / 20);
  const matrix = new Array(rows);
  for (let i = 0; i < rows; i++) {
    matrix[i] = aniosArray.slice(i * 20, (i + 1) * 20);
  }
  return matrix;
});
let num_mes = ref(null);
const defaulYearMonth = computed(() => {
  if ( validaAnio(modelAño.value) && num_mes.value)
    return modelAño.value + '/' + (num_mes.value + '').padStart(2, '0') || '';
  else if (modelAño.value)
    return `${modelAño.value}/00`;
  return '2024/00';
});

const verProxyAño = ref(true);
const verProxyMes = ref(true);
function enviarAnio(anio, id_slide) {
  verProxyAño.value = false;
  modelAño.value = anio;
  setTimeout(() => {
    verProxyAño.value = true;
  }, 500);
}
function eventSelectMes(event) {
  verProxyMes.value = false;
  modelMes.value = listMes[event.month - 1];
  num_mes.value = event.month;
  setTimeout(() => {
    verProxyMes.value = true;
  }, 500);
}
function eventSelectDia(event) {
  modelAño.value = event.year;
  modelMes.value = listMes[event.month - 1];
  num_mes.value = event.month;
}
watch(() => modeldate.value, (newval,oldval) => {
  modelDia.value = newval.split('/')[2];
});
watch(()=>modelAño.value,(newval,oldval) => {
  modelslide.value = encontrarSlide(newval);
});
function inMes(val) {
  return listMes.includes(val);
}
function validaAnio(val) {
  return val !== '' && val !== null && val >= props.RangoAños[0] && val <= props.RangoAños[1];
}
function encontrarSlide(año){
  const canti = anios.value.length;
  let cantiSlide = 0;
  for (let i=0;i<canti;i++){
    cantiSlide = anios.value[i].length;
    if (anios.value[i][cantiSlide-1] >= año)
      return i;
  }
  return 0;
}
</script>

<style lang="sass" scoped>
.Anio 
  padding: 2px
  height: 25px
  text-align: center
  border-radius: 20%
  margin: 0px
  cursor: pointer
.Anio:hover
  background-color: $grey-9

.selectAnio 
  background-color: blue
  color: white
</style>
