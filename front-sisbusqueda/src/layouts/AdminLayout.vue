<template>
  <q-layout view="hHh Lpr lff" class="">
    <q-header elevated :class="$q.dark.isActive ? 'bg-primary' : 'bg-primary'">
      <q-toolbar>
        <q-btn flat @click="drawer = !drawer" round dense icon="menu" />
        <q-toolbar-title>Header</q-toolbar-title>
        <SwitchDarkMode></SwitchDarkMode>
        <NabvarUser></NabvarUser>
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="drawer"
      show-if-above
      :mini="miniState"
      @mouseover="miniState = false"
      @mouseout="miniState = true"
      :width="250"
      :breakpoint="500"
      bordered
      :class="$q.dark.isActive ? 'bg-grey-9' : 'bg-grey-2'"
    >
      <q-scroll-area class="fit" :horizontal-thumb-style="{ opacity: 0 }">
        <q-list  padding>
          <q-item
            :to="{ name: 'Dash' }"
            :active="link === 'Dash'"
            @click="link = 'Dash'"
            clickable
            v-ripple
            class="q-ma-xs rounded-borders"
            active-class="my-menu-link"
          >
            <q-item-section avatar>
              <q-icon name="home" />
            </q-item-section>

            <q-item-section> Inicio </q-item-section>
          </q-item>
          <q-separator />
          <q-item v-if="userStore.hasPermission('admin-permisos')"
              :to="{ name: 'Permisos' }"
              :active="link === 'Permisos'"
              @click="link = 'Permisos'"
              clickable
              v-ripple
              class="q-ma-xs rounded-borders"
              active-class="my-menu-link"
            >
              <q-item-section avatar>
                <q-icon name="mdi-key" />
              </q-item-section>

            <q-item-section> Permisos </q-item-section>
          </q-item>
          <q-item v-if="userStore.hasPermission('admin-roles')"
            :to="{ name: 'Roles' }"
            :active="link === 'Roles'"
            @click="link = 'Roles'"
            clickable
            v-ripple
            class="q-ma-xs rounded-borders"
            active-class="my-menu-link"
          >
            <q-item-section avatar>
              <q-icon name="mdi-account-key" />
            </q-item-section>

            <q-item-section> Roles </q-item-section>
          </q-item>

          <q-item v-if="userStore.hasPermission('admin-usuarios')"
            :to="{ name: 'Usuarios' }"
            :active="link === 'Usuarios'"
            @click="link = 'Usuarios'"
            clickable
            v-ripple
            class="q-ma-xs rounded-borders"
            active-class="my-menu-link"
          >
            <q-item-section avatar>
              <q-icon name="mdi-account" />
            </q-item-section>

            <q-item-section> Usuarios </q-item-section>
          </q-item>

          <q-item v-if="userStore.hasPermission('admin-areas')"
            :to="{ name: 'Areas' }"
            :active="link === 'Areas'"
            @click="link = 'Areas'"
            clickable
            v-ripple
            class="q-ma-xs rounded-borders"
            active-class="my-menu-link"
          >
            <q-item-section avatar>
              <q-icon name="mdi-account" />
            </q-item-section>

            <q-item-section> Areas </q-item-section>
          </q-item>

          <q-item v-if="userStore.hasPermission('admin-busquedas')"
            :to="{ name: 'Busqueda' }"
            :active="link === 'Busqueda'"
            @click="link = 'Busqueda'"
            clickable
            v-ripple
            class="q-ma-xs rounded-borders"
            active-class="my-menu-link"
          >
            <q-item-section avatar>
              <q-icon name="mdi-account" />
            </q-item-section>

            <q-item-section> Busqueda </q-item-section>
          </q-item>

          <q-item v-if="userStore.hasPermission('admin-verificaciones')"
            :to="{ name: 'Verificacion' }"
            :active="link === 'Verificacion'"
            @click="link = 'Verificacion'"
            clickable
            v-ripple
            class="q-ma-xs rounded-borders"
            active-class="my-menu-link"
          >
            <q-item-section avatar>
              <q-icon name="mdi-account" />
            </q-item-section>

            <q-item-section> Verificacion </q-item-section>
          </q-item>

          <!-- /**** panel de Escrituras*********************************************************** */ -->
          <q-expansion-item expand-separator icon="collections_bookmark" label="Panel de escrituras" default-opened header-class="bg-blue-6 text-white" expand-icon-class="text-white" >
            <div class="q-ml-md line-l">
              <q-item v-if="userStore.hasPermission('admin-libro')" clickable v-ripple class="q-ma-xs rounded-borders" active-class="my-menu-link"
                  :to="{ name: 'Libros' }" :active="link === 'Libros'" @click="link = 'Libros'" >
                  <!-- <q-item-section avatar> <q-icon name="mdi-account" /> </q-item-section> -->
                  <q-item-section> Libros  </q-item-section>
              </q-item>
              <q-item v-if="userStore.hasPermission('admin-notario')" clickable v-ripple class="q-ma-xs rounded-borders" active-class="my-menu-link"
                  :to="{ name: 'Notarios' }" :active="link === 'Notarios'" @click="link = 'Notarios'" >
                  <!-- <q-item-section avatar> <q-icon name="mdi-account" /> </q-item-section> -->
                  <q-item-section> Notarios  </q-item-section>
              </q-item>
              <q-item v-if="userStore.hasPermission('admin-subseries')" clickable v-ripple class="q-ma-xs rounded-borders" active-class="my-menu-link"
                  :to="{ name: 'SubSeries' }" :active="link === 'SubSeries'" @click="link = 'SubSeries'" >
                  <!-- <q-item-section avatar> <q-icon name="mdi-account" /> </q-item-section> -->
                  <q-item-section> Subseries  </q-item-section>
              </q-item>
            </div>
          </q-expansion-item>
          <!-- /******** ************************************************************************* */ -->
          <q-separator />
    <!-- Pante de Tablas Anteriores  **************************************************** -->
          <q-expansion-item expand-separator icon="list" label="SA" default-opened header-class="bg-blue-6 text-white" expand-icon-class="text-white" >
            <div class="q-ml-md line-l">
              <q-item v-if="userStore.hasPermission('admin-anteriores')" clickable v-ripple class="q-ma-xs rounded-borders" active-class="my-menu-link"
                  :to="{ name: 'Anteriores' }" :active="link === 'Anteriores'" @click="link = 'Anteriores'" >
                  <!-- <q-item-section avatar> <q-icon name="mdi-account" /> </q-item-section> -->
                  <q-item-section> Anteriores </q-item-section>
              </q-item>
              <q-item v-if="userStore.hasPermission('admin-anteriores2')" clickable v-ripple class="q-ma-xs rounded-borders" active-class="my-menu-link"
                  :to="{ name: 'Anteriores2' }" :active="link === 'Anteriores2'" @click="link = 'Anteriores2'" >
                  <q-item-section> Anteriores2 </q-item-section>
              </q-item>
              <q-item v-if="userStore.hasPermission('admin-sis2018')" clickable v-ripple class="q-ma-xs rounded-borders" active-class="my-menu-link"
                  :to="{ name: 'Sis2018' }" :active="link === 'Sis2018'" @click="link = 'Sis2018'" >
                  <q-item-section> Sis2018 </q-item-section>
              </q-item>
              <q-item v-if="userStore.hasPermission('admin-sis20182')" clickable v-ripple class="q-ma-xs rounded-borders" active-class="my-menu-link"
                  :to="{ name: 'Sis2018_2' }" :active="link === 'Sis2018_2'" @click="link = 'Sis2018_2'">
                  <q-item-section> Sis2018 2 </q-item-section>
              </q-item>
              <q-item v-if="userStore.hasPermission('admin-nuevo')" clickable v-ripple class="q-ma-xs rounded-borders" active-class="my-menu-link"
                  :to="{ name: 'Nuevo' }" :active="link === 'Nuevo'" @click="link = 'Nuevo'" >
                  <q-item-section> Nuevo </q-item-section>
              </q-item>
              <q-item v-if="userStore.hasPermission('admin-nuevo2')" clickable v-ripple class="q-ma-xs rounded-borders" active-class="my-menu-link"
                  :to="{ name: 'Nuevo2' }" :active="link === 'Nuevo2'" @click="link = 'Nuevo2'" >
                  <q-item-section> Nuevo 2 </q-item-section>
              </q-item>
              <q-item v-if="userStore.hasPermission('admin-sia')" clickable v-ripple class="q-ma-xs rounded-borders" active-class="my-menu-link"
                  :to="{ name: 'Sia' }" :active="link === 'Sia'" @click="link = 'Sia'" >
                  <q-item-section> Sia </q-item-section>
              </q-item>
              <q-item v-if="userStore.hasPermission('admin-arbolito')" clickable v-ripple class="q-ma-xs rounded-borders" active-class="my-menu-link"
                  :to="{ name: 'Arbolito' }" :active="link === 'Arbolito'" @click="link = 'Arbolito'" >
                  <q-item-section> Arbolito </q-item-section>
              </q-item>
            </div>
          </q-expansion-item>
    <!-- ********************************************************************* -->
        </q-list>
      </q-scroll-area>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { onMounted, ref } from "vue";
import SwitchDarkMode from "components/SwitchDarkMode.vue";
import { useUserStore } from "src/stores/user-store";
import NabvarUser from "components/NabvarUser.vue";
import { useRoute } from "vue-router";
import { format } from "quasar";
const userStore = useUserStore();

const drawer = ref(false);
const miniState = ref(true);
const route = useRoute();
const link = ref(route.name);
</script>
<style lang="sass">
.my-menu-link
  color: white
  background: $primary

.line-l
  border-left: 1px solid $grey-5
</style>
