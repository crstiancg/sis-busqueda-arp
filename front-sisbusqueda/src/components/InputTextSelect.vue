<template>
  <div style="position: relative;" ref="recomendacion">
    <q-input outlined borderless dense clearable :label="label" :loading="loading"
      v-model="model" :class="Class"
      @update:model-value="emitir(model)">
    </q-input>
    <div v-if="show" class="posicion" @focusout="show=false">
      <div v-for="val, index in filterOptions" :key="index" @click="show=false; emitir(val); " :class="$q.dark.isActive ? 'select-dark':'select'" class="cursor-pointer q-px-md q-py-sm">
        <span>
          {{ typeof val === 'object'? val[op_label] :val}}
        </span>
      </div>
    </div>
  </div>
</template>
<script setup>
import { computed, onMounted, ref, watch } from 'vue';
const emit = defineEmits(['update:modelValue']);
const props = defineProps({
  label:{default:'Select recomendaciones'},
  modelValue: {default:null},
  options: {default:null},
  OptionLabel:{default:'nombre'},
  GenerateList :{default:null},
  Class :{default:''},
});
let array = [];
const op_label = ref('');
const model = ref('');

const show = ref(false);
const loading = ref(false);

const recomendacion = ref(null);

onMounted(async () => {
  loading.value=true;
  op_label.value = props.GenerateList ? props.GenerateList.column : props.OptionLabel;
  array = await ExtraerDatos(props.options);
  loading.value=false;
  document.addEventListener('click', ClickFueraDelRef);
});
function emitir(_model){
  emit('update:modelValue', _model? typeof _model === 'object'?_model[op_label.value]:_model :_model);
  model.value = _model && typeof _model === 'object'?_model[op_label.value]:_model;
}
async function ExtraerDatos(options){
  if(props.options.hasOwnProperty('getData')){
    if(props.GenerateList) return await props.options.getData(props.GenerateList);
    const data = (await props.options.getData({params: {rowsPerPage: 0,order_by:op_label.value}}))
    if(data.hasOwnProperty('data')){
      return data.data;
    }else
      return data;
  }else{
    return options
  }
}
watchEffect(async()=>{
  let newVal = props.options;
  loading.value=true;
  array = await ExtraerDatos(newVal);
  loading.value=false;
});
watch(model,(newtext,oldtext)=>{
  if (newtext && newtext.length > 0 && !array.some(item => {
          const compareText = (typeof item === 'object' ? item[op_label.value] : item) || '';
          return compareText === newtext;
        })
  ){
    show.value = true;
  } else {
    show.value = false;
  }
});
const filterOptions = computed(()=> {
  if (model.value) {
    return array.filter(item => {
      const compareText = (typeof item === 'object' ? item[op_label.value] : item) || '';
      return compareText.toString().toLowerCase().includes(model.value.toLowerCase());
    });
  } else return [];
});
const ClickFueraDelRef = (event) => {
  if (recomendacion.value && !recomendacion.value.contains(event.target)) {
    if (show.value) {
      show.value = false;
    }
  }
};
</script>
<style lang="sass" scoped>
.select
  background-color: $grey-3

.select-dark
  background-color: $grey-10
.select:hover
  background-color: $grey-5

.select-dark:hover
  background-color: $grey-8

.posicion
  position: absolute
  z-index: 100
  max-height: 200px
  overflow-y: auto
  width: 100%

</style>
