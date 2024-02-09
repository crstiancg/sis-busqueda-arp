<template>
  <q-page>
    <div class="q-pa-md q-gutter-sm absolute-position">
      <q-breadcrumbs>
        <q-breadcrumbs-el icon="home" />
        <q-breadcrumbs-el label="Libros" icon="book" />
      </q-breadcrumbs>
    </div>
    <q-separator />
    <div class="row q-ma-md">
      <q-btn-toggle v-model="opcionVista" spread no-caps toggle-color="primary" color="white" text-color="grey" class="col-12 col-md-6 col-lg-4"
              :options="[ {label: 'Mis Registros', value: 1}, {label: 'Todos los Registros', value: 2}]" />
    </div>
    <div class="row q-ma-md">
      <q-btn v-if="opcionVista===1" :label="$q.screen.lt.sm ? '' : 'Agregar'" color="primary" icon-right="add" @click="AgregarLibro"
        :disable="tipoAccion.includes('editar') || cantidad_registrando>=2" :loading="cargar" />
      <q-space />
      <SelectInput label="Notario" dense class="q-mx-sm" clearable v-model="idNotario" :options="allNotarios"
        OptionLabel="nombre_completo" OptionValue="id" />
      <q-input placeholder="Buscar" dense class="q-mx-sm" v-model="busqueda" clearable>
        <template v-slot:append>
          <q-icon name="search" />
        </template>
      </q-input>
    </div>
    <div class="row justify-center full-height relative-position">
      <div class="col-12 q-pa-sm column items-center">
        <div>Paginas:</div>
        <q-pagination color="primary" boundary-numbers direction-links v-model="paginacion.pagina"
          :max="parseInt(paginacion.ultimo_pag)"
          :max-pages="paginacion.ultimo_pag ? paginacion.ultimo_pag > 5 ? 5 : parseInt(paginacion.ultimo_pag) : 1" />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 q-pa-sm" v-for="(v, k) in libros" :key="k">
        <q-card class="my-card">
          <q-card-section class="text-white"
            :class="tipoAccion[k] === 'agregar' ? 'bg-positive' : tipoAccion[k] === 'editar' ? 'bg-secondary' : 'bg-primary'">
            <div v-if="tipoAccion[k] === 'list'" class="text-h5 text-center" style="height: 1.5em;">{{ v.protocolo }}</div>
            <q-input v-else label="N° Protocolo" dense clearable class="q-my-sm" v-model="v.protocolo"
              :readonly="tipoAccion[k] === 'list'" mask="P-######" reverse-fill-mask fill-mask="0"
              :error-message="errores[k] ? errores[k].protocolo ? errores[k].protocolo[0] : '' : ''"
              :error="errores[k] && errores[k].protocolo != null" />
          </q-card-section>
          <q-card-section>
              <SelectInput label="Notario" dense class="q-my-sm" v-model="v.notario_id" :options="allNotarios"
                OptionLabel="nombre_completo" OptionValue="id" :readonly="tipoAccion[k] === 'list'"
                :error-message="errores[k] ? errores[k].notario_id ? errores[k].notario_id[0] : '' : ''"
                :error="errores[k] && errores[k].notario_id != null" />
                <div class="row justify-end">
                  <q-toggle v-if="tipoAccion[k] === 'editar'" v-model="v.estado" :false-value="0" :true-value="1"  checked-icon="edit"/>
                  <q-badge outline :color="v?.estado===0?'primary':'secondary'" class="text-subtitle2">{{ v?.estado===0?'Finalizado':'Registrando' }}</q-badge>
                </div>
              <div class="row justify-end">ID: {{ v.id }} | F.A.: {{ convertDate(v.updated_at, 'dd/MMM/yyyy h:mm a') }}</div>
          </q-card-section>
          <q-separator />
          <q-card-actions align="right">
            <template v-if="ID_user_login && ID_user_login===v.user_id && (v.estado===1 || cantidad_registrando<2 || tipoAccion[k]==='editar')">
              <q-btn v-if="tipoAccion[k] === 'list'" @click="EditarLibro(v.id, k)"
                :disable="tipoAccion.includes('agregar')">Editar</q-btn>
              <q-btn v-else @click="GuardarLibro(v, k)">Guardar</q-btn>
              <q-btn v-if="tipoAccion[k] !== 'list'" color="negative" @click="CancelarAccion(k)">Cancelar</q-btn>
            </template>
            <q-btn v-if="tipoAccion[k] === 'list'" color="primary" icon="search"
              @click="showProyecto(v)">Ver Escrituras</q-btn>
          </q-card-actions>
        </q-card>
      </div>
      <q-inner-loading v-if="cargar" :showing="cargar">
        <q-spinner-facebook size="250px" color="light-blue" />
      </q-inner-loading>
      <div v-if="!cargar && libros?.[0]" class="col-12 q-pa-lg column items-center">
        <div>Paginas:</div>
        <q-pagination color="primary" boundary-numbers direction-links v-model="paginacion.pagina"
          :max="parseInt(paginacion.ultimo_pag)"
          :max-pages="paginacion.ultimo_pag ? paginacion.ultimo_pag > 5 ? 5 : parseInt(paginacion.ultimo_pag) : 1" />
      </div>
    </div>
    <q-separator />
  </q-page>
</template>
<script setup>
import { onMounted, ref, watch } from "vue";
import { useRouter } from "vue-router";
import LibroService from "src/services/LibroService";
import SelectInput from "src/components/SelectInput.vue";
import NotarioService from "src/services/NotarioService";
import { convertDate } from "src/utils/ConvertDate";
import { useQuasar } from "quasar";
import { useUserStore } from "src/stores/user-store";

