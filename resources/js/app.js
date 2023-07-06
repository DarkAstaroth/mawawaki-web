import { createApp } from "vue/dist/vue.esm-bundler";
import RolesIndex from "./Pages/configuraciones/roles/index.vue";
import RolesCrear from "./Pages/configuraciones/roles/crear.vue";

import ModuloIndex from './Pages/configuraciones/modulos/index.vue'

const rolesComponent = createApp({
    components: {
        "roles-index": RolesIndex,
        "roles-crear": RolesCrear,
    },
});

const modulosComponent = createApp({
    components: {
        "modulos-index": ModuloIndex,

    },
});
rolesComponent.mount("#roles-component");
modulosComponent.mount('#modulos-component')