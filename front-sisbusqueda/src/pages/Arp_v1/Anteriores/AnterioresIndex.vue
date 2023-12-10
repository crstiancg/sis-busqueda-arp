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

        <q-breadcrumbs-el label="Data Anteriores" icon="mdi-account-key" />
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
        <SelectInput label="Notarios" v-model="nombreNptario" :options="AnteriorService" optionValue="notario" OptionLabel="notario" :PropidadData="false"/>
        {{ nombreNptario }}
    </div>

    <q-table
      :rows-per-page-options="[7, 10, 15]"
      class="my-sticky-header-table htable q-ma-sm"
      title="Roles"
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
            {{ col.label }}
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
import { ref, onMounted } from "vue";
import AnteriorService from "src/services/arp_v1/AnteriorService";
import SelectInput from "src/components/SelectInput.vue";
import { useQuasar } from "quasar";
import RolesForm from "src/pages/Admin/Roles/RolesForm.vue";
const $q = useQuasar();

const listaNotario = ref();
async function verDat(){
  const dato = await AnteriorService.getData({
    params: { rowsPerPage: 100, page:1, search: 'Manuel', order_by:'id' },
  })
  // listaNotario.value = dato;
  console.log(dato);
}
verDat(); //

const columns = [
  // { field: (row) => row.id, name: "id", label: "Id", aling: "center", sortable: true, },
  { field: (row) => row.notario, name: "notario", label: "Notario", aling: "center", sortable: true,},
  { field: (row) => row.lugar, name: "lugar", label: "Lugar", aling: "center", sortable: true,},
  // { field: (row) => row.subserie, name: "subserie", label: "Subserie", aling: "center", sortable: true,},
  // { field: (row) => row.fecha, name: "fecha", label: "Fecha", aling: "center", sortable: true,},
  // { field: (row) => row.bien, name: "bien", label: "Bien", aling: "center", sortable: true,},
  // { field: (row) => row.protocolo, name: "protocolo", label: "Protocolo", aling: "center", sortable: true,},
];

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

const nombreNptario = ref();




async function onRequest(props) {
  const { page, rowsPerPage, sortBy, descending } = props.pagination;
  const filter = props.filter;
  loading.value = true;

  const fetchCount = rowsPerPage === 0 ? 0 : rowsPerPage;
  const order_by = filter? '': descending ? "-" + sortBy : sortBy;
  const { data, total = 0 } = await AnteriorService.getData({
    params: { rowsPerPage: fetchCount, page, search: filter, order_by },
  });;
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

onMounted(() => {
  // tableRef.value.requestServerInteraction();
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
  const row = await AnteriorService.get(id);
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
    await AnteriorService.delete(id);
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
