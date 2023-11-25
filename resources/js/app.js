// import { rolesComponent } from "./app/configuraciones/roles";
// import { modulosComponent } from "./app/configuraciones/modulos";
// import { permisosComponent } from "./app/configuraciones/permisos";
// import { usuariosComponent } from "./app/configuraciones/usuarios";
// import { caballosComponent } from "./app/gestion/caballos";
// import { eventosComponent } from "./app/gestion/eventos";
// import { pacientesComponent } from "./app/gestion/pacientes";
// import { terapiasComponent } from "./app/gestion/terapias";
// import { reportesComponent } from "./app/gestion/reportes";
// import { clientesComponent } from "./app/gestion/clientes";

// function mountComponent(component, elementId) {
//     const element = document.getElementById(elementId);
//     if (element) {
//         component.mount(`#${elementId}`);
//     }
// }

// // Monta componentes solo si los elementos HTML existen en la página
// mountComponent(rolesComponent, "roles-component");
// mountComponent(modulosComponent, "modulos-component");
// mountComponent(permisosComponent, "permisos-component");
// mountComponent(usuariosComponent, "usuarios-component");
// mountComponent(caballosComponent, "caballos-component");
// mountComponent(eventosComponent, "eventos-component");
// mountComponent(pacientesComponent, "pacientes-component");
// mountComponent(terapiasComponent, "terapias-component");
// mountComponent(reportesComponent, "reportes-component");
// mountComponent(clientesComponent, "clientes-component");

import { Ziggy } from "@/ziggy";
import route from "ziggy-js";

import { createApp } from "vue/dist/vue.esm-bundler";
// import UsuariosIndex from "../js/Pages/core/usuarios/index.vue";
// import UsuarioSetup from "../../Pages/core/usuarios/setup.vue";
// import UsuarioPerfil from "../../Pages/core/usuarios/perfil.vue";
// import UsuarioControl from "../../Pages/core/usuarios/control.vue";
// import UsuarioNotificacionMenu from "../../Pages/core/usuarios/notificacionMenu.vue";

import LaravelPermissionToVueJS from "laravel-permission-to-vuejs";
import PrimeVue from "primevue/config";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
import "primevue/resources/themes/lara-light-teal/theme.css";
import "primevue/resources/primevue.min.css";
import "primeicons/primeicons.css";
import usuarios from './app/configuraciones/usuarios'


export const usuariosComponent = createApp({
    components: {
        ...usuarios
        // "usuarios-index": UsuariosIndex,
        // "usuarios-setup": UsuarioSetup,
        // "usuario-perfil": UsuarioPerfil,
        // "usuario-control": UsuarioControl,
        // "usuario-notificacion": UsuarioNotificacionMenu,
    },
})
    .use(Ziggy)
    .use(LaravelPermissionToVueJS)
    .use(PrimeVue)
    .use(Toast, {})
    .mixin({ methods: { route } })
    .mount("#app");
