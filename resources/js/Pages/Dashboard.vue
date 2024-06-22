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
</template>

<script>
import axios from "axios";
import dayjs from "dayjs";
import "dayjs/locale/es";
import { useDataEventos } from "@/store/dataEventos";
import Avisos from "./../Pages/dashboard/avisos.vue";
export default {
    name: "EventosDashboard",
    components: { Avisos },
    data() {
        return {
            usuariosEvento: [],
            eventos: [],
            visible: false,
            busqueda: "",
            esResponsivo: false,
        };
    },
    setup() {
        const store = useDataEventos();
        return { store };
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
    },
};
</script>
