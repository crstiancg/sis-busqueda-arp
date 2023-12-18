<template>
    <q-dialog v-model="formPermisos">
      <SolicitudesForm
        :title="title"
        :edit="edit"
        :id="editId"
        ref="solicitudesformRef"
        @save="save"
      ></SolicitudesForm>
    </q-dialog>
    <q-page>
      <div class="q-pa-md q-gutter-sm">
        <q-breadcrumbs>
          <q-breadcrumbs-el icon="home" />

          <q-breadcrumbs-el label="Permisos" icon="mdi-key" />
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
              formPermisos = true;
              edit = false;
              title = 'Añadir Notario';
            }
          "
        />
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
  import SolicitudService from "src/services/SolicitudService";
  import { useQuasar } from "quasar";
  import SolicitudesForm from "src/pages/Solicitudes/SolicitudesForm.vue";
  const $q = useQuasar();

async function verDat(){
  const dato = await SolicitudService.getData({
    params: { rowsPerPage: 100, page:1, search: '', order_by:'' },
  })
  console.log(dato);
}

// verDat();

const columns = [
  { name: 'index', label: '#', field: 'index' },
  { field: (row) => row.id, name: "id", label: "ID", align: "left", sortable_: true, search: true },
  { field: (row) => row.solicitante.nombre_completo, name: "solicitante.nombre_completo", label: "Solicitante", align: "left", sortable_: true, search: true },
  { field: (row) => row.subserie.nombre, name: "subserie.nombre", label: "Subserie", align: "center", sortable_: true, search: true },
  { field: (row) => row.estado, name: "estado", label: "Estado", align: "center", sortable_: true, },
  { field: (row) => row.updated_at , name: "updated_at", label: "Fecha actualizacion", align: "center", sortable_: true, },
];

  const tableRef = ref();
  const formPermisos = ref(false);
  const solicitudesformRef = ref();
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
      params: { rowsPerPage: fetchCount, page, search: filter, order_by },
    });
    console.log(data);
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
    formPermisos.value = false;
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
    title.value = "Editar Notario";
    formPermisos.value = true;
    edit.value = true;
    editId.value = id;
    const row = await SolicitudService.get(id);
    console.log(row);

    solicitudesformRef.value.form.setData({
      id: row.id,

    });
    solicitudesformRef.value.setValue(row);
  }

  async function eliminar(id) {
    $q.dialog({
      title: "¿Estas seguro de eliminar este registro?",
      message: "Este proceso es irreversible.",
      cancel: true,
      persistent: true,
    }).onOk(async () => {
      await SolicitudService.delete(id);
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
