<template>
    <div
        class="max-w-md mx-auto p-6 bg-white border border-gray-200 rounded-md"
    >
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Ajustes de cuenta</h2>
        <form @submit.prevent="submitForm">
            <div class="mb-6">
                <label
                    for="email"
                    class="block mb-2 text-sm font-medium text-gray-700"
                    >Correo electrónico:</label
                >
                <InputText
                    id="email"
                    v-model="email"
                    type="email"
                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                />
                <small class="text-danger" v-if="enviado || v$?.email.$error">
                    {{ v$.email.$errors[0]?.$message }}
                </small>
                <Button
                    label="Cambiar Email"
                    @click="cambiarEmail"
                    class="mt-2 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded"
                />
            </div>
            <div class="mb-4">
                <label
                    for="oldPassword"
                    class="block mb-2 text-sm font-medium text-gray-700"
                    >Contraseña actual:</label
                >
                <Password
                    id="oldPassword"
                    v-model="oldPassword"
                    :feedback="false"
                    class="w-full"
                    :toggleMask="true"
                    inputClass="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                />
                <small
                    class="text-danger"
                    v-if="enviado || v$?.oldPassword.$error"
                >
                    {{ v$.oldPassword.$errors[0]?.$message }}
                </small>
            </div>
            <div class="mb-4">
                <label
                    for="newPassword"
                    class="block mb-2 text-sm font-medium text-gray-700"
                    >Nueva contraseña:</label
                >
                <Password
                    id="newPassword"
                    v-model="newPassword"
                    :feedback="false"
                    class="w-full"
                    :toggleMask="true"
                    inputClass="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                />
                <small
                    class="text-danger"
                    v-if="enviado || v$?.newPassword.$error"
                >
                    {{ v$.newPassword.$errors[0]?.$message }}
                </small>
            </div>
            <div class="mb-6">
                <label
                    for="confirmPassword"
                    class="block mb-2 text-sm font-medium text-gray-700"
                    >Confirmar nueva contraseña:</label
                >
                <Password
                    id="confirmPassword"
                    v-model="confirmPassword"
                    :feedback="false"
                    class="w-full"
                    :toggleMask="true"
                    inputClass="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                />
                <small
                    class="text-danger"
                    v-if="enviado || v$?.confirmPassword.$error"
                >
                    {{ v$.confirmPassword.$errors[0]?.$message }}
                </small>
            </div>
            <Button
                label="Cambiar Contraseña"
                type="submit"
                class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded"
            />
        </form>
    </div>
</template>

<script>
import { useDataPerfil } from "@/store/dataPerfil";
import useVuelidate from "@vuelidate/core";
import {
    required,
    email,
    minLength,
    sameAs,
    helpers,
} from "@vuelidate/validators";
import InputText from "primevue/inputtext";
import Password from "primevue/password";
import Button from "primevue/button";
import Swal from "sweetalert2";

export default {
    name: "AjusteCuenta",
    components: {
        InputText,
        Password,
        Button,
    },
    data() {
        return {
            storeUsuario: useDataPerfil(),
            email: "",
            oldPassword: "",
            newPassword: "",
            confirmPassword: "",
            v$: useVuelidate(),
            enviado: false,
        };
    },
    validations() {
        return {
            email: {
                required: helpers.withMessage(
                    "El correo electrónico es requerido",
                    required
                ),
                email: helpers.withMessage(
                    "El formato del correo electrónico no es válido",
                    email
                ),
            },
            oldPassword: {
                required: helpers.withMessage(
                    "La contraseña actual es requerida",
                    required
                ),
            },
            newPassword: {
                required: helpers.withMessage(
                    "La nueva contraseña es requerida",
                    required
                ),
                minLength: helpers.withMessage(
                    "La contraseña debe tener al menos 8 caracteres",
                    minLength(8)
                ),
            },
            confirmPassword: {
                required: helpers.withMessage(
                    "La confirmación de la contraseña es requerida",
                    required
                ),
                sameAsPassword: helpers.withMessage(
                    "Las contraseñas no coinciden",
                    sameAs(this.newPassword)
                ),
            },
        };
    },
    methods: {
        async cambiarEmail() {
            this.enviado = true;
            const isEmailValid = await this.v$.email.$validate();
            if (isEmailValid) {
                if (this.email === this.storeUsuario.usuario.email) {
                    Swal.fire({
                        title: "Error",
                        text: "Debes ingresar un correo electrónico diferente al actual",
                        icon: "error",
                        confirmButtonText: "Entendido",
                    });
                } else {
                    const result = await Swal.fire({
                        title: "¿Estás seguro?",
                        text: "¿Quieres cambiar tu dirección de correo electrónico?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Sí, cambiar",
                        cancelButtonText: "Cancelar",
                    });

                    if (result.isConfirmed) {
                        try {
                            await this.storeUsuario.cambiarCorreo(
                                this.storeUsuario.usuario.id,
                                this.email
                            );
                            Swal.fire(
                                "¡Cambiado!",
                                "Tu dirección de correo electrónico ha sido actualizada.",
                                "success"
                            );
                        } catch (error) {
                            Swal.fire(
                                "Error",
                                "No se pudo actualizar el correo electrónico.",
                                "error"
                            );
                        }
                    }
                }
            }
        },
        async submitForm() {
            this.enviado = true;
            const isFormValid = await this.v$.$validate();
            if (isFormValid) {
                const result = await Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¿Quieres cambiar tu contraseña?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, cambiar",
                    cancelButtonText: "Cancelar",
                });

                if (result.isConfirmed) {
                    try {
                        await this.storeUsuario.cambiarContrasena(
                            this.storeUsuario.usuario.id,
                            this.oldPassword,
                            this.newPassword
                        );
                        Swal.fire(
                            "¡Cambiado!",
                            "Tu contraseña ha sido actualizada.",
                            "success"
                        );
                        this.oldPassword = "";
                        this.newPassword = "";
                        this.confirmPassword = "";
                        this.enviado = false;
                    } catch (error) {
                        Swal.fire(
                            "Error",
                            "No se pudo actualizar la contraseña.",
                            "error"
                        );
                    }
                }
            }
        },
    },
    mounted() {
        this.email = this.storeUsuario.usuario.email || "";
    },
};
</script>
