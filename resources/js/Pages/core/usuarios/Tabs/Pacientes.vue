<template>
    <div class="card card-bordered mb-10">
        <div class="card-header">
            <h3 class="card-title">Listado de Pacientes</h3>
            <div class="card-toolbar">
                <button
                    type="button"
                    class="btn btn-sm btn-success"
                    data-bs-toggle="modal"
                    data-bs-target="#kt_modal_1"
                    @click="estadoModal(true)"
                >
                    <i class="far fa-plus text-white"></i>
                    Nuevo
                </button>
            </div>
            <Dialog
                v-model:visible="modalPaciente"
                maximizable
                modal
                header="Crear Solicitud"
                :style="{ width: '50rem' }"
                :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
            >
                <form @submit.prevent="enviarSolicitud">
                    <div class="row">
                        <div class="col">
                            <h5>Datos Personales</h5>
                        </div>
                        <div class="col-md-12">
                            <div class="d-flex flex-column gap-2 mb-5">
                                <label for="username">Nombre</label>
                                <InputText
                                    class="uppercase"
                                    id="username"
                                    v-model="datosPersona.nombre"
                                    aria-describedby="username-help"
                                />
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex flex-column gap-2 mb-5">
                                <label for="username">Apellido Paterno</label>
                                <InputText
                                    class="uppercase"
                                    id="username"
                                    v-model="datosPersona.paterno"
                                    aria-describedby="username-help"
                                />
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex flex-column gap-2 mb-5">
                                <label for="username">Apellido materno</label>
                                <InputText
                                    class="uppercase"
                                    id="username"
                                    v-model="datosPersona.materno"
                                    aria-describedby="username-help"
                                />
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex flex-column gap-2 mb-5">
                                <label for="username"
                                    >Carnet de Identidad</label
                                >
                                <InputText
                                    class="uppercase"
                                    id="username"
                                    v-model="datosPersona.ci"
                                    aria-describedby="username-help"
                                />
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex flex-column gap-2 mb-5">
                                <label for="username"
                                    >Fecha de Nacimiento</label
                                >
                                <Calendar
                                    class="w-100"
                                    id="calendar-12h"
                                    v-model="datosPersona.fechaNacimiento"
                                    :disabled="controlFecha"
                                />
                            </div>
                        </div>

                        <div class="col-12">
                            <h5>Datos Paciente</h5>
                        </div>

                        <div class="col-md-12">
                            <div class="d-flex flex-column gap-2 mb-5">
                                <label for="username">Estado de Salud</label>
                                <InputText
                                    class="uppercase"
                                    id="username"
                                    v-model="datosPaciente.estadoSalud"
                                    aria-describedby="username-help"
                                />
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="d-flex flex-column gap-2 mb-5">
                                <label for="username">Precondicion</label>
                                <InputText
                                    class="uppercase"
                                    id="username"
                                    v-model="datosPaciente.precondicion"
                                    aria-describedby="username-help"
                                />
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex flex-column gap-2 mb-5">
                                <label for="contactoEmergenciaNombre"
                                    >Contacto de Emergencia - Nombre</label
                                >
                                <InputText
                                    class="uppercase"
                                    id="contactoEmergenciaNombre"
                                    v-model="
                                        datosPaciente.contactoEmergenciaNombre
                                    "
                                />
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex flex-column gap-2 mb-5">
                                <label for="contactoEmergenciaTelefono"
                                    >Contacto de Emergencia - Teléfono</label
                                >
                                <InputText
                                    class="uppercase"
                                    id="contactoEmergenciaTelefono"
                                    v-model="
                                        datosPaciente.contactoEmergenciaTelefono
                                    "
                                />
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Enviar Solicitud
                    </button>
                </form>
            </Dialog>
        </div>
        <div class="card-body">
            <input
                class="form-control mb-5"
                type="text"
                v-model="busqueda"
                @input="filtrarPacientes"
                placeholder="Buscar..."
            />

            <div class="col-12 col-md-12">
                <div class="table-responsive">
                    <table
                        v-if="this.pacientes.length > 0"
                        class="table table-bordered"
                    >
                        <thead>
                            <tr
                                class="py-4 border-gray-200 fw-semibold fs-7 border-bottom"
                            >
                                <th class="min-w-150px">Nombre</th>
                                <th class="min-w-150px">CI</th>
                                <th class="min-w-150px">Estado</th>
                                <th class="min-w-150px">
                                    Contacto de Emergencia
                                </th>
                                <th class="min-w-150px">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="paciente in this.pacientes"
                                :key="paciente.id"
                            >
                                <td>
                                    {{ paciente.persona.nombre }}
                                    {{ paciente.persona.paterno }}
                                    {{ paciente.persona.materno }}
                                </td>
                                <td>{{ paciente.persona.ci }}</td>

                                <td>
                                    <Badge
                                        :value="
                                            paciente.estado
                                                ? 'Activo'
                                                : 'Inactivo'
                                        "
                                        :severity="
                                            paciente.estado
                                                ? 'success'
                                                : 'danger'
                                        "
                                    ></Badge>
                                </td>

                                <td>
                                    {{ paciente.contacto_emergencia_nombre }} -
                                    {{ paciente.contacto_emergencia_telefono }}
                                </td>
                                <td>
                                    <a
                                        :href="
                                            route(
                                                'servicio.paciente',
                                                paciente.id
                                            )
                                        "
                                        class="btn btn-outline btn-outline-dashed btn-outline-default"
                                    >
                                        <i
                                            class="ki-duotone ki-like-2 fs-1 text-primary"
                                        >
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        Servicios
                                    </a>

                                    <Button
                                        v-tooltip.bottom="{
                                            value: 'Ver detalles',
                                            showDelay: 300,
                                            hideDelay: 300,
                                        }"
                                        @click="verDetallesPaciente(paciente)"
                                        icon="pi pi-eye"
                                        severity="info"
                                        text
                                        rounded
                                        aria-label="Ver"
                                    />

                                    <Button
                                        v-if="is('admin')"
                                        v-tooltip.bottom="{
                                            value: 'Eliminar',
                                            showDelay: 300,
                                            hideDelay: 300,
                                        }"
                                        @click="
                                            confirmarEliminarPaciente(
                                                $event,
                                                paciente.id
                                            )
                                        "
                                        icon="pi pi-times"
                                        severity="danger"
                                        text
                                        rounded
                                        aria-label="Eliminar"
                                    />
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div
                        v-else
                        class="text-center py-4 flex flex-col items-center"
                    >
                        <img
                            src="/assets/ilustraciones/sin_doc.svg"
                            alt=""
                            width="150"
                            height="150"
                        />
                        <p>No hay pacientes registrados.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <Dialog
        v-model:visible="modalDetallesPaciente"
        maximizable
        modal
        header="Detalles Paciente"
        :style="{ width: '80rem', height: '700px' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
    >
        <div class="flex gap-20 w-full">
            <div id="credencial" ref="elementToConvert">
                <div class="relative w-[300px]">
                    <div class="absolute inset-0">
                        <img
                            src="/assets/ilustraciones/credencial.png"
                            alt=""
                            crossorigin="anonymous"
                        />
                    </div>
                    <div class="relative z-10 h-[700px]">
                        <div class="h-full">
                            <div class="flex flex-col items-center">
                                <div class="container_usuario mt-20">
                                    <img
                                        :src="
                                            '/'.concat(
                                                this.pacienteSeleccionado
                                                    .profile_photo_path
                                            )
                                        "
                                        alt=""
                                        class="crop"
                                        width="150"
                                        crossorigin="anonymous"
                                    />
                                </div>

                                <div
                                    class="flex flex-col items-center font-bold text-xl text-gray-900 mt-5"
                                >
                                    <span>
                                        {{
                                            this.pacienteSeleccionado.persona
                                                .nombre
                                        }}
                                    </span>

                                    <a>
                                        {{
                                            this.pacienteSeleccionado.persona
                                                .paterno
                                        }}
                                        {{
                                            this.pacienteSeleccionado.persona
                                                .materno
                                        }}
                                    </a>
                                </div>

                                <div
                                    v-if="this.pacienteSeleccionado.codigo"
                                    class="mt-5"
                                >
                                    <qrcode-vue
                                        :value="
                                            this.pacienteSeleccionado.codigo
                                        "
                                        :size="90"
                                        level="L"
                                        foreground="#102820"
                                        background="transparent"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full">
                <div
                    class="w-full p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700"
                >
                    <h5
                        class="mb-4 text-xl font-medium text-gray-500 dark:text-gray-400"
                    >
                        Standard plan
                    </h5>
                    <div
                        class="flex items-baseline text-gray-900 dark:text-white"
                    >
                        <span class="text-3xl font-semibold">$</span>
                        <span class="text-5xl font-extrabold tracking-tight"
                            >49</span
                        >
                        <span
                            class="ms-1 text-xl font-normal text-gray-500 dark:text-gray-400"
                            >/month</span
                        >
                    </div>
                    <ul role="list" class="space-y-5 my-7">
                        <li class="flex items-center">
                            <svg
                                class="flex-shrink-0 w-4 h-4 text-blue-700 dark:text-blue-500"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"
                                />
                            </svg>
                            <span
                                class="text-base font-normal leading-tight text-gray-500 dark:text-gray-400 ms-3"
                                >2 team members</span
                            >
                        </li>
                        <li class="flex">
                            <svg
                                class="flex-shrink-0 w-4 h-4 text-blue-700 dark:text-blue-500"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"
                                />
                            </svg>
                            <span
                                class="text-base font-normal leading-tight text-gray-500 dark:text-gray-400 ms-3"
                                >20GB Cloud storage</span
                            >
                        </li>
                        <li class="flex">
                            <svg
                                class="flex-shrink-0 w-4 h-4 text-blue-700 dark:text-blue-500"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"
                                />
                            </svg>
                            <span
                                class="text-base font-normal leading-tight text-gray-500 dark:text-gray-400 ms-3"
                                >Integration help</span
                            >
                        </li>
                        <li class="flex line-through decoration-gray-500">
                            <svg
                                class="flex-shrink-0 w-4 h-4 text-gray-400 dark:text-gray-500"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"
                                />
                            </svg>
                            <span
                                class="text-base font-normal leading-tight text-gray-500 ms-3"
                                >Sketch Files</span
                            >
                        </li>
                        <li class="flex line-through decoration-gray-500">
                            <svg
                                class="flex-shrink-0 w-4 h-4 text-gray-400 dark:text-gray-500"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"
                                />
                            </svg>
                            <span
                                class="text-base font-normal leading-tight text-gray-500 ms-3"
                                >API Access</span
                            >
                        </li>
                        <li class="flex line-through decoration-gray-500">
                            <svg
                                class="flex-shrink-0 w-4 h-4 text-gray-400 dark:text-gray-500"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"
                                />
                            </svg>
                            <span
                                class="text-base font-normal leading-tight text-gray-500 ms-3"
                                >Complete documentation</span
                            >
                        </li>
                        <li class="flex line-through decoration-gray-500">
                            <svg
                                class="flex-shrink-0 w-4 h-4 text-gray-400 dark:text-gray-500"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"
                                />
                            </svg>
                            <span
                                class="text-base font-normal leading-tight text-gray-500 ms-3"
                                >24×7 phone & email support</span
                            >
                        </li>
                    </ul>
                    <button
                        type="button"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-200 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-900 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex justify-center w-full text-center"
                    >
                        Choose plan
                    </button>
                </div>
            </div>
        </div>
    </Dialog>

    <ConfirmPopup></ConfirmPopup>
    <Toast />
