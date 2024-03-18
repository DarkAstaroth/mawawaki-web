import { defineStore } from "pinia";
import axios from "axios";

const eliminarDocumento = async (id) => {
    try {
        const respuesta = await axios.delete(`/api/eliminar/archivo/${id}`);
        return respuesta.data

    } catch (error) {
        throw new Error(error);
    }
}

export const useDataPerfil = defineStore("dataPerfil", {
    state: () => ({
        usuario: {},
        notificaciones: [],
        documentos: [],
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
                    this.notificaciones = response.data.notificaciones;
                    this.no_visto = response.data.no_visto;
                })
                .catch((error) => { });
        },
        async obtenerDocumentosUsuario(id) {
            try {
                const respuesta = await axios.get(`/api/obtener-documentacion/${id}`)
                this.documentos = respuesta.data.data
            } catch (error) {
                throw new Error(error)
            }
        },
        marcarTodasLeidas() {
            axios
                .put(`/api/notificaciones/marcar-leidas/${this.usuario.id}`)
                .then((response) => {
                    this.store.obtenerNotificaciones(1, "", "todos");
                })
                .catch((error) => { });
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
        eliminarDocumento
    },
});
