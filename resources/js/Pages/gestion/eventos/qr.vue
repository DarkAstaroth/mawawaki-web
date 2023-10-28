<template>
    <div class="card card-bordered">
        <div class="card-header">
            <h3 class="card-title">Listado de QR's asignados</h3>
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
                                ? "Crear QR"
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
                        ? generarQR()
                        : actualizarEvento()
                        ">
                        <div class="modal-body">
                            <div class="form-group mb-5">
                                <VueDatePicker v-model="fecha_expiracion"></VueDatePicker>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                Cerrar
                            </button>
                            <button type="submit" class="btn btn-success">
                                Generar
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
                            <th class="min-w-150px">Codigo</th>
                            <th class="min-w-150px">Fecha expiracion</th>
                            <th class="min-w-150px">Usos</th>
                            <th class="min-w-150px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="qr in qrs" :key="qr.id">
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="px-2 d-flex flex-column">
                                        <div>
                                            {{ qr.CodigoQR }}
                                        </div>

                                    </div>
                                </div>
                            </td>

                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="px-2 d-flex flex-column">
                                        <div>
                                            hoy
                                        </div>

                                    </div>
                                </div>
                            </td>

                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="px-2 d-flex flex-column">
                                        <div>
                                            10
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
                                                <a class="dropdown-item" data-bs-toggle="modal"
                                                    :data-bs-target="`#${qr.CodigoQR}`"
                                                    @click="actualizarURL(qr.CodigoQR)"><i
                                                        class="bi bi-pencil-square fs-4"></i>
                                                    Generar</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>

                            <div class="modal fade" tabindex="-1" :id="qr.CodigoQR">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3 class="modal-title">
                                                Generar QR
                                            </h3>

                                            <!--begin::Close-->
                                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2"
                                                data-bs-dismiss="modal" aria-label="Close">
                                                <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span
                                                        class="path2"></span></i>
                                            </div>
                                            <!--end::Close-->
                                        </div>
                                        <!-- <form action=""> -->

                                        <div class="modal-body">
                                            <div class="d-flex justify-content-center">
                                                <qrcode-vue :value="valorQR" :size="size" level="Q" />
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                Cerrar
                                            </button>
                                            <button type="submit" class="btn btn-success">
                                                Imprimir
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </tr>
                        <tr v-if="qrs.length === 0">
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
import QrcodeVue from 'qrcode.vue'
import dayjs from 'dayjs';
import 'dayjs/locale/es'
import axios from "axios";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
dayjs.locale('es')


export default {
    name: "EventoDetalle",
    props: ['evento'],
    setup() { },
    data() {
        return {
            qrs: [],
            fecha_expiracion: null,
            valorQR: null,
            size: 200,
            idEvento: this.evento.id,
            busqueda: "",
            paginacion: {
                total: 0,
                porPagina: 10,
                paginaActual: 1,
                ultimaPagina: 1,
                desde: 0,
                hasta: 0,
            },
            modo: "detalle",
            enviado: false,
        };
    },
    template: '<qrcode-vue :value="value"></qrcode-vue>',
    components: { VueDatePicker, QrcodeVue },
    validations() { },
    mounted() {
        this.cargarQRS(1);
    },
    methods: {
        cargarQRS(pagina) {
            axios
                .get(`/api/qr/evento/${this.idEvento}?page=${pagina}&busqueda=${this.busqueda}`)
                .then((response) => {
                    this.qrs = response.data.qrs;
                    this.paginacion = response.data.paginacion;
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        generarQR() {
            axios
                .post("/api/qr/generar", {
                    idEvento: this.idEvento,
                })
                .then((response) => {
                    Swal.fire({
                        title: "Éxito",
                        text: "El QR se genero correctamente",
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Aceptar",
                        customClass: {
                            confirmButton: "btn btn-primary",
                        },
                    });
                    this.cargarQRS(1);
                })
                .catch((error) => {
                    console.log(error)
                    Swal.fire({
                        title: "Upss..",
                        text: "Hubo un error al generar el QR",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Aceptar",
                        customClass: {
                            confirmButton: "btn btn-primary",
                        },
                    });
                });
        },
        actualizarURL(codigo) {
            this.valorQR = "http://localhost:8000/asistencia/" + codigo
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
