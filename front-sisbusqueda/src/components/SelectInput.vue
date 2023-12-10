<template>
  <q-select outlined use-input borderless dense fill-input hide-selected
      debounce="200"
      clearable
      input-debounce="0"
      :label="label"
      v-model="model"
      @update:model-value="emitir(model)"
      @filter="filter"
      :options="filterOptions"
      :option-value="optionValue"
      :option-label="OptionLabel"
      :loading="loading"
      :disable="loading"
      >
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
  optionValue:{default:'id'},
  OptionLabel:{default:'nombre'},
  PropidadData :{default:true}
});
let stringOptions = null;
const model = ref('');
const loading = ref(false);

function emitir(_model){
  emit('update:modelValue', _model && typeof _model === 'object'?_model[props.optionValue]:_model)
}
onMounted(async () => {
  loading.value=true;
  stringOptions = props.options.hasOwnProperty('getData') ? props.PropidadData? (await props.options.getData({params: {rowsPerPage: 0,order_by:props.OptionLabel}})).data: (await props.options.getData({params: {rowsPerPage: 0,order_by:props.OptionLabel}})) :props.options;
  if (props.modelValue) {
    if (typeof stringOptions[0] === 'object' && typeof props.modelValue !== 'object')
      model.value = stringOptions.find(v => v[props.optionValue] === props.modelValue);
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
                return v[props.OptionLabel].toString().toLowerCase().includes(needle);
              }else
                return v.toString().toLowerCase().includes(needle)
            }
          )
      }
    })
}
</script>
