import { Ziggy } from "@/ziggy";
import route from "ziggy-js";

import { createApp } from "vue/dist/vue.esm-bundler";
import UsuariosIndex from "../../Pages/core/usuarios/index.vue";
import UsuarioSetup from "../../Pages/core/usuarios/setup.vue";
import UsuarioPerfil from "../../Pages/core/usuarios/perfil.vue";
import UsuarioControl from "../../Pages/core/usuarios/control.vue";
import LaravelPermissionToVueJS from "laravel-permission-to-vuejs";
import PrimeVue from "primevue/config";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
import "primevue/resources/themes/lara-light-teal/theme.css";
import "primevue/resources/primevue.min.css";
import "primeicons/primeicons.css";

export const usuariosComponent = createApp({
    components: {
        "usuarios-index": UsuariosIndex,
        "usuarios-setup": UsuarioSetup,
        "usuario-perfil": UsuarioPerfil,
        "usuario-control": UsuarioControl,
    },
})
    .use(Ziggy)
    .use(LaravelPermissionToVueJS)
    .use(PrimeVue)
    .use(Toast, {})
    .mixin({ methods: { route } });
