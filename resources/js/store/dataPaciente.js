import { defineStore } from "pinia";
import axios from "axios";

export const useDataPacientes = defineStore("dataPaciente", {
    state: () => ({
        pacientes: [],
        servicios: [],
        personal: [],
    }),
    persist: true,
    actions: {
        cargarPacientes(pagina = 1, busqueda = "", parametro = "") {
            const url =
                "/api/pacientes?page=" +
                pagina +
                "&busqueda=" +
                busqueda +
                "&parametro=" +
                parametro;
            axios
                .get(url)
                .then((response) => {
                    this.pacientes = response.data.pacientes;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        obtenerPersonal() {
            axios
                .get(`/api/personal`)
                .then((response) => {
                    console.log(response);
                    this.personal = response.data.personales;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        async cargarServicios(id, datos) {
            try {
                const respuesta = await axios.get(`/api/servicios`);
                this.servicios = respuesta.data.servicios;
            } catch (error) {
                return "Error al registrar";
            }
        },
        async registrarServicio(id, datos) {
            try {
                const datosServicio = {
                    paciente_id: id,
                    ...datos,
                };

                const respuesta = await axios.post(
                    `/api/servicios`,
                    datosServicio
                );

                return "Servicio Registrado con éxito";
            } catch (error) {
                return "Error al registrar";
            }
        },
    },
});
