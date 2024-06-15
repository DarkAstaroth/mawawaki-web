import { defineStore } from "pinia";
import axios from "axios";

const registrarAsistencias = async (
    UsuarioID,
    EventoID,
    fecha_hora_entrada,
    fecha_hora_salida
) => {
    try {
        const respuesta = await axios.post(
            "/api/asistencia/solicitar/registro",
            {
                UsuarioID,
                EventoID,
                fecha_hora_entrada,
                fecha_hora_salida,
            }
        );

        return respuesta;
    } catch (error) {
        throw new Error(error);
    }
};

const eliminarAsistencia = async (id) => {
    try {
        const respuesta = await axios.delete(`/api/asistencia/${id}`);
        return respuesta.data;
    } catch (error) {
        throw new Error(error);
    }
};

const verificarAsistencia = async (id) => {
    try {
        const respuesta = await axios.post(`/api/asistencias/verificar/${id}`);
        return respuesta.data;
    } catch (error) {
        throw new Error(error);
    }
};

const modificarSalida = async (id) => {
    try {
        const respuesta = await axios.post(
            `/api/asistencias/modificar/salida/${id}`
        );
        return respuesta.data;
    } catch (error) {
        throw new Error(error);
    }
};

export const useDataAsistencias = defineStore("dataAsistencias", {
    state: () => ({
        asistencias: [],
        paginacion: {
            total: 0,
            porPagina: 10,
            paginaActual: 1,
            ultimaPagina: 1,
            desde: 0,
            hasta: 0,
        },
        total: {},
    }),
    persist: true,
    actions: {
        async modificarSalida(id, horaSalida) {
            try {
                const respuesta = await axios.put(
                    `/api/asistencias/modificar/salida/${id}`,
                    {
                        fecha_hora_salida: `${horaSalida.hours}:${horaSalida.minutes}:${horaSalida.seconds}`,
                    }
                );
                return respuesta;
            } catch (error) {
                throw new Error(error);
            }
        },
        registrarAsistencias,
        eliminarAsistencia,
        verificarAsistencia,
        async registrarAsistencia(evento, codigoQR) {
            try {
                const respuesta = await axios.post(
                    `/api/asistencia/registro/qr`,
                    {
                        codigoQR,
                        evento: evento.id,
                    }
                );
                console.log(respuesta);
            } catch (error) {}
        },
        async obtenerAsistencias(idUsuario, idEvento, page) {
            try {
                const respuesta = await axios.post(
                    `/api/asistencias/usuario/${idUsuario}`,
                    {
                        idEvento,
                        page,
                    }
                );
                this.asistencias = respuesta.data.asistencias;
                this.paginacion = respuesta.data.paginacion;
                this.total = respuesta.data.total;
                return respuesta;
            } catch (error) {
                throw new Error(error);
            }
        },
    },
});
