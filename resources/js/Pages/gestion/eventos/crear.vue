<template>
    <form class="input-feild" v-on:submit.prevent="crearEvento">
        <div class="d-flex justify-content-between gap-5 mb-5">
            <h3 class="card-title">Crear nuevo evento</h3>
            <div class="flex gap-2">
                <Button
                    label="Volver"
                    icon="pi pi-arrow-left"
                    class="p-button-secondary"
                    @click="volver"
                />
                <Button
                    type="submit"
                    label="Guardar"
                    icon="pi pi-check"
                    class="p-button-success"
                    @click="guardarEvento"
                />
            </div>
        </div>

        <div
            :class="[
                'd-flex gap-5',
                esResponsivo ? ' flex-column ' : 'flex-row',
            ]"
        >
            <div class="card p-5 flex-grow-1">
                <div class="card flex justify-content-center">
                    <div class="d-flex flex-column gap-2">
                        <label for="titulo">Nombre del evento</label>
                        <InputText v-model="nombre" class="uppercase" />
                        <div
                            v-if="v$?.nombre.$error"
                            class="fv-plugins-message-container invalid-feedback"
                        >
                            <div
                                data-field="text_input"
                                data-validator="notEmpty"
                            >
                                El nombre del evento es requerido
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex w-100 gap-5 my-5">
                    <div class="d-flex flex-column w-100">
                        <label for="calendar-12h" class="font-bold block mb-2">
                            Fecha inicio
                        </label>
                        <Calendar
                            class="w-full"
                            id="calendar-12h"
                            v-model="fechaInicio"
                            showTime
                            hourFormat="12"
                        />
                        <div
                            v-if="v$?.fechaInicio.$error"
                            class="fv-plugins-message-container invalid-feedback"
                        >
                            <div
                                data-field="text_input"
                                data-validator="notEmpty"
                            >
                                La fecha de inicio del evento es requerida
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column w-100">
                        <label for="calendar-12h" class="font-bold block mb-2">
                            Fecha Fin
                        </label>
                        <Calendar
                            class="w-100"
                            id="calendar-12h"
                            v-model="fechaFin"
                            showTime
                            hourFormat="12"
                        />
                        <div
                            v-if="v$?.fechaFin.$error"
                            class="fv-plugins-message-container invalid-feedback"
                        >
                            <div
                                data-field="text_input"
                                data-validator="notEmpty"
                            >
                                La fecha final del evento es requerida
                            </div>
                        </div>
                    </div>
                </div>

                <label for="titulo">Tipo de evento</label>
                <div class="d-flex">
                    <div class="d-flex flex-grow-1">
                        <div class="d-flex align-items-center p-2">
                            <RadioButton
                                v-model="tipoEvento"
                                inputId="tipoEvento1"
                                name="publico"
                                value="publico"
                            />
                            <label for="tipoEvento1" class="ml-5 px-2"
                                >Público</label
                            >
                        </div>
                        <div class="d-flex align-items-center p-2">
                            <RadioButton
                                v-model="tipoEvento"
                                inputId="tipoEvento2"
                                name="privado"
                                value="privado"
                            />
                            <label for="tipoEvento2" class="ml-5 px-2"
                                >Privado</label
                            >
                        </div>
                    </div>

                    <div class="d-flex align-items-center flex-grow-1">
                        <label for="ingreso" class="ml-5 px-2"
                            >Solo ingreso</label
                        >
                        <div class="p-1 mt-1">
                            <InputSwitch v-model="soloIngreso" />
                        </div>
                    </div>
                </div>

                <div class="card flex justify-content-center">
                    <div class="d-flex flex-column gap-2">
                        <label for="titulo">Lugar del evento</label>
                        <InputText v-model="lugar" />
                        <div
                            v-if="v$?.lugar.$error"
                            class="fv-plugins-message-container invalid-feedback"
                        >
                            <div
                                data-field="text_input"
                                data-validator="notEmpty"
                            >
                                El lugar del evento es requerido
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-5">
                    <label for="calendar-12h" class="font-bold block mb-2">
                        Descripcion
                    </label>
                    <Textarea
                        class="w-100"
                        v-model="descripcion"
                        rows="5"
                        placeholder="Descripción"
                    />
                    <div
                        v-if="v$?.descripcion.$error"
                        class="fv-plugins-message-container invalid-feedback"
                    >
                        <div data-field="text_input" data-validator="notEmpty">
                            La descripción del evento es requerida
                        </div>
                    </div>
                </div>

                <div class="mb-6 w-100">
                    <MultiSelect
                        class="w-100"
                        v-model="usuariosSeleccionados"
                        :options="usuariosFiltro"
                        filter
                        optionLabel="name"
                        placeholder="Selecciona Usuarios"
                        :maxSelectedLabels="3"
                    />
                </div>
            </div>

            <div class="card flex-grow-1">
                <MapaComponent @obtenerDatos="establecerDatos" />
            </div>
        </div>
    </form>
    <Toast />

    <Dialog
        v-model:visible="eventoCreado"
        modal
        header="Evento Creado"
        :style="{ width: '25rem' }"
        :closable="false"
    >
        <Message severity="success" :closable="false"
            >Evento Creado con éxito</Message
        >

        <div class="flex flex-col items-center gap-5 justify-center">
            <div
                id="evento_qr"
                ref="elementToConvert"
                class="flex flex-col items-center gap-5"
            >
                <qrcode-vue
                    :value="nuevoQR.CodigoQR"
                    :size="100"
                    level="Q"
                    :ref="`codigoQR${nuevoQR.CodigoQR}`"
                    render-as="svg"
                />

                <div class="flex flex-col items-center justify-center">
                    <span class="font-bold text-emerald-500">Evento</span>
                    <span class="mb-5">{{ eventoDatos.nombre }}</span>

                    <div
                        v-if="eventoDatos.fecha_hora_inicio"
                        class="flex flex-col items-center"
                    >
                        <span class="font-bold text-emerald-500">
                            Inicio:
                        </span>
                        <span>{{ eventoDatos.fecha_hora_inicio }}</span>
                    </div>
                    <div
                        v-if="eventoDatos.fecha_hora_fin"
                        class="flex flex-col items-center"
                    >
                        <span class="font-bold text-emerald-500">Fin: </span>
                        <span>{{ eventoDatos.fecha_hora_fin }}</span>
                    </div>
                </div>
            </div>

            <div class="flex gap-2">
                <Button @click="volver">Continuar</Button>
                <Button severity="info" @click="descargarQR">Descargar</Button>
            </div>
        </div>
    </Dialog>
