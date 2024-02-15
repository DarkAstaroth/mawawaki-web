import { defineStore } from "pinia";
import axios from "axios";

export const useDataEventos = defineStore("dataEventos", {
    state: () => ({
        eventos: [],
    }),
    persist: true,
    actions: {
        cargarEventos(pagina, busqueda) {
            const url = `/api/eventos?page=${pagina}&busqueda=${busqueda}`;
            axios
                .get(url)
                .then((response) => {
                    this.eventos = response.data.eventos;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        async crearEvento(
            nombre,
            fechaInicio,
            fechaFin,
            lugar,
            descripcion,
            latitud,
            longitud
        ) {
            await axios
                .post("/api/evento", {
                    nombre,
                    fechaInicio,
                    fechaFin,
                    lugar,
                    descripcion,
                    latitud,
                    longitud,
                })
                .then(() => {
                    return "El evento fue creado correctamente";
                });
        },
        async registarMarcado(usuario, evento, qr) {
            axios
                .post("/api/asistencia/registrar", {
                    usuario,
                    evento,
                    qr,
                })
                .then((response) => {
                    return "Marcado registrado correctamente";
                })
                .catch((error) => {
                    return "Error al realizar el marcado";
                });
        },
    },
});
