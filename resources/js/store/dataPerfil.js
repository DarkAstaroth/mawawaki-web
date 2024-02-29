import { defineStore } from "pinia";
import axios from "axios";

export const useDataPerfil = defineStore("dataPerfil", {
    state: () => ({
        usuario: {},
        notificaciones: [],
        no_visto: 0,
    }),
    persist: true,
    actions: {
        obtenerUsuario(usuario) {
            this.usuario = usuario;
        },
        obtenerNotificaciones(pagina, busqueda, parametro) {
            axios
                .get(`/api/notificaciones/usuario/${this.usuario.id}`, {
                    params: {
                        page: pagina,
                        busqueda,
                        parametro,
                    },
                })
                .then((response) => {
                    console.log(response);
                    this.notificaciones = response.data.notificaciones;
                    this.no_visto = response.data.no_visto;
                })
                .catch((error) => {});
        },
        marcarTodasLeidas() {
            axios
                .put(`/api/notificaciones/marcar-leidas/${this.usuario.id}`)
                .then((response) => {
                    this.store.obtenerNotificaciones(1, "", "todos");
                })
                .catch((error) => {
                    console.error(
                        "Error al marcar notificaciones como leídas:",
                        error
                    );
                });
        },
        async modificarDatos(usuario) {
            try {
                const respuesta = await axios.put(
                    `/api/usuario/${this.usuario.id}`,
                    usuario
                );
                return respuesta;
            } catch (error) {
                throw error;
            }
        },
    },
});
