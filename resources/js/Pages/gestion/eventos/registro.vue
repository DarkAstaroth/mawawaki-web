<template>
  <div>
    <div v-if="dentroRadio && !redirigiendo">
      <div
        class="alert alert-dismissible bg-light-primary d-flex flex-center flex-column py-10 px-10 px-lg-20 mb-5 w-100"
      >
        <i class="ki-duotone ki-fingerprint-scanning fs-5tx text-primary mb-10">
          <span class="path1"></span>
          <span class="path2"></span>
          <span class="path3"></span>
          <span class="path4"></span>
          <span class="path5"></span>
        </i>

        <div class="text-center w-100">
          <h1 class="fw-bold mb-5">{{ this.evento.nombre }}</h1>
          <div
            class="separator separator-dashed border-primary opacity-25 mb-5"
          ></div>
          <div
            class="d-flex flex-column align-items-start mb-9 text-gray-900 w-100"
          >
            <div>
              <strong>Fecha Inicio :</strong>
              {{ formatearFecha(this.evento.fecha_hora_inicio) }}
            </div>
            <div>
              <strong>Fecha Fin :</strong>
              {{ formatearFecha(this.evento.fecha_hora_fin) }}
            </div>
            <div>
              <strong>Descripción :</strong> {{ this.evento.descripcion }}
            </div>
          </div>

          <div class="d-flex flex-center flex-wrap">
            <button
              type="submit"
              class="btn btn-primary mb-10"
              @click="registrarAsistencia"
            >
              <i class="ki-duotone ki-fingerprint-scanning fs-1">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
                <span class="path4"></span>
                <span class="path5"></span>
              </i>
              Registrar
            </button>
          </div>
        </div>
      </div>
    </div>
    <div v-else-if="!redirigiendo">
      <div class="alert alert-danger d-flex p-5">
        <i class="ki-duotone ki-map fs-2hx text-danger me-4"
          ><span class="path1"></span><span class="path2"></span>
          <span class="path3"></span>
        </i>
        <div class="d-flex flex-column align-items-start">
          <h4 class="text-danger text-start">Estás fuera de rango</h4>
          <p class="text-start">
            Tu ubicación está fuera del rango permitido para el registro
          </p>
        </div>
      </div>
    </div>
    <div v-else>
      <div
        class="alert alert-dismissible bg-light-success d-flex flex-center flex-column py-10 px-10 px-lg-20 mb-5 w-100"
      >
        <i class="ki-duotone ki-fingerprint-scanning fs-5tx text-success mb-10">
          <span class="path1"></span>
          <span class="path2"></span>
          <span class="path3"></span>
          <span class="path4"></span>
          <span class="path5"></span>
        </i>

        <div class="text-center w-100">
          <h1 class="fw-bold mb-5">Registrado</h1>

          <div class="d-flex flex-column flex-center flex-wrap">
            <p>Redirigiendo...</p>
            <ProgressSpinner
              style="width: 30px; height: 30px"
              strokeWidth="4"
              fill="var(--surface-ground)"
              animationDuration=".5s"
              aria-label="Custom ProgressSpinner"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
  <Toast position="top-center" />
</template>
  
  <script>
import { useDataEventos } from "@/store/dataEventos";
import { useToast } from "primevue/usetoast";
import Swal from "sweetalert2";

export default {
  props: ["evento", "usuario", "qr"],
  setup() {
    const store = useDataEventos();
    const toast = useToast();

    return { store, toast };
  },
  data() {
    return {
      dentroRadio: false,
      redirigiendo: false, // Variable para controlar si se está redirigiendo
    };
  },
  mounted() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition((position) => {
        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;

        // Calcular distancia
        const R = 6371e3; // Radio de la Tierra en metros
        const lat1 = (this.evento.latitud * Math.PI) / 180;
        const lat2 = (latitude * Math.PI) / 180;
        const deltaLat = ((latitude - this.evento.latitud) * Math.PI) / 180;
        const deltaLon = ((longitude - this.evento.longitud) * Math.PI) / 180;

        const a =
          Math.sin(deltaLat / 2) * Math.sin(deltaLat / 2) +
          Math.cos(lat1) *
            Math.cos(lat2) *
            Math.sin(deltaLon / 2) *
            Math.sin(deltaLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

        const distancia = R * c; // Distancia en metros

        if (distancia <= 100) {
          this.dentroRadio = true;
        }
      });
    } else {
    }
  },
  methods: {
    registrarAsistencia() {
      this.store
        .registarMarcado(this.usuario, this.evento.id, this.qr)
        .then(() => {
          this.toast.add({
            severity: "success",
            summary: "Registrado!",
            detail: "Tu marcado se a realizado con éxito",
            life: 2000,
          });

          this.redirigiendo = true; // Marcar como redirigiendo antes de la redirección

          setTimeout(() => {
            window.location.href = "/dashboard";
          }, 2000);
        });
    },
    formatearFecha(timestamp) {
      const fechaInicio = new Date(timestamp * 1000); // Multiplica por 1000 para convertir a milisegundos
      return fechaInicio.toLocaleString(); // Formato local de fecha y hora
    },
  },
};
</script>
  

