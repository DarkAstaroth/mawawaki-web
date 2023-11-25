import usuarios from "./app/configuraciones/usuarios";
import roles from "./app/configuraciones/roles";
import modulos from "./app/configuraciones/modulos";
import permisos from "./app/configuraciones/permisos";
import caballos from "./app/gestion/caballos";
import eventos from "./app/gestion/eventos";
import pacientes from "./app/gestion/pacientes";
import terapias from "./app/gestion/terapias";
import reportes from "./app/gestion/reportes";
import clientes from "./app/gestion/clientes";

export default {
    ...usuarios,
    ...roles,
    ...modulos,
    ...permisos,
    ...caballos,
    ...eventos,
    ...pacientes,
    ...terapias,
    ...reportes,
    ...clientes,
};