</template>

<script>
import { ref } from "vue";
import dayjs from "dayjs";
import "dayjs/locale/es";
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
import { useVuelidate } from "@vuelidate/core";
import { required } from "@vuelidate/validators";
import { useGeolocation } from "@vueuse/core";
import QrcodeVue from "qrcode.vue";
import { toPng } from "html-to-image";

export default {
    name: "EventosCrear",
    components: {
        VueDatePicker,
        LMap,
        LTileLayer,
        LCircleMarker,
        LMarker,
        MapaComponent,
        QrcodeVue,
    },
    setup() {
        const vueGeo = useGeolocation();
        const store = useDataEventos();
        const toast = useToast();
        const tipoEvento = ref("");
        const soloIngreso = ref(true);
        const usuariosSeleccionados = ref();
        const usuariosFiltro = ref();
        return {
            vueGeo,
            store,
            toast,
            tipoEvento,
            soloIngreso,
            usuariosSeleccionados,
            usuariosFiltro,
            v$: useVuelidate(),
        };
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
            esResponsivo: false,
            eventoCreado: false,
            nuevoQR: null,
            eventoDatos: null,
        };
    },

    validations() {
        return {
            nombre: { required },
            fechaInicio: { required },
            fechaFin: { required },
            lugar: { required },
            descripcion: { required },
        };
    },
    created() {
        this.verificarResponsivo();
        window.addEventListener("resize", this.verificarResponsivo);
    },
    destroyed() {
        window.removeEventListener("resize", this.verificarResponsivo);
    },
    mounted() {
        this.store.obtenerUsuarios().then(() => {
            this.usuariosFiltro = this.store.usuarios.map((usuario) => {
                let nombreCompleto = "";
                if (usuario.persona.nombre !== "") {
                    nombreCompleto =
                        `${usuario.persona.nombre} ${usuario.persona.paterno} ${usuario.persona.materno}`.trim();
                }
                return { name: nombreCompleto, id: usuario.id };
            });
        });
        this.store.cargarEventos(1, this.busqueda);
        this.tipoEvento = "privado";
    },
    methods: {
        volver() {
            this.$router.push({ name: "dashboard.eventos" });
            this.eventoCreado = false;
            this.nuevoQR = null;
            this.eventoDatos = null;
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
            this.v$.$touch();
            if (!this.v$.$error) {
                this.store
                    .crearEvento(
                        this.nombre,
                        this.fechaInicio,
                        this.fechaFin,
                        this.lugar,
                        this.descripcion,
                        this.latitud,
                        this.longitud,
                        this.tipoEvento,
                        this.soloIngreso,
                        this.usuariosSeleccionados
                    )
                    .then((respuesta) => {
                        console.log(respuesta);
                        this.eventoDatos = respuesta.evento;
                        this.nuevoQR = respuesta.nuevoQr;
                        this.eventoCreado = true;
                        // Swal.fire({
                        //     icon: "success",
                        //     title: "Operación Exitosa",
                        //     text: "Evento creado correctamente",
                        //     timer: 2000,
                        //     showConfirmButton: false,
                        // });

                        // this.$router.push({ name: "dashboard.eventos" });
                    })
                    .catch((respuesta) => {
                        this.mostrarMensaje(
                            "error",
                            "Error al crear registro",
                            respuesta
                        );
                    });
            }
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
        verificarResponsivo() {
            this.esResponsivo = window.innerWidth < 768;
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
