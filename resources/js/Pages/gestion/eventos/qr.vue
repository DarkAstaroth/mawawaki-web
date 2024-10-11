<template>
    <div class="card card-bordered">
        <div class="card-header">
            <h3 class="card-title">Listado de QR's asignados</h3>
            <div class="div card-toolbar">
                <button
                    type="button"
                    class="btn btn-sm btn-success"
                    @click="resetModalData(true)"
                >
                    <i class="text-white far fa-plus"></i>
                    Nuevo
                </button>
            </div>
        </div>

        <Dialog
            v-model:visible="modalCrearQR"
            modal
            header="generar QR"
            position="top"
            :style="{ width: '50rem' }"
            :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
        >
            <form class="input-feild" v-on:submit.prevent="generarQR">
                <div class="py-5">
                    <div class="form-group mb-5">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="d-flex flex-column gap-2">
                                    <label for="username"
                                        >Fecha de expiración</label
                                    >
                                    <Calendar
                                        class="w-100"
                                        id="calendar-12h"
                                        v-model="fecha_vencimiento"
                                        showTime
                                        hourFormat="12"
                                        :disabled="controlFecha"
                                    />
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="d-flex flex-column gap-2">
                                    <label for="username"
                                        >Cantidad de usos</label
                                    >
                                    <InputText
                                        id="username"
                                        type="number"
                                        v-model="cantidad_usos"
                                        aria-describedby="username-help"
                                        :disabled="controlLimite"
                                        :value="cantidadUsosDisplay"
                                    />
                                </div>
                            </div>

                            <div class="col col-md-12 mt-5">
                                <div class="d-flex gap-2">
                                    <div class="flex align-items-center">
                                        <Checkbox
                                            v-model="limiteUsos"
                                            inputId="fecha"
                                            name="AjustesQR"
                                            value="-1"
                                            @click="desactivarFecha"
                                        />
                                        <label for="fecha" class="mx-2">
                                            Sin fecha de expiración
                                        </label>
                                    </div>
                                    <div class="flex align-items-center">
                                        <Checkbox
                                            v-model="limiteUsos"
                                            inputId="uso"
                                            name="AjustesQR"
                                            value="-2"
                                            @click="desactivarLimite"
                                        />
                                        <label for="uso" class="mx-2">
                                            Sin limite de usos
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-5">
                            <div class="col">
                                <button class="btn btn-sm btn-success">
                                    Generar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </Dialog>

        <div class="card-body">
            <DataTable
                :value="qrs"
                :paginator="true"
                :rows="10"
                :rowHover="true"
                :resizableColumns="true"
                columnResizeMode="fit"
                class="p-datatable-sm"
                :rowsPerPageOptions="[10, 20, 50]"
                dataKey="id"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                currentPageReportTemplate="Mostrando {first} a {last} de {totalRecords} QR's"
            >
                <Column header="Código">
                    <template #body="{ data: qr }">
                        <div class="p-4">
                            <qrcode-vue
                                :value="qr.CodigoQR"
                                :size="50"
                                level="Q"
                                :ref="`codigoQR${qr.CodigoQR}`"
                                render-as="svg"
                            />
                        </div>
                    </template>
                </Column>
                <Column header="Fecha Vencimiento">
                    <template #body="{ data: qr }">
                        {{
                            qr.fecha_vencimiento === null
                                ? "Sin fecha de expiración"
                                : fechaHoraLegible(qr.fecha_vencimiento)
                        }}
                    </template>
                </Column>

                <Column header="Cantidad de usos">
                    <template #body="{ data: qr }">
                        {{
                            qr.cantidad_usos === -1
                                ? "Sin límite"
                                : qr.cantidad_usos
                        }}
                    </template>
                </Column>
                <Column header="Acciones">
                    <template #body="{ data: qr }">
                        <Button
                            v-if="is('admin|Asistente') "
                            v-tooltip.bottom="{
                                value: 'Generar',
                                showDelay: 300,
                                hideDelay: 300,
                            }"
                            icon="fi fi-sr-qrcode"
                            severity="info"
                            text
                            rounded
                            @click="actualizarURL(qr.CodigoQR)"
                        />
                    </template>
                </Column>
            </DataTable>
            <Dialog
                v-model:visible="modalQR"
                modal
                :style="{ width: '25rem' }"
                header="QR Generado"
            >
                <div class="flex flex-col items-center gap-5 justify-center">
                    <div
                        id="evento_qr"
                        ref="elementToConvert"
                        class="flex flex-col items-center gap-5"
                    >
                        <qrcode-vue
                            :value="valorQR"
                            :size="200"
                            level="Q"
                            :ref="`codigoQR${qrSeleccionado}`"
                            render-as="svg"
                        />

                        <div class="flex flex-col items-center justify-center">
                            <span class="font-bold text-emerald-500"
                                >Evento</span
                            >
                            <span class="mb-5">{{ this.evento.nombre }}</span>

                            <div
                                v-if="this.evento.fecha_hora_inicio"
                                class="flex flex-col items-center"
                            >
                                <span class="font-bold text-emerald-500">
                                    Inicio:
                                </span>
                                <span>{{
                                    fechaHoraLegible(
                                        this.evento.fecha_hora_inicio
                                    )
                                }}</span>
                            </div>
                            <div
                                v-if="this.evento.fecha_hora_fin"
                                class="flex flex-col items-center"
                            >
                                <span class="font-bold text-emerald-500"
                                    >Fin:
                                </span>
                                <span>{{
                                    fechaHoraLegible(this.evento.fecha_hora_fin)
                                }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <Button severity="info" @click="descargarQR"
                            >Descargar</Button
                        >
                    </div>
                </div>
            </Dialog>
        </div>
    </div>
</template>

<script>
import jsPDF from "jspdf";
import html2canvas from "html2canvas";
import QrcodeVue from "qrcode.vue";
import dayjs from "dayjs";
import "dayjs/locale/es";
import axios from "axios";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import VueQrcode from "@chenfengyuan/vue-qrcode";
import Calendar from "primevue/calendar";
import InputText from "primevue/inputtext";
import Checkbox from "primevue/checkbox";
import.meta.env.VITE_APP_BASE_URL;
import { toPng } from "html-to-image";

dayjs.locale("es");

export default {
    name: "EventoDetalle",
    props: ["evento"],
    components: {
        VueDatePicker,
        QrcodeVue,
        Dialog,
        VueQrcode,
        Button,
        Calendar,
        InputText,
        Checkbox,
    },
    setup() {},
    data() {
        return {
            imageData: null,
            newData: null,
            qrs: [],
            fecha_vencimiento: null,
            cantidad_usos: 10,
            limiteUsos: null,
            controlLimite: false,
            controlFecha: false,
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
            qrSeleccionado: null,
        };
    },
    template: '<qrcode-vue :value="value"></qrcode-vue>',

    validations() {},
    mounted() {
        this.qrSeleccionado = null;
        this.cargarQRS(1);
    },
    computed: {
        cantidadUsosDisplay() {
            return this.cantidad_usos === -1
                ? "Sin límite"
                : this.cantidad_usos;
        },
    },
    methods: {
        cargarQRS(pagina) {
            axios
                .get(
                    `/api/qr/evento/${this.evento.id}?page=${pagina}&busqueda=${this.busqueda}`
                )
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
                    fechaVencimiento: this.fecha_vencimiento,
                    cantidadUsos: this.cantidad_usos,
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

            this.resetModalData(false);
        },
        async generarPDF(id) {
            const doc = new jsPDF();
            const variable = `codigoQR${id}`;
            const qrData = this.$refs[variable][0];
            const qrContainer = qrData.$el;
            this.valorQR = qrData.value;
            this.size = qrData.size;
            const canvas = await html2canvas(qrContainer);
            const qrImage = canvas.toDataURL("image/jpeg");

            // Calcular las posiciones para centrar la imagen del código QR
            const qrWidth = 100; // Ancho de la imagen del código QR
            const qrHeight = 100; // Altura de la imagen del código QR
            const qrX = (doc.internal.pageSize.getWidth() - qrWidth) / 2;
            const qrY = (doc.internal.pageSize.getHeight() - qrHeight - 20) / 2; // Desplazamiento para dejar espacio para el texto

            doc.addImage(qrImage, "JPEG", qrX, qrY, qrWidth, qrHeight);

            // Agregar texto centrado después de la imagen del código QR
            const text = this.nombreEvento;
            const fontSize = 12; // Tamaño de fuente
            const textWidth =
                (doc.getStringUnitWidth(text) * fontSize) /
                doc.internal.scaleFactor;
            const textX = (doc.internal.pageSize.getWidth() - textWidth) / 2;
            const textY = qrY + qrHeight + 10; // Desplazamiento para el texto

            doc.text(text, textX, textY);

            doc.save("QR_GENERADO.pdf");
        },
        actualizarURL(codigo) {
            this.qrSeleccionado = codigo;
            this.modalQR = true;
            this.valorQR = `${
                import.meta.env.VITE_APP_BASE_URL
            }/asistencia/${codigo}`;
        },
        fechaHoraLegible(fecha) {
            return dayjs.unix(fecha).format("D [de] MMMM [-] H:mm");
        },
        resetModalData: function (estado) {
            this.modalCrearQR = estado;
            this.fecha_vencimiento = null;
            this.cantidad_usos = 0;
        },
        fechaHoraLegible(fecha) {
            return dayjs.unix(fecha).format("D [de] MMMM [-] H:mm");
        },
        desactivarFecha() {
            this.controlFecha = !this.controlFecha;
            if (this.controlFecha) {
                this.fecha_vencimiento = null;
            }
        },
        desactivarLimite() {
            this.controlLimite = !this.controlLimite;
            if (this.controlLimite) {
                this.cantidad_usos = -1;
            }
        },
        descargarQR() {
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
    },
};
</script>
