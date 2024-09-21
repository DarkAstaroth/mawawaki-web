<template>
    <Avisos />
    <div v-if="!is('cliente')">
        <h2>Eventos</h2>
        <div class="row align-items-stretch">
            <div
                v-for="evento in eventos"
                :key="evento.id"
                class="col-md-3 mb-3"
            >
                <Card class="h-100">
                    <template #header> </template>
                    <template #title>{{ evento.nombre }}</template>
                    <template #subtitle>
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
                        >
                        </Badge>
                        <Badge
                            v-if="!haFinalizado(evento.fecha_hora_fin)"
                            value="Programado"
                            severity="warning"
                        >
                        </Badge>
                        <Badge
                            v-else
                            value="Finalizado"
                            severity="danger"
                        ></Badge>
                    </template>
                    <template #content>
                        <strong
                            >Fecha:
                            {{
                                fechaHoraLegible(evento.fecha_hora_inicio)
                            }}</strong
                        >
                        <p class="m-0">{{ evento.descripcion }}</p>
                    </template>
                    <template #footer>
                        <div
                            class="d-flex justify-content-between align-items-center"
                        >
                            <Badge
                                v-if="evento.solo_ingreso === 1"
                                value="Solo ingreso"
                                severity="success"
                            ></Badge>
                            <Button @click="buscarAsistentes(evento.id)"
                                >Asistentes</Button
                            >
                        </div>
                    </template>
                </Card>
            </div>
        </div>
        <Dialog
            v-model:visible="visible"
            modal
            header="Lista de asistentes"
            :style="{ width: '50vw' }"
            :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
        >
            <div class="col-12 col-md-12">
                <input
                    type="text"
                    v-model="busqueda"
                    placeholder="Buscar..."
                    class="form-control mb-3"
                />
                <DataTable
                    :value="usuariosFiltrados"
                    paginator
                    :rows="10"
                    :rowsPerPageOptions="[5, 10]"
                    tableStyle="min-width: 50rem"
                >
                    <Column
                        field="ci"
                        header="CARNET"
                        style="width: 25%"
                    ></Column>
                    <Column
                        field="paterno"
                        header="PATERNO"
                        style="width: 25%"
                    ></Column>
                    <Column
                        field="materno"
                        header="MATERNO"
                        style="width: 25%"
                    ></Column>
                    <Column
                        field="nombres"
                        header="NOMBRES"
                        style="width: 25%"
                    ></Column>
                </DataTable>
            </div>
        </Dialog>
    </div>

    <button
        @click="onFabClick"
        class="fixed bottom-4 right-4 w-20 h-20 bg-emerald-600 text-white flex items-center justify-center shadow-lg hover:bg-emeradl-700 transition duration-200"
        style="border-radius: 9999px"
    >
        <span class="text-2xl"><i class="fi fi-sr-qr-scan"></i></span>
    </button>

    <Dialog
        v-model:visible="dialogVisible"
        :style="{ width: '90vw', maxWidth: '500px' }"
        :modal="true"
        :contentStyle="{ maxHeight: '80vh', overflow: 'auto' }"
    >
        <template #header>
            <h3>Buscar usuario</h3>
        </template>

        <template v-if="!codigoSeleccionado && !datosPaciente">
            <LectorQR @codigoQR="buscarUsuario" />
            <div
                class="p-4 mb-4 mt-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 flex flex-col"
                role="alert"
            >
                <span class="font-bold">Escanea Credencial</span>
                <span>
                    Utiliza el scanner para buscar a usuarios del centro de
                    terapias equinas</span
                >
            </div>
        </template>
        <template
            v-if="
                serviciosDisponibles.length === 0 &&
                sesionesDisponibles.length === 0 &&
                datosPaciente
            "
        >
            <div class="card">
                <div class="card-body lg:pt-9 lg:pb-7.5">
                    <div class="flex justify-center mb-2.5">
                        <div class="size-20 relative">
                            <img
                                class="rounded-full"
                                :src="
                                    '/'.concat(
                                        this.datosPaciente.profile_photo_path
                                    )
                                "
                            />
                            <div
                                class="flex size-2.5 bg-success rounded-full absolute bottom-0.5 left-16 transform -translate-y-1/2"
                            ></div>
                        </div>
                    </div>
                    <div
                        class="flex items-center justify-center gap-1.5 mb-2.5"
                    >
                        <a
                            class="hover:text-primary-active text-base leading-5 font-medium text-gray-900"
                            href="#"
                        >
                            {{ datosPaciente.persona.nombre }}
                            {{ datosPaciente.persona.paterno }}
                            {{ datosPaciente.persona.materno }}
                        </a>
                        <svg
                            class="text-primary"
                            fill="none"
                            height="16"
                            viewBox="0 0 15 16"
                            width="15"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M14.5425 6.89749L13.5 5.83999C13.4273 5.76877 13.3699 5.6835 13.3312 5.58937C13.2925 5.49525 13.2734 5.39424 13.275 5.29249V3.79249C13.274 3.58699 13.2324 3.38371 13.1527 3.19432C13.0729 3.00494 12.9565 2.83318 12.8101 2.68892C12.6638 2.54466 12.4904 2.43073 12.2998 2.35369C12.1093 2.27665 11.9055 2.23801 11.7 2.23999H10.2C10.0982 2.24159 9.99722 2.22247 9.9031 2.18378C9.80898 2.1451 9.72371 2.08767 9.65249 2.01499L8.60249 0.957487C8.30998 0.665289 7.91344 0.50116 7.49999 0.50116C7.08654 0.50116 6.68999 0.665289 6.39749 0.957487L5.33999 1.99999C5.26876 2.07267 5.1835 2.1301 5.08937 2.16879C4.99525 2.20747 4.89424 2.22659 4.79249 2.22499H3.29249C3.08699 2.22597 2.88371 2.26754 2.69432 2.34731C2.50494 2.42709 2.33318 2.54349 2.18892 2.68985C2.04466 2.8362 1.93073 3.00961 1.85369 3.20013C1.77665 3.39064 1.73801 3.5945 1.73999 3.79999V5.29999C1.74159 5.40174 1.72247 5.50275 1.68378 5.59687C1.6451 5.691 1.58767 5.77627 1.51499 5.84749L0.457487 6.89749C0.165289 7.19 0.00115967 7.58654 0.00115967 7.99999C0.00115967 8.41344 0.165289 8.80998 0.457487 9.10249L1.49999 10.16C1.57267 10.2312 1.6301 10.3165 1.66878 10.4106C1.70747 10.5047 1.72659 10.6057 1.72499 10.7075V12.2075C1.72597 12.413 1.76754 12.6163 1.84731 12.8056C1.92709 12.995 2.04349 13.1668 2.18985 13.3111C2.3362 13.4553 2.50961 13.5692 2.70013 13.6463C2.89064 13.7233 3.0945 13.762 3.29999 13.76H4.79999C4.90174 13.7584 5.00275 13.7775 5.09687 13.8162C5.191 13.8549 5.27627 13.9123 5.34749 13.985L6.40499 15.0425C6.69749 15.3347 7.09404 15.4988 7.50749 15.4988C7.92094 15.4988 8.31748 15.3347 8.60999 15.0425L9.65999 14C9.73121 13.9273 9.81647 13.8699 9.9106 13.8312C10.0047 13.7925 10.1057 13.7734 10.2075 13.775H11.7075C12.1212 13.775 12.518 13.6106 12.8106 13.3181C13.1031 13.0255 13.2675 12.6287 13.2675 12.215V10.715C13.2659 10.6132 13.285 10.5122 13.3237 10.4181C13.3624 10.324 13.4198 10.2387 13.4925 10.1675L14.55 9.10999C14.6953 8.96452 14.8104 8.79176 14.8887 8.60164C14.9671 8.41152 15.007 8.20779 15.0063 8.00218C15.0056 7.79656 14.9643 7.59311 14.8847 7.40353C14.8051 7.21394 14.6888 7.04197 14.5425 6.89749ZM10.635 6.64999L6.95249 10.25C6.90055 10.3026 6.83864 10.3443 6.77038 10.3726C6.70212 10.4009 6.62889 10.4153 6.55499 10.415C6.48062 10.4139 6.40719 10.3982 6.33896 10.3685C6.27073 10.3389 6.20905 10.2961 6.15749 10.2425L4.37999 8.44249C4.32532 8.39044 4.28169 8.32793 4.25169 8.25867C4.22169 8.18941 4.20593 8.11482 4.20536 8.03934C4.20479 7.96387 4.21941 7.88905 4.24836 7.81934C4.27731 7.74964 4.31999 7.68647 4.37387 7.63361C4.42774 7.58074 4.4917 7.53926 4.56194 7.51163C4.63218 7.484 4.70726 7.47079 4.78271 7.47278C4.85816 7.47478 4.93244 7.49194 5.00112 7.52324C5.0698 7.55454 5.13148 7.59935 5.18249 7.65499L6.56249 9.05749L9.84749 5.84749C9.95296 5.74215 10.0959 5.68298 10.245 5.68298C10.394 5.68298 10.537 5.74215 10.6425 5.84749C10.6953 5.90034 10.737 5.96318 10.7653 6.03234C10.7935 6.1015 10.8077 6.1756 10.807 6.25031C10.8063 6.32502 10.7908 6.39884 10.7612 6.46746C10.7317 6.53608 10.6888 6.59813 10.635 6.64999Z"
                                fill="currentColor"
                            ></path>
                        </svg>
                    </div>
                    <div
                        class="flex flex-wrap justify-center items-center gap-4 mb-7"
                    >
                        <div class="flex items-center text-sm text-gray-700">
                            <i
                                class="ki-filled ki-abstract-41 me-1 text-gray-500"
                            >
                            </i>
                            CI:{{ datosPaciente.persona.ci }}
                        </div>
                    </div>

                    <div
                        class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 flex flex-col"
                        role="alert"
                    >
                        <span class="font-bold">Contacto de Emergencia</span>
                        <span>
                            {{ datosPaciente.contacto_emergencia_nombre }}
                        </span>
                        <hr />
                        <span class="font-bold">Contacto de emergencia</span>
                        <span>
                            {{ datosPaciente.contacto_emergencia_telefono }}
                        </span>
                        <hr />
                        <span class="font-bold">Contraindicación</span>
                        <span>
                            {{ datosPaciente.precondicion }}
                        </span>
                    </div>

                    <div class="flex justify-center">
                        <Button @click="verSesionesDisponibles">
                            Ver servicios
                        </Button>
                    </div>
                </div>
            </div>
        </template>

        <template
            v-if="
                serviciosDisponibles.length !== 0 &&
                sesionesDisponibles.length === 0 &&
                datosPaciente
            "
        >
            <div class="max-h-[60vh] overflow-y-auto pr-2">
                <div
                    v-for="servicio in serviciosDisponibles"
                    :key="servicio.id"
                    class="mb-3"
                >
                    <Card class="shadow-sm">
                        <template #title>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium">{{
                                    servicio.tipo_servicio
                                }}</span>
                                <Button
                                    label="Ver sesiones"
                                    size="small"
                                    @click="verSesiones(servicio.id)"
                                />
                            </div>
                        </template>
                        <template #content>
                            <div class="text-sm text-gray-700">
                                Disponibles:
                                <span class="font-medium text-green-600">{{
                                    servicio.sesiones_disponibles
                                }}</span>
                                | Realizadas:
                                <span class="font-medium">{{
                                    servicio.sesiones_realizadas
                                }}</span>
                            </div>
                        </template>
                    </Card>
                </div>
            </div>
        </template>

        <div v-if="sesionesDisponibles.length !== 0 && datosPaciente">
            <div class="flex mb-3">
                Sesiones disponibles:
                <span class="text-emerald-500">
                    {{ sesionesDisponibles.length }}
                </span>
            </div>
            <div class="max-h-[60vh] overflow-y-auto pr-2">
                <div
                    v-for="sesion in sesionesDisponibles"
                    :key="sesion.id"
                    class="mb-3"
                >
                    <div class="rounded-xl bg-emerald-50 py-5">
                        <div class="flex flex-col items-center">
                            <span class="text-sm"> Fecha sesión: </span>
                            <span class="text-sm font-medium">{{
                                fechaHoraLegible(sesion.fecha_sesion)
                            }}</span>
                            <Button
                                label="Registrar"
                                size="small"
                                class="mt-5"
                                @click="registrarSesion(sesion.id)"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <template
            v-if="
                serviciosDisponibles.length === 0 &&
                useDataPacientes &&
                codigoSeleccionado
            "
        >
            No hay servicios disponibles
        </template>

        <template #footer>
            <Button label="Cerrar" @click="reiniciarScanner" />
        </template>
    </Dialog>
