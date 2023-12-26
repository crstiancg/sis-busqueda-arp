<template>
  <q-page>
    <!-- <q-dialog v-model="dialogFolio">
      <CardFormFolioVue :proyecto="libro"></CardFormFolioVue>
    </q-dialog> -->
    <div class="q-pa-md q-gutter-sm">
      <q-breadcrumbs>
        <q-breadcrumbs-el icon="home" />

        <q-breadcrumbs-el label="Proyectos en curso" icon="mdi-account" />
      </q-breadcrumbs>
    </div>
    <q-separator />
    <div class="row q-ma-md">
      <q-btn :label="$q.screen.lt.sm ? '' : 'Agregar'" color="primary"
      icon-right="add" @click="AgregarLibro" />
      <q-space/>
      <SelectInput label="Notario" dense class="q-mx-sm"
      v-model="filtros" :options="allNotarios" OptionLabel="nombre_completo" OptionValue="id" />
      <q-input placeholder="Buscar" dense class="q-mx-sm"
          v-model="busqueda" clearable>
        <template v-slot:append>
          <q-icon name="search" />
        </template>
      </q-input>
    </div>
    <div class="q-pa-sm column items-center">
      <div>Paginas:</div>
      <q-pagination color="primary" boundary-numbers direction-links
          v-model="paginacion.pagina"
          :max="parseInt(paginacion.ultimo_pag)"
          :max-pages="paginacion.ultimo_pag?paginacion.ultimo_pag>5?5:parseInt(paginacion.ultimo_pag):1"
      />
    </div>
    <div class="row justify-center">
      <div class="col-xs-12 col-md-6 col-lg-4 q-pa-sm" v-for="(v, k) in libros" :key="k">
        <q-card class="my-card">
          <q-card-section class="text-white" :class="tipoAccion[k] === 'agregar'?'bg-positive':tipoAccion[k] === 'editar'?'bg-secondary':'bg-primary'">
            <div class="text-h5 text-center">{{ v.nombre }}</div>
            <q-popup-edit v-if="tipoAccion[k]!=='list'" v-model="v.nombre" buttons v-slot="scope">
              <q-input v-model="scope.value" dense autofocus counter @keyup.enter="scope.set" label-set="aceptar" label-cancel="cancelar"/>
            </q-popup-edit>
          </q-card-section>

          <q-card-section class="">
            <q-form>
              <q-input label="N° Protocolo" dense clearable class="q-my-sm"
                  v-model="v.protocolo" :readonly="tipoAccion[k]==='list'" mask="P-####"/>
              <SelectInput label="Notario" dense class="q-my-sm"
                  v-model="v.notario_id" :options="allNotarios" OptionLabel="nombre_completo" OptionValue="id"
                  :readonly="tipoAccion[k]==='list'" />
                <div v-show="false">{{ v.fecha = convertDate(v.fecha,"yyyy/MM/dd") }}</div>
              <q-input label="Fecha" dense clearable  class="q-my-sm"
                  v-model="v.fecha" :readonly="tipoAccion[k]==='list'" mask="date" :rules="['date']">
                <template v-slot:prepend>
                  <q-icon icon name="event" class="cursor-pointer">
                    <q-popup-proxy v-if="tipoAccion[k]!=='list'" cover transition-show="scale" transition-hide="scale">
                      <q-date v-model="v.fecha">
                        <div class="row items-center justify-end">
                          <q-btn v-close-popup label="Close" color="primary" flat />
                        </div>
                      </q-date>
                    </q-popup-proxy>
                  </q-icon>
                </template>
              </q-input>

            </q-form>
          </q-card-section>

          <q-separator />

          <q-card-actions align="right">
            <div>ID: {{ v.id }}  F.A.: {{ v.updated_at }}</div>
            <q-space></q-space>
            <q-btn v-if="tipoAccion[k]==='list'" @click="tipoAccion[k] = 'editar'">Editar</q-btn>
            <q-btn v-else @click="AgregaEditaLibro(v,tipoAccion[k])">Guardar</q-btn>
            <q-btn v-if="tipoAccion[k]!=='list'" color="negative" @click="CancelarAccion(k)">Cancelar</q-btn>
            <q-btn v-if="tipoAccion[k]==='list'" color="primary" icon="search" @click="showProyecto(v)">Consultar</q-btn>
          </q-card-actions>
        </q-card>
      </div>
      <q-inner-loading v-if="cargar" :showing="cargar">
        <q-spinner-gears size="50px" color="primary" />
      </q-inner-loading>
    </div>
    <q-separator />
    <div class="q-pa-lg column items-center">
      <div>Paginas:</div>
      <q-pagination color="primary" boundary-numbers direction-links
        v-model="paginacion.pagina"
        :max="parseInt(paginacion.ultimo_pag)"
        :max-pages="paginacion.ultimo_pag?paginacion.ultimo_pag>5?5:parseInt(paginacion.ultimo_pag):1"
      />
    </div>
  </q-page>
