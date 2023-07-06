import { createApp } from "vue/dist/vue.esm-bundler";
import PermisosIndex from '../../Pages/configuraciones/permisos/index.vue'

export const permisosComponent = createApp({
    components: {
        "permisos-index": PermisosIndex
    }
})