import { Ziggy } from "@/ziggy";
import route from "ziggy-js";

import { createApp } from "vue/dist/vue.esm-bundler";
import LaravelPermissionToVueJS from "laravel-permission-to-vuejs";
import EventosIndex from "../../Pages/gestion/eventos/index.vue";
import EventoDetalle from "../../Pages/gestion/eventos/detalle.vue";
import EventoQR from "../../Pages/gestion/eventos/qr.vue";
import PrimeVue from "primevue/config";
import "primevue/resources/themes/lara-light-teal/theme.css";


export const eventosComponent = createApp({
    components: {
        "eventos-index": EventosIndex,
        "evento-detalle": EventoDetalle,
        "evento-qrs": EventoQR,
    },
})
    .use(Ziggy)
    .use(PrimeVue)
    .use(LaravelPermissionToVueJS)
    .mixin({ methods: { route } });
