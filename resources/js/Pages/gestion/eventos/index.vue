<template>
    <div class="card card-bordered">
        <div class="card-header">
            <h3 class="card-title">Listado de eventos</h3>
            <div class="div card-toolbar">
                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#kt_modal_1"
                    @click="
                        modo = 'crear';
                    resetModalData();
                    ">
                    <i class="text-white far fa-plus"></i>
                    Nuevo
                </button>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" id="kt_modal_1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">
                            {{
                                modo === "crear"
                                ? "Crear Evento"
                                : "Editar Evento"
                            }}
                        </h3>

                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                            aria-label="Close">
                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                        <!--end::Close-->
                    </div>
                    <!-- <form action=""> -->
                    <form class="input-feild" v-on:submit.prevent="
                    modo === 'crear'
                        ? crearEvento()
                        : actualizarEvento()
                        ">
                        <div class="modal-body">
                            <div class="mb-5 form-group">
                                <input type="text" v-model="nombre" id="" class="form-control"
                                    placeholder="Nombre del evento" aria-describedby="helpId" />
                                <div v-if="v$?.nombre.$error" class="m-2 fv-plugins-message-container invalid-feedback">
                                    <div data-field="text_input" data-validator="notEmpty">
                                        El nombre del evento es requirido
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-5">
                                <VueDatePicker v-model="fechaInicio"></VueDatePicker>
                            </div>

                            <div class="form-group mb-5">
                                <VueDatePicker v-model="fechaFin"></VueDatePicker>
                            </div>

                            <div class="mb-5 form-group">
                                <input type="text" v-model="lugar" id="" class="form-control" placeholder="Lugar del evento"
                                    aria-describedby="helpId" />
                                <div v-if="v$?.lugar.$error" class="m-2 fv-plugins-message-container invalid-feedback">
                                    <div data-field="text_input" data-validator="notEmpty">
                                        El lugar del evento es requirido
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <textarea class="form-control" v-model="descripcion" id="" rows="3"
                                    placeholder="Descripción"></textarea>
                                <div v-if="v$?.descripcion.$error"
                                    class="m-2 fv-plugins-message-container invalid-feedback">
                                    <div data-field="text_input" data-validator="notEmpty">
                                        La descripcion del lugar es requerida.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                Cerrar
                            </button>
                            <button type="submit" class="btn btn-success">
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body">
            <input class="mb-5 form-control" type="text" v-model="busqueda" @input="filtrarEventos"
                placeholder="Buscar..." />
            <div class="table-responsive">
                <table class="table table-striped table-sm table-bordered">
                    <thead>
                        <tr class="py-4 border-gray-200 fw-semibold fs-7 border-bottom">
                            <th class="min-w-150px">Nombre</th>
                            <th class="min-w-150px">Fecha Inicio</th>
                            <th class="max-w-100px">Fecha Fin</th>
                            <th class="max-w-100px">Lugar</th>
                            <th class="max-w-100px">Descripcion</th>
                            <th class="min-w-150px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="evento in eventos" :key="evento.id">
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="px-2 d-flex flex-column">
                                        <div>
                                            {{ evento.nombre }}
                                        </div>

                                    </div>
                                </div>
                            </td>

                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="px-2 d-flex flex-column">
                                        <div>
                                            {{ fechaHoraLegible(evento.fecha_hora_inicio) }}
                                        </div>

                                    </div>
                                </div>
                            </td>

                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="px-2 d-flex flex-column">
                                        <div>
                                            {{ fechaHoraLegible(evento.fecha_hora_fin) }}
                                        </div>

                                    </div>
                                </div>
                            </td>

                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="px-2 d-flex flex-column">
                                        <div>
                                            {{ evento.lugar }}
                                        </div>

                                    </div>
                                </div>
                            </td>

                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="px-2 d-flex flex-column">
                                        <div>
                                            {{ evento.descripcion }}
                                        </div>

                                    </div>
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

                                            </li>
                                            <li>
                                                <a :href="route('evento.detalle', { id: evento.id })"
                                                    class="dropdown-item"><i class="bi bi-pencil-square fs-4"></i>

                                                    Detalles</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="eventos.length === 0">
                            <td colspan="5" class="text-center">
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
import dayjs from 'dayjs';
import 'dayjs/locale/es'
import axios from "axios";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
dayjs.locale('es')


export default {
    name: "EventosIndex",
    components: { VueDatePicker },
    setup() { },
    data() {
        return {
            eventos: [],
            nombre: "",
            fechaInicio: null,
            fechaFin: null,
            lugar: "",
            descripcion: "",
            busqueda: "",
            paginacion: {
                total: 0,
                porPagina: 10,
                paginaActual: 1,
                ultimaPagina: 1,
                desde: 0,
                hasta: 0,
            },
            modo: "crear",
            enviado: false,
        };
    },
    validations() { },
    mounted() {
        this.cargarEventos(1);
    },
    methods: {
        filtrarEventos() {
            this.cargarEventos(1);
        },
        cargarEventos(pagina) {
            const url =
                "/api/eventos?page=" + pagina + "&busqueda=" + this.busqueda;
            axios
                .get(url)
                .then((response) => {
                    this.eventos = response.data.eventos;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        crearEvento() {
            axios
                .post("/api/evento", {
                    nombre: this.nombre,
                    fechaInicio: this.fechaInicio,
                    fechaFin: this.fechaFin,
                    lugar: this.lugar,
                    descripcion: this.descripcion,
                })
                .then(() => {
                    Swal.fire({
                        title: "Éxito",
                        text: "El evento fue creado correctamente",
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Aceptar",
                        customClass: {
                            confirmButton: "btn btn-primary",
                        },
                    });
                    $("#kt_modal_1").modal("hide");
                    this.busqueda = "";
                    this.cargarEventos(1);
                })
                .catch();
        },
        fechaHoraLegible(fecha) {
            return dayjs.unix(fecha).format('D [de] MMMM [-] H:mm');
        },
        resetModalData: function () {
            this.nombre = "";
            this.description = "";
        },
    },
};
</script>
