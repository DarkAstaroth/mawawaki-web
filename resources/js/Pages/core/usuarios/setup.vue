<template>
    <div>
        <div class="stepper stepper-pills" id="kt_stepper_example_basic">
            <div class="stepper-nav flex-center flex-wrap mb-10">
                <div
                    class="stepper-item my-4"
                    :class="{ current: pasoActivo === 0 }"
                    data-kt-stepper-element="nav"
                >
                    <div class="stepper-wrapper d-flex align-items-center">
                        <div class="stepper-icon w-30px h-30px">
                            <i class="stepper-check fas fa-check"></i>
                            <span class="stepper-number">1</span>
                        </div>

                        <div class="stepper-label">
                            <div class="stepper-desc">Tipo de cuenta</div>
                        </div>
                    </div>

                    <div class="stepper-line h-40px"></div>
                </div>

                <div
                    class="stepper-item my-4 ml-2"
                    :class="{ current: pasoActivo === 1 }"
                    data-kt-stepper-element="nav"
                >
                    <div class="stepper-wrapper d-flex align-items-center">
                        <div class="stepper-icon w-30px h-30px">
                            <i class="stepper-check fas fa-check"></i>
                            <span class="stepper-number">2</span>
                        </div>

                        <div class="stepper-label">
                            <div class="stepper-desc">Datos relevantes</div>
                        </div>
                    </div>

                    <div class="stepper-line h-40px"></div>
                </div>
            </div>

            <div v-if="pasoActivo === 0">
                <div class="d-flex flex-column gap-3">
                    <input
                        type="radio"
                        class="btn-check"
                        v-model="opcionSeleccionada"
                        value="personal"
                        id="opcionPersonal"
                    />
                    <label
                        class="btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex align-items-center mb-5"
                        for="opcionPersonal"
                    >
                        <i class="ki-duotone ki-user-square fs-4x me-4">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>

                        <span class="d-block fw-semibold text-start">
                            <span class="text-gray-900 fw-bold d-block fs-3"
                                >Personal</span
                            >
                            <span class="text-muted fw-semibold fs-6"
                                >Administradores, Pasantes, Voluntarios, etc
                            </span>
                        </span>
                    </label>

                    <input
                        type="radio"
                        class="btn-check"
                        v-model="opcionSeleccionada"
                        value="cliente"
                        id="opcionCliente"
                    />
                    <label
                        class="btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex align-items-center"
                        for="opcionCliente"
                    >
                        <i class="ki-duotone ki-heart-circle fs-4x me-4">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>

                        <span class="d-block fw-semibold text-start">
                            <span class="text-gray-900 fw-bold d-block fs-3"
                                >Cliente</span
                            >
                            <span class="text-muted fw-semibold fs-6"
                                >Usuarios / Estudiantes del Centro de
                                Terapias</span
                            >
                        </span>
                    </label>
                </div>

                <button class="btn btn-primary mt-5" @click="irAlSiguientePaso">
                    <span class="indicator-label">Siguiente</span>
                </button>
            </div>

            <div
                v-if="opcionSeleccionada && pasoActivo === 1"
                class="mb-10 d-grid mt-5"
            >
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
                                @input="validarCampos"
                            />
                            <small
                                class="text-danger"
                                v-if="
                                    enviado || v$?.datosPersonal.carnet.$error
                                "
                                >El carnet de Identidad es requerido</small
                            >
                        </div>

                        <div class="d-flex flex-column gap-2 mb-5 mt-5">
                            <label for="carnet">Fecha de nacimiento</label>
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
                                v-if="
                                    enviado || v$?.datosPersonal.carnet.$error
                                "
                                >El carnet de Identidad es requerido</small
                            >
                        </div>

                        <div class="d-flex flex-column gap-2 mb-5">
                            <label for="telefono">Teléfono</label>
                            <InputText
                                id="telefono"
                                v-model="datosPersonal.telefono"
                                aria-describedby="telefono"
                                placeholder="Ingrese su numero de telefono"
                                @input="validarCampos"
                            />
                            <small
                                class="text-danger"
                                v-if="
                                    enviado || v$?.datosPersonal.telefono.$error
                                "
                                >El teléfono es requerido</small
                            >
                        </div>

                        <div class="d-flex flex-column gap-2 mb-5">
                            <label for="direccion">Dirección </label>
                            <InputText
                                id="direccion"
                                v-model="datosPersonal.direccion"
                                aria-describedby="direccion"
                                placeholder="Ingrese su dirección"
                                @input="validarCampos"
                            />
                            <small
                                class="text-danger"
                                v-if="
                                    enviado ||
                                    v$?.datosPersonal.direccion.$error
                                "
                                >La dirección es requerida</small
                            >
                        </div>

                        <span class="font-bold mb-10">Estudios</span>

                        <div class="d-flex flex-column gap-2 mb-5">
                            <label for="universidad">Universidad</label>
                            <InputText
                                id="universidad"
                                v-model="datosPersonal.universidad"
                                aria-describedby="universidad"
                                placeholder="Solo Sigla. Ejemplo (UMSA)"
                                @input="validarCampos"
                            />
                            <small
                                class="text-danger"
                                v-if="
                                    enviado ||
                                    v$?.datosPersonal.universidad.$error
                                "
                                >La Universidad es requerida</small
                            >
                        </div>

                        <div class="d-flex flex-column gap-2 mb-5">
                            <label for="facultad">Facultad</label>
                            <InputText
                                id="facultad"
                                v-model="datosPersonal.facultad"
                                aria-describedby="facultad"
                                placeholder="Solo Sigla. Ejemplo (FHCE)"
                                @input="validarCampos"
                            />
                            <small
                                class="text-danger"
                                v-if="
                                    enviado || v$?.datosPersonal.facultad.$error
                                "
                                >La Facultad es requerida</small
                            >
                        </div>

                        <div class="d-flex flex-column gap-2 mb-5">
                            <label for="facultad">Carrera</label>
                            <InputText
                                id="facultad"
                                v-model="datosPersonal.carrera"
                                aria-describedby="facultad"
                                placeholder="Ejemplo Psicología"
                                @input="validarCampos"
                            />
                            <small
                                class="text-danger"
                                v-if="
                                    enviado || v$?.datosPersonal.carrera.$error
                                "
                                >La Carrera es requerida</small
                            >
                        </div>
                    </div>
                    <div v-else-if="opcionSeleccionada === 'cliente'">
                        <input type="hidden" name="tipo" value="cliente" />
                        <div class="d-flex flex-column gap-2">
                            <label for="ocupacion" class="font-bold"
                                >Verifica tu información:</label
                            >
                            <div
                                class="mb-4 p-5 text-sm text-emerald-800 rounded-lg bg-emerald-50 dark:bg-gray-800 dark:text-emerald-400 divide-y-2"
                                role="alert"
                            >
                                <div class="flex flex-col mb-2">
                                    <span class="font-bold">Nombres:</span>
                                    <span class="">
                                        {{ this.usuario.persona.nombre }}</span
                                    >
                                </div>
                                <div class="flex flex-col mb-2">
                                    <span class="font-bold">Paterno:</span>
                                    <span class="">
                                        {{ this.usuario.persona.paterno }}</span
                                    >
                                </div>
                                <div class="flex flex-col mb-2">
                                    <span class="font-bold">Materno:</span>
                                    <span class="">
                                        {{ this.usuario.persona.materno }}</span
                                    >
                                </div>
                                <div class="flex flex-col mb-2">
                                    <span class="font-bold">Carnet:</span>
                                    <span class="">
                                        {{ this.usuario.persona.ci }}</span
                                    >
                                </div>
                                <div class="flex flex-col mb-2">
                                    <span class="font-bold">Correo:</span>
                                    <span class="">
                                        {{ this.usuario.email }}</span
                                    >
                                </div>
                                <div class="flex flex-col mb-2">
                                    <span class="font-bold">Telefono:</span>
                                    <span class="">
                                        {{
                                            this.usuario.persona.telefono
                                        }}</span
                                    >
                                </div>
                                <div class="flex flex-col mb-2">
                                    <span class="font-bold">Dirección:</span>
                                    <span class="">
                                        {{
                                            this.usuario.persona.direccion
                                        }}</span
                                    >
                                </div>
                            </div>
                        </div>
                    </div>

                    <button
                        class="btn btn-secondary mt-5"
                        @click="irAlPasoAnterior"
                    >
                        <span class="indicator-label">Anterior</span>
                    </button>

                    <button type="submit" class="btn btn-primary mt-5 ms-5">
                        <span class="indicator-label">Enviar solicitud</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import InputText from "primevue/inputtext";
