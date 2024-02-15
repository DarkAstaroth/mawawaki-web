<template>
  <div>
    <div v-if="dentroRadio && !redirigiendo">
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
    <div v-else-if="!redirigiendo">
      <div class="alert alert-danger d-flex p-5">
        <i class="ki-duotone ki-map fs-2hx text-danger me-4"
          ><span class="path1"></span><span class="path2"></span>
          <span class="path3"></span>
        </i>
        <div class="d-flex flex-column align-items-start">
          <h4 class="text-danger text-start">Estás fuera de rango</h4>
          <p>Tu ubicación está fuera del rango permitido para el registro</p>
        </div>
      </div>
    </div>
    <div v-else>
      <p>Redirigiendo...</p>
    </div>
  </div>
  <Toast />
</template>
  
  <script>
import { useDataEventos } from "@/store/dataEventos";
import { useToast } from "primevue/usetoast";

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
    console.log(this.evento.latitud);
    console.log(this.evento.longitud);
    console.log(this.usuario, "usuario");
    console.log(this.qr, "qr");
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition((position) => {
        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;
        console.log(`Latitude: ${latitude}, Longitude: ${longitude}`);

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
        console.log(`Distancia al evento: ${distancia} metros`);

        if (distancia <= 100) {
          this.dentroRadio = true;
        }
      });
    } else {
      console.log("Geolocation is not supported by this browser.");
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
            detail: "Tu marcado se a realizado con exito",
            life: 2000,
          });

          this.redirigiendo = true; // Marcar como redirigiendo antes de la redirección

          setTimeout(() => {
            window.location.href = "/dashboard";
          }, 2000);
        });
    },
  },
};
</script>
  

