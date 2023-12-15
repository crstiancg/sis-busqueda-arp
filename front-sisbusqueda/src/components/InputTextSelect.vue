
<script setup>
import { computed, onMounted, ref, watch } from 'vue';
const emit = defineEmits(['update:modelValue']);
const props = defineProps({
  label:{default:'Select'},
  modelValue: {default:null},
  options: {default:null},
  OptionLabel:{default:'nombre'},
  GenerateList :{default:null},
});
const model = ref('');
const loading = ref(false);
const op_label = ref('');
let array = [];

const filterOptions = computed(()=> {
  if (model.value) {
    return array.filter(item => {
      const compareText = (typeof item === 'object' ? item[op_label.value] : item) || '';
      return compareText.toString().toLowerCase().includes(model.value.toLowerCase());
    });
  } else {
    return [];
  }
});
const show = ref(false);
watch(model,(newtext,oldtext)=>{
  if (newtext && newtext.length > 2 && !array.some(item => {
    const compareText = (typeof item === 'object' ? item[op_label.value] : item) || '';
    return compareText === newtext;
  })) {
    show.value = true;
  } else {
    show.value = false;
  }
});

onMounted(async () => {
  loading.value=true;
  op_label.value = props.GenerateList ? props.GenerateList.column : props.OptionLabel;
  array = props.options.hasOwnProperty('getData') ?
        props.GenerateList?
          (await props.options.getData(props.GenerateList))
          :(await props.options.getData({params: {rowsPerPage: 0,order_by:op_label.value}})).data
      :props.options;
  loading.value=false;
});
function emitir(_model){
  emit('update:modelValue', _model? typeof _model === 'object'?_model[op_label.value]:_model :_model);
  model.value = _model && typeof _model === 'object'?_model[op_label.value]:_model;
}
</script>
<template>
  <div style="position: relative;">
    <q-input outlined borderless dense clearable :label="label" :loading="loading"
      v-model="model"
      @update:model-value="emitir(model)">
    </q-input>
    <div v-if="show" class="posicion" @focusout="show=false">
      <div v-for="val, index in filterOptions" :key="index" @click="show=false; emitir(val); " :class="$q.dark.isActive ? 'select-dark':'select'" class="cursor-pointer q-pa-sm">
        <span>
          {{ typeof val === 'object'? val[op_label] :val}}
        </span>
      </div>
    </div>
  </div>
</template>

<style lang="sass" scoped>
.select
  background-color: $grey-3

.select-dark
  background-color: $grey-9
.select:hover
  background-color: $grey-5

.select-dark:hover
  background-color: $grey-8

.posicion
  position: absolute
  z-index: 100
  max-height: 100px
  overflow-y: auto
  width: 100%

</style>
