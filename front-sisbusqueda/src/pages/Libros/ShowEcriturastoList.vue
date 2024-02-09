<template>
  <q-dialog v-model="dialogEscritura" persistent>
    <CardFormEscritura :Libro="libro" :Editar="escrituraEdit" @save="save"></CardFormEscritura>
  </q-dialog>
  <q-page>
    <div class="q-pa-md q-gutter-sm">
      <q-breadcrumbs>
        <q-breadcrumbs-el icon="home" />
        <q-breadcrumbs-el icon="book" label="Libros" :to="{ name: 'Libros' }" />
        <q-breadcrumbs-el icon="receipt_long" :label="libro.protocolo" />
      </q-breadcrumbs>
    </div>
    <q-separator />
    <div class="q-gutter-xs q-pa-sm">
      <!-- botones de Aministracion *********************************************************  -->
      <q-btn color="primary" :disable="loading" icon-right="add" :label="$q.screen.lt.sm ? '' : 'Agregar'"
        @click="editar(null)" />
    </div>

    <q-table class="my-sticky-header-table htable q-ma-sm" binary-state-sort title="Escrituras"
      :rows-per-page-options="[5, 10, 15, 20]" :rows="rows" :columns="columns" row-key="id"
      v-model:pagination="pagination" ref="tableRef" :loading="loading" :filter="filter" @request="onRequest">
      <template v-slot:top-right>
        <q-input active-class="text-white" standout="bg-primary" color="white" dense debounce="500" v-model="filter"
          placeholder="Buscar">
          <template v-slot:append> <q-icon name="search" /> </template>
        </q-input>
      </template>
      <template v-slot:header="props">
        <q-tr :props="props">
          <q-th v-for="col in props.cols" :key="col.name" :props="props">
            {{ col.label }}
          </q-th>
          <q-th auto-width> Acciones </q-th>
        </q-tr>
      </template>
      <template v-slot:body="props">
        <q-tr :props="props">
          <q-td v-for="col in props.cols" :key="col.name" :props="props">
            <template v-if="col.name === 'otorgantes.nombre'">
              <div v-for="nombres, index in col.value" :key="index">{{ nombres.nombre_completo }}</div>
            </template>
            <template v-else-if="col.name === 'favorecidos.nombre'">
              <div v-for="nombres, index in col.value" :key="index">{{ nombres.nombre_completo }}</div>
            </template>
            <template v-else>
              {{ col.value }}
            </template>
          </q-td>
          <q-td auto-width>
            <q-btn size="sm" outline color="green" round class="q-mr-xs" @click="editar(props.row)" icon="edit" />
            <q-btn size="sm" outline color="red" round @click="eliminar(props.row.id)" icon="delete" />
          </q-td>
        </q-tr>
      </template>
    </q-table>
  </q-page>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useQuasar } from "quasar";
import { useRoute } from "vue-router";
import CardFormEscritura from "../Libros/CardFormEscritura.vue";
import LibroService from "src/services/LibroService";
import EscrituraService from "src/services/EscrituraService";
import { convertDate } from "src/utils/ConvertDate";
const route = useRoute();
const $q = useQuasar();
const dialogEscritura = ref(false);
const libro = ref({});
const columns = [
  { field: (row) => row.cod_escritura, name: "cod_escritura", label: "Cod. Escritura", align: "center", sortable: true, },
  { field: (row) => row.fecha, name: "fecha", label: "Fecha", align: "center", sortable: true, },
  { field: (row) => row.cod_folioInicial + '  a  ' + row.cod_folioFinal, name: "cod_folioInicial", label: "Folios", align: "center", sortable: true, },
  { field: (row) => row.bien, name: "bien", label: "Bien", align: "center", sortable: true, },
  { field: (row) => row.sub_serie ? row.sub_serie.nombre : 'sin SubSerie', name: "sub_series.nombre", label: "Subserie", align: "center", sortable: true, },
  { field: (row) => row.otorgantes ? row.otorgantes : 'sin Otorgantes', name: "otorgantes.nombre", label: "Otorgantes", align: "left", sortable: true, },
  { field: (row) => row.favorecidos ? row.favorecidos : 'sin Favorecidos', name: "favorecidos.nombre", label: "Favorecidos", align: "left", sortable: true, },
  { field: (row) => row.updated_at ? convertDate(row.updated_at, 'dd/MM/yyyy HH:mm:ss') : 'sin Fecha de Actualización', name: "updated_at", label: "F. Actualización", align: "left", sortable: true, },
];

const tableRef = ref();
const rows = ref([]);
const filter = ref("");
const loading = ref(false);
const pagination = ref({
  sortBy: "id",
  descending: false,
  page: 1,
  rowsPerPage: 7,
  rowsNumber: 10,
});

const escrituraEdit = ref(null);
async function onRequest(props) {
  const { page, rowsPerPage, sortBy, descending } = props.pagination;
  const filter = props.filter;
  loading.value = true;

  const fetchCount = rowsPerPage === 0 ? 0 : rowsPerPage;
  const order_by = descending ? "-" + sortBy : sortBy;
  const { data, total = 0 } = await EscrituraService.getData({
    params: {
      filter_by: { libro_id: route.params.id, },
      // filter_range:{cod_escritura:{from:10,to:20}},
      // date_range:{fecha:{from:'2000/01/01',to:'2010/05/01'}},
      rowsPerPage: fetchCount,
      page,
      search: filter,
      order_by,
    },
  });
  // console.log(data);
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

onMounted(async () => {
  await getLibro();
  tableRef.value.requestServerInteraction();
});

const save = () => {
  dialogEscritura.value = false;
  tableRef.value.requestServerInteraction();
  $q.notify({
    type: "positive",
    message: "Guardado con Exito.",
    position: "top-right",
    progress: true,
    timeout: 1000,
  });
};
async function editar(row) {
  escrituraEdit.value = row;
  dialogEscritura.value = true;
  // title.value = "Editar Usuario";
  // formUser.value = true;
  // edit.value = true;
  // editId.value = id;
  // const row = await UsuarioService.get(id);
  // console.log(row);

  // usuariosformRef.value.form.setData({
  //   id: row.user.id,
  //   name: row.user.name,
  //   email: row.user.email,
  //   rolesSelected: row.rolesSelected,
  // });
}

async function eliminar(id) {
  $q.dialog({
    title: "¿Estas seguro de eliminar este registro? " + id,
    message: "Este proceso es irreversible.",
    cancel: true,
    persistent: true,
  }).onOk(async () => {
    // await UsuarioService.delete(id);
    tableRef.value.requestServerInteraction();
    $q.notify({
      type: "positive",
      message: "Eliminado con Exito.",
      position: "top-right",
      progress: true,
      timeout: 1000,
    });
  });
}
const getLibro = async () => {
  const res = await LibroService.get(route.params.id);
  libro.value = res;
};
const addProyecto = () => {
  escrituraEdit.value = null;
  dialogEscritura.value = true;
};
</script>

<style lang="sass">
.my-sticky-header-table
  /* height or max-height is important */
  height: 75vh

  .q-table__top,
  .q-table__bottom,
  thead tr:first-child th
    /* bg color is important for th; just specify one */


  thead tr th
    position: sticky
    z-index: 1
    background-color: $primary
    color: white
  thead tr:first-child th
    top: 0

  /* this is when the loading indicator appears */
  &.q-table--loading thead tr:last-child th
    /* height of all previous header rows */
    top: 48px

  /* prevent scrolling behind sticky top row on focus */
  tbody
    /* height of all previous header rows */
    scroll-margin-top: 48px

.htable
  #height: calc(100vh - 157px)
</style>
