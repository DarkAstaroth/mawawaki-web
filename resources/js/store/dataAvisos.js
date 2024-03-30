import { defineStore } from "pinia";
import axios from "axios";

const crearAviso = async (data) => {
    console.log(data);
    try {
        const formData = new FormData();
        // Agrega los datos del aviso al FormData
        Object.keys(data).forEach((key) => {
            if (key === "archivo") {
                // Si es el archivo, lo agrega al FormData
                formData.append(key, data[key]);
            } else {
                // Para otros datos, simplemente los agrega
                formData.append(key, data[key]);
            }
        });
        console.log(formData);

        const respuesta = await axios.post("/api/aviso/crear", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });
        return respuesta.data;
    } catch (error) {
        throw new Error(error);
    }
};

const obtenerAvisos = async (pagina, busqueda) => {
    try {
        const respuesta = await axios.get(
            `/api/avisos?page=${pagina}&busqueda=${busqueda}`
        );
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
        async crearAviso(data) {
            try {
                const respuesta = await crearAviso(data);
                return respuesta;
            } catch (error) {
                throw new Error(error);
            }
        },
    },
});
