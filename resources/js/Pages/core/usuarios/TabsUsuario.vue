<template>
  <div class="card card-bordered">
    <div class="card-body">
      <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
        <li class="nav-item">
          <a
            class="nav-link active"
            data-bs-toggle="tab"
            href="#kt_tab_pane_1"
            @click="cambiarParametro('todos')"
            >Asistencias</a
          >
        </li>
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_2"
            >Documentos</a
          >
        </li>
        <li class="nav-item">
          <a
            class="nav-link"
            data-bs-toggle="tab"
            href="#kt_tab_pane_3"
            @click="store.marcarTodasLeidas"
          >
            Notificaciones
            <span
              v-if="store.no_visto > 0"
              class="badge badge-primary badge-circle badge-sm"
            >
              {{ store.no_visto }}
            </span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_3"
            >Actividades</a
          >
        </li>
      </ul>

      <div class="tab-content" id="myTabContent">
        <div
          class="tab-pane fade show active"
          id="kt_tab_pane_1"
          role="tabpanel"
        >
          <AsistenciasUsuario />
        </div>
        <div class="tab-pane fade" id="kt_tab_pane_2" role="tabpanel">
          <DocumentacionUsuario />
        </div>
        <div class="tab-pane fade" id="kt_tab_pane_3" role="tabpanel">
          <NotificacionesUsuario />
        </div>
        <div class="tab-pane fade" id="kt_tab_pane_4" role="tabpanel">
          <DocumentacionUsuario />
        </div>
      </div>
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
