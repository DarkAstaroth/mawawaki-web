<template>
  <div class="row">
    <div class="col-12">
      <div
        v-for="notificacion in store.notificaciones"
        :key="notificacion.id"
        class="bg-secondary rounded p-4 mb-2"
      >
        <div class="d-flex justify-content-between">
          <h4>{{ notificacion.titulo }}</h4>
          <span :class="badgeClass(notificacion.tipo)">{{
            badgeText(notificacion.tipo)
          }}</span>
        </div>
        <div class="mb-5">{{ notificacion.mensaje }}</div>
        <div>Fecha de creación: {{ formatFecha(notificacion.created_at) }}</div>
      </div>
    </div>
  </div>
</template>

<script>
import { useToast } from "vue-toastification";
import { useDataPerfil } from "../../../../store/dataPerfil";

export default {
  name: "NotificacionesUsuario",
  setup() {
    const toast = useToast();
    const store = useDataPerfil();

    const badgeClass = (tipo) => {
      switch (tipo) {
        case "success":
          return "badge badge-success";
        case "warning":
          return "badge badge-warning";
        case "error":
          return "badge badge-danger";
        case "info":
          return "badge badge-info";
        default:
          return "badge badge-info";
      }
    };

    const badgeText = (tipo) => {
      switch (tipo) {
        case "success":
          return "Confirmacion";
        case "warning":
          return "Alerta";
        case "error":
          return "Error";
        case "info":
          return "Informativo";
        default:
          return "Informativo";
      }
    };

    const formatFecha = (fecha) => {
      return new Date(fecha).toLocaleString();
    };

    return { store, toast, badgeClass, badgeText, formatFecha };
  },
  data() {
    return {
      busqueda: "",
      parametros: "todos",
    };
  },
  mounted() {
    this.store.obtenerNotificaciones(1, this.busqueda, this.parametros);
  },
  methods: {},
};
</script>
