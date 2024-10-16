import UsuariosIndex from "../../Pages/core/usuarios/index.vue";
import UsuarioSetup from "../../Pages/core/usuarios/setup.vue";
import UsuarioPerfil from "../../Pages/core/usuarios/perfil.vue";
import UsuarioControl from "../../Pages/core/usuarios/control.vue";
import UsuarioActividades from "../../Pages/core/usuarios/actividades.vue";
import ConfigGeneral from "../../Pages/configuraciones/general/index.vue";
import DashboardEventos from "../../Pages/Dashboard.vue";
import DashboardAvisos from "../../Pages/dashboard/avisos.vue";
import ClienteIndex from "../../Pages/clientes/index.vue";
import ClienteCreate from "../../Pages/clientes/create.vue";

export default {
    "usuarios-index": UsuariosIndex,
    "usuarios-setup": UsuarioSetup,
    "usuario-perfil": UsuarioPerfil,
    "usuario-control": UsuarioControl,
    "usuario-actividades": UsuarioActividades,
    "config-general": ConfigGeneral,
    "dashboard-eventos": DashboardEventos,
    "dashboard-avisos": DashboardAvisos,
    "clientes-index": ClienteIndex,
    "clientes-create": ClienteCreate,
};
