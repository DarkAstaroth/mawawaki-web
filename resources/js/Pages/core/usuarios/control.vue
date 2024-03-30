<template>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="row">
                <div class="col-12">
                    <div class="card pt-4 mb-6 mb-xl-9">
                        <div class="card-header border-0">
                            <div class="card-title">
                                <h2>Datos Solicitud</h2>
                            </div>

                            <div class="d-flex gap-2">
                                <div
                                    class="card-toolbar"
                                    v-if="!usuario?.personal?.codigo_personal"
                                >
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-flex btn-info"
                                        data-bs-toggle="modal"
                                        data-bs-target="#kt_modal_add_payment"
                                        @click="generarCodigo(usuario.id)"
                                    >
                                        <i class="ki-duotone ki-check-square">
                                            <span class="path1"></span>
                                            <span class="path2"></span> </i
                                        >Otorgar código
                                    </button>
                                </div>

                                <div class="card-toolbar">
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-flex btn-success"
                                        data-bs-toggle="modal"
                                        data-bs-target="#kt_modal_add_payment"
                                        @click="verificarCuenta(usuario.id)"
                                    >
                                        <i class="ki-duotone ki-check-square">
                                            <span class="path1"></span>
                                            <span class="path2"></span> </i
                                        >Verificar cuenta
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="card-body pt-0 pb-5">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td>Nombres:</td>
                                            <td>
                                                <span
                                                    v-if="
                                                        usuario?.persona?.nombre
                                                    "
                                                    >{{
                                                        usuario?.persona?.nombre
                                                    }}</span
                                                >
                                                <span v-else
                                                    >No se registraron
                                                    nombres</span
                                                >
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Apellido Paterno:</td>
                                            <td>
                                                <span
                                                    v-if="
                                                        usuario?.persona
                                                            ?.paterno
                                                    "
                                                    >{{
                                                        usuario?.persona
                                                            ?.paterno
                                                    }}</span
                                                >
                                                <span v-else
                                                    >No se registró apellido
                                                    paterno</span
                                                >
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Apellido Materno:</td>
                                            <td>
                                                <span
                                                    v-if="
                                                        usuario?.persona
                                                            ?.materno
                                                    "
                                                    >{{
                                                        usuario?.persona
                                                            ?.materno
                                                    }}</span
                                                >
                                                <span v-else
                                                    >No se registró apellido
                                                    materno</span
                                                >
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Email:</td>
                                            <td>
                                                <span v-if="usuario.email">{{
                                                    usuario.email
                                                }}</span>
                                                <span v-else
                                                    >No se registró email</span
                                                >
                                            </td>
                                        </tr>

                                        <tr v-if="usuario.personal">
                                            <td>Universidad:</td>
                                            <td>
                                                <span
                                                    v-if="
                                                        usuario.personal
                                                            .universidad
                                                    "
                                                    >{{
                                                        usuario.personal
                                                            .universidad
                                                    }}</span
                                                >
                                                <span v-else
                                                    >No se registró
                                                    universidad</span
                                                >
                                            </td>
                                        </tr>

                                        <tr v-if="usuario.personal">
                                            <td>Facultad:</td>
                                            <td>
                                                <span
                                                    v-if="
                                                        usuario.personal
                                                            .facultad
                                                    "
                                                    >{{
                                                        usuario.personal
                                                            .facultad
                                                    }}</span
                                                >
                                                <span v-else
                                                    >No se registró
                                                    facultad</span
                                                >
                                            </td>
                                        </tr>

                                        <tr v-if="usuario.personal">
                                            <td>Carrera:</td>
                                            <td>
                                                <span
                                                    v-if="
                                                        usuario.personal.carrera
                                                    "
                                                    >{{
                                                        usuario.personal.carrera
                                                    }}</span
                                                >
                                                <span v-else
                                                    >No se registró
                                                    carrera</span
                                                >
                                            </td>
                                        </tr>

                                        <tr
                                            v-if="
                                                usuario.tipo_solicitud ===
                                                'cliente'
                                            "
                                        >
                                            <td>Ocupación:</td>
                                            <td>
                                                <span
                                                    class="badge badge-success"
                                                    v-if="
                                                        usuario.tipo_solicitud
                                                    "
                                                    >{{
                                                        usuario.tipo_solicitud
                                                    }}</span
                                                >
                                                <span v-else>Sin Registro</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Tipo Solicitud:</td>
                                            <td>
                                                <span
                                                    class="badge badge-success"
                                                    v-if="
                                                        usuario.tipo_solicitud
                                                    "
                                                    >{{
                                                        usuario.tipo_solicitud
                                                    }}</span
                                                >
                                                <span v-else>Sin Registro</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Estado:</td>
                                            <td>
                                                <span v-if="usuario.estado"
                                                    ><span
                                                        class="badge badge-success"
                                                        >Activo</span
                                                    >
                                                </span>
                                                <span v-else>
                                                    <div class="d-flex gap-3">
                                                        <span
                                                            v-if="
                                                                usuario.solicitud ===
                                                                    1 &&
                                                                usuario.verificada ===
                                                                    0
                                                            "
                                                        >
                                                            <span
                                                                class="badge badge-warning"
                                                                >En espera</span
                                                            >
                                                        </span>
                                                        <span
                                                            v-if="
                                                                usuario.estado ===
                                                                0
                                                            "
                                                            ><span
                                                                class="badge badge-danger"
                                                                >Inactivo</span
                                                            >
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
                <div class="col-12 mb-5" v-if="is('admin')">
                    <div class="card card-bordered">
                        <div class="card-header">
                            <h3 class="card-title">Roles</h3>
                            <div class="div card-toolbar">
                                <button
                                    type="button"
                                    class="btn btn-sm btn-info"
                                    @click="actualizarRoles()"
                                >
                                    <i class="fa-solid fa-arrows-rotate"></i>
                                    Actualizar Roles
                                </button>
                            </div>
                        </div>

                        <div class="card-body">
                            <VueMultiselect
                                :multiple="true"
                                :close-on-select="true"
                                v-model="rolesSeleccionados"
                                :options="roles"
                                selectLabel="Selecionar rol"
                                placeholder="Buscar rol..."
                                selectedLabel="Seleccionado"
                                :show-labels="false"
                            >
                                <template v-slot:noResult>
                                    <span>No se encontraron resultados</span>
                                </template>
                            </VueMultiselect>
                        </div>
                    </div>
                </div>

                <div class="col-12 mb-5" v-if="is('admin')">
                    <div class="card card-bordered">
                        <div class="card-header">
                            <h3 class="card-title">Asistencia</h3>
                        </div>

                        <div class="card-body">
                            <AsistenciaGeneral :usuario="usuario" />
                        </div>
                    </div>
                </div>

                <div class="col-12 mb-5" v-if="is('admin')">
                    <div class="card card-bordered">
                        <div class="card-header">
                            <h3 class="card-title">Documentos</h3>
                        </div>

                        <div class="card-body">
                            <DocumentacionUsuario :usuario="usuario" />
                        </div>
                    </div>
                </div>

                <div class="col-12" v-if="is('admin')">
                    <ActividadesUsuario :usuario="usuario" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { useVuelidate } from "@vuelidate/core";
import VueMultiselect from "vue-multiselect";
import DocumentacionUsuario from "./Tabs/Documentacion.vue";
import ActividadesUsuario from "./actividades.vue";
import AsistenciaGeneral from "./Tabs/AsistenciaGeneral.vue";

export default {
    name: "UsuarioControl",
    props: ["usuario"],
    components: {
        VueMultiselect,
        DocumentacionUsuario,
        ActividadesUsuario,
        AsistenciaGeneral,
    },

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
                text: "¡Esta acción habilitará al usuario!",
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
                        .catch((error) => {});
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

        generarCodigo(usuarioId) {
            Swal.fire({
                title: "¿Estás seguro?",
                text: "¡Esta acción otorgara un código único!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, continuar",
                cancelButtonText: "Cancelar",
            }).then((result) => {
                if (result.isConfirmed) {
                    axios
                        .post(`/api/personal/generar-codigo/${usuarioId}`)
                        .then((response) => {
                            Swal.fire({
                                title: "Éxito",
                                text: "¡Codigo asignado correctamente!",
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Aceptar",
                                customClass: {
                                    confirmButton: "btn btn-primary",
                                },
                            });
                        })
                        .catch((error) => {});
                }
            });
        },
    },
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>
