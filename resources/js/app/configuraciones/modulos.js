import { createApp } from "vue/dist/vue.esm-bundler";
import ModuloIndex from '../../Pages/configuraciones/modulos/index.vue'

export const modulosComponent = createApp({
    components: {
        "modulos-index": ModuloIndex,

    },
});