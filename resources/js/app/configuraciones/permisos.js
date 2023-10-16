import { Ziggy } from "@/ziggy";
import route from "ziggy-js";

import { createApp } from "vue/dist/vue.esm-bundler";
import PermisosIndex from "../../Pages/configuraciones/permisos/index.vue";
import LaravelPermissionToVueJS from "laravel-permission-to-vuejs";

export const permisosComponent = createApp({
    components: {
        "permisos-index": PermisosIndex,
    },
})
    .use(Ziggy)
    .use(LaravelPermissionToVueJS)
    .mixin({ methods: { route } });
