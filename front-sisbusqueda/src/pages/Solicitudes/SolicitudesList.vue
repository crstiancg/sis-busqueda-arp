<template>
    <q-dialog v-if="userStore.getAreaId === 1" v-model="solicitudForm" persistent>
      <SolicitudesForm title="Registrar una Solicitud" @save="save($event)"/>
    </q-dialog>
    <q-dialog v-if="userStore.getAreaId === 2" v-model="busquedaForm" persistent>
      <BusquedasForm title="Registro de Resultados de Busqueda"
        :solici_id="SolicitudID" :solicitud="_solicitud" @save="save($event)" />
    </q-dialog>
    <q-dialog v-if="userStore.getAreaId === 3" v-model="verificaForm" persistent>
      <VerificacionesForm title="Registro de Verificación de Busqueda"
        :solici_id="SolicitudID" :solicitud="_solicitud" :regisBusqueda="_regisBusque" @save="save($event)" />
    </q-dialog>
    <q-page>
      <div class="q-pa-md q-gutter-sm">
        <q-breadcrumbs>
          <q-breadcrumbs-el icon="home" />
          <q-breadcrumbs-el label="Solicitudes" icon="mdi-key" />
        </q-breadcrumbs>
      </div>
      <q-separator />
      <div class="q-gutter-xs q-pa-sm">
        <q-btn v-if="userStore.getAreaId===1" color="primary" :label="$q.screen.lt.sm ? '' : 'Agregar'" icon-right="add"
          @click="solicitudForm = true" :disable="loading"/>
      </div>

      <q-table
        :rows-per-page-options="[5, 10, 15,20]"
        class="my-sticky-header-table htable q-ma-sm"
        title="Solicitudes"
        ref="tableRef"
        :rows="rows"
        :columns="columns"
        row-key="id"
        v-model:pagination="pagination"
        :loading="loading"
        :filter="filter"
        binary-state-sort
        @request="onRequest"
      >
        <template v-slot:top-right>
          <q-input  active-class="text-white" standout="bg-primary" color="white" dense debounce="500"
              v-model="filter" placeholder="Buscar" >
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>
        </template>
        <template v-slot:body="props">
          <q-tr :props="props">
            <q-td v-for="col in props.cols" :key="col.name" :props="props">
              <span v-if="col.name === 'updated_at'">{{ convertDate(col.value,'dd/MM/yyyy h:mm:ss a') }}</span>
              <span v-else-if="col.name === 'estado'">
                <q-badge :color="col.value ==='Finalizado'?'green':'orange'" class="text-subtitle2">
                  {{ col.value }}
                </q-badge>
              </span>
              <div v-else-if="col.name === 'acciones'">
                <GenerarPDFSolicitud :vericon="true" icon="picture_as_pdf" size="sm" outline round class="q-mr-xs"
                  :datosSolicitudRow="props.row"/>
                <q-btn v-if="userStore.getAreaId === 2" outline color="blue"  icon="search" size="sm" round class="q-mr-xs"
                    @click="busqueda(props.row.id,props.row)" />
                <q-btn v-if="userStore.getAreaId === 3" outline color="blue"  icon="rule" size="sm" round class="q-mr-xs"
                    @click="verificacion(props.row.id,props.row)" /> 
                <q-btn v-if="userStore.getAreaId===1 && props.row.area_id===1" outline color="blue"  icon="point_of_sale" size="sm" round class="q-mr-xs"
                    @click="caja(props.row.id,props.row)" />
              </div>
              <span v-else>{{ col.value }}</span>
            </q-td>
          </q-tr>
        </template>
      </q-table>
    </q-page>
</template>

<script setup>
  import { ref, onMounted } from "vue";
  import SolicitudService from "src/services/SolicitudService";
  import BusquedaService from "src/services/BusquedaService";
  import { useQuasar } from "quasar";
  import SolicitudesForm from "src/pages/Solicitudes/SolicitudesForm.vue";
  import BusquedasForm from "./Registros/BusquedasForm.vue";
  import VerificacionesForm from "./Registros/VerificacionesForm.vue";
  import GenerarPDFSolicitud from "src/components/GenerarPDFSolicitud.vue";
  import { convertDate } from "src/utils/ConvertDate";
  import { useUserStore } from "src/stores/user-store";
  const userStore = useUserStore();
  const $q = useQuasar();

