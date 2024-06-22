<template>
    <div>
        <div v-if="dentroRadio && !redirigiendo">
            <div class="w-full mt-5">
                <div
                    class="mb-4 p-5 text-sm text-emerald-800 rounded-lg bg-emerald-50 dark:bg-gray-800 dark:text-emerald-400 divide-y"
                    role="alert"
                >
                    <div class="flex flex-col mb-2">
                        <span class="font-bold">Evento:</span>
                        <span class=""> {{ evento.nombre }}</span>
                    </div>

                    <div class="flex flex-col mb-2">
                        <span class="font-bold">Fecha Inicio:</span>
                        <span class="">
                            {{ formatearFecha(evento.fecha_hora_inicio) }}
                        </span>
                    </div>

                    <div class="flex flex-col mb-2">
                        <span class="font-bold">Fecha Fin:</span>
                        <span class="">
                            {{ formatearFecha(evento.fecha_hora_fin) }}
                        </span>
                    </div>

                    <div class="flex flex-col mb-2">
                        <span class="font-bold">Descripción:</span>
                        <span class=""> {{ evento.descripcion }}</span>
                    </div>
                    <div class="flex justify-center pt-5">
                        <button
                            type="submit"
                            class="bg-emerald-500 hover:bg-emerald-600 focus:bg-emerald-400 text-white rounded-lg px-4 py-3 font-urbanist font-bold"
                            @click="registrarAsistencia"
                        >
                            <div class="flex items-center gap-2">
                                <i class="fi fi-rr-fingerprint text-xl"></i>
                                <span class="">Registrar</span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div v-else-if="!redirigiendo">
            <div
                class="mt-5 p-5 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-emerald-400 divide-y"
                role="alert"
            >
                <div class="flex flex-col items-center">
                    <i class="fi fi-br-location-dot-slash text-4xl mb-5"></i>
                    <h4 class="text-red-800 font-bold mb-2">
                        Estás fuera de rango
                    </h4>
                    <p class="text-center">
                        Tu ubicación está fuera del rango permitido para el
                        registro
                    </p>
                </div>
            </div>
        </div>
        <div v-else>
            <div
                class="flex flex-col items-center mb-4 p-5 text-sm text-emerald-800 rounded-lg bg-emerald-50 dark:bg-gray-800 dark:text-emerald-400"
                role="alert"
            >
                <i class="fi fi-sr-check-circle text-4xl mb-5"></i>
                <h1 class="fw-bold mb-5 font-bold">Registrado</h1>
                <div class="text-center w-100">
                    <div class="d-flex flex-column flex-center flex-wrap">
                        <p class="mb-5">Redirigiendo...</p>
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
            redirigiendo: false,
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
                const deltaLat =
                    ((latitude - this.evento.latitud) * Math.PI) / 180;
                const deltaLon =
                    ((longitude - this.evento.longitud) * Math.PI) / 180;

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
