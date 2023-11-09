<template>
    <div class="card card-bordered">
        <div class="card-header">
            <h3 class="card-title">Listado de QR's asignados</h3>
            <div class="div card-toolbar">
                <button type="button" class="btn btn-sm btn-success" @click="
                    modo = 'crear';
                resetModalData();
                ">
                    <i class="text-white far fa-plus"></i>
                    Nuevo
                </button>
            </div>
        </div>


        <Dialog v-model:visible="modalCrearQR" modal header="generar QR" position="top" :style="{ width: '50rem' }"
            :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <form class="input-feild" v-on:submit.prevent="
                modo === 'crear'
                    ? generarQR()
                    : actualizarEvento()
                ">
                <div class="py-5">
                    <div class="form-group mb-5">
                        <div class="row">
                            <div class="col col-md-6">
                                <div class="d-flex flex-column gap-2">
                                    <label for="username">Fecha de expiración</label>
                                    <Calendar class="w-100" id="calendar-12h" v-model="fecha_expiracion" showTime
                                        hourFormat="12" />
                                </div>
                            </div>
                            <div class="col col-md-6">
                                <div class="d-flex flex-column gap-2">
                                    <label for="username">Cantidad de usos</label>
                                    <InputText id="username"  type="number" v-model="value" aria-describedby="username-help" />
                                </div>
                            </div>
                        </div>

                        <div class="row mt-5">
                            <div class="col">
                                <button class="btn btn-sm btn-success">Generar</button>
                            </div>
                        </div>



                        <!-- <VueDatePicker class="startDate" v-model="fecha_expiracion" placeholder="Fecha de vencimiento"
                            format="yyyy/MM/dd">
                        </VueDatePicker> -->
                    </div>
                </div>


            </form>
        </Dialog>





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


                            <Dialog v-model:visible="modalQR" modal header="QR Generado" :style="{ width: '50rem' }"
                                :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="d-flex w-100 px-10">
                                        <qrcode-vue :value="valorQR" :size="200" level="Q"
                                            :ref="`codigoQR${qr.CodigoQR}`" />
                                        <div class="mx-10">
                                            <table class="table table-bordered">

                                                <tbody>
                                                    <tr>
                                                        <th scope="row">Evento</th>
                                                        <td>{{ nombreEvento }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Fecha limite</th>
                                                        <td>Jacob</td>

                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end w-100 mt-2">
                                        <button type="submit" class="btn btn-success" @click="generarPDF(qr.CodigoQR)">
                                            Descargar
                                        </button>
                                    </div>
                                </div>
                            </Dialog>
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
import jsPDF from "jspdf";
import html2canvas from "html2canvas";
import QrcodeVue from 'qrcode.vue'
import dayjs from 'dayjs';
import 'dayjs/locale/es'
import axios from "axios";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import VueQrcode from '@chenfengyuan/vue-qrcode';
import Calendar from 'primevue/calendar';
import InputText from 'primevue/inputtext';


dayjs.locale('es')


export default {
    name: "EventoDetalle",
    props: ['evento'],
    components: { VueDatePicker, QrcodeVue, Dialog, VueQrcode, Button, Calendar, InputText },
    setup() { },
    data() {
        return {
            imageData: null, newData: null,
            qrs: [],
            fecha_expiracion: null,
            valorQR: "",
            size: 200,
            idEvento: this.evento.id,
            nombreEvento: this.evento.nombre,
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
            modalQR: false,
            modalCrearQR: false,
        };
    },
    template: '<qrcode-vue :value="value"></qrcode-vue>',

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
        async generarPDF(id) {
            const doc = new jsPDF();
            const variable = `codigoQR${id}`
            const qrData = this.$refs[variable][0];
            const qrContainer = qrData.$el;
            this.valorQR = qrData.value;
            this.size = qrData.size;
            const canvas = await html2canvas(qrContainer);
            const qrImage = canvas.toDataURL("image/jpeg");
            doc.addImage(qrImage, "JPEG", 10, 10, 50, 50);

            // Guarda o muestra el documento PDF
            doc.save("codigoQR.pdf");
        },
        actualizarURL(codigo) {
            this.modalQR = true
            this.valorQR = "http://localhost:8000/asistencia/" + codigo
        },
        fechaHoraLegible(fecha) {
            return dayjs.unix(fecha).format('D [de] MMMM [-] H:mm');
        },
        resetModalData: function () {
            this.modalCrearQR = true
            this.nombre = "";
            this.description = "";
        },
    },
};
</script>
