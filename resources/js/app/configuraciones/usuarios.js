import { Ziggy } from "@/ziggy";
import route from "ziggy-js";

import { createApp } from "vue/dist/vue.esm-bundler";
import UsuariosIndex from "../../Pages/core/usuarios/index.vue";
import UsuarioSetup from "../../Pages/core/usuarios/setup.vue";
import UsuarioPerfil from "../../Pages/core/usuarios/perfil.vue";
import UsuarioControl from "../../Pages/core/usuarios/control.vue";
import LaravelPermissionToVueJS from "laravel-permission-to-vuejs";

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
    .mixin({ methods: { route } });
