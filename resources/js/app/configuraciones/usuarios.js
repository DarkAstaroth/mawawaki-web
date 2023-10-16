import { Ziggy } from "@/ziggy";
import route from "ziggy-js";

import { createApp } from "vue/dist/vue.esm-bundler";
import UsuariosIndex from "../../Pages/core/usuarios/index.vue";
import UsuarioPerfil from "../../Pages/core/usuarios/perfil.vue";
import LaravelPermissionToVueJS from "laravel-permission-to-vuejs";

export const usuariosComponent = createApp({
    components: {
        "usuarios-index": UsuariosIndex,
        "usuario-perfil": UsuarioPerfil,
    },
})
    .use(Ziggy)
    .use(LaravelPermissionToVueJS)
    .mixin({ methods: { route } });
