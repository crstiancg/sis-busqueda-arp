<template>
  <!-- <q-dialog v-model="formRole">
    <RolesForm
      :title="title"
      :edit="edit"
      :id="editId"
      ref="rolesformRef"
      @save="save"
    ></RolesForm>
  </q-dialog> -->
  <q-page>
    <div class="q-pa-md q-gutter-sm">
      <q-breadcrumbs>
        <q-breadcrumbs-el icon="home" />

        <q-breadcrumbs-el label="Data Anteriores 2" icon="mdi-account-key" />
      </q-breadcrumbs>
    </div>
    <q-separator />
    <div class="q-gutter-xs q-pa-sm">
      <q-btn
        color="primary"
        :disable="loading"
        :label="$q.screen.lt.sm ? '' : 'Agregar'"
        icon-right="add"
        @click="
          {
            formRole = true;
            edit = false;
            title = 'Añadir Rol';
          }
        "
      />
    </div>
    <div class="row">
      <SelectInput class="col-4 q-px-xs" label="Notarios" v-model="nombreNotario" :options="GenerateListService"
        :GenerateList="{ column: 'notario', table: 'anterior2' }" />
      <SelectInput class="col-4 q-px-xs" label="Lugar" v-model="nombreLugar" :options="GenerateListService"
        :GenerateList="{ column: 'lugar', table: 'anterior2' }" />
      <!-- <InputTextSelect class="col-4 q-px-xs" label="Notarios" v-model="nombreNotario_" :options="GenerateListService"
        :GenerateList="{ column: 'otorgantes', table: 'anterior' }"></InputTextSelect> -->
      <SelectInput class="col-4 q-px-xs" label="Subserie" v-model="nombreSubserie" :options="GenerateListService"
        :GenerateList="{ column: 'subserie', table: 'anterior2' }" />
    </div>
    <q-table
      :rows-per-page-options="[7, 10, 15]"
      class="my-sticky-header-table htable q-ma-sm"
      title="Anteriores 2"
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
        <q-input
          active-class="text-white"
          standout="bg-primary"
          color="white"
          dense
          debounce="500"
          v-model="filter"
          placeholder="Buscar"
        >
          <template v-slot:append>
            <q-icon name="search" />
          </template>
        </q-input>
      </template>
      <template v-slot:header="props">
        <q-tr :props="props">
          <q-th v-for="col in props.cols" :key="col.name" :props="props">
            <span v-if="col.sortable_" class="span-icono" @click="props.sort(col.name)">
              <q-icon class="q-table__sort-icon icon-sort" style="" name="arrow_downward" />
              {{ col.label }}
            </span>
            <span v-else>{{ col.label }}</span>
            <q-icon v-if="col.search" class="q-pa-xs q-mx-xs cursor-pointer" :class="$q.dark.isActive ? 'btn-buscar-dark' : 'btn-buscar'" name="search" size="xs">
              <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                <q-input clearable class="q-px-sm" dense debounce="500" v-model="busColum[col.name]" placeholder="Buscar">
                  <template v-slot:append> <q-icon name="search" /> </template>
                </q-input>
              </q-popup-proxy>
            </q-icon>
          </q-th>
          <q-th auto-width> Acciones </q-th>
        </q-tr>
      </template>

      <template v-slot:body="props">
        <q-tr :props="props">
          <q-td v-for="col in props.cols" :key="col.name" :props="props">
            {{ col.value }}
          </q-td>
          <q-td auto-width>
            <q-btn
              size="sm"
              outline
              color="green"
              round
              @click="editar(props.row.id)"
              icon="edit"
              class="q-mr-xs"
            />
            <q-btn
              size="sm"
              outline
              color="red"
              round
              @click="eliminar(props.row.id)"
              icon="delete"
            />
          </q-td>
        </q-tr>
      </template>
    </q-table>
  </q-page>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import Anterior2Service from "src/services/arp_v1/Anterior2Service";
