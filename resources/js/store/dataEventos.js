import { defineStore } from "pinia";
import axios from "axios";

export const useDataEventos = defineStore("dataEventos", {
    state: () => ({
        eventos: [],
        usuarios: [],
    }),
    persist: true,
    actions: {
        async obtenerUsuarios(pagina, busqueda) {
            const url = `/api/usuarios/filtro`;
            await axios
                .get(url)
                .then((response) => {
                    this.usuarios = response.data.usuarios;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
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
            longitud,
            tipoEvento,
            soloIngreso,
            usuariosFiltro
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
                    tipoEvento,
                    soloIngreso,
                    usuariosFiltro,
                })
                .then(() => {
                    return "El evento fue creado correctamente";
                })
                .catch(() => {
                    throw new Error("Error al crear el evento.");
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
