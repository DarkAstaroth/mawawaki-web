import { Ziggy } from "@/ziggy";
import route from "ziggy-js";

import { createApp } from "vue/dist/vue.esm-bundler";
import ModuloIndex from "../../Pages/configuraciones/modulos/index.vue";
import LaravelPermissionToVueJS from "laravel-permission-to-vuejs";

export const modulosComponent = createApp({
    components: {
        "modulos-index": ModuloIndex,
    },
})
    .use(Ziggy)
    .use(LaravelPermissionToVueJS)
    .mixin({ methods: { route } });
