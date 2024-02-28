<template>
  <div class="card card-bordered">
    <div class="card-body">
      <TabView :scrollable="true">
        <TabPanel header="Datos personales" @click="cambiarParametro('todos')">
          <AsistenciasUsuario />
        </TabPanel>
        <TabPanel header="Asistencias" @click="cambiarParametro('todos')">
          <AsistenciasUsuario />
        </TabPanel>
        <TabPanel header="Eventos" @click="cambiarParametro('todos')">
          <AsistenciasUsuario />
        </TabPanel>
        <TabPanel header="Documentos">
          <DocumentacionUsuario />
        </TabPanel>
        <TabPanel header="Notificaciones" @click="store.marcarTodasLeidas">
          <NotificacionesUsuario />
        </TabPanel>
      </TabView>
    </div>
  </div>
</template>

<script>
import { useDataPerfil } from "../../../store/dataPerfil";
import VueMultiselect from "vue-multiselect";
import AsistenciasUsuario from "./Tabs/Asistencias.vue";
import DocumentacionUsuario from "./Tabs/Documentacion.vue";
import NotificacionesUsuario from "./Tabs/Notificaciones.vue";

export default {
  name: "TabsUsuario",
  components: {
    VueMultiselect,
    AsistenciasUsuario,
    DocumentacionUsuario,
    NotificacionesUsuario,
  },

  setup() {
    const store = useDataPerfil();
    return { store };
  },

  data() {
    return {
      asistencias: [],
    };
  },
  validations() {
    return {};
  },
  mounted() {
    // this.store.obtenerNotificaciones();
  },
  methods: {},
};
</script>


<style src="vue-multiselect/dist/vue-multiselect.css"></style>
