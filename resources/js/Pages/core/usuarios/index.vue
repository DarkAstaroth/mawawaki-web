<template>
    <div class="row">
        <div class="col-6 col-md-2 mb-5">
            <div class="card">
                <div class="card-header p-5">
                    <div class="w-100 d-flex flex-column justify-content-center align-items-center">
                        <div>
                            <span class="fs-3x fw-bold p-2">{{
                                ficha.activos
                            }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="me-3 text-success ki-duotone ki-user-square fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                            <span class="opacity-75 pt-1 fw-semibold fs-6">Activos</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-2 mb-5">
            <div class="card">
                <div class="card-header p-5">
                    <div class="w-100 d-flex flex-column justify-content-center align-items-center">
                        <div>
                            <span class="fs-3x fw-bold p-2">{{
                                ficha.no_activos
                            }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="me-3 text-danger ki-duotone ki-user-square fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                            <span class="opacity-75 pt-1 fw-semibold fs-6">Inactivos</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-2 mb-5">
            <div class="card">
                <div class="card-header p-5">
                    <div class="w-100 d-flex flex-column justify-content-center align-items-center">
                        <div>
                            <span class="fs-3x fw-bold p-2">{{
                                ficha.solicitudes
                            }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="me-3 text-info ki-duotone ki-questionnaire-tablet fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <span class="opacity-75 pt-1 fw-semibold fs-6">Solicitudes</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-2 mb-5">
            <div class="card">
                <div class="card-header p-5">
                    <div class="w-100 d-flex flex-column justify-content-center align-items-center">
                        <div>
                            <span class="fs-3x fw-bold p-2">{{
                                ficha.verificados
                            }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="me-3 text-success ki-duotone ki-shield-tick fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <span class="opacity-75 pt-1 fw-semibold fs-6">Verificados</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-2 mb-5">
            <div class="card">
                <div class="card-header p-5">
                    <div class="w-100 d-flex flex-column justify-content-center align-items-center">
                        <div>
                            <span class="fs-3x fw-bold p-2">{{
                                ficha.por_verificar
                            }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="me-3 text-warning ki-duotone ki-shield-tick fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <span class="opacity-75 pt-1 fw-semibold fs-6">Por verificar</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-bordered">
        <div class="card-header">
            <h3 class="card-title">Listado de usuarios</h3>
            <div class="div card-toolbar">
                <button type="button" class="btn btn-sm btn-success">
                    <i class="text-white far fa-plus"></i>
                    Nuevo
                </button>
            </div>
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_1"
                        @click="cambiarParametro('todos')">Todos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_2"
                        @click="cambiarParametro('activos')">Activos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_3"
                        @click="cambiarParametro('solicitudes')">Solicitudes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_4"
                        @click="cambiarParametro('verificados')">Verificados</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_5"
                        @click="cambiarParametro('porVerificar')">Por verificar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_6"
                        @click="cambiarParametro('inactivos')">Inactivos
                        <!-- <span class="bullet bullet-dot bg-danger translate-middle top-0 start-50 animation-blink">
                        </span> -->
                    </a>
                </li>
            </ul>
            <input class="mb-5 form-control" type="text" v-model="busqueda" @input="filtrarUsuarios"
                placeholder="Buscar..." />
            <div class="table-responsive">
                <table class="table table-striped table-sm table-bordered">
                    <thead>
                        <tr class="py-4 border-gray-200 fw-semibold fs-7 border-bottom">
                            <th class="min-w-150px">Nombre</th>
                            <th class="min-w-150px">Rol</th>
                            <th class="max-w-100px">Creado en</th>
                            <th class="max-w-100px">Estado</th>
                            <th class="min-w-150px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="usuario in usuarios" :key="usuario.id">
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <img :src="usuario.profile_photo_path" alt="foto" class="p-1 rounded-pill" width="50" />
                                    <div class="px-2 d-flex flex-column">
                                        <div>
                                            {{ usuario.name }}
                                        </div>
                                        <div class="py-1 bd-highlight">
                                            <small>
                                                {{ usuario.email }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">
                                <div class="d-flex gap-2">
                                    <div v-for="urol in usuario.roles" :key="urol.id">
                                        <span class="badge badge-secondary">{{
                                            urol.name
                                        }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">
                                {{
                                    new Date(usuario.created_at).toLocaleString(
                                        "es-ES",
                                        {
                                            weekday: "long",
                                            day: "numeric",
                                            month: "long",
                                            year: "numeric",
                                            hour: "numeric",
                                            minute: "numeric",
                                            second: "numeric",
                                        }
                                    )
                                }}
                            </td>
                            <td class="align-items-center">
                                <div class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" :id="usuario.id"
                                        :checked="usuario.estado" @click="cambiarEstado(usuario)" />
                                </div>
                            </td>
                            <td class="align-items-center">
                                <div class="d-flex">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle btn-sm" type="button"
                                            id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"
                                            data-boundary="viewport">
                                            Acciones
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            <li>
                                                <a class="dropdown-item" data-bs-toggle="tooltip"
                                                    data-bs-custom-class="tooltip-inverse" data-bs-placement="bottom"
                                                    title="Ver perfil"
                                                    :href="route('usuario.control', { id: usuario.id })"><i
                                                        class="bi bi-eye-fill fs-4"></i>
                                                    Ver Perfil</a>
                                            </li>
                                            <li>
                                                <a href="#" class="dropdown-item" data-bs-toggle="modal"
                                                    data-bs-target="#kt_modal_1"><i class="bi bi-pencil-square fs-4"></i>

                                                    Editar</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="usuarios.length === 0">
                            <td colspan="4" class="text-center">
                                No hay datos
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "UsuariosIndex",
    setup() { },
    data() {
        return {
            ficha: {
                activos: 0,
                no_activos: 0,
                solicitudes: 0,
                verificados: 0,
                por_verificar: 0,
            },
            usuarios: [],
            busqueda: "",
            parametro: "todos",
            paginacion: {
                total: 0,
                porPagina: 10,
                paginaActual: 1,
                ultimaPagina: 1,
                desde: 0,
                hasta: 0,
            },
        };
    },
    validations() { },
    mounted() {
        this.cargarUsuarios(1);
        this.cargarFichasUsuario();
    },
    methods: {
        filtrarUsuarios() {
            this.cargarUsuarios(1);
        },
        cambiarParametro(tabSeleccionada) {
            this.parametro = tabSeleccionada;
            this.cargarUsuarios(1);
        },
        cargarUsuarios(pagina) {
            const url =
                "/api/usuarios?page=" +
                pagina +
                "&busqueda=" +
                this.busqueda +
                "&parametro=" +
                this.parametro;
            axios
                .get(url)
                .then((response) => {
                    this.usuarios = response.data.usuarios;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        cargarFichasUsuario() {
            const url = `/api/fichasUsuarios`;
            axios
                .get(url)
                .then((response) => {
                    this.ficha.activos = response.data.activos;
                    this.ficha.no_activos = response.data.no_activos;
                    this.ficha.solicitudes = response.data.solicitudes;
                    this.ficha.verificados = response.data.verificados;
                    this.ficha.por_verificar = response.data.por_verificar;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        cambiarEstado(usuario) {
            if (usuario.estado) {
                this.mostrarModalConfirmacion(usuario, "¡Esta deshabilitará al usuario!", "Sí, deshabilitar");
            } else {
                this.mostrarModalConfirmacion(usuario, "¡Esta habilitará al usuario!", "Sí, habilitar");
            }


        },
        mostrarModalConfirmacion(usuario, mensaje, textoBoton) {
            return Swal.fire({
                title: "¿Estás seguro?",
                text: mensaje,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: textoBoton,
                cancelButtonText: "Cancelar",
            }).then((result) => {
                if (result.isConfirmed) {
                    axios
                        .put(`/api/estado/usuario/${usuario.id}`, { estado: usuario.estado })
                        .then((response) => {
                            Swal.fire({
                                title: "Éxito",
                                text: "¡Estado actualizado correctamente!",
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Aceptar",
                                customClass: {
                                    confirmButton: "btn btn-primary",
                                },
                            });
                            $(`#${usuario.id}`).prop("checked", usuario.estado);

                        })
                        .catch((error) => { });
                } else {
                    $(`#${usuario.id}`).prop("checked", usuario.estado);
                }
                this.cargarUsuarios(1);
                this.cargarFichasUsuario();
            });
        }
    },
};
</script>
