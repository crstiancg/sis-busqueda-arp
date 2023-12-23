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

    <div class="row justify-center">
      <div class="col-12 col-sm-auto q-pa-sm" v-for="(v, k) in libros" :key="k">
        <q-card class="my-card">
          <q-card-section class="bg-primary text-white">
            <div class="text-h5 text-center">{{ v.nombre }}</div>
          </q-card-section>

          <q-card-section class="">
            <q-form>
              <q-input label="Proyecto ID" v-model="v.id" dense readonly>
              </q-input>
              <q-input
                label="Fecha Creacion"
                v-model="v.created_at"
                dense
                readonly
              >
              </q-input>
              <q-input
                label="Notario"
                dense
                v-model="v.notario.nombre_completo"
                readonly
              >
              </q-input>
              <q-input
                label="N° Protocolo"
                v-model="v.protocolo"
                dense
                readonly
              >
              </q-input>
            </q-form>
          </q-card-section>

          <q-separator />

          <q-card-actions align="right">
            <q-btn>Editar</q-btn>
            <q-btn color="primary" icon="search" @click="showProyecto(v)"
              >Consultar</q-btn
            >
          </q-card-actions>
        </q-card>
      </div>
    </div>
  </q-page>
</template>
<script setup>
import LibroService from "src/services/arp_v1/LibroService";
import { onMounted, ref } from "vue";
import { useRouter } from "vue-router";
import CardFormFolioVue from "components/CardFormFolio.vue";

const router = useRouter();

const libros = ref([]);
const libro = ref({});
const dialogFolio = ref(false);

onMounted(async () => {
  console.log("hola");
  const res = await LibroService.getData();
  console.log(res);
  libros.value = res;
});



const showProyecto = (object) => {
  router.push({
    name: "Proyecto",
    params: { id: object.id },
  });
  // libro.value = object;
  // dialogFolio.value = true;
};
</script>

<style lang="sass" scoped>
.my-card
  min-width: 350px
  width: 100%
  max-width: auto
</style>
