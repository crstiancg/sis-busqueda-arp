<template>
  <div :class="customClass">
    <span>
      {{ props.displayText }}
      <button v-if="showButton" @click="showFullText">Ver más</button>
    </span>
    <div v-if="showTooltip" :class="{ 'tooltip': autoReduce }">{{ fullText }}</div>
  </div>

</template>

<script setup>
import { ref, computed, defineProps } from 'vue';

const props = defineProps({
  text:{type:String,default:'Un libro (del latín liber, libri) es una obra impresa, manuscrita o pintada en una serie de hojas de papel, pergamino, vitela u otro material, unidas por un lado (es decir, encuadernadas) y protegidas con tapas, también llamadas cubiertas. Un libro puede tratar sobre cualquier tema. Según la definición de la Unesco, un libro debe poseer veinticinco hojas mínimo (49 páginas), pues de veinticuatro hojas o menos sería un folleto; y de una hasta cuatro páginas se consideran hojas sueltas (en una o dos hojas)​ También se llama «libro» a una obra de gran extensión publicada en varias unidades independientes, llamadas tomos o volúmenes. Otras veces se llama «libro» a cada una de las partes de una obra, aunque físicamente se publiquen todas en un mismo volumen (ejemplo: Libros de la Biblia).'},
  charLimit:{type:Number,default:50},
  wordLimit:{type:Number,default:20},
  autoReduce:{type:Boolean,default:false},
}
);

const showTooltip = ref(false);

const displayText = computed(() => {
  if (props.autoReduce) {
    return props.text.slice(0, props.charLimit);
  } else {
    const words = props.text.split(' ').slice(0, props.wordLimit).join(' ');
    return words.length > props.charLimit ? words.slice(0, props.charLimit) : words;
  }
});

const fullText = computed(() => props.text);

const showButton = computed(() => props.autoReduce && props.text.length > props.charLimit);

const showFullText = () => {
  showTooltip.value = true;
};
</script>

<style scoped>
/* Aquí puedes agregar estilos según tus necesidades */
.tooltip {
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}
</style>
