import { Ziggy } from "@/ziggy";
import route from "ziggy-js";

import { createApp } from "vue/dist/vue.esm-bundler";
import CaballosIndex from "../../Pages/gestion/caballos/index.vue";
import CaballosCreate from "../../Pages/gestion/caballos/create.vue";
import LaravelPermissionToVueJS from "laravel-permission-to-vuejs";
import PrimeVue from "primevue/config";
import "primevue/resources/themes/lara-light-teal/theme.css";

export const caballosComponent = createApp({
    components: {
        "caballos-index": CaballosIndex,
        "caballos-create": CaballosCreate,
        "caballos-edit": CaballosCreate,
    },
})
    .use(Ziggy)
    .use(LaravelPermissionToVueJS)
    .use(PrimeVue)
    .mixin({ methods: { route } });
