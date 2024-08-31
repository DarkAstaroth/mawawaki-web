import { defineStore } from "pinia";
import axios from "axios";

export const useDataPacientes = defineStore("dataPaciente", {
    state: () => ({
        pacientes: [],
        servicios: [],
        pagos: [],
        sesiones: [],
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
                this.pacienteActual = response.data.paciente;
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
                    contraindicacion: datosPaciente.contraindicacion,
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

        async actualizarPacienteModal(pacienteActualizado) {
            try {
                const response = await axios.put(
                    `/api/pacientes/${pacienteActualizado.id}`,
                    pacienteActualizado
                );
                return response.data;
            } catch (error) {
                console.error("Error al actualizar paciente:", error);
                throw error;
            }
        },

        async eliminarPaciente(idPaciente) {
            this.loading = true;
            this.error = null;
            try {
                const response = await axios.delete(
                    `/api/pacientes/${idPaciente}`
                );
                return response.data;
            } catch (error) {
                this.loading = false;
                this.error = error.response
                    ? error.response.data.message
                    : error.message;
                console.error("Error al eliminar paciente:", error);
                throw error;
            }
        },
        async cargarPacientesAction(usuarioId) {
            this.loading = true;
            this.error = null;
            try {
                const url = `/api/pacientes/cliente/${usuarioId}`;
                const response = await axios.get(url);
                this.pacientes = response.data.data;
            } catch (error) {
                console.error("Error al cargar pacientes:", error);
                this.error = error.message || "Error al cargar pacientes";
            } finally {
                this.loading = false;
            }
        },

        async eliminarServicio(id, paciente) {
            try {
                const response = await axios.delete(`/api/servicios/${id}`);
                await this.cargarPaciente(paciente, "paciente");
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
        // async registrarPago(servicioId, datosPago) {
        //     try {
        //         console.log(datosPago);
        //         const response = await axios.post(
        //             `/api/registrar/pago/servicio/${servicioId}`,
        //             datosPago,
        //             {
        //                 headers: {
        //                     "Content-Type": "multipart/form-data",
        //                 },
        //             }
        //         );
        //         // You might want to update some local state here
        //         return response.data;
        //     } catch (error) {
        //         console.error("Error al registrar el pago:", error);
        //         throw error;
        //     }
        // },

        async registrarPago(servicioId, datosPago) {
            try {
                // Convertir la fecha a timestamp Unix
                const fechaPago = new Date(datosPago.fecha_pago);
                const timestampUnix = Math.floor(fechaPago.getTime() / 1000);
                console.log(timestampUnix, "UNIX");

                // Crear un nuevo objeto FormData
                const formData = new FormData();

                // Agregar todos los campos al FormData, reemplazando fecha_pago con el timestamp
                for (const [key, value] of Object.entries(datosPago)) {
                    if (key === "fecha_pago") {
                        formData.append(key, timestampUnix);
                    } else if (key === "comprobante" && value instanceof File) {
                        formData.append(key, value, value.name);
                    } else {
                        formData.append(key, value);
                    }
                }

                console.log(
                    "Datos del pago a enviar:",
                    Object.fromEntries(formData)
                );
                console.log(formData);
                const response = await axios.post(
                    `/api/registrar/pago/servicio/${servicioId}`,
                    formData,
                    {
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    }
                );

                // You might want to update some local state here
                return response.data;
            } catch (error) {
                console.error("Error al registrar el pago:", error);
                throw error;
            }
        },
        async obtenerPagosServicio(servicioId) {
            try {
                const response = await axios.get(
                    `/api/pagos/servicio/${servicioId}`
                );
                this.pagos = response.data;
            } catch (error) {
                console.error(
                    "Error al obtener los pagos del servicio:",
                    error
                );
                throw error;
            }
        },
        async programarSesiones(datos) {
            try {
                const response = await axios.post(
                    "/api/programar-sesiones",
                    datos
                );
                // Aquí puedes actualizar el estado local si es necesario
                return response.data;
            } catch (error) {
                console.error("Error al programar las sesiones:", error);
                throw error;
            }
        },
        async obtenerSesionesServicio(servicioId) {
            try {
                const response = await axios.get(
                    `/api/sesiones/servicio/${servicioId}`
                );
                this.sesiones = response.data;
            } catch (error) {
                console.error(
                    "Error al obtener las sesiones del servicio:",
                    error
                );
                throw error;
            }
        },
        async obtenerCaballos() {
            try {
                const response = await axios.get("/api/sesion/caballos");
                return response.data;
            } catch (error) {
                console.error("Error al obtener los caballos:", error);
                throw error;
            }
        },
        async actualizarSesion(sesionId, datosSesion) {
            console.log(datosSesion);
            try {
                const response = await axios.put(
                    `/api/editar/sesion/${sesionId}`,
                    datosSesion
                );
                return response.data;
            } catch (error) {
                console.error("Error al actualizar la sesión:", error);
                throw error;
            }
        },
        async verificarPago(idPago) {
            try {
                const response = await axios.put(
                    `/api/pagos/${idPago}/verificar`
                );
                return response.data.message;
            } catch (error) {
                console.error("Error al verificar el pago:", error);
                throw error;
            }
        },
        async eliminarServicio(idServicio) {
            try {
                const response = await axios.delete(
                    `/api/servicios/${idServicio}`
                );

                return response.data.message;
            } catch (error) {
                console.error("Error al eliminar el servicio:", error);
                throw error;
            }
        },
        async eliminarPago(idPago) {
            try {
                const response = await axios.delete(`/api/pagos/${idPago}`);
                return response.data.message;
            } catch (error) {
                console.error("Error al eliminar el pago:", error);
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
