<template>
  <q-btn icon="event" round color="primary">
        <q-popup-proxy v-if="verProxy" cover transition-show="scale" transition-hide="scale">
          <q-date v-model="modeldate" minimal
                default-year-month="1980/01"
                default-view="Years"
                @navigation="hola($event)">
            <div class="row items-center justify-end q-gutter-sm">
              <q-btn label="Cancelar" color="primary" flat v-close-popup />
            </div>
          </q-date>
        </q-popup-proxy>
      </q-btn>
      <!-- <q-date
        v-model="modeldate"
        minimal
        default-year-month="1986/01"
        default-view="Years"
        @navigation="hola($event)"
      /> -->
      {{ modeldate }} - {{ modelAnioMes }}
      <q-btn v-if="modelAnioMes" icon="event" round color="primary">
        <q-popup-proxy v-if="verProxy" cover transition-show="scale" transition-hide="scale">
          <q-date v-model="modeldate" minimal
                :default-year-month="modelAnioMes.year?modelAnioMes?.year+'/01':'1980/01'"
                default-view="Months"
                @navigation="hola($event)">
            <div class="row items-center justify-end q-gutter-sm">
              <q-btn label="Cancelar" color="primary" flat v-close-popup />
            </div>
          </q-date>
        </q-popup-proxy>
      </q-btn>
</template>

<script setup>
const modeldate = ref();
const modelAnioMes = ref();

const verProxy = ref(true);
function hola(event){
  verProxy.value=false;
  modelAnioMes.value = event;
  setTimeout(() => {
    verProxy.value=true;
  },1000);
}

</script>

<style lang="scss" scoped>

</style>
