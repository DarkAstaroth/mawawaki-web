import { defineStore } from "pinia";
import axios from "axios";

export const useDataEventos = defineStore("dataEventos", {
    state: () => ({
        eventos: [],
        usuarios: [],
        eventoPrincipal: {},
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
                .catch((error) => {});
        },
        cargarEventos(pagina, busqueda) {
            const url = `/api/eventos?page=${pagina}&busqueda=${busqueda}`;
            axios
                .get(url)
                .then((response) => {
                    this.eventos = response.data.eventos;
                })
                .catch((error) => {});
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
        async modificarEvento(
            id,
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
                .put(`/api/evento/${id}`, {
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
                    return "El evento fue modificado correctamente";
                })
                .catch(() => {
                    throw new Error("Error al modificar evento.");
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
        async verificarEvento() {
            try {
                const response = await axios.get("/api/eventos/principal");
                return response; // Devuelve la respuesta si la solicitud es exitosa
            } catch (error) {
                throw new Error("Error al verificar el evento");
            }
        },
        async eventosFiltro() {
            try {
                const response = await axios.get(
                    "/api/eventos/principal-false"
                );
                return response;
            } catch (error) {
                throw new Error("Error al verificar el evento");
            }
        },
        async establecerPrincipal(evento) {
            try {
                const response = await axios.put(
                    "/api/eventos/asignar",
                    evento
                );
                return response;
            } catch (error) {
                throw new Error("Error al establecer el evento principal");
            }
        },
        async obtenerEventoPrincipal() {
            try {
                const response = await axios.get("/api/evento/principal");
                this.eventoPrincipal = response.data;
                return response;
            } catch (error) {
                throw new Error("Error al establecer el evento principal");
            }
        },
        async calcularTotalAsistencias(idUsuario, idEvento) {
            try {
                const response = await axios.post("/api/asistencias/total", {
                    idUsuario,
                    idEvento,
                });

                return response.data;
            } catch (error) {
                throw new Error("Error al establecer el evento principal");
            }
        },
        async asistenciasPrincipalPDF(idUsuario, idEvento) {
            try {
                const respuesta = await axios.post(
                    "/api/pdf/asistencias/principal/usuario",
                    {
                        idUsuario,
                        idEvento,
                    }
                );
                return respuesta;
            } catch (error) {
                throw error;
            }
        },
    },
});
