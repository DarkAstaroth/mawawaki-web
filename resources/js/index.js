import { createRouter, createWebHistory } from "vue-router";
import ClientesIndex from "./Pages/clientes/index.vue";
import ClientesCreate from "./Pages/clientes/create.vue";
import UsuariosIndex from "./Pages/core/usuarios/index.vue";
import ServicioIndex from "./Pages/gestion/servicios/index.vue";
import ServicioDetalle from "./Pages/gestion/servicios/detalle.vue";
import EventosIndex from "./Pages/gestion/eventos/index.vue";
import EventosCreate from "./Pages/gestion/eventos/crear.vue";

const routes = [
    {
        path: "/dashboard/clientes",
        name: "clientes.index",
        component: ClientesIndex,
    },
    {
        path: "/dashboard/eventos",
        name: "dashboard.eventos",
        component: EventosIndex,
    },
    {
        path: "/dashboard/eventos/create",
        name: "dashboard.eventos.create",
        component: EventosCreate,
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
    {
        path: "/dashboard/detalles/servicio/:idPaciente/:idServicio",
        name: "servicios.detalle",
        component: ServicioDetalle,
        props: true,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
