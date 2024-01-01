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
      <q-btn :label="$q.screen.lt.sm ? '' : 'Agregar'" color="primary" icon-right="add" @click="AgregarLibro"
        :disable="tipoAccion.includes('editar')" />
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
      <div class="col-xs-12 col-md-6 col-lg-4 q-pa-sm" v-for="(v, k) in libros" :key="k">
        <q-card class="my-card">
          <q-card-section class="text-white"
            :class="tipoAccion[k] === 'agregar' ? 'bg-positive' : tipoAccion[k] === 'editar' ? 'bg-secondary' : 'bg-primary'">
            <div v-if="tipoAccion[k] === 'list'" class="text-h5 text-center" style="height: 1.5em;">{{ v.nombre }}</div>
            <q-input v-else label="Nombre Libro" dense clearable class="q-my-sm" color="blue-1" v-model="v.nombre"
              :readonly="tipoAccion[k] === 'list'"
              :error-message="errores[k] ? errores[k].nombre ? errores[k].nombre[0] : '' : ''"
              :error="errores[k] && errores[k].nombre != null" />
            <!-- <q-popup-edit v-if="tipoAccion[k]!=='list'" v-model="v.nombre" buttons v-slot="scope" label-set="aceptar" label-cancel="cancelar">
              <q-input v-model="scope.value" dense autofocus counter @keyup.enter="scope.set"/>
            </q-popup-edit> -->
          </q-card-section>

          <q-card-section class="">
            <q-form>
              <q-input label="N° Protocolo" dense clearable class="q-my-sm" v-model="v.protocolo"
                :readonly="tipoAccion[k] === 'list'" mask="P-####"
                :error-message="errores[k] ? errores[k].protocolo ? errores[k].protocolo[0] : '' : ''"
                :error="errores[k] && errores[k].protocolo != null" />
              <SelectInput label="Notario" dense class="q-my-sm" v-model="v.notario_id" :options="allNotarios"
                OptionLabel="nombre_completo" OptionValue="id" :readonly="tipoAccion[k] === 'list'"
                :error-message="errores[k] ? errores[k].notario_id ? errores[k].notario_id[0] : '' : ''"
                :error="errores[k] && errores[k].notario_id != null" />
            </q-form>
          </q-card-section>

          <q-separator />

          <q-card-actions align="right">
            <div>
              <div>F.A.:</div>
              <div>{{ convertDate(v.updated_at, 'dd/MMM/yyyy h:mm a') }}</div>
            </div>
            <div class="q-ml-sm">ID: {{ v.id }}</div>
            <q-space></q-space>
            <q-btn v-if="tipoAccion[k] === 'list'" @click="EditarLibro(v.id, k)"
              :disable="tipoAccion.includes('agregar')">Editar</q-btn>
            <q-btn v-else @click="GuardarLibro(v, k)">Guardar</q-btn>
            <q-btn v-if="tipoAccion[k] !== 'list'" color="negative" @click="CancelarAccion(k)">Cancelar</q-btn>
            <q-btn v-if="tipoAccion[k] === 'list'" color="primary" icon="search"
              @click="showProyecto(v)">Consultar</q-btn>
          </q-card-actions>
        </q-card>
      </div>
      <q-inner-loading v-if="cargar" :showing="cargar">
        <q-spinner-facebook size="250px" color="light-blue" />
      </q-inner-loading>
      <div v-if="!cargar" class="col-12 q-pa-lg column items-center">
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

const busqueda = ref();
const idNotario = ref();
const cargar = ref(false);

const errores = ref([]);
const tipoAccion = ref([]);
onMounted(async () => {
  cargar.value = true;
  allNotarios.value = (await NotarioService.getData({ params: { rowsPerPage: 0, order_by: 'nombre_completo' } })).data;
  cargar.value = false;
  await CargarData(paginacion.value);
});

async function CargarData(pag) {
  cargar.value = true;
  let filtros = { notario_id: idNotario.value, };
  const res = await LibroService.getData({
    params: {
      rowsPerPage: pag.num_filas,
      page: pag.pagina,
      order_by: pag.ordenado_por,
      search: busqueda.value,
      filter_by: filtros,
    },
  });
  paginacion.value.primer_pag = await res.current_page;
  paginacion.value.ultimo_pag = await res.last_page;
  paginacion.value.total_datos = await res.total;
  libros.value = await res.data;
  console.log(libros.value);
  CargarTipoAccion(libros.value.length);
  cargar.value = false;
}
/*** reactividad de la paginación ************************************************ */
watch(() => paginacion.value.pagina, async (newVal, oldVal) => {
  cargar.value = true;
  await CargarData(paginacion.value);
  cargar.value = false;
});
watch(() => idNotario.value, async (newVal, oldVal) => {
  cargar.value = true;
  paginacion.value.pagina = 1;
  await CargarData(paginacion.value);
  cargar.value = false;
});
watch(() => busqueda.value, async (newVal, oldVal) => {
  if (newVal && newVal.length > 3) {
    cargar.value = true;
    paginacion.value.pagina = 1;
    await CargarData(paginacion.value);
    cargar.value = false;
  } else if (newVal === null || newVal === '') {
    cargar.value = true;
    paginacion.value.pagina = 1;
    await CargarData(paginacion.value);
    cargar.value = false;
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
    nombre: 'Libro Nuevo ...',
    fecha: convertDate(new Date, "yyyy/MM/dd"),
    protocolo: 'P-',
    notario_id: null,
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
    try {
      errores.value[index] = null;
      const resp = await LibroService.save(object);
      console.log(resp);
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
  min-width: 350px
  width: 100%
  max-width: auto
</style>
