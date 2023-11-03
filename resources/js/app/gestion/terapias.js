import { Ziggy } from "@/ziggy";
import route from "ziggy-js";

import { createApp } from "vue/dist/vue.esm-bundler";
import TerapiasIndex from "../../Pages/gestion/terapias/index.vue";
import LaravelPermissionToVueJS from "laravel-permission-to-vuejs";

export const terapiasComponent = createApp({
    components: {
        "terapias-index": TerapiasIndex,
    },
})
    .use(Ziggy)
    .use(LaravelPermissionToVueJS)
    .mixin({ methods: { route } });
