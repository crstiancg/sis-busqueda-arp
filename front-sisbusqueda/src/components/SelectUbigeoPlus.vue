<!-- ejemplo de uso -->
<!--
<div class="row">
  <SelectUbigeoPlus v-model="codigo" cod_departamento="21" cod_provincia="01"
    :show_departamento="true" :show_provincia="true" Class="q-pa-sm col-12 col-md-4 col-sm-6"/>
</div>
-->
<template>
  <q-select v-if="cod_departamento === null || show_departamento"
      borderless dense debounce="300" outlined use-input hide-selected fill-input input-debounce="0" clearable
      v-model="model_depa" label="Departamento" :class="Class"
      :options="optionsDepartamentos" option-label="nombre" option-value="cod_dep"
      @filter="filterDepartamentos" :loading="loading" :disable="loading || props.cod_departamento !== null">
      <template v-slot:no-option>
          <q-item>
              <q-item-section class="text-grey">
                  No se han encontrado resultados
              </q-item-section>
          </q-item>
      </template>
  </q-select>
  <q-select v-if="cod_provincia === null || show_provincia"
      borderless dense debounce="300" outlined use-input hide-selected fill-input input-debounce="0" clearable
      v-model="model_prov" label="Provincia" :class="Class"
      :options="optionsProvincias"  option-label="nombre" option-value="cod_prov"
      @filter="filterProvincias" :loading="loading" :disable="loading || props.cod_provincia !== null">
      <template v-slot:no-option>
          <q-item>
              <q-item-section class="text-grey">
                  No se han encontrado resultados
              </q-item-section>
          </q-item>
      </template>
  </q-select>
  <q-select borderless dense debounce="300" outlined use-input hide-selected fill-input input-debounce="0" clearable
      v-model="model_dist" label="Distrito" :class="Class"
      :options="optionsDistritos"  option-label="nombre" option-value="cod_dist"
      @filter="filterDistritos" :loading="loading" :disable="loading">
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
import UbigeosService from 'src/services/UbigeoService';
import { onBeforeMount, onMounted, ref, watch } from 'vue';
const emit = defineEmits(['update:modelValue']);
const props = defineProps({
    modelValue: {default:null},
    Class: {default:''},
    cod_departamento: {type:String,default:null},
    cod_provincia: {type:String,default:null},
    show_departamento: {default:false},
    show_provincia: {default:false},
});
const loading = ref(false);

const model_depa = ref();
const model_prov = ref();
const model_dist = ref();

let allDepartamentos = [];
let allProvincias = [];
let allDistritos = [];

let stringDepartamentos = [];
let stringProvincias = [];
let stringDistritos = [];

let banderaModelValue = props.modelValue !== null;
let banderaCodDeparta = props.cod_departamento !== null;

function emitir(_model){
  emit('update:modelValue', _model? typeof _model === 'object'? _model.cod_dep+_model.cod_prov+_model.cod_dist:_model:null);
}
function separarCadena(cadena) {
  return cadena.match(/.{1,2}/g) || [];
}
onMounted(async () => {
  loading.value = true;
  [allDepartamentos, allProvincias, allDistritos] = await UbigeosService.getAllUbigeo();
  stringDepartamentos = allDepartamentos;
  await CargarModel();
  loading.value = false;
});

/* *** metodos Get ************* */
async function getProvincias(cod_dep) {
  stringProvincias = allProvincias.filter(v => v.cod_dep === cod_dep);
}
async function getDistritos(cod_dep, cod_prov) {
  stringDistritos = allDistritos.filter(v => v.cod_dep === cod_dep && v.cod_prov === cod_prov);
}

/******* revisar cambios de valor ************************ */
watch(model_depa,(newval,oldval)=>{
  if(banderaModelValue) return;
  model_prov.value = null;
  if(newval) getProvincias(newval.cod_dep);
});
watch(model_prov,(newval,oldval)=>{
  if(banderaCodDeparta && !banderaModelValue) banderaModelValue = false;
  if(banderaModelValue)  return;
  model_dist.value = null;
  if(newval) getDistritos(newval.cod_dep,newval.cod_prov);
});
watch(model_dist,(newval,oldval)=>{
  banderaModelValue = false;
  emitir(newval);
});

watch(()=>props.modelValue,async (newval,oldval)=>{
  console.log(newval);
  await CargarModel();
});

async function CargarModel(){
  banderaModelValue = props.modelValue !== null;
  let codigo_array = banderaModelValue ? separarCadena(props.modelValue):[null,null,null];
  if (props.cod_departamento) codigo_array[0] = props.cod_departamento;
  if(props.cod_departamento && props.cod_provincia) codigo_array[1] = props.cod_provincia;
  if(codigo_array[0]){
    model_depa.value = stringDepartamentos.find(v => v.cod_dep === codigo_array[0]);
    await getProvincias(codigo_array[0]);
  }
  if(codigo_array[0] && codigo_array[1]){
    model_prov.value = stringProvincias.find(v => v.cod_prov === codigo_array[1]);
    await getDistritos(codigo_array[0], codigo_array[1]);
  }
  if (banderaModelValue) model_dist.value = stringDistritos.find(v => v.cod_dist === codigo_array[2]);
}

/*******  para los filtros *********************************************************** */
const optionsDepartamentos = ref(stringDepartamentos);
const optionsProvincias = ref(stringProvincias);
const optionsDistritos = ref(stringDistritos);
function filterDepartamentos(val, update) {
  update(() => {
    if (val === '') optionsDepartamentos.value = stringDepartamentos;
    else {
      optionsDepartamentos.value = stringDepartamentos.filter(v => {
        return v.nombre.toString().toLowerCase().includes(val.toLowerCase());
      });
    }
  })
};
function filterProvincias(val, update) {
  update(() => {
    if (val === '') optionsProvincias.value = stringProvincias;
    else {
      optionsProvincias.value = stringProvincias.filter(v => {
        return v.nombre.toString().toLowerCase().includes(val.toLowerCase());
      });
    }
  })
};
function filterDistritos(val, update) {
  update(() => {
    if (val === '') optionsDistritos.value = stringDistritos;
    else {
      optionsDistritos.value = stringDistritos.filter(v => {
        return v.nombre.toString().toLowerCase().includes(val.toLowerCase());
      });
    }
  })
};

// function setData(){
//     model_depa.value = props.cod_departamento.departamento;
//     model_prov.value = props.cod_prov.provincia;
// }

// defineExpose({
//   setData
// });
</script>
