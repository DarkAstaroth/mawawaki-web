import { Ziggy } from "@/ziggy";
import route from "ziggy-js";

import { createApp } from "vue/dist/vue.esm-bundler";
import UsuariosIndex from "../../Pages/core/usuarios/index.vue";

export const usuariosComponent = createApp({
    components: {
        "usuarios-index": UsuariosIndex,
    },
})
    .use(Ziggy)
    .mixin({ methods: { route } });
