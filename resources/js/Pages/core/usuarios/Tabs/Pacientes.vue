<template>
    <div class="card card-bordered mb-10">
        <Dialog
            v-model:visible="modalPaciente"
            maximizable
            modal
            :header="modoEdicion ? 'Editar Paciente' : 'Crear Paciente'"
            :style="{ width: '50rem' }"
            :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
            @hide="limpiarModal"
        >
            <form
                @submit.prevent="
                    modoEdicion ? actualizarPaciente() : enviarSolicitud()
                "
            >
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
                            <label for="username">Carnet de Identidad</label>
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
                            <label for="username">Fecha de Nacimiento</label>
                            <Calendar
                                class="w-100"
                                id="calendar-12h"
                                v-model="datosPersona.fechaNacimiento"
                                :disabled="controlFecha"
                                :maxDate="new Date()"
                                dateFormat="dd/mm/yy"
                            />
                        </div>
                    </div>

                    <div class="col-12">
                        <h5>Datos Extra</h5>
                    </div>

                    <div class="col-md-12">
                        <div class="d-flex flex-column gap-2 mb-5">
                            <label>Contraindicación</label>
                            <div class="d-flex gap-4">
                                <div class="d-flex align-items-center">
                                    <RadioButton
                                        v-model="datosPaciente.contraindicacion"
                                        inputId="contraindicacion1"
                                        name="contraindicacion"
                                        value="SI"
                                    />
                                    <label for="contraindicacion1" class="ml-2"
                                        >Sí</label
                                    >
                                </div>
                                <div class="d-flex align-items-center">
                                    <RadioButton
                                        v-model="datosPaciente.contraindicacion"
                                        inputId="contraindicacion2"
                                        name="contraindicacion"
                                        value="NO"
                                    />
                                    <label for="contraindicacion2" class="ml-2"
                                        >No</label
                                    >
                                </div>
                            </div>
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
                                v-model="datosPaciente.contactoEmergenciaNombre"
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

                <Button type="submit">
                    {{ modoEdicion ? "Actualizar Paciente" : "Crear Paciente" }}
                </Button>
            </form>
        </Dialog>

        <div class="card-body">
            <DataTable
                :value="this.storePaciente.pacientes"
                :paginator="true"
                :rows="10"
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                :rowsPerPageOptions="[10, 20, 50]"
                currentPageReportTemplate="Mostrando {first} a {last} de {totalRecords} usuarios"
                :globalFilterFields="[
                    'persona.nombre',
                    'persona.paterno',
                    'persona.materno',
                    'persona.ci',
                ]"
                :filters="filters"
                filterDisplay="menu"
                dataKey="id"
                :rowHover="true"
                v-model:filters="filters"
                :resizableColumns="true"
                columnResizeMode="fit"
                class="p-datatable-sm"
            >
                <template #header>
                    <div
                        class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-4 mb-4"
                    >
                        <h5 class="text-xl font-bold m-0">Usuarios</h5>
                        <div
                            class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2 w-full sm:w-auto"
                        >
                            <span class="p-input-icon-left w-full sm:w-auto">
                                <i class="pi pi-search" />
                                <InputText
                                    v-model="filters['global'].value"
                                    placeholder="Buscar paciente"
                                    class="w-full"
                                />
                            </span>
                            <Button
                                label="Nuevo"
                                icon="pi pi-plus"
                                class="p-button-success w-full sm:w-auto"
                                @click="estadoModal(true)"
                            />
                        </div>
                    </div>
                </template>
                <Column field="persona.nombre" header="Nombre" sortable>
                    <template #body="slotProps">
                        <div class="flex align-items-center">
                            <img
                                :src="
                                    this.valorDominio +
                                    '/' +
                                    slotProps.data.profile_photo_path
                                "
                                :alt="slotProps.data.persona.nombre"
                                class="mr-2"
                                style="
                                    width: 32px;
                                    height: 32px;
                                    border-radius: 50%;
                                "
                            />
                            <span>
                                {{
                                    `${slotProps.data.persona.nombre} ${slotProps.data.persona.paterno} ${slotProps.data.persona.materno}`
                                }}
                            </span>
                        </div>
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            class="p-column-filter"
                            placeholder="Buscar por nombre"
                        />
                    </template>
                </Column>

                <Column field="persona.ci" header="CI" sortable>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            class="p-column-filter"
                            placeholder="Buscar por CI"
                        />
                    </template>
                </Column>

                <Column
                    field="contacto_emergencia_nombre"
                    header="Contacto de Emergencia"
                    sortable
                >
                    <template #body="slotProps">
                        {{
                            `${slotProps.data.contacto_emergencia_nombre} - ${slotProps.data.contacto_emergencia_telefono}`
                        }}
                    </template>
                </Column>

                <Column
                    field="persona.fecha_nacimiento"
                    header="Fecha de Nacimiento"
                    sortable
                >
                    <template #body="slotProps">
                        {{
                            formatDate(slotProps.data.persona.fecha_nacimiento)
                        }}
                    </template>
                </Column>

                <Column header="Edad" sortable :sortField="calcularEdad">
                    <template #body="slotProps">
                        {{
                            calcularEdad(
                                slotProps.data.persona.fecha_nacimiento
                            )
                        }}
                    </template>
                </Column>

                <Column field="estado" header="Estado" sortable>
                    <template #body="slotProps">
                        <Badge
                            :value="
                                slotProps.data.estado ? 'Activo' : 'Inactivo'
                            "
                            :severity="
                                slotProps.data.estado ? 'success' : 'danger'
                            "
                        />
                    </template>
                    <template #filter="{ filterModel }">
                        <Dropdown
                            v-model="filterModel.value"
                            :options="estadoOptions"
                            placeholder="Seleccionar estado"
                            class="p-column-filter"
                        />
                    </template>
                </Column>

                <Column
                    header="Acciones"
                    :exportable="false"
                    style="min-width: 8rem"
                >
                    <template #body="slotProps">
                        <Button
                            icon="pi pi-heart"
                            class="p-button-rounded p-button-success p-button-text"
                            @click="irAServicios(slotProps.data.id)"
                            v-tooltip.bottom="'Ir a servicios'"
                        />
                        <Button
                            icon="pi pi-eye"
                            class="p-button-rounded p-button-info p-button-text"
                            @click="verDetallesPaciente(slotProps.data)"
                        />
                        <Button
                            icon="pi pi-pencil"
                            class="p-button-rounded p-button-warning p-button-text"
                            @click="editarPaciente(slotProps.data)"
                            v-tooltip.bottom="'Editar'"
                        />

                        <Button
                            v-if="is('admin')"
                            icon="pi pi-times"
                            class="p-button-rounded p-button-danger p-button-text"
                            @click="
                                confirmarEliminarPaciente(
                                    $event,
                                    slotProps.data.id,
                                    this.usuario.id
                                )
                            "
                        />
                    </template>
                </Column>
            </DataTable>
        </div>
    </div>

    <Dialog
        v-model:visible="modalDetallesPaciente"
        modal
        position="top"
        header="Credencial usuario"
        :style="{ width: '40rem' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
    >
        <div class="flex gap-20 w-full justify-center">
            <div id="credencial" ref="elementToConvert">
                <div class="relative w-[300px]">
                    <div class="absolute inset-0">
                        <img
                            src="/assets/ilustraciones/credencial.png"
                            alt=""
                            crossorigin="anonymous"
                        />
                    </div>
                    <div class="relative z-10 h-[450px]">
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
        </div>

        <div class="flex mt-5 justify-center">
            <Button
                type="button"
                @click="convertAndDownloadImage"
                severity="info"
            >
                <div class="flex justify-center gap-2 w-full">
                    <i class="text-white fi fi-br-download"></i>
                    <strong>Descargar</strong>
                </div>
            </Button>
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
import Dialog from "primevue/dialog";
import { useDataPacientes } from "@/store/dataPaciente";
import QrcodeVue from "qrcode.vue";
import { toPng } from "html-to-image";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import Badge from "primevue/badge";
import { FilterMatchMode } from "primevue/api";