</template>

<script>
import axios from "axios";
import dayjs from "dayjs";
import "dayjs/locale/es";
import { useDataEventos } from "@/store/dataEventos";
import Avisos from "./../Pages/dashboard/avisos.vue";
import LectorQR from "@/Pages/dashboard/lectorqr.vue";
import { useDataPacientes } from "@/store/dataPaciente";

export default {
    name: "EventosDashboard",
    components: { Avisos, LectorQR },
    data() {
        return {
            datosPaciente: null,
            codigoSeleccionado: null,
            usuariosEvento: [],
            eventos: [],
            visible: false,
            busqueda: "",
            esResponsivo: false,
            dialogVisible: false,

            serviciosDisponibles: [],
            sesionesDisponibles: [],
        };
    },
    setup() {
        const store = useDataEventos();
        const storePaciente = useDataPacientes();
        return { store, storePaciente };
    },
    created() {
        this.verificarResponsivo();
        window.addEventListener("resize", this.verificarResponsivo);
    },
    destroyed() {
        window.removeEventListener("resize", this.verificarResponsivo);
    },
    mounted() {
        this.obtenerEventos();
    },
    computed: {
        usuariosFiltrados() {
            const busqueda = this.busqueda.toLowerCase();
            return this.usuariosEvento.filter((usuario) => {
                return (
                    (usuario.ci &&
                        usuario.ci.toLowerCase().includes(busqueda)) ||
                    (usuario.paterno &&
                        usuario.paterno.toLowerCase().includes(busqueda)) ||
                    (usuario.materno &&
                        usuario.materno.toLowerCase().includes(busqueda)) ||
                    (usuario.nombres &&
                        usuario.nombres.toLowerCase().includes(busqueda))
                );
            });
        },
    },
    methods: {
        onFabClick() {
            this.dialogVisible = true;
        },
        reiniciarScanner() {
            this.datosPaciente = null;
            this.codigoSeleccionado = null;
            this.dialogVisible = false;
            this.serviciosDisponibles = [];
            this.sesionesDisponibles = [];
            this.dialogVisible = false;
        },
        async verSesiones(servicioId) {
            try {
                const respuesta = await this.storePaciente.verSesiones(
                    servicioId
                );
                this.sesionesDisponibles = respuesta;
                if (this.sesionesDisponibles.length === 0) {
                    this.reiniciarScanner();
                    await Swal.fire({
                        icon: "info",
                        title: "No hay sesiones disponibles",
                        text: "No se encontraron sesiones disponibles",
                        confirmButtonText: "OK",
                    });
                }
            } catch (error) {
                console.error("Error al obtener sesiones:", error);
            }
        },
        verificarResponsivo() {
            this.esResponsivo = window.innerWidth < 768;
        },
        obtenerEventos() {
            axios
                .get("api/eventos/privados")
                .then((resultado) => {
                    this.eventos = resultado.data;
                })
                .catch((err) => {
                    console.error("Error al obtener eventos:", err);
                });
        },
        fechaHoraLegible(fecha) {
            return dayjs.unix(fecha).format("D [de] MMMM [-] h:mm A");
        },
        haFinalizado(fechaFin) {
            return dayjs().isAfter(dayjs.unix(fechaFin));
        },
        buscarAsistentes(evento) {
            this.visible = true;
            this.store.obtenerUsuariosEvento(evento).then((respuesta) => {
                this.usuariosEvento = respuesta;
            });
        },
        async buscarUsuario(obj) {
            this.codigoSeleccionado = obj[0];
            const paciente = await this.storePaciente.buscarPacientePorCodigo(
                obj[0]
            );
            this.datosPaciente = paciente;
            console.log(this.datosPaciente);
        },
        async verSesionesDisponibles() {
            try {
                this.serviciosDisponibles =
                    await this.storePaciente.obtenerServiciosDisponibles(
                        this.datosPaciente.id
                    );
                console.log(this.serviciosDisponibles);
                this.codigoSeleccionado = null;
            } catch (error) {
                console.error("Error al obtener servicios disponibles:", error);
                // Manejar el error (por ejemplo, mostrar un mensaje al usuario)
            }
        },
        async registrarSesion(sesionId) {
            try {
                const response = await axios.post(
                    `/api/sesiones/${sesionId}/registrar`
                );
                this.reiniciarScanner();
                await Swal.fire({
                    icon: "success",
                    title: "Éxito",
                    text: "Sesión registrada correctamente",
                    confirmButtonText: "OK",
                });
            } catch (error) {
                // Show error message with Swal
                await Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "No se pudo registrar la sesión",
                    confirmButtonText: "OK",
                });
            }
        },
    },
};
</script>