</template>
<script setup>
import { onMounted, ref, watchEffect } from "vue";
import { useRouter } from "vue-router";
import LibroService from "src/services/LibroService";
import SelectInput from "src/components/SelectInput.vue";
import NotarioService from "src/services/NotarioService";
import { convertDate } from "src/utils/ConvertDate";
import CardFormFolioVue from "components/CardFormFolio.vue";
import { useQuasar } from "quasar";

const $q = useQuasar();

const router = useRouter();
const form = ref({});
const paginacion = ref({
  primer_pag:null,
  pagina:1,
  ultimo_pag:null,
  total_datos: null,
  num_filas:12,
  ordenado_por:'updated_at',
});
const libros = ref([]);
const cache_libros = [];
const allNotarios = ref([]);
const busqueda = ref();
const filtros = ref();
const cargar = ref(false);

let bandera = 0;
const tipoAccion = ref([]);
onMounted(async () => {
  await CargarData(paginacion.value);
  // console.log(bandera++);
  cargar.value = true;
  allNotarios.value = (await NotarioService.getData({params: {rowsPerPage: 0,order_by:'nombre_completo'}})).data;
  cargar.value = false;
});

async function CargarData(pag){
  cargar.value = true;
  const res = await LibroService.getData({
      params: {
        rowsPerPage: pag.num_filas,
        page:pag.pagina,
        order_by:pag.ordenado_por,
        search: busqueda.value,
        notario_id: filtros.value,
      },
    });
    // console.log(res);
  paginacion.value.primer_pag = await res.current_page;
  paginacion.value.ultimo_pag = await res.last_page;
  paginacion.value.total_datos = await res.total;
  libros.value = await res.data;
  CargarTipoAccion(libros.value.length);
  cargar.value = false;
}
/*** reactividad de la paginación ************************************************ */
watchEffect(async () => {
  const newVal = paginacion.value.pagina;
  cargar.value = true;
  await CargarData(paginacion.value);
  // console.log(bandera++);
  cargar.value = false;
});
watchEffect(async () => {
  const newVal = busqueda.value;
  cargar.value = true;
  await CargarData(paginacion.value);
  // console.log(bandera++);
  cargar.value = false;
});
watchEffect(async () => {
  const newVal = filtros.value;
  cargar.value = true;
  await CargarData(paginacion.value);
  // console.log(bandera++);
  cargar.value = false;
});
/********************************************************************************** */
const showProyecto = (object) => {
  router.push({
    name: "Proyecto",
    params: { id: object.id },
  });
};
function AgregarLibro(){
  tipoAccion.value.unshift('agregar');
  libros.value.unshift({
    id:null,
    nombre:'Libro Nuevo ...',
    fecha:convertDate(new Date,"yyyy/MM/dd"),
    protocolo:'P-',
    notario_id:null,
  });
}
function AgregaEditaLibro(object,tip_acc){
  console.log(tip_acc,object);
  $q.dialog({
        title: tip_acc==='editar'?"Editar":"Agregar",
        message: "¿Estas seguro de "+tip_acc+" este registro?",
        cancel: true,
        persistent: true,
    }).onOk(async () => {
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
    });
}
function CancelarAccion(index){
  if(tipoAccion.value[index]==='agregar'){
    libros.value.shift();
    tipoAccion.value.shift();
  }
  CargarTipoAccion(libros.value.length)
}
function CargarTipoAccion(len){
  tipoAccion.value = Array(len).fill('list');
}
</script>

<style lang="sass" scoped>
.my-card
  min-width: 350px
  width: 100%
  max-width: auto
</style>
