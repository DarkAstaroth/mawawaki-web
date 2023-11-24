import { Ziggy } from "@/ziggy";
import route from "ziggy-js";

import { createApp } from "vue/dist/vue.esm-bundler";
import LaravelPermissionToVueJS from "laravel-permission-to-vuejs";
import PacientesIndex from "../../Pages/gestion/pacientes/index.vue";
import PrimeVue from "primevue/config";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
import "primevue/resources/themes/lara-light-teal/theme.css";
import "primevue/resources/primevue.min.css";
import "primeicons/primeicons.css";

export const pacientesComponent = createApp({
    components: {
        "pacientes-index": PacientesIndex,
    },
})
    .use(Ziggy)
    .use(Toast, {})
    .use(PrimeVue)
    .use(LaravelPermissionToVueJS)
    .mixin({ methods: { route } });
