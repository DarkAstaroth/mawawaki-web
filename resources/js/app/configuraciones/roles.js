import { Ziggy } from "@/ziggy";
import route from "ziggy-js";

import { createApp } from "vue/dist/vue.esm-bundler";
import RolesIndex from "../../Pages/configuraciones/roles/index.vue";
import RolesCrear from "../../Pages/configuraciones/roles/crear.vue";
import PermisoRol from "../../Pages/configuraciones/roles/permiso-rol.vue";

export const rolesComponent = createApp({
    components: {
        "roles-index": RolesIndex,
        "roles-crear": RolesCrear,
        "permiso-rol": PermisoRol,
    },
})
    .use(Ziggy)
    .mixin({ methods: { route } });
