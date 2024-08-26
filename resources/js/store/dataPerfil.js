import { defineStore } from "pinia";
import axios from "axios";

const eliminarDocumento = async (id) => {
    try {
        const respuesta = await axios.delete(`/api/eliminar/archivo/${id}`);
        return respuesta.data;
    } catch (error) {
        throw new Error(error);
    }
};

export const useDataPerfil = defineStore("dataPerfil", {
    state: () => ({
        usuario: {},
        notificaciones: [],
        documentos: [],
        no_visto: 0,
        cargarFoto: false,
        cargarDocumento: false,
        cargarNotificaciones: false,
    }),
    persist: true,
    actions: {
        estadoDocumento(estado) {
            this.cargarDocumento = estado;
        },
        obtenerUsuario(usuario) {
            console.log(usuario);
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
                .catch((error) => {});
        },
        async obtenerDocumentosUsuario(id) {
            try {
                console.log(id);
                const respuesta = await axios.get(
                    `/api/obtener-documentacion/${id}`
                );
                console.log(respuesta);
                this.documentos = respuesta.data.data;
                this.cargarDocumento = false;
            } catch (error) {
                console.log(error);
                this.cargarDocumento = false;
                throw new Error(error);
            }
        },
        marcarTodasLeidas() {
            axios
                .put(`/api/notificaciones/marcar-leidas/${this.usuario.id}`)
                .then((response) => {
                    this.store.obtenerNotificaciones(1, "", "todos");
                })
                .catch((error) => {});
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
        async verificarDocumento(id) {
            try {
                const respuesta = await axios.put(
                    `/api/documentos/${id}/revision`
                );
                return respuesta;
            } catch (error) {
                throw new Error(Error);
            }
        },
        eliminarDocumento,
    },
});
