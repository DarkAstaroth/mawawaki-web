import { createApp } from 'vue/dist/vue.esm-bundler';
import RolesIndex from './Pages/configuraciones/roles/index.vue';
import RolesCrear from './Pages/configuraciones/roles/crear.vue';

const rolesComponent = createApp({
    components: {
        'roles-index': RolesIndex,
        'roles-crear': RolesCrear,
    },
},);
rolesComponent.mount('#roles-component');