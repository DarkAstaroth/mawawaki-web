import { defineStore } from "pinia";
import axios from "axios";

export const useDataUsuarios = defineStore("dataUsuarios", {
    state: () => ({
        ficha: {
            activos: 0,
            no_activos: 0,
            solicitudes: 0,
            verificados: 0,
            por_verificar: 0,
        },
        fichaActividad: {
            total: 0,
            verificados: 0,
            destacados: 0,
        },
        usuarios: [],
        actividades: [],
        mensaje: { success: "", error: "", warning: "" },
        paginacion: {
            actividades: {
                total: 0,
                porPagina: 10,
                paginaActual: 1,
                ultimaPagina: 1,
                desde: 0,
                hasta: 0,
            },
        },
    }),
    persist: true,
    mutations: {
        SET_MENSAJE(state, { tipo, mensaje }) {
            state.mensaje[tipo] = mensaje;
        },
    },
    actions: {
        cargarFichasUsuario(rol) {
            let url = "";
            if (rol) {
                url = "/api/fichasUsuarios?rol=cliente";
            } else {
                url = "/api/fichasUsuarios";
            }
            axios
                .get(url)
                .then((response) => {
                    this.ficha.total = response.data.total_usuarios;
                    this.ficha.activos = response.data.activos;
                    this.ficha.no_activos = response.data.no_activos;
                    this.ficha.solicitudes = response.data.solicitudes;
                    this.ficha.verificados = response.data.verificados;
                    this.ficha.por_verificar = response.data.por_verificar;
                })
                .catch((error) => {});
        },
        cargarFichasActividad(id) {
            const url = `/api/fichas/actividad/usuario/${id}`;
            axios
                .get(url)
                .then((response) => {
                    console.log(response.data.fichaActividad);
                    this.fichaActividad = response.data.fichaActividad;
                })
                .catch((error) => {});
        },
        async cargarUsuarios(pagina, busqueda, parametro) {
            const respuesta = await axios.get("/api/usuarios", {
                params: {
                    page: pagina,
                    busqueda: busqueda,
                    parametro: parametro,
                },
            });
            console.log(respuesta);
            this.usuarios = respuesta.data.usuarios;

            return respuesta.data.paginacion;
        },
        async cargarClientes(pagina, busqueda, parametro) {
            const respuesta = await axios.get("/api/usuarios", {
                params: {
                    page: pagina,
                    busqueda: busqueda,
                    parametro: parametro,
                    rol: "cliente",
                },
            });
            this.usuarios = respuesta.data.usuarios;

            return respuesta.data.paginacion;
        },
        async crearUsuario(data) {
            try {
                const respuesta = axios.post(`/api/registrar/usuario`, data);
                return respuesta;
            } catch (error) {
                return error;
            }
        },
        async actualizarEstado(id, estado) {
            try {
                const respuesta = axios.put(`/api/estado/usuario/${id}`, {
                    estado: estado,
                });
                return respuesta;
            } catch (error) {
                return error;
            }
        },
        async mandarNotificacion(id, tipo, notificacion) {
            try {
                const respuesta = axios.post(`/api/enviar-notificacion/${id}`, {
                    tipo,
                    titulo: notificacion.titulo,
                    mensaje: notificacion.mensaje,
                });
                return respuesta;
            } catch (error) {
                return error;
            }
        },
        async cargarActividades(pagina, busqueda, parametro, id) {
            try {
                const respuesta = await axios.get(
                    `/api/actividades/usuario/${id}`,
                    {
                        params: {
                            page: pagina,
                            busqueda: busqueda,
                            parametro: parametro,
                        },
                    }
                );
                this.paginacion.actividades = respuesta.data.paginacion;
                this.actividades = respuesta.data.actividades;
            } catch (error) {
                return error;
            }
        },
        async registrarActividad(id, actividad) {
            try {
                await axios.post(`/api/registrar/actividad/usuario/${id}`, {
                    titulo: actividad.titulo,
                    descripcion: actividad.descripcion,
                    fecha: actividad.fecha,
                });
                return "Actividad registrada con éxito";
            } catch (error) {
                throw error;
            }
        },
        async verificarActividad(id) {
            try {
                const respuesta = await axios.patch(
                    `/api/verificar/actividad/${id}`
                );
                return respuesta.data;
            } catch (error) {
                throw error;
            }
        },
        async destacarActividad(id) {
            try {
                const respuesta = await axios.patch(
                    `/api/destacar/actividad/${id}`
                );
                return respuesta.data;
            } catch (error) {
                throw error;
            }
        },
        async eliminarActividad(id) {
            try {
                const respuesta = await axios.delete(
                    `/api/eliminar/actividad/${id}`
                );

                return respuesta.data;
            } catch (error) {
                throw error;
            }
        },
        async usuariosPDF() {
            try {
                const respuesta = await axios.get("/api/pdf/usuarios");
                return respuesta;
            } catch (error) {
                throw error;
            }
        },
        asignarMensaje(tipo, mensaje) {
            if (["success", "error", "warning"].includes(tipo)) {
                this.mensaje[tipo] = mensaje;
            } else {
            }
        },
        limpiarMensaje({ commit }, tipo) {
            if (["success", "error", "warning"].includes(tipo)) {
                commit("SET_MENSAJE", { tipo, mensaje: "" });
            } else {
            }
        },
    },
});
