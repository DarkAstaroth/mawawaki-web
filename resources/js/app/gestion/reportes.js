import { Ziggy } from "@/ziggy";
import route from "ziggy-js";

import { createApp } from "vue/dist/vue.esm-bundler";
import ReportesIndex from "../../Pages/gestion/reportes/index.vue";
import LaravelPermissionToVueJS from "laravel-permission-to-vuejs";

export const reportesComponent = createApp({
    components: {
        "reportes-index": ReportesIndex,
    },
})
    .use(Ziggy)
    .use(LaravelPermissionToVueJS)
    .mixin({ methods: { route } });
