<template>
    <div class="card card-bordered">
        <div class="card-header">
            <h3 class="card-title">Listado de eventos</h3>
            <div class="div card-toolbar">
                <a
                    :href="route('eventos.create')"
                    type="button"
                    class="btn btn-sm btn-success"
                >
                    <i class="text-white far fa-plus"></i>
                    Nuevo
                </a>
            </div>
        </div>

        <div class="card-body">
            <input
                class="mb-5 form-control"
                type="text"
                v-model="busqueda"
                @input="filtrarEventos"
                placeholder="Buscar..."
            />
            <div class="table-responsive">
                <table class="table table-striped table-sm table-bordered">
                    <thead>
                        <tr
                            class="py-4 border-gray-200 fw-semibold fs-7 border-bottom"
                        >
                            <th class="min-w-150px">Nombre</th>
                            <th class="min-w-150px">Fecha Inicio</th>
                            <th class="min-w-150px">Fecha Fin</th>
                            <th class="min-w-200px">Lugar</th>
                            <th class="min-w-150px">Tipo Ingreso</th>
                            <th class="min-w-150px">Visibilidad</th>
                            <th class="min-w-150px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="evento in store.eventos" :key="evento.id">
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
                                            {{
                                                fechaHoraLegible(
                                                    evento.fecha_hora_inicio
                                                )
                                            }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="px-2 d-flex flex-column">
                                        <div>
                                            {{
                                                fechaHoraLegible(
                                                    evento.fecha_hora_fin
                                                )
                                            }}
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
                                            <Badge
                                                :value="
                                                    evento.solo_ingreso
                                                        ? 'Solo Ingreso'
                                                        : 'Ingreso - Salida'
                                                "
                                                :severity="
                                                    evento.solo_ingreso
                                                        ? 'info'
                                                        : 'warning'
                                                "
                                            ></Badge>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="px-2 d-flex flex-column">
                                        <div>
                                            <Badge
                                                :value="
                                                    evento.tipo.toLowerCase() ===
                                                    'privado'
                                                        ? 'Privado'
                                                        : 'Público'
                                                "
                                                :severity="
                                                    evento.tipo.toLowerCase() ===
                                                    'privado'
                                                        ? 'info'
                                                        : 'warning'
                                                "
                                            ></Badge>
                                            <Badge
                                                v-if="evento.principal"
                                                value="Principal"
                                                severity="success"
                                            ></Badge>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="align-items-center">
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
                                    :href="
                                        route('evento.qrs', {
                                            id: evento.id,
                                        })
                                    "
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
                                        aria-label="Cancel"
                                /></a>

                                <a
                                    :href="
                                        route('evento.detalle', {
                                            id: evento.id,
                                        })
                                    "
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
                                        aria-label="Cancel"
                                /></a>
                                <Button
                                    v-if="is('admin|Asistente')"
                                    v-tooltip.bottom="{
                                        value: 'Eliminar',
                                        showDelay: 300,
                                        hideDelay: 300,
                                    }"
                                    @click="
                                        confirmarEliminar($event, evento.id)
                                    "
                                    icon="pi pi-times"
                                    severity="danger"
                                    text
                                    rounded
                                    aria-label="Cancel"
                                />
                            </td>
                        </tr>
                        <tr v-if="store.eventos.length === 0">
                            <td colspan="5" class="text-center">
                                No hay datos
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <nav>
                <ul class="pagination">
                    <li
                        class="page-item"
                        :class="{
                            disabled: this.store.paginacion.paginaActual === 1,
                        }"
                    >
                        <a
                            class="page-link"
                            href="#"
                            @click="cambiarPaginacion(1)"
                            >Primera</a
                        >
                    </li>
                    <li
                        class="page-item"
                        :class="{
                            disabled: this.store.paginacion.paginaActual === 1,
                        }"
                    >
                        <a
                            class="page-link"
                            href="#"
                            @click="
                                cambiarPaginacion(
                                    this.store.paginacion.paginaActual - 1
                                )
                            "
                            >Anterior</a
                        >
                    </li>
                    <li
                        class="page-item"
                        v-for="page in this.store.paginacion.ultimaPagina"
                        :key="page"
                        :class="{
                            active: this.store.paginacion.paginaActual === page,
                        }"
                    >
                        <a
                            class="page-link"
                            href="#"
                            @click="cambiarPaginacion(page)"
                            >{{ page }}</a
                        >
                    </li>
                    <li
                        class="page-item"
                        :class="{
                            disabled:
                                this.store.paginacion.paginaActual ===
                                this.store.paginacion.ultimaPagina,
                        }"
                    >
                        <a
                            class="page-link"
                            href="#"
                            @click="
                                cambiarPaginacion(
                                    this.store.paginacion.paginaActual + 1
                                )
                            "
                            >Siguiente</a
                        >
                    </li>
                    <li
                        class="page-item"
                        :class="{
                            disabled:
                                this.store.paginacion.paginaActual ===
                                this.store.paginacion.ultimaPagina,
                        }"
                    >
                        <a
                            class="page-link"
                            href="#"
                            @click="
                                cambiarPaginacion(
                                    this.store.paginacion.ultimaPagina
                                )
                            "
                            >Última</a
                        >
                    </li>
                </ul>
            </nav>
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
            modalCrearEvento: false,
            latitud: null,
            longitud: null,
            modalScanner: false,
            eventoSeleccionado: null,
        };
    },

    validations() {},

    mounted() {
        this.store.cargarEventos(1, this.busqueda);
    },
    methods: {
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
    },
};
</script>
