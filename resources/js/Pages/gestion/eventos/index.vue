<template>
    <div class="card card-bordered">
        <div class="card-header">
            <h3 class="card-title">Listado de eventos</h3>
            <div class="div card-toolbar">
                <Button
                    label="Nuevo"
                    icon="pi pi-plus"
                    class="p-button-success"
                    @click="navegarCrearEvento"
                />
            </div>
        </div>

        <div class="card-body">
            <DataTable
                :value="store.eventos"
                :paginator="true"
                :rows="10"
                :filters="filters"
                :rowHover="true"
                v-model:filters="filters"
                :resizableColumns="true"
                columnResizeMode="fit"
                class="p-datatable-sm"
                :rowsPerPageOptions="[10, 20, 50]"
                dataKey="id"
                :globalFilterFields="['nombre']"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                currentPageReportTemplate="Mostrando {first} a {last} de {totalRecords} eventos"
            >
                <template #header>
                    <div class="flex justify-content-between">
                        <InputText
                            v-model="filters['global'].value"
                            placeholder="Buscar..."
                            class="p-inputtext-sm"
                            style="width: 300px"
                        />
                    </div>
                </template>
                <Column
                    field="nombre"
                    header="Nombre"
                    filter
                    :filterPlaceholder="'Buscar por nombre'"
                ></Column>
                <Column header="Fecha Inicio">
                    <template #body="{ data: evento }">
                        {{ fechaHoraLegible(evento.fecha_hora_inicio) }}
                    </template>
                </Column>
                <Column header="Fecha Fin">
                    <template #body="{ data: evento }">
                        {{ fechaHoraLegible(evento.fecha_hora_fin) }}
                    </template>
                </Column>
                <Column field="lugar" header="Lugar" filter></Column>
                <Column header="Tipo Ingreso">
                    <template #body="{ data: evento }">
                        <Badge
                            :value="
                                evento.solo_ingreso
                                    ? 'Solo Ingreso'
                                    : 'Ingreso - Salida'
                            "
                            :severity="evento.solo_ingreso ? 'info' : 'warning'"
                        />
                    </template>
                </Column>
                <Column header="Visibilidad">
                    <template #body="{ data: evento }">
                        <Badge
                            :value="
                                evento.tipo.toLowerCase() === 'privado'
                                    ? 'Privado'
                                    : 'Público'
                            "
                            :severity="
                                evento.tipo.toLowerCase() === 'privado'
                                    ? 'info'
                                    : 'warning'
                            "
                        />
                        <Badge
                            v-if="evento.principal"
                            value="Principal"
                            severity="success"
                        />
                    </template>
                </Column>
                <Column header="Acciones">
                    <template #body="{ data: evento }">
                        <Button
                            v-if="is('admin|Asistente') & !evento.principal"
                            v-tooltip.bottom="{
                                value: 'Generar reporte',
                                showDelay: 300,
                                hideDelay: 300,
                            }"
                            icon="fi fi-br-file-pdf"
                            severity="danger"
                            text
                            rounded
                            @click="obtenerReporteEvento(evento)"
                        />
                        <Button
                            v-if="is('admin|Asistente')"
                            v-tooltip.bottom="{
                                value: 'Scannear',
                                showDelay: 300,
                                hideDelay: 300,
                            }"
                            icon="fi fi-br-qr-scan"
                            severity="success"
                            text
                            rounded
                            @click="estadoModalScanner(true, evento)"
                        />

                        <a
                            :href="route('evento.qrs', { id: evento.id })"
                            v-if="is('admin|Asistente')"
                        >
                            <Button
                                v-tooltip.bottom="{
                                    value: 'QR',
                                    showDelay: 300,
                                    hideDelay: 300,
                                }"
                                icon="fi fi-sr-qrcode"
                                severity="secondary"
                                text
                                rounded
                                aria-label="QR Code"
                            />
                        </a>

                        <a
                            :href="route('evento.detalle', { id: evento.id })"
                            v-if="is('admin|Asistente')"
                        >
                            <Button
                                v-tooltip.bottom="{
                                    value: 'Editar',
                                    showDelay: 300,
                                    hideDelay: 300,
                                }"
                                icon="fi fi-br-file-edit"
                                severity="info"
                                text
                                rounded
                                aria-label="Editar Evento"
                            />
                        </a>

                        <Button
                            v-if="is('admin|Asistente') && !evento.principal"
                            v-tooltip.bottom="{
                                value: 'Eliminar',
                                showDelay: 300,
                                hideDelay: 300,
                            }"
                            @click.prevent="
                                confirmarEliminar($event, evento.id)
                            "
                            icon="pi pi-times"
                            severity="danger"
                            text
                            rounded
                            aria-label="Eliminar Evento"
                        />
                    </template>
                </Column>
            </DataTable>
        </div>
    </div>
    <Toast />
    <Dialog
        v-model:visible="modalScanner"
        modal
        header="Scannear Qr"
        position="center"
        :style="{ width: '50rem' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
    >
        <div>
            <div class="d-flex flex-column">
                <span>
                    <strong>Evento:</strong> {{ eventoSeleccionado.nombre }}
                </span>
                <span>
                    <strong>Fecha Inicio</strong>
                    {{ fechaHoraLegible(eventoSeleccionado.fecha_hora_inicio) }}
                </span>
                <span>
                    <strong>Fecha Fin</strong>
                    {{ fechaHoraLegible(eventoSeleccionado.fecha_hora_fin) }}
                </span>
            </div>
        </div>
        <LectorQR @codigoQR="registrarQR" />
    </Dialog>
    <ConfirmPopup></ConfirmPopup>