</template>

<script>
import { useDataPerfil } from "../../../../store/dataPerfil";
import axios from "axios";
import { useToast } from "primevue/usetoast";
import Dropdown from "primevue/dropdown";
import { ref } from "vue";
import { useConfirm } from "primevue/useconfirm";
import.meta.env.VITE_APP_BASE_URL;
import Dialog from "primevue/dialog";
import { useDataPacientes } from "@/store/dataPaciente";
import QrcodeVue from "qrcode.vue";

export default {
    name: "DocumentacionUsuario",
    props: ["usuario"],
    components: { Dropdown, Dialog, QrcodeVue },
    data() {
        return {
            usuario: this.usuario,
            pacientes: [],
            datosPersona: {
                nombre: "",
                paterno: "",
                materno: "",
                ci: "",
                fechaNacimiento: "",
            },
            datosPaciente: {
                tipoPaciente: "",
                fechaIngreso: "",
                estadoSalud: "",
                precondicion: "",
                contactoEmergenciaNombre: "",
                contactoEmergenciaTelefono: "",
            },
            esResponsivo: false,
            valorDominio: import.meta.env.VITE_APP_BASE_URL,
            pacienteSeleccionado: null,
            modalPaciente: false,
            modalDetallesPaciente: false,
        };
    },
    mounted() {
        this.cargarPacientes(1);
    },
    created() {
        this.verificarResponsivo();
        window.addEventListener("resize", this.verificarResponsivo);
    },
    destroyed() {
        window.removeEventListener("resize", this.verificarResponsivo);
    },
    setup() {
        const toast = useToast();
        const selectedTipoDocumento = ref(null);
        const archivoSeleccionado = ref(null);
        const store = useDataPerfil();
        const storePaciente = useDataPacientes();
        const confirm = useConfirm();

        const confirm2 = (event, idArchivo) => {
            confirm.require({
                target: event.currentTarget,
                message: "¿Quieres borrar el registro?",
                icon: "pi pi-info-circle",
                rejectClass:
                    "p-button-secondary p-button-outlined p-button-sm me-5",
                acceptClass: "p-button-danger p-button-sm",
                rejectLabel: "Cancelar",
                acceptLabel: "Borrar",
                accept: () => {
                    store.eliminarDocumento(idArchivo).then(() => {
                        store.obtenerDocumentosUsuario(pacienteSeleccionado.id);
                        toast.add({
                            severity: "success",
                            summary: "Confirmado",
                            detail: "Se borró el archivo",
                            life: 3000,
                        });
                    });
                },
                reject: () => {
                    toast.add({
                        severity: "error",
                        summary: "Cancelado",
                        detail: "No se realizó la acción",
                        life: 3000,
                    });
                },
            });
        };

        return {
            store,
            storePaciente,
            toast,
            selectedTipoDocumento,
            archivoSeleccionado,
            confirm2,
        };
    },

    methods: {
        cargarPacientes(pagina) {
            const url =
                "/api/pacientes/cliente/" +
                this.usuario.id +
                "?page=" +
                pagina +
                "&busqueda=" +
                this.busqueda +
                "&parametro=" +
                this.parametro;
            axios
                .get(url)
                .then((response) => {
                    this.pacientes = response.data.pacientes;
                    console.log(this.pacientes);
                })
                .catch((error) => {});
        },
        verificarResponsivo() {
            this.esResponsivo = window.innerWidth < 768;
        },
        estadoModal: function (estado) {
            this.modalPaciente = estado;
        },
        verDetallesPaciente(paciente) {
            this.pacienteSeleccionado = paciente;
            this.modalDetallesPaciente = true;
        },
        enviarSolicitud() {
            this.storePaciente
                .crearPaciente(
                    this.usuario,
                    this.datosPersona,
                    this.datosPaciente
                )
                .then((respuesta) => {
                    console.log(respuesta);
                    Swal.fire({
                        title: "Registro exitoso",
                        text: respuesta.data.message,
                        icon: "success",
                    });
                    this.limpiarModal();
                    this.modalPaciente = false;
                    this.cargarPacientes(1);
                })
                .catch((error) => {
                    console.log(error);
                    Swal.fire({
                        title: "Registro exitoso",
                        // text: respuesta.data.message,
                        icon: "error    ",
                    });
                });
        },
        limpiarModal() {
            this.datosPersona = {
                nombre: "",
                paterno: "",
                materno: "",
                ci: "",
                fechaNacimiento: "",
            };
            this.datosPaciente = {
                tipoPaciente: "",
                estadoSalud: "",
                precondicion: "",
                contactoEmergenciaNombre: "",
                contactoEmergenciaTelefono: "",
            };
        },
        irAServicios(paciente) {
            this.$router.push({
                name: "servicios.paciente",
                params: { id: paciente.id },
            });
        },
    },
};
</script>
