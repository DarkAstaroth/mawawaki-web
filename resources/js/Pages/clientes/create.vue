<template>
    <div class="flex justify-center">
        <div class="card card-bordered w-1/2">
            <div class="card-header">
                <h3 class="card-title">Crear nuevo usuario</h3>
            </div>

            <form class="mt-6 px-10" @submit.prevent="submitForm">
                <div class="mb-4">
                    <label class="block text-gray-700">Nombres</label>
                    <InputText v-model="nombre" class="w-100 uppercase" />
                    <div
                        v-if="v$?.nombre.$error"
                        class="m-2 fv-plugins-message-container invalid-feedback"
                    >
                        <div data-field="nombre" data-validator="required">
                            El nombre es requerido
                        </div>
                    </div>
                </div>
                <div class="flex gap-4 mb-4">
                    <div class="w-1/2">
                        <label class="block text-gray-700"
                            >Apellido Paterno</label
                        >
                        <InputText
                            v-model="apellidoPaterno"
                            class="w-100 uppercase"
                        />
                        <div
                            v-if="v$?.apellidoPaterno.$error"
                            class="m-2 fv-plugins-message-container invalid-feedback"
                        >
                            <div
                                data-field="apellidoPaterno"
                                data-validator="required"
                            >
                                El apellido paterno es requerido
                            </div>
                        </div>
                    </div>
                    <div class="w-1/2">
                        <label class="block text-gray-700"
                            >Apellido Materno</label
                        >
                        <InputText
                            v-model="apellidoMaterno"
                            class="w-100 uppercase"
                        />
                        <div
                            v-if="v$?.apellidoMaterno.$error"
                            class="m-2 fv-plugins-message-container invalid-feedback"
                        >
                            <div
                                data-field="apellidoMaterno"
                                data-validator="required"
                            >
                                El apellido materno es requerido
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700"
                        >Correo Electrónico</label
                    >
                    <InputText v-model="correoElectronico" class="w-100" />
                    <div
                        v-if="v$?.correoElectronico.$error"
                        class="m-2 fv-plugins-message-container invalid-feedback"
                    >
                        <div
                            data-field="correoElectronico"
                            data-validator="required"
                        >
                            El correo electrónico es requerido
                        </div>
                        <div v-if="v$.correoElectronico.email.$error">
                            <div
                                data-field="correoElectronico"
                                data-validator="email"
                            >
                                El correo electrónico debe ser válido
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700"
                        >Carnet de Identidad</label
                    >
                    <InputText
                        v-model="carnetIdentidad"
                        class="w-100 uppercase"
                    />
                    <div
                        v-if="v$?.carnetIdentidad.$error"
                        class="m-2 fv-plugins-message-container invalid-feedback"
                    >
                        <div
                            data-field="carnetIdentidad"
                            data-validator="required"
                        >
                            El carnet de identidad es requerido
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Teléfono</label>
                    <InputText v-model="telefono" class="w-100 uppercase" />
                    <div
                        v-if="v$?.telefono.$error"
                        class="m-2 fv-plugins-message-container invalid-feedback"
                    >
                        <div data-field="telefono" data-validator="required">
                            El teléfono es requerido
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Dirección</label>
                    <InputText v-model="direccion" class="w-100 uppercase" />
                    <div
                        v-if="v$?.direccion.$error"
                        class="m-2 fv-plugins-message-container invalid-feedback"
                    >
                        <div data-field="direccion" data-validator="required">
                            La dirección es requerida
                        </div>
                    </div>
                </div>

                <Button
                    type="submit"
                    label="Guardar"
                    icon="pi pi-save"
                    :loading="loading"
                    @click="submitForm"
                    class="mb-5"
                />
            </form>
        </div>
    </div>
</template>

<script>
import { useVuelidate } from "@vuelidate/core";
import { required, email } from "@vuelidate/validators";
import { useDataUsuarios } from "@/store/dataUsuario";

export default {
    setup() {
            const v$ = useVuelidate();
            const store = useDataUsuarios();
            return { v$, store };
    },
    data() {
        return {
            nombre: "",
            apellidoPaterno: "",
            apellidoMaterno: "",
            correoElectronico: "",
            carnetIdentidad: "",
            telefono: "",
            direccion: "",
            loading: false,
        };
    },
    validations() {
        return {
            nombre: { required },
            apellidoPaterno: { required },
            apellidoMaterno: { required },
            correoElectronico: { required, email },
            carnetIdentidad: { required },
            telefono: { required },
            direccion: { required },
        };
    },
    methods: {
        submitForm() {
            this.v$.$touch();

            if (!this.v$.$invalid) {
                this.loading = true;
                this.store
                    .crearUsuario({
                        nombres: this.nombre,
                        paterno: this.apellidoPaterno,
                        materno: this.apellidoMaterno,
                        email: this.correoElectronico,
                        carnet_identidad: this.carnetIdentidad,
                        telefono: this.telefono,
                        direccion: this.direccion,
                    })
                    .then((respuesta) => {
                        if (respuesta.status === 200) {
                            Swal.fire({
                                title: "Usuario creado!",
                                text: respuesta.data.message,
                                icon: "success",
                            });
                            this.$router.push({ name: "usuarios.index" });
                        }
                        this.loading = false;
                    })
                    .catch((error) => {
                        if (error.response && error.response.data) {
                            Swal.fire({
                                icon: "error",
                                title: "Upsss..",
                                text: error.response.data.error,
                            });
                        } else {
                            console.log(error);
                            Swal.fire({
                                icon: "error",
                                title: "Upsss..",
                                text: "Ocurrió un error inesperado.",
                            });
                        }
                        this.loading = false;
                    });
            }
        },
    },
};
</script>

<style scoped>
.text-red-500 {
    color: red;
}
</style>
