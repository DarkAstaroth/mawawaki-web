import { createRouter, createWebHistory } from "vue-router";
import ClientesIndex from "./Pages/clientes/index.vue";
import ClientesCreate from "./Pages/clientes/create.vue";

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
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