const columns = [
  { field: (row) => row.id, name: "id", label: "ID", align: "left", sortable_: true },
  { field: (row) => row.solicitante.tipo_documento ==='DNI'?row.solicitante.nombre_completo:row.solicitante.asunto, name: "solicitante.nombre_completo", label: "Solicitante", align: "left", sortable_: true },
  { field: (row) => row.estado, name: "estado", label: "Estado", align: "center", sortable_: true, },
  { field: (row) => row.updated_at , name: "updated_at", label: "Fecha actualizacion", align: "center", sortable_: true, },
  { field: '' , name: "acciones", label: "Acciones", align: "center" },
];

const solicitudForm = ref(false);
const busquedaForm = ref(false);
const verificaForm = ref(false);
const SolicitudID = ref();

const tableRef = ref();
const rows = ref([]);
const filter = ref("");
const loading = ref(false);
const pagination = ref({
  sortBy: "id",
  descending: false,
  page: 1,
  rowsPerPage: 10,
  rowsNumber: 10,
});

async function onRequest(props) {
  const { page, rowsPerPage, sortBy, descending } = props.pagination;
  const filter = props.filter;
  loading.value = true;

  const fetchCount = rowsPerPage === 0 ? 0 : rowsPerPage;
  const order_by = descending ? "-" + sortBy : sortBy;
  const { data, total = 0 } = await SolicitudService.getData({
    params: { 
      area_id: userStore.getAreaId!==1?userStore.getAreaId:"", 
      user_id: userStore.getAreaId===1?userStore.id:"", 
      estado: "",
      rowsPerPage: fetchCount, 
      page, 
      search: filter, 
      order_by, 
    },
  });
  // clear out existing data and add new
  rows.value.splice(0, rows.value.length, ...data);
  // don't forget to update local pagination object
  !total
    ? (pagination.value.rowsNumber = data.length)
    : (pagination.value.rowsNumber = total);
  pagination.value.page = page;
  pagination.value.rowsPerPage = rowsPerPage;
  pagination.value.sortBy = sortBy;
  pagination.value.descending = descending;
  // ...and turn of loading indicator
  loading.value = false;
}
onMounted(async() => {
  await userStore.getUser();
  tableRef.value.requestServerInteraction();
});

const save = (tipo_dialog) => {
  console.log(tipo_dialog);
  if (tipo_dialog && tipo_dialog === 'busqueda') busquedaForm.value = false;
  if (tipo_dialog && tipo_dialog === 'verificacion') verificaForm.value = false;
  if (tipo_dialog && tipo_dialog === 'solicitud') solicitudForm.value = false;
  tableRef.value.requestServerInteraction();
  $q.notify({
    type: "positive",
    message: "Guardado con Exito.",
    position: "top-right",
    progress: true,
    timeout: 1000,
  });
};
let _solicitud = ref(null);
function busqueda(id,_soli){
  busquedaForm.value = true;
  SolicitudID.value = id;
  _solicitud.value = _soli;
}
let _regisBusque = ref(null);
async function verificacion(id,_soli){
  const resBusqu = await BusquedaService.getData({params:{
    rowsPerPage:0,
    solicitud_id: id,
  }});
  if (resBusqu.data?.[0])
    _regisBusque.value = resBusqu.data[0];
  verificaForm.value = true;
  SolicitudID.value = id;
  _solicitud.value = _soli;
}
function caja(id,_soli){
  console.log('caja para:',id);
}

  // const title = ref("");
  // const edit = ref(false);
  // const editId = ref();

  // async function editar(id) {
  //   title.value = "Editar Solicitud";
  //   solicitudForm.value = true;
  //   edit.value = true;
  //   editId.value = id;
    
  //   const row = await SolicitudService.get(id);
  //   console.log(row);
  //   // solicitudesformRef.value.setValue(row);
  // }


  // async function eliminar(id) {
  //   $q.dialog({
  //     title: "¿Estas seguro de eliminar este registro?",
  //     message: "Este proceso es irreversible.",
  //     cancel: true,
  //     persistent: true,
  //   }).onOk(async () => {
  //     await SolicitudService.delete(id);
  //     tableRef.value.requestServerInteraction();
  //     $q.notify({
  //       type: "positive",
  //       message: "Eliminado con Exito.",
  //       position: "top-right",
  //       progress: true,
  //       timeout: 1000,
  //     });
  //   });
  // }
  </script>
