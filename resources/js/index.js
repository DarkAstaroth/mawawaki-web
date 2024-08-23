import { createRouter, createWebHistory } from "vue-router";
import ClientesIndex from "./Pages/clientes/index.vue";
import ClientesCreate from "./Pages/clientes/create.vue";
import UsuariosIndex from "./Pages/core/usuarios/index.vue";
import ServicioIndex from "./Pages/gestion/servicios/index.vue";

const routes = [
    {
        path: "/dashboard/clientes",
        name: "clientes.index",
        component: ClientesIndex,
    },
    {
        path: "/dashboard/clientes/create",
        name: "clientes.create",
        component: ClientesCreate,
    },
    {
        path: "/dashboard/usuarios",
        name: "usuarios.index",
        component: UsuariosIndex,
    },
    {
        path: "/dashboard/servicio/paciente/:id",
        name: "servicios.paciente",
        component: ServicioIndex,
        props: true,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
