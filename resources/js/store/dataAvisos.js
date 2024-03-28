import { defineStore } from "pinia";
import axios from "axios";

const obtenerAvisos = async (pagina, busqueda) => {
    try {
        const respuesta = await axios.get(
            `/api/avisos?page=${pagina}&busqueda=${busqueda}`
        );
        console.log(respuesta);
        return respuesta.data;
    } catch (error) {
        throw new Error(error);
    }
};

const obtenerAvisosGlobales = async () => {
    try {
        const respuesta = await axios.get("/api/avisos/globales");
        return respuesta.data;
    } catch (error) {
        throw new Error(error);
    }
};

const eliminarAviso = async (id) => {
    try {
        const respuesta = await axios.delete(`/api/avisos/${id}`);
        return respuesta.data;
    } catch (error) {
        throw new Error(error);
    }
};

export const useDataAvisos = defineStore("dataAvisos", {
    state: () => ({
        avisos: [],
        paginacion: {
            total: 0,
            porPagina: 10,
            paginaActual: 1,
            ultimaPagina: 1,
            desde: 0,
            hasta: 0,
        },
    }),
    persist: true,
    actions: {
        obtenerAvisosGlobales,
        async cargarAvisos(pagina, busqueda) {
            const respuesta = await obtenerAvisos(pagina, busqueda);
            this.avisos = respuesta.avisos.data;
            this.paginacion = respuesta.paginacion;
        },

        async eliminarAviso(id) {
            const respuesta = await eliminarAviso(id);
            return respuesta;
        },
    },
});
