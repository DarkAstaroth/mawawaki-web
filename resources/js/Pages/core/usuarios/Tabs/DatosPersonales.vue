<template>
    <form action="">
        <div
            :class="[
                'd-flex w-100 gap-5',
                esResponsivo ? 'flex-column' : 'flex-row',
            ]"
        >
            <div class="flex-grow-1">
                <h3>Datos Personales</h3>
                <div class="d-flex flex-column gap-2 mb-5">
                    <label for="nombre">Nombres</label>
                    <InputText
                        id="nombre"
                        v-model="usuarioData.persona.nombre"
                        aria-describedby="nombre"
                        placeholder="Nombres"
                        @input="validarCampos"
                        class="text-capitalize"
                    />
                    <small
                        class="text-danger"
                        v-if="enviado || v$?.usuarioData.persona.nombre.$error"
                        >El nombre es requerida</small
                    >
                </div>

                <div
                    :class="[
                        'd-flex w-100 ',
                        esResponsivo ? 'flex-column' : 'flex-row gap-5',
                    ]"
                >
                    <div class="d-flex flex-grow-1 flex-column gap-2 mb-5">
                        <label for="paterno">Paterno</label>
                        <InputText
                            id="paterno"
                            v-model="usuarioData.persona.paterno"
                            aria-describedby="paterno"
                            placeholder="Paterno"
                            @input="validarCampos"
                            class="text-capitalize"
                        />
                        <small
                            class="text-danger"
                            v-if="
                                enviado ||
                                v$?.usuarioData.persona.paterno.$error
                            "
                            >El apellido paterno es requerido</small
                        >
                    </div>
                    <div class="d-flex flex-grow-1 flex-column gap-2 mb-5">
                        <label for="materno">Materno</label>
                        <InputText
                            id="materno"
                            v-model="usuarioData.persona.materno"
                            aria-describedby="Materno"
                            placeholder="Materno"
                            @input="validarCampos"
                            class="text-capitalize"
                        />
                        <small
                            class="text-danger"
                            v-if="
                                enviado ||
                                v$?.usuarioData.persona.materno.$error
                            "
                            >El apellido materno es requerido</small
                        >
                    </div>
                </div>

                <div
                    :class="[
                        'd-flex w-100 ',
                        esResponsivo ? 'flex-column' : 'flex-row gap-5',
                    ]"
                >
                    <div class="d-flex flex-grow-1 flex-column gap-2 mb-5">
                        <label for="materno">Carnet de Identidad</label>
                        <InputText
                            type="number"
                            id="ci"
                            v-model="usuarioData.persona.ci"
                            aria-describedby="ci"
                            placeholder="Solo números"
                            @input="validarCampos"
                        />
                        <small
                            class="text-danger"
                            v-if="enviado || v$?.usuarioData.persona.ci.$error"
                            >El CI es requerido</small
                        >
                    </div>

                    <div class="d-flex flex-grow-1 flex-column gap-2 mb-5">
                        <label for="materno">Fecha de nacimiento</label>
                        <VueDatePicker
                            v-model="usuarioData.persona.fecha_nacimiento"
                            :enable-time-picker="false"
                            select-text="Seleccionar"
                            cancel-text="Cancelar"
                            locale="es"
                            format="dd/MM/yyyy"
                        />
                        <small
                            class="text-danger"
                            v-if="
                                enviado ||
                                v$?.usuarioData.persona.fecha_nacimiento.$error
                            "
                            >La fecha de nacimiento es requerido</small
                        >
                    </div>

                    <div class="d-flex flex-grow-1 flex-column gap-2 mb-5">
                        <label for="telefono">Teléfono</label>
                        <InputText
                            type="number"
                            id="telefono"
                            v-model="usuarioData.persona.telefono"
                            aria-describedby="telefono"
                            placeholder="Solo números"
                            @input="validarCampos"
                        />
                        <small
                            class="text-danger"
                            v-if="
                                enviado ||
                                v$?.usuarioData.persona.telefono.$error
                            "
                            >El teléfono es requerido</small
                        >
                    </div>
                </div>

                <div class="d-flex flex-column gap-2 mb-5">
                    <label for="telefono">Dirección</label>
                    <Textarea
                        v-model="usuarioData.persona.direccion"
                        rows="5"
                        cols="30"
                    />
                    <small
                        class="text-danger"
                        v-if="
                            enviado || v$?.usuarioData.persona.direccion.$error
                        "
                        >La dirección es requerida</small
                    >
                </div>
            </div>

            <div v-if="noEsCliente()">
                <div v-if="this.store.usuario.personal" class="flex-grow-1">
                    <h3>Estudios</h3>
                    <div class="d-flex flex-column gap-2 mb-5">
                        <label for="universidad">Universidad</label>
                        <InputText
                            id="universidad"
                            v-model="usuarioData.personal.universidad"
                            aria-describedby="universidad"
                            placeholder="Solo Sigla. Ejemplo (UMSA)"
                            @input="validarCampos"
                        />
                        <small
                            class="text-danger"
                            v-if="
                                enviado ||
                                v$?.usuarioData.personal.universidad.$error
                            "
                            >La Universidad es requerida</small
                        >
                    </div>

                    <div class="d-flex flex-column gap-2 mb-5">
                        <label for="facultad">Facultad</label>
                        <InputText
                            id="facultad"
                            v-model="usuarioData.personal.facultad"
                            aria-describedby="facultad"
                            placeholder="Solo Sigla. Ejemplo (FHCE)"
                            @input="validarCampos"
                        />
                        <small
                            class="text-danger"
                            v-if="
                                enviado ||
                                v$?.usuarioData.personal.facultad.$error
                            "
                            >La Facultad es requerida</small
                        >
                    </div>

                    <div class="d-flex flex-column gap-2 mb-5">
                        <label for="facultad">Carrera</label>
                        <InputText
                            id="facultad"
                            v-model="usuarioData.personal.carrera"
                            aria-describedby="facultad"
                            placeholder="Ejemplo Psicología"
                            @input="validarCampos"
                        />
                        <small
                            class="text-danger"
                            v-if="
                                enviado ||
                                v$?.usuarioData.personal.carrera.$error
                            "
                            >La Carrera es requerida</small
                        >
                    </div>
                </div>
            </div>
        </div>
        <div
            :class="[
                'd-flex w-100',
                esResponsivo ? 'justify-content-center' : 'justify-content-end',
            ]"
        >
            <Button
                :class="[esResponsivo && 'w-100 d-flex justify-content-center']"
                @click="modificarDatosPersonales"
                >Actualizar Datos</Button
            >
        </div>
    </form>
    <Toast />
