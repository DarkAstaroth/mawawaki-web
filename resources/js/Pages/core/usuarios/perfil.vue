<template>
    <PerfilGlobal :usuario="usuario" />
</template>

<script>
import { useVuelidate } from "@vuelidate/core";
import VueMultiselect from 'vue-multiselect'
import PerfilGlobal from './perfilGlobal.vue'

export default {
    name: "usuarioPerfil",
    props: ["usuario"],
    components: { VueMultiselect, PerfilGlobal },

    setup() {
        return { v$: useVuelidate() };
    },

    data() {
        return {
            usuario: this.usuario,
            enviado: false,
            rolesSeleccionados: this.usuario.roles
                .filter((usuario) => usuario.name !== "invitado")
                .map((usuario) => usuario.name),
            roles: [],
        };
    },
    validations() {
        return {};
    },
    mounted() {
        this.cargarRoles(1);
    },
    methods: {
        verificarCuenta(usuarioId) {
            Swal.fire({
                title: "¿Estás seguro?",
                text: "¡Esta habilitará al usuario!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, verificar",
                cancelButtonText: "Cancelar",
            }).then((result) => {
                if (result.isConfirmed) {
                    axios
                        .put(`/api/verificar/usuario/${usuarioId}`)
                        .then((response) => {
                            this.usuario = response.data.usuario;
                            Swal.fire({
                                title: "Éxito",
                                text: "¡Usuario verificado correctamente!",
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Aceptar",
                                customClass: {
                                    confirmButton: "btn btn-primary",
                                },
                            });
                        })
                        .catch((error) => { });
                }
            });
        },
        cargarRoles: function (pagina) {
            axios
                .get("/api/roles")
                .then((response) => {
                    this.roles = response.data.roles.map((rol) => rol.name);
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        actualizarRoles() {
            axios
                .put(`/api/sincronizar/rol/usuario/${this.usuario.id}`, {
                    roles: this.rolesSeleccionados,
                })
                .then((response) => {
                    Swal.fire({
                        title: "Éxito",
                        text: "Roles actualizados correctamente",
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Aceptar",
                        customClass: {
                            confirmButton: "btn btn-primary",
                        },
                    });
                })
                .catch((error) => {
                    console.error(error);
                });
        },
    },
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>
