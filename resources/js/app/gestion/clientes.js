import { Ziggy } from "@/ziggy";
import route from "ziggy-js";

import { createApp } from "vue/dist/vue.esm-bundler";
import ClientePacientes from "../../Pages/gestion/clientes/pacientes.vue";
import LaravelPermissionToVueJS from "laravel-permission-to-vuejs";
import PrimeVue from "primevue/config";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
import "primevue/resources/themes/lara-light-teal/theme.css";
import "primevue/resources/primevue.min.css";
import "primeicons/primeicons.css";

export const clientesComponent = createApp({
    components: {
        "clientes-pacientes": ClientePacientes,
    },
})
    .use(Ziggy)
    .use(LaravelPermissionToVueJS)
    .use(PrimeVue)
    .use(Toast, {})
    .mixin({ methods: { route } });
