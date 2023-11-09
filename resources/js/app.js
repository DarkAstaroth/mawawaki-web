import { rolesComponent } from "./app/configuraciones/roles";
import { modulosComponent } from "./app/configuraciones/modulos";
import { permisosComponent } from "./app/configuraciones/permisos";
import { usuariosComponent } from "./app/configuraciones/usuarios";
import { caballosComponent } from "./app/gestion/caballos";
import { eventosComponent } from "./app/gestion/eventos";
import { pacientesComponent } from "./app/gestion/pacientes";
import { terapiasComponent } from "./app/gestion/terapias";
import { reportesComponent } from "./app/gestion/reportes";

function mountComponent(component, elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        component.mount(`#${elementId}`);
    }
}

// Monta componentes solo si los elementos HTML existen en la página
mountComponent(rolesComponent, "roles-component");
mountComponent(modulosComponent, "modulos-component");
mountComponent(permisosComponent, "permisos-component");
mountComponent(usuariosComponent, "usuarios-component");
mountComponent(caballosComponent, "caballos-component");
mountComponent(eventosComponent, "eventos-component");
mountComponent(pacientesComponent, "pacientes-component");
mountComponent(terapiasComponent, "terapias-component");
mountComponent(reportesComponent, "reportes-component");