export default {
    name: "DocumentacionUsuario",
    props: ["usuario"],
    components: {
        Dropdown,
        Dialog,
        QrcodeVue,
        DataTable,
        Column,
        InputText,
        Button,
        Badge,
    },
    data() {
        return {
            valorDominio: import.meta.env.VITE_APP_BASE_URL,
            modoEdicion: false,
            pacientes: ref([]),
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
                contraindicacion: "",
                contactoEmergenciaNombre: "",
                contactoEmergenciaTelefono: "",
            },
            esResponsivo: false,
            valorDominio: import.meta.env.VITE_APP_BASE_URL,
            pacienteSeleccionado: null,
            modalPaciente: false,
            modalDetallesPaciente: false,
            loading: true,
            selectedPacientes: [],
            filters: {
                global: { value: null, matchMode: FilterMatchMode.CONTAINS },
                "persona.nombre": {
                    value: null,
                    matchMode: FilterMatchMode.STARTS_WITH,
                },
                "persona.ci": {
                    value: null,
                    matchMode: FilterMatchMode.STARTS_WITH,
                },
                estado: { value: null, matchMode: FilterMatchMode.EQUALS },
            },
            estadoOptions: ["Activo", "Inactivo"],
        };
    },
    mounted() {
        // this.cargarPacientes();
        this.storePaciente
            .cargarPacientesAction(this.usuario.id)
            .then((respuesta) => {
                this.pacientes = respuesta;
            });
        this.verificarResponsivo();
        window.addEventListener("resize", this.verificarResponsivo);
    },
    unmounted() {
        window.removeEventListener("resize", this.verificarResponsivo);
    },
    setup() {
        const toast = useToast();
        const selectedTipoDocumento = ref(null);
        const archivoSeleccionado = ref(null);
        const store = useDataPerfil();
        const storePaciente = useDataPacientes();
        const confirm = useConfirm();

        const actualizarPacientes = (nuevosPacientes) => {
            this.pacientes = nuevosPacientes;
        };

        const confirmarEliminarPaciente = (event, idPaciente, usuario) => {
            confirm.require({
                target: event.currentTarget,
                message: "¿Quieres borrar el registro?",
                icon: "pi pi-info-circle",
                acceptClass: "p-button-danger p-button-sm",
                acceptLabel: "Borrar",
                accept: () => {
                    storePaciente
                        .eliminarPaciente(idPaciente)
                        .then(() => {
                            Swal.fire({
                                title: "Éxito",
                                text: "Se borró el registro",
                                icon: "success",
                                confirmButtonText: "Aceptar",
                            });
                            storePaciente.cargarPacientesAction(usuario);
                        })
                        .catch((error) => {
                            Swal.fire({
                                title: "Error",
                                text: "No se pudo borrar el registro",
                                icon: "error",
                                confirmButtonText: "Aceptar",
                            });
                            console.error("Error al eliminar :", error);
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
            confirmarEliminarPaciente,
            actualizarPacientes,
        };
    },
    methods: {
        cargarPacientes() {
            this.storePaciente.cargarPacientesAction(this.usuario.id);
        },
        verificarResponsivo() {
            this.esResponsivo = window.innerWidth < 768;
        },
        estadoModal(estado) {
            this.modalPaciente = estado;
            if (!estado) {
                this.limpiarModal();
            }
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
                .then(() => {
                    this.toast.add({
                        severity: "success",
                        summary: "Registro exitoso",
                        detail: "El usuario ha sido registrado correctamente",
                        life: 3000,
                    });
                    this.modalPaciente = false;
                    this.limpiarModal();
                    this.cargarPacientes();
                })
                .catch((error) => {
                    this.toast.add({
                        severity: "error",
                        summary: "Error en el registro",
                        detail: "Ocurrió un error al registrar el usuario",
                        life: 3000,
                    });
                });
        },
        actualizarPaciente() {
            const pacienteActualizado = {
                id: this.pacienteSeleccionado.id,
                persona: {
                    nombre: this.datosPersona.nombre,
                    paterno: this.datosPersona.paterno,
                    materno: this.datosPersona.materno,
                    ci: this.datosPersona.ci,
                    fecha_nacimiento: Math.floor(
                        this.datosPersona.fechaNacimiento.getTime() / 1000
                    ),
                },
                contraindicacion: this.datosPaciente.contraindicacion,
                contacto_emergencia_nombre:
                    this.datosPaciente.contactoEmergenciaNombre,
                contacto_emergencia_telefono:
                    this.datosPaciente.contactoEmergenciaTelefono,
            };
            console.log(pacienteActualizado);

            this.storePaciente
                .actualizarPacienteModal(pacienteActualizado)
                .then(() => {
                    Swal.fire({
                        title: "Éxito",
                        text: "Paciente actualizado correctamente",
                        icon: "success",
                        confirmButtonText: "Aceptar",
                    });

                    this.cargarPacientes();
                })
                .catch((error) => {
                    Swal.fire({
                        title: "Error",
                        text: "No se pudo actualizar el paciente",
                        icon: "error",
                        confirmButtonText: "Aceptar",
                    });
                    console.error("Error al actualizar paciente:", error);
                });
            this.modalPaciente = false;
        },
        calcularEdad(fechaNacimiento) {
            const fechaNac = new Date(fechaNacimiento * 1000);
            const hoy = new Date();
            let edad = hoy.getFullYear() - fechaNac.getFullYear();
            const mes = hoy.getMonth() - fechaNac.getMonth();

            if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNac.getDate())) {
                edad--;
            }

            return edad;
        },

        limpiarModal() {
            this.modoEdicion = false;
            this.pacienteSeleccionado = null;
            this.datosPersona = {
                nombre: "",
                paterno: "",
                materno: "",
                ci: "",
                fechaNacimiento: "",
            };
            this.datosPaciente = {
                contraindicacion: "",
                contactoEmergenciaNombre: "",
                contactoEmergenciaTelefono: "",
            };
        },
        convertAndDownloadImage() {
            const element = this.$refs.elementToConvert;
            toPng(element, {
                cacheBust: true,
                useCORS: true,
            })
                .then((dataUrl) => {
                    const link = document.createElement("a");
                    link.href = dataUrl;
                    link.download = "credencial.png";
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                })
                .catch((error) => {
                    console.error("Error:", error);
                });
        },
        editarPaciente(paciente) {
            console.log(paciente);
            this.modoEdicion = true;
            this.pacienteSeleccionado = paciente;
            this.datosPersona = {
                nombre: paciente.persona.nombre,
                paterno: paciente.persona.paterno,
                materno: paciente.persona.materno,
                ci: paciente.persona.ci,
                fechaNacimiento: new Date(
                    paciente.persona.fecha_nacimiento * 1000
                ),
            };
            this.datosPaciente = {
                contraindicacion: paciente.precondicion,
                contactoEmergenciaNombre: paciente.contacto_emergencia_nombre,
                contactoEmergenciaTelefono:
                    paciente.contacto_emergencia_telefono,
            };
            this.modalPaciente = true;
        },
        irAServicios(pacienteId) {
            window.location.href = this.route("servicio.paciente", pacienteId);
        },
        formatDate(unixTimestamp) {
            return new Date(unixTimestamp * 1000).toLocaleDateString();
        },
    },
};
</script>
