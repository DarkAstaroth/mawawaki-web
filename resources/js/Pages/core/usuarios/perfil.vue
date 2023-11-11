<template>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="d-flex flex-column flex-xl-row">
                <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
                    <div class="card mb-5 mb-xl-8">
                        <div class="card-body pt-15">
                            <div class="d-flex flex-center flex-column mb-5">
                                <div class="symbol symbol-100px symbol-circle mb-7">
                                    <img :src="'/' + usuario.profile_photo_path" alt="image" />
                                </div>

                                <a href="#" class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1">{{
                                    usuario.persona.nombre }}</a>

                                <div class="fs-5 fw-semibold text-muted mb-6">
                                    Software Enginer
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-lg-row-fluid ms-lg-15">
                    <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8">
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab"
                                href="#kt_usuario_solicitud">Solicitud</a>
                        </li>

                        <li class="nav-item ms-auto">
                            <a href="#" class="btn btn-primary ps-7" data-kt-menu-trigger="click"
                                data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">Acciones
                                <i class="ki-duotone ki-down fs-2 me-0"></i></a>

                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold py-4 w-250px fs-6"
                                data-kt-menu="true">
                                <div class="menu-item px-5">
                                    <a href="#" class="menu-link px-5">Reporte</a>
                                </div>

                                <div class="menu-item px-5">
                                    <a href="#" class="menu-link text-danger px-5">Inactivar cuenta</a>
                                </div>
                            </div>
                        </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="#kt_usuario_solicitud" role="tabpanel">
                            <div class="card pt-4 mb-6 mb-xl-9">
                                <div class="card-header border-0">
                                    <div class="card-title">
                                        <h2>Datos Solicitud</h2>
                                    </div>

                                    <div class="card-toolbar">
                                        <button type="button" class="btn btn-sm btn-flex btn-success" data-bs-toggle="modal"
                                            data-bs-target="#kt_modal_add_payment" @click="verificarCuenta(usuario.id)">
                                            <i class="ki-duotone ki-check-square">
                                                <span class="path1"></span>
                                                <span class="path2"></span> </i>Verificar cuenta
                                        </button>
                                    </div>
                                </div>

                                <div class="card-body pt-0 pb-5">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <td>Nombres:</td>
                                                    <td>
                                                        <span v-if="usuario.persona
                                                                .nombre
                                                            ">{{
        usuario.persona
            .nombre
    }}</span>
                                                        <span v-else>No se registraron
                                                            nombres</span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>Apellido Paterno:</td>
                                                    <td>
                                                        <span v-if="usuario.persona
                                                                    .paterno
                                                                ">{{
            usuario.persona
                .paterno
        }}</span>
                                                        <span v-else>No se registró
                                                            apellido
                                                            paterno</span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>Apellido Materno:</td>
                                                    <td>
                                                        <span v-if="usuario.persona
                                                                    .materno
                                                                ">{{
            usuario.persona
                .materno
        }}</span>
                                                        <span v-else>No se registró
                                                            apellido
                                                            materno</span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>Email:</td>
                                                    <td>
                                                        <span v-if="usuario.email">{{
                                                            usuario.email
                                                        }}</span>
                                                        <span v-else>No se registró
                                                            email</span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>Estado:</td>
                                                    <td>
                                                        <span v-if="usuario.estado
                                                                "><span class="badge badge-success">Activo</span>
                                                        </span>
                                                        <span v-else>
                                                            <div class="d-flex gap-3">
                                                                <span v-if="usuario.solicitud ===
                                                                    1 &&
                                                                    usuario.verificada ===
                                                                    0
                                                                    ">
                                                                    <span class="badge badge-warning">En
                                                                        espera</span>
                                                                </span>
                                                                <span v-if="usuario.estado ===
                                                                    0
                                                                    "><span class="badge badge-danger">Inactivo</span>
                                                                </span>
                                                            </div>
                                                        </span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card card-bordered">
                        <div class="card-header">
                            <h3 class="card-title">Roles</h3>
                            <div class="div card-toolbar">
                                <button type="button" class="btn btn-sm btn-info" @click="actualizarRoles()">
                                    <i class="fa-solid fa-arrows-rotate"></i>
                                    Actualizar Roles
                                </button>
                            </div>
                        </div>

                        <div class="card-body">
                            <VueMultiselect :multiple="true" :close-on-select="true" v-model="rolesSeleccionados"
                                :options="roles" selectLabel="Selecionar rol" placeholder="Buscar rol..."
                                selectedLabel="Seleccionado" :show-labels="false">
                                <template v-slot:noResult>
                                    <span>No se encontraron resultados</span>
                                </template>
                            </VueMultiselect>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { useVuelidate } from "@vuelidate/core";
import VueMultiselect from "vue-multiselect";

export default {
    name: "usuarioPerfil",
    props: ["usuario"],
    components: { VueMultiselect },

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
