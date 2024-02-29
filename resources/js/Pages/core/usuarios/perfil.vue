<template>
  <div>
    <template v-if="cargando">
      <p>Cargando...</p>
    </template>
    <template v-else>
      <PerfilGlobal />
    </template>
  </div>
</template>
  

<script>
import { useDataPerfil } from "../../../store/dataPerfil";
import VueMultiselect from "vue-multiselect";
import PerfilGlobal from "./perfilGlobal.vue";

export default {
  name: "UsuarioPerfil",
  props: ["usuario"],
  components: { VueMultiselect, PerfilGlobal },

  setup() {
    const store = useDataPerfil();
    return { store };
  },

  data() {
    return {
      usuario: this.usuario,
      enviado: false,
      rolesSeleccionados: this.usuario.roles
        .filter((usuario) => usuario.name !== "invitado")
        .map((usuario) => usuario.name),
      roles: [],
      cargando: true,
      enviado: false,
    };
  },
  validations() {
    return {};
  },

  mounted() {
    if (Object.keys(this.usuario).length > 0) {
      this.store.obtenerUsuario(this.usuario);
      this.cargando = false;
    } else {
      // Si el prop usuario está vacío, establece cargando en false directamente
      this.cargando = false;
    }
  },
};
</script>