const $q = useQuasar();

const router = useRouter();
let form = {};
const paginacion = ref({
  primer_pag: null,
  pagina: 1,
  ultimo_pag: null,
  total_datos: null,
  num_filas: 12,
  ordenado_por: 'updated_at',
});
const libros = ref([]);
const allNotarios = ref([]);
const opcionVista = ref(1);

const userStore = useUserStore();
const ID_user_login = ref(null);
const cantidad_registrando = ref(null);

const busqueda = ref(null);
const idNotario = ref(null);

const errores = ref([]);
const tipoAccion = ref([]);
const cargar = ref(false);
onMounted(async () => {
  cargar.value = true;
  ID_user_login.value = await userStore.id;
  allNotarios.value = (await NotarioService.getData({ params: { rowsPerPage: 0, order_by: 'nombre_completo' } })).data;
  cargar.value = false;
  await CargarData(paginacion.value);
});

async function CargarData(pag) {
  cargar.value = true;
  if (ID_user_login.value===null) ID_user_login.value = await userStore.id;
  let filtros = { notario_id: idNotario.value, user_id:opcionVista.value===1?ID_user_login.value:null};
  const res = await LibroService.getData({
    params: {
      rowsPerPage: pag.num_filas,
      page: pag.pagina,
      order_by: pag.ordenado_por,
      search: busqueda.value,
      filter_by: filtros,
      fun_agrega: [{column:'estado',fun:'COUNT',groupBy:'estado',where:['user_id','=',ID_user_login.value]},],
    },
  });
  // console.log(res);
  paginacion.value.primer_pag = await res.current_page;
  paginacion.value.ultimo_pag = await res.last_page;
  paginacion.value.total_datos = await res.total;
  const otros_datos = await res?.mas_datos[0];
  if(otros_datos){
    cantidad_registrando.value = otros_datos.find(item => item.estado === 1)? otros_datos.find(item => item.estado === 1).result_COUNT:0;
    console.log(cantidad_registrando.value);
  }
  libros.value = await res.data;
  // console.log(ID_user_login.value,libros.value);
  CargarTipoAccion(libros.value.length);
  cargar.value = false;
}
/*** reactividad de la paginación ************************************************ */
watch(() => opcionVista.value, async (newVal, oldVal) => {
  paginacion.value.pagina = 1;
  await CargarData(paginacion.value);
});
watch(() => paginacion.value.pagina, async (newVal, oldVal) => {
  await CargarData(paginacion.value);
});
watch(() => idNotario.value, async (newVal, oldVal) => {
  paginacion.value.pagina = 1;
  await CargarData(paginacion.value);
});
watch(() => busqueda.value, async (newVal, oldVal) => {
  if (newVal && newVal.length > 3) {
    paginacion.value.pagina = 1;
    await CargarData(paginacion.value);
  } else if (newVal === null || newVal === '') {
    paginacion.value.pagina = 1;
    await CargarData(paginacion.value);
  }
});
/********************************************************************************** */
const showProyecto = (object) => {
  router.push({
    name: "Escrituras",
    params: { id: object.id },
  });
};
function AgregarLibro() {
  tipoAccion.value.unshift('agregar');
  errores.value.unshift(null);
  libros.value.unshift({
    id: null,
    protocolo: 'P-',
    notario_id: null,
    user_id: ID_user_login.value,
  });
}
let backup = {};
async function EditarLibro(libro_id, index) {
  tipoAccion.value[index] = 'editar';
  backup[index.toString()] = await LibroService.get(libro_id);
}
async function CancelarAccion(index) {
  if (tipoAccion.value[index] === 'agregar') {
    libros.value.splice(index, 1); // libros.value.shift();
    tipoAccion.value.splice(index, 1); // tipoAccion.value.shift();
    errores.value.splice(index, 1); // tipoAccion.value.shift();
  } else {
    libros.value[index] = backup[index.toString()];
    tipoAccion.value[index] = 'list';
    errores.value[index] = null;
  }
}
function GuardarLibro(object, index) {
  $q.dialog({
    title: tipoAccion.value[index] === 'editar' ? "Editar" : "Agregar",
    message: "¿Estas seguro de " + tipoAccion.value[index] + " este registro?",
    cancel: true,
    persistent: true,
  }).onOk(async () => {
    if(object.protocolo.match(/\d+/)?.[0] && parseInt(object.protocolo.match(/\d+/)?.[0])!==0){
      try {
        errores.value[index] = null;
        const resp = await LibroService.save(object);
        // console.log(resp);
        paginacion.value.pagina = 1;
        await CargarData(paginacion.value);
        $q.notify({
          type: 'positive',
          message: 'Guardado con Exito.',
          position: 'top-right',
          progress: true,
          timeout: 1000,
        });
      } catch (error) {
        console.log(error.response.data.errors);
        errores.value[index] = error.response.data.errors;
      }
    }else
      errores.value[index] = {protocolo:['Ingrese un protocolo valido']};

  });
  // if(tip_acc==='editar'){
  //   form.value = useForm("put", "api/notarios/" + props.id, {
  //     id: "",
  //     nombres: "",
  //     apellido_paterno: "",
  //     apellido_materno: "",
  //     año_inicio: "",
  //     año_final: "",
  //     nombre_completo: "",
  //     ubigeo_cod: null,
  //   });
  // }
}

function CargarTipoAccion(len) {
  tipoAccion.value = Array(len).fill('list');
  errores.value = Array(len).fill(null);
}
</script>

<style lang="sass" scoped>
.my-card
  min-width: 200px
  width: 100%
  max-width: auto
</style>
