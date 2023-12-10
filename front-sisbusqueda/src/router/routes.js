const routes = [
  {
    path: "/login",

    component: () => import("layouts/AuthLayout.vue"),
    children: [
      {
        path: "",
        name: "Login",
        component: () => import("pages/Auth/LoginPage.vue"),
      },
    ],
  },

  {
    path: "/",

    component: () => import("layouts/AdminLayout.vue"),
    meta: { requiresAuth: true },
    children: [
      {
        path: "admin",
        name: "Dash",
        component: () => import("pages/Tramites/TramitesForm.vue"),
      },
      {
        path: "permisos",
        name: "Permisos",
        component: () => import("pages/Admin/Permisos/PermisosList.vue"),
      },
      {
        path: "roles",
        name: "Roles",
        component: () => import("pages/Admin/Roles/RolesList.vue"),
      },
      {
        path: "usuarios",
        name: "Usuarios",
        component: () => import("pages/Admin/Usuarios/UsuariosList.vue"),
      },
      {
        path: "anteriores",
        name: "Anteriores",
        component: () => import("pages/Arp_v1/Anteriores/AnterioresIndex.vue"),
      },
      {
        path: "anteriores2",
        name: "Anteriores2",
        component: () => import("pages/Arp_v1/Anteriores2/Anteriores2Index.vue"),
      },
      {
        path: "arbolito",
        name: "Arbolito",
        component: () => import("pages/Arp_v1/Arbolito/ArbolitoIndex.vue"),
      },
      {
        path: "nuevo",
        name: "Nuevo",
        component: () => import("pages/Arp_v1/Nuevo/NuevoIndex.vue"),
      },
      {
        path: "sia",
        name: "Sia",
        component: () => import("pages/Arp_v1/Sia/SiaIndex.vue"),
      },
      {
        path: "sis2018",
        name: "Sis2018",
        component: () => import("pages/Arp_v1/Sis2018/Sis2018Index.vue"),
      },
      {
        path: "sis2018_2",
        name: "Sis2018_2",
        component: () => import("pages/Arp_v1/Sis2018_2/Sis2018_2Index.vue"),
      },

    ],
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: "/:catchAll(.*)*",
    component: () => import("pages/ErrorNotFound.vue"),
  },
];

export default routes;