</template>

<script>
import { ref } from "vue";
import dayjs from "dayjs";
import "dayjs/locale/es";
import axios from "axios";
import VueDatePicker from "@vuepic/vue-datepicker";
import MapaComponent from "./mapa.vue";
import "@vuepic/vue-datepicker/dist/main.css";
dayjs.locale("es");
import { useDataEventos } from "../../../store/dataEventos";
import { useToast } from "primevue/usetoast";
import "leaflet/dist/leaflet.css";
import {
    LMap,
    LTileLayer,
    LCircleMarker,
    LMarker,
} from "@vue-leaflet/vue-leaflet";
import LectorQR from "@/Pages/dashboard/lectorqr.vue";
import { useDataAsistencias } from "@/store/dataAsistencias";
import { useConfirm } from "primevue/useconfirm";
import { FilterMatchMode } from "primevue/api";
import jsPDF from "jspdf";
import autoTable from "jspdf-autotable";

export default {
    name: "EventosIndex",
    components: {
        LectorQR,
        VueDatePicker,
        LMap,
        LTileLayer,
        LCircleMarker,
        LMarker,
        MapaComponent,
    },
    setup() {
        const store = useDataEventos();
        const storeAsistencias = useDataAsistencias();
        const toast = useToast();
        const confirm = useConfirm();

        const confirmarEliminar = (event, idAsistencia) => {
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
                    store.eliminarEvento(idAsistencia).then(() => {
                        store.cargarEventos(1, "");
                        toast.add({
                            severity: "success",
                            summary: "Confirmado",
                            detail: "Se registro se elimino con éxito",
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

        return { store, toast, storeAsistencias, confirmarEliminar };
    },
    data() {
        return {
            zoom: 17,
            coordinates: [-16.499981, -68.1552951],
            geojsonOptions: {},
            eventos: [],
            nombre: "",
            fechaInicio: null,
            fechaFin: null,
            lugar: "",
            descripcion: "",
            busqueda: "",
            modo: "crear",
            enviado: false,
            modalCrearEvento: false,
            latitud: null,
            longitud: null,
            modalScanner: false,
            eventoSeleccionado: null,
            filters: {
                global: { value: null, matchMode: FilterMatchMode.CONTAINS },
                nombre: {
                    value: null,
                    matchMode: FilterMatchMode.STARTS_WITH,
                },
            },
        };
    },

    validations() {},

    mounted() {
        this.store.cargarEventos(1, this.busqueda);
    },
    methods: {
        navegarCrearEvento() {
            this.$router.push({ name: "dashboard.eventos.create" });
        },
        establecerDatos(obj) {
            this.latitud = obj.lat;
            this.longitud = obj.lng;
        },
        obtenerCoordenadas(location) {
            this.latitud = location.lat;
            this.longitud = location.lng;
        },
        mostrarMensaje(tipo, titulo, texto) {
            this.toast.add({
                severity: tipo,
                summary: titulo,
                detail: texto,
                life: 2000,
            });
        },
        filtrarEventos() {
            this.store.cargarEventos(1, this.busqueda);
        },

        crearEvento() {
            this.store
                .crearEvento(
                    this.nombre,
                    this.fechaInicio,
                    this.fechaFin,
                    this.lugar,
                    this.descripcion,
                    this.latitud,
                    this.longitud
                )
                .then(() => {
                    this.mostrarMensaje(
                        "success",
                        "Operación Exitosa",
                        "Evento creado correctamente"
                    );
                    $("#kt_modal_1").modal("hide");
                    this.busqueda = "";
                    this.store.cargarEventos(1, this.busqueda);
                    this.modalCrearEvento = !this.modalCrearEvento;
                });
        },
        fechaHoraLegible(fecha) {
            return dayjs.unix(fecha).format("D [de] MMMM [-] H:mm");
        },
        resetModalData: function () {
            this.nombre = "";
            this.description = "";
        },
        estadoModal() {
            this.modalCrearEvento = !this.modalCrearEvento;
        },
        estadoModalScanner(estado, evento) {
            this.modalScanner = estado;
            this.eventoSeleccionado = evento;
        },
        registrarQR(obj) {
            this.storeAsistencias.registrarAsistencia(
                this.eventoSeleccionado,
                obj[0]
            );
        },
        cambiarPaginacion(pagina) {
            this.store.cargarEventos(pagina, this.busqueda, this.parametro);
        },
        obtenerReporteEvento(evento) {
            this.store
                .obtenerUsuariosEventoPdf(evento.id)
                .then((respuesta) => {
                    console.log(respuesta);
                    var doc = new jsPDF("l", "pt", "letter");
                    doc.setFontSize(10);

                    var titulo = "REPORTE EVENTO";
                    var tituloWidth =
                        (doc.getStringUnitWidth(titulo) *
                            doc.internal.getFontSize()) /
                        doc.internal.scaleFactor;
                    var x =
                        (doc.internal.pageSize.getWidth() - tituloWidth) / 2;
                    doc.text(titulo, x, 20);

                    var nombreEvento = respuesta.data.evento.nombre;
                    var lugar = respuesta.data.evento.lugar;
                    var fechaInicio = respuesta.data.evento.fecha_hora_inicio;
                    var fechaFin = respuesta.data.evento.fecha_hora_fin;

                    doc.setFont("helvetica", "bold");
                    doc.text("EVENTO: ", 40, 40);
                    doc.setFont("helvetica", "normal");
                    doc.text(nombreEvento, 100, 40);

                    doc.setFont("helvetica", "bold");
                    doc.text("LUGAR: ", 40, 55);
                    doc.setFont("helvetica", "normal");
                    doc.text(lugar, 100, 55);

                    doc.setFont("helvetica", "bold");
                    doc.text("FECHA INICIO: ", 40, 70);
                    doc.setFont("helvetica", "normal");
                    doc.text(fechaInicio, 120, 70);

                    doc.setFont("helvetica", "bold");
                    doc.text("FECHA FIN: ", 40, 85);
                    doc.setFont("helvetica", "normal");
                    doc.text(fechaFin, 120, 85);

                    var startY = 100;

                    var footer = function (data) {
                        var str = "Página " + data.pageNumber;
                        var pageWidth = doc.internal.pageSize.getWidth();
                        var textWidth =
                            (doc.getStringUnitWidth(str) *
                                doc.internal.getFontSize()) /
                            doc.internal.scaleFactor;
                        doc.setFontSize(10);
                        doc.text(
                            str,
                            pageWidth - data.settings.margin.right - textWidth,
                            doc.internal.pageSize.getHeight() - 10
                        );
                        doc.text(
                            "Centro Integral de Terapias Equinas",
                            doc.internal.pageSize.getWidth() / 2,
                            doc.internal.pageSize.getHeight() - 10,
                            { align: "center" }
                        );
                    };

                    var bodyData = respuesta.data.asistentes.map(
                        (asistente) => [
                            asistente.nro,
                            asistente.ci,
                            asistente.nombres,
                            asistente.paterno,
                            asistente.materno,
                            asistente.roles,
                        ]
                    );

                    var options = {
                        startY: startY,
                        head: [
                            [
                                "Nro",
                                "CI",
                                "Nombres",
                                "Paterno",
                                "Materno",
                                "Roles",
                            ],
                        ],
                        body: bodyData,
                        margin: { top: 20, bottom: 20 },
                        theme: "grid",
                        styles: { fontSize: 8 },
                        didDrawPage: footer,
                    };

                    var nombreArchivo =
                        "Reporte_asistentes_evento_" +
                        nombreEvento +
                        "_" +
                        fechaInicio.split(" ")[0] +
                        ".pdf";

                    doc.autoTable(options);

                    doc.save(nombreArchivo);
                })
                .catch((error) => {
                    console.error("Error al generar el PDF:", error);
                });
        },
    },
};
</script>
