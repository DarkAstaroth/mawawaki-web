<template>
    <div class="row">
        <div class="col-12">
            <!-- Verificar si hay notificaciones -->
            <div v-if="store.notificaciones.length > 0">
                <!-- Iterar sobre las notificaciones si hay alguna -->
                <div v-for="notificacion in store.notificaciones" :key="notificacion.id"
                    class="bg-secondary rounded p-4 mb-2">
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
            <!-- Mostrar la imagen y el texto cuando no hay notificaciones -->
            <div v-else class="d-flex justify-content-center py-10 flex-column align-items-center gap-5">
                <img src="/assets/ilustraciones/sin_mensajes.svg" alt="" width="150" height="150" />
                <span>No hay notificaciones</span>
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
