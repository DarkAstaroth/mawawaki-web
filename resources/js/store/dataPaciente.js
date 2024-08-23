import { defineStore } from "pinia";
import axios from "axios";

export const useDataPacientes = defineStore("dataPaciente", {
    state: () => ({
        pacientes: [],
        servicios: [],
        personal: [],
        pacienteActual: null,
    }),
    persist: true,
    actions: {
        async cargarPacientes(pagina = 1, busqueda = "", parametro = "") {
            try {
                const response = await axios.get("/api/detalle/pacientes", {
                    params: { page: pagina, busqueda, parametro },
                });
                this.pacientes = response.data.pacientes.data;
                return response.data.pacientes;
            } catch (error) {
                console.error("Error al cargar pacientes:", error);
                throw error;
            }
        },

        async cargarPaciente(id, filtros = {}) {
            try {
                const response = await axios.get(
                    `/api/detalle/pacientes/${id}`,
                    {
                        params: filtros,
                    }
                );
                return response.data.paciente;
            } catch (error) {
                console.error("Error al cargar el paciente:", error);
                throw error;
            }
        },

        async obtenerPersonal() {
            try {
                const response = await axios.get(`/api/personal`);
                this.personal = response.data.personales;
                return this.personal;
            } catch (error) {
                console.error("Error al obtener personal:", error);
                throw error;
            }
        },

        async cargarServicios(id) {
            try {
                const response = await axios.get(`/api/servicios`, {
                    params: { paciente_id: id },
                });
                this.servicios = response.data.servicios;
                return this.servicios;
            } catch (error) {
                console.error("Error al cargar servicios:", error);
                throw error;
            }
        },

        async crearPaciente(usuario, datosPersona, datosPaciente) {
            try {
                const response = await axios.post("/api/pacientes/registrar", {
                    usuario: usuario.id,
                    nombre: datosPersona.nombre,
                    paterno: datosPersona.paterno,
                    materno: datosPersona.materno,
                    ci: datosPersona.ci,
                    fechaNacimiento: datosPersona.fechaNacimiento,
                    estadoSalud: datosPaciente.estadoSalud,
                    precondicion: datosPaciente.precondicion,
                    contactoEmergenciaNombre:
                        datosPaciente.contactoEmergenciaNombre,
                    contactoEmergenciaTelefono:
                        datosPaciente.contactoEmergenciaTelefono,
                });
                await this.cargarPacientes();
                return response.data;
            } catch (error) {
                console.error("Error al crear paciente:", error);
                throw error;
            }
        },

        async eliminarServicio(id) {
            try {
                const response = await axios.delete(`/api/servicios/${id}`);
                await this.cargarServicios(this.pacienteActual.id);
                return response.data;
            } catch (error) {
                console.error("Error al eliminar servicio:", error);
                throw error;
            }
        },

        async actualizarPaciente(id, datos) {
            try {
                const response = await axios.put(`/api/pacientes/${id}`, datos);
                await this.cargarPaciente(id);
                return response.data;
            } catch (error) {
                console.error("Error al actualizar paciente:", error);
                throw error;
            }
        },

        async registrarServicio(pacienteId, datosServicio) {
            try {
                const response = await axios.post(
                    `/api/registrar/servicio/paciente/${pacienteId}`,
                    datosServicio
                );

                // Actualizar el estado local si es necesario
                if (
                    this.pacienteActual &&
                    this.pacienteActual.id === pacienteId
                ) {
                    if (!this.pacienteActual.servicios) {
                        this.pacienteActual.servicios = [];
                    }
                    this.pacienteActual.servicios.push(response.data.servicio);
                }

                return response.data.servicio;
            } catch (error) {
                console.error("Error al registrar el servicio:", error);
                throw error;
            }
        },
    },
    getters: {
        getPacienteById: (state) => (id) => {
            return state.pacientes.find((paciente) => paciente.id === id);
        },
        getServiciosByPacienteId: (state) => (id) => {
            return state.servicios.filter(
                (servicio) => servicio.paciente_id === id
            );
        },
    },
});