import { useQuasar } from "quasar";
import GenerateListService from "src/services/arp_v1/GenerateListService";
import RolesForm from "src/pages/Admin/Roles/RolesForm.vue";
import SelectInput from "src/components/SelectInput.vue";

const $q = useQuasar();

async function verDat(){
  const filtros = {lugar:'col1',col2:'col2',notario:'col1',col4:'col2'};
  const dato = await Anterior2Service.getData({
    params: { rowsPerPage: 10, page:1, search: 'roger', order_by:''},
  })
  console.log(dato);
}

// verDat();

const columns = [
  { field: (row) => row.id, name: "id", label: "Id", aling: "center", sortable: true, },
  { field: (row) => row.notario, name: "notario", label: "Notario", aling: "center", sortable: true, search: true},
  { field: (row) => row.lugar, name: "lugar", label: "Lugar", aling: "center", sortable: true, search: true},
  { field: (row) => row.subserie, name: "subserie", label: "Subserie", aling: "center", sortable: true,},
  { field: (row) => row.fecha, name: "fecha", label: "Fecha", aling: "center", sortable: true,},
  { field: (row) => row.bien, name: "bien", label: "Bien", aling: "center", sortable: true,},
  { field: (row) => row.protocolo, name: "protocolo", label: "Protocolo", aling: "center", sortable: true,},
  { field: (row) => row.otorgantes, name: "otorgantes", label: "Otorgantes", aling: "center", sortable: true, search: true},
  { field: (row) => row.favorecidos, name: "favorecidos", label: "Favorecidos", aling: "center", sortable: true, search: true},
];

const nombreNotario = ref();
const nombreLugar = ref();
const nombreSubserie = ref();

const busColum = ref({});

watch(nombreNotario, (newValue, oldValue) => {
  tableRef.value.requestServerInteraction();
});
watch(nombreLugar, (newValue, oldValue) => {
  tableRef.value.requestServerInteraction();
});
watch(nombreSubserie, (newValue, oldValue) => {
  tableRef.value.requestServerInteraction();
});
watch(busColum.value, (newValue, oldValue) => {
  tableRef.value.requestServerInteraction();
});

const tableRef = ref();
const formRole = ref(false);
const rolesformRef = ref();
const title = ref("");
const edit = ref(false);
const editId = ref();
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

async function onRequest(props) {
  const { page, rowsPerPage, sortBy, descending } = props.pagination;
  const filter = props.filter;
  loading.value = true;

  const fetchCount = rowsPerPage === 0 ? 0 : rowsPerPage;
  const order_by = filter? '': descending ? "-" + sortBy : sortBy;
  const filtros = {notario: nombreNotario.value, lugar: nombreLugar.value, subserie: nombreSubserie.value};
  const { data, total = 0 } = await Anterior2Service.getData({
    params: { rowsPerPage: fetchCount, page, search: filter, order_by, search_by:busColum.value, filter_by:filtros,},
  });;
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


onMounted(() => {
  tableRef.value.requestServerInteraction();
});

const save = () => {
  formRole.value = false;
  tableRef.value.requestServerInteraction();
  $q.notify({
    type: "positive",
    message: "Guardado con Exito.",
    position: "top-right",
    progress: true,
    timeout: 1000,
  });
};
async function editar(id) {
  title.value = "Editar Rol";
  formRole.value = true;
  edit.value = true;
  editId.value = id;
  const row = await Anterior2Service.get(id);
  console.log(row);

  rolesformRef.value.form.setData({
    id: row.rol.id,
    name: row.rol.name,
    permisosSelected: row.permisosSelected,
  });
}

async function eliminar(id) {
  $q.dialog({
    title: "¿Estas seguro de eliminar este registro?",
    message: "Este proceso es irreversible.",
    cancel: true,
    persistent: true,
  }).onOk(async () => {
    await Anterior2Service.delete(id);
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
</script>

<style lang="sass">
.my-sticky-header-table
  /* height or max-height is important */
  height: 80vh

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
