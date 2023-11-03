import { Ziggy } from "@/ziggy";
import route from "ziggy-js";

import { createApp } from "vue/dist/vue.esm-bundler";
import LaravelPermissionToVueJS from "laravel-permission-to-vuejs";
import PacientesIndex from "../../Pages/gestion/pacientes/index.vue";

export const pacientesComponent = createApp({
    components: {
        "pacientes-index": PacientesIndex,
    },
})
    .use(Ziggy)
    .use(LaravelPermissionToVueJS)
    .mixin({ methods: { route } });
