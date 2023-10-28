import { rolesComponent } from "./app/configuraciones/roles";
import { modulosComponent } from "./app/configuraciones/modulos";
import { permisosComponent } from "./app/configuraciones/permisos";
import { usuariosComponent } from "./app/configuraciones/usuarios";
import { caballosComponent } from "./app/gestion/caballos";
import { eventosComponent } from "./app/gestion/eventos";

rolesComponent.mount("#roles-component");
modulosComponent.mount("#modulos-component");
permisosComponent.mount("#permisos-component");
usuariosComponent.mount("#usuarios-component");
caballosComponent.mount("#caballos-component");
eventosComponent.mount("#eventos-component");
