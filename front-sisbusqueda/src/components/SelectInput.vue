<template>
  <q-select outlined use-input borderless dense fill-input hide-selected
      debounce="200" clearable input-debounce="0"
      :label="label"
      v-model="model"
      @update:model-value="emitir(model)"
      @filter="filter"
      :options="filterOptions"
      :option-value="op_value"
      :option-label="op_label"
      :loading="loading"
      :disable="loading"
      >
      <template v-slot:prepend>
        <slot name="prepend"></slot>
      </template>
      <template v-slot:append >
        <slot name="append"></slot>
      </template>
      <template v-if="active_before" v-slot:before >
        <slot name="before"></slot>
      </template>
      <!-- <template v-slot:after >
        <slot name="after"></slot>
      </template>
      <template v-slot:error >
        <slot name="error"></slot>
      </template>
      <template v-slot:hint >
        <slot name="hint"></slot>
      </template> -->
      <!-- <template v-slot:counter >
        <slot name="counter"></slot>
      </template>
      <template v-slot:loading >
        <slot name="loading"></slot>
      </template>
      <template v-slot:before-options >
        <slot name="before-options"></slot>
      </template>
      <template v-slot:after-options >
        <slot name="after-options"></slot>
      </template> -->
      <template v-slot:no-option>
          <q-item>
              <q-item-section class="text-grey">
                  No se han encontrado resultados
              </q-item-section>
          </q-item>
      </template>
  </q-select>
</template>

<script setup>
import { ref, onMounted } from 'vue';
//Emits
const emit = defineEmits(['update:modelValue']);
//Props
const props = defineProps({
  label:{default:'Select'},
  modelValue: {default:null},
  options: {default:null},
  OptionValue:{default:'id'},
  OptionLabel:{default:'nombre'},
  active_before:{default:false},
  GenerateList :{default:null},
  LimpEspaRepe :{default:false},

});
let stringOptions = null;
const model = ref('');
const loading = ref(false);
const op_label = ref('');
const op_value = ref('');

function limpiarEspaciosRepetidos(array,val) {
  // Limpiar espacios en cada notario
  array.forEach((item) => {
    item[val] = item[val].replace(/\s+/g, ' ').trim();
  });
  // Eliminar elementos duplicados
  const array_ = array.filter(
    (item, index, self) =>
      index ===
      self.findIndex((t) => t[val] === item[val])
  );
  return array_;
}
function emitir(_model){
  if(typeof props.OptionValue === 'object' && _model && typeof _model === 'object'){
    let op_array_value = {};
    props.OptionValue.forEach(element => {
      if (_model?.[element])
        op_array_value[element] = _model[element];
    });
    emit('update:modelValue', op_array_value);
  }else
    emit('update:modelValue', _model && typeof _model === 'object' ? _model[op_value.value]:_model);
}
onMounted(async () => {
  loading.value=true;
  op_label.value = typeof props.OptionLabel === 'object'? props.OptionLabel[0]: props.GenerateList ? props.GenerateList.column : props.OptionLabel;
  op_value.value = typeof props.OptionValue === 'object'? props.OptionValue[0]: props.GenerateList ? props.GenerateList.column : props.OptionValue;

  stringOptions = props.options.hasOwnProperty('getData') ?
        props.GenerateList?
          (await props.options.getData(props.GenerateList))
          :(await props.options.getData({params: {rowsPerPage: 0,order_by:op_label.value}})).data
      :props.options;

  if (props.LimpEspaRepe)
    stringOptions = limpiarEspaciosRepetidos(stringOptions,op_value.value);

  if (props.modelValue) {
    if (typeof stringOptions[0] === 'object' && typeof props.modelValue !== 'object')
      model.value = stringOptions.find(v => v[op_value.value] === props.modelValue);
    else
      model.value = props.modelValue;
    emitir(model.value);
  }
  loading.value=false;
});
const filterOptions = ref(stringOptions);
function filter(val, update) {
  update(() => {
      if (val === '') {
        filterOptions.value = stringOptions
      } else {
          const needle = val.toLowerCase();
          filterOptions.value = stringOptions.filter(v => {
              if(typeof v === 'object'){
                return v[op_label.value].toString().toLowerCase().includes(needle);
              }else
                return v.toString().toLowerCase().includes(needle)
            }
          )
      }
    })
}
</script>
