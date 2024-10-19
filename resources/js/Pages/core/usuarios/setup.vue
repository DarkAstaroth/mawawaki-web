<template>
    <div>
        <div class="stepper stepper-pills" id="kt_stepper_example_basic">
            <div class="mb-10 d-grid mt-5">
                <form @submit.prevent="enviarFormulario">
                    <div v-if="opcionSeleccionada === 'personal'">
                        <input type="hidden" name="tipo" value="personal" />
                        <span class="font-bold mb-10">Datos Personales</span>

                        <div class="d-flex flex-column gap-2 mb-5 mt-5">
                            <label for="carnet">Carnet de Identidad</label>
                            <InputText
                                id="carnet"
                                v-model="datosPersonal.carnet"
                                aria-describedby="carnet"
                                placeholder="Ingrese su numero de carnet"
                            />
                            <small
                                class="text-danger"
                                v-if="v$?.datosPersonal.carnet.$error"
                            >
                                El carnet de Identidad es requerido
                            </small>
                        </div>

                        <div class="d-flex flex-column gap-2 mb-5 mt-5">
                            <label for="fechaNacimiento"
                                >Fecha de nacimiento</label
                            >
                            <VueDatePicker
                                v-model="datosPersonal.fechaNacimiento"
                                :enable-time-picker="false"
                                select-text="Seleccionar"
                                cancel-text="Cancelar"
                                locale="es"
                                format="dd/MM/yyyy"
                            />
                            <small
                                class="text-danger"
                                v-if="v$?.datosPersonal.fechaNacimiento.$error"
                            >
                                La fecha de nacimiento es requerida
                            </small>
                        </div>

                        <div class="d-flex flex-column gap-2 mb-5">
                            <label for="telefono">Teléfono</label>
                            <InputText
                                id="telefono"
                                v-model="datosPersonal.telefono"
                                aria-describedby="telefono"
                                placeholder="Ingrese su numero de telefono"
                            />
                            <small
                                class="text-danger"
                                v-if="v$?.datosPersonal.telefono.$error"
                            >
                                El teléfono es requerido
                            </small>
                        </div>

                        <div class="d-flex flex-column gap-2 mb-5">
                            <label for="direccion">Dirección</label>
                            <InputText
                                id="direccion"
                                v-model="datosPersonal.direccion"
                                aria-describedby="direccion"
                                placeholder="Ingrese su dirección"
                            />
                            <small
                                class="text-danger"
                                v-if="v$?.datosPersonal.direccion.$error"
                            >
                                La dirección es requerida
                            </small>
                        </div>

                        <span class="font-bold mb-10">Estudios</span>

                        <div class="d-flex flex-column gap-2 mb-5">
                            <label for="universidad">Universidad</label>
                            <InputText
                                id="universidad"
                                v-model="datosPersonal.universidad"
                                aria-describedby="universidad"
                                placeholder="Solo Sigla. Ejemplo (UMSA)"
                            />
                            <small
                                class="text-danger"
                                v-if="v$?.datosPersonal.universidad.$error"
                            >
                                La Universidad es requerida
                            </small>
                        </div>

                        <div class="d-flex flex-column gap-2 mb-5">
                            <label for="facultad">Facultad</label>
                            <InputText
                                id="facultad"
                                v-model="datosPersonal.facultad"
                                aria-describedby="facultad"
                                placeholder="Solo Sigla. Ejemplo (FHCE)"
                            />
                            <small
                                class="text-danger"
                                v-if="v$?.datosPersonal.facultad.$error"
                            >
                                La Facultad es requerida
                            </small>
                        </div>

                        <div class="d-flex flex-column gap-2 mb-5">
                            <label for="carrera">Carrera</label>
                            <InputText
                                id="carrera"
                                v-model="datosPersonal.carrera"
                                aria-describedby="carrera"
                                placeholder="Ejemplo Psicología"
                            />
                            <small
                                class="text-danger"
                                v-if="v$?.datosPersonal.carrera.$error"
                            >
                                La Carrera es requerida
                            </small>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-5">
                        Enviar solicitud
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import InputText from "primevue/inputtext";
import { useVuelidate } from "@vuelidate/core";
import { required } from "@vuelidate/validators";

export default {
    components: { InputText },
    props: ["usuario"],
    setup() {
        return { v$: useVuelidate() };
    },
    data() {
        return {
            datosPersonal: {
                telefono: "",
                carnet: "",
                fechaNacimiento: null,
                direccion: "",
                universidad: "",
                facultad: "",
                carrera: "",
            },
            opcionSeleccionada: "personal",
            enviado: false,
        };
    },
    validations() {
        if (this.opcionSeleccionada === "personal") {
            return {
                datosPersonal: {
                    telefono: { required },
                    carnet: { required },
                    fechaNacimiento: { required },
                    direccion: { required },
                    universidad: { required },
                    facultad: { required },
                    carrera: { required },
                },
            };
        }
        return {};
    },
    methods: {
        enviarFormulario() {
            this.enviado = true;
            this.v$.$touch();
            console.log(this.datosPersonal);
            axios
                .post(`/api/invitado/enviar/solicitud`, {
                    tipo: this.opcionSeleccionada,
                    datos:
                        this.opcionSeleccionada === "personal"
                            ? this.datosPersonal
                            : {},
                    usuario_id: this.usuario.id,
                })
                .then(() => {
                    Swal.fire({
                        title: "Éxito",
                        text: "Solicitud enviada con éxito",
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Aceptar",
                        customClass: {
                            confirmButton: "btn btn-primary",
                        },
                    }).then(() => {
                        window.location.href = route("verificar.cuenta");
                    });
                })
                .catch((error) => {
                    const errorMessage =
                        error.response?.data?.error ||
                        "Ha ocurrido un error. Intente nuevamente.";
                    Swal.fire({
                        title: "Error",
                        text: errorMessage,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Aceptar",
                        customClass: {
                            confirmButton: "btn btn-danger",
                        },
                    });
                });
        },
    },
};
</script>