import Dropdown from "primevue/dropdown";
import { useVuelidate } from "@vuelidate/core";
import { required } from "@vuelidate/validators";

export default {
    components: { InputText, Dropdown },
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
            datosCliente: { ocupacion: "" },
            usuario: this.usuario,
            paisSeleccionado: null,
            paises: [
                { label: "País 1", value: "pais1" },
                { label: "País 2", value: "pais2" },
            ],
            opcionSeleccionada: "personal",
            inputPersonal: "",
            ocupacion: "",
            pasoActivo: 0,
            tipoCuenta: this.usuario.gauth_type,
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
    mounted() {},
    methods: {
        irAlSiguientePaso() {
            if (this.opcionSeleccionada) {
                this.irAlPaso(1);
            }
        },
        irAlPasoAnterior() {
            this.irAlPaso(0);
        },
        enviarFormulario() {
            this.enviado = true;
            this.v$.$touch();
            if (this.v$.$invalid) {
                return;
            }
            axios
                .post(`/api/invitado/enviar/solicitud`, {
                    tipo: this.opcionSeleccionada,
                    datos:
                        this.opcionSeleccionada === "personal"
                            ? this.datosPersonal
                            : {},
                    usuario_id: this.usuario.id,
                })
                .then((response) => {
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
                .catch((error) => {});
        },
        irAlPaso(indice) {
            this.pasoActivo = indice;
        },
        validarCampos() {
            this.enviado = false;
            this.v$.$touch();
        },
    },
};
</script>