</template>

<script>
import { useDataPerfil } from "@/store/dataPerfil";
import { useVuelidate } from "@vuelidate/core";
import { required } from "@vuelidate/validators";
import { useToast } from "primevue/usetoast";

export default {
    name: "Datos Personales",
    data() {
        let usuarioData = {
            persona: {
                nombre: this.store.usuario.persona.nombre,
                paterno: this.store.usuario.persona.paterno,
                materno: this.store.usuario.persona.materno,
                ci: this.store.usuario.persona.ci,
                telefono: this.store.usuario.persona.telefono,
                direccion: this.store.usuario.persona.direccion,
                fecha_nacimiento: new Date(
                    this.store.usuario.persona.fecha_nacimiento * 1000
                ),
            },
        };

        if (this.store.usuario.personal) {
            usuarioData.personal = {
                universidad: this.store.usuario.personal.universidad,
                facultad: this.store.usuario.personal.facultad,
                carrera: this.store.usuario.personal.carrera,
            };
        }

        return {
            usuarioData,
            esResponsivo: false,
        };
    },
    setup() {
        const store = useDataPerfil();
        const toast = useToast();

        return { store, v$: useVuelidate(), toast };
    },
    validations() {
        let validationRules = {
            usuarioData: {
                persona: {
                    nombre: { required },
                    paterno: { required },
                    materno: { required },
                    ci: { required },
                    telefono: { required },
                    direccion: { required },
                    fecha_nacimiento: { required },
                },
            },
        };

        if (this.usuarioData.personal) {
            validationRules.usuarioData.personal = {
                universidad: { required },
                facultad: { required },
                carrera: { required },
            };
        }

        return validationRules;
    },
    created() {
        this.verificarResponsivo();
        window.addEventListener("resize", this.verificarResponsivo);
    },
    destroyed() {
        window.removeEventListener("resize", this.verificarResponsivo);
    },
    mounted() {},
    methods: {
        noEsCliente() {
            console.log(
                !this.store.usuario.roles.some(
                    (role) => role.name === "cliente"
                )
            );
            return !this.store.usuario.roles.some(
                (role) => role.name === "cliente"
            );
        },
        mostrarMensaje(tipo, titulo, texto) {
            this.toast.add({
                severity: tipo,
                summary: titulo,
                detail: texto,
                life: 2000,
            });
        },
        validarCampos() {
            this.enviado = false;
            this.v$.$touch();
        },
        verificarResponsivo() {
            this.esResponsivo = window.innerWidth < 768;
        },
        modificarDatosPersonales() {
            this.v$.$touch();
            if (!this.v$.$error) {
                this.store
                    .modificarDatos({ usuario: this.usuarioData })
                    .then((respuesta) => {
                        this.mostrarMensaje(
                            "success",
                            "Operación Exitosa",
                            respuesta.data.message
                        );
                    });
            }
        },
    },
};
</script>
