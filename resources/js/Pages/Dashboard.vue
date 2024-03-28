<template>

    <Avisos />

    <h3></h3>
    <h2>Eventos</h2>
    <div class="row align-items-stretch">
        <div v-for="evento in eventos" :key="evento.id" class="col-md-3 mb-3">
            <Card class="h-100">
                <template #header> </template>
                <template #title>{{ evento.nombre }}</template>
                <template #subtitle>
                    <Badge :value="evento.tipo.toLowerCase() === 'privado' ? 'Privado' : 'Público'"
                        :severity="evento.tipo.toLowerCase() === 'privado' ? 'info' : 'warning'">
                    </Badge>
                    <Badge v-if="!haFinalizado(evento.fecha_hora_fin)" value="Programado" severity="warning">
                    </Badge>
                    <Badge v-else value="Finalizado" severity="danger"></Badge>
                </template>
                <template #content>
                    <strong>Fecha: {{ fechaHoraLegible(evento.fecha_hora_inicio) }}</strong>
                    <p class="m-0">{{ evento.descripcion }}</p>
                </template>
                <template #footer>
                    <div class="d-flex justify-content-between align-items-center">
                        <Badge v-if="evento.solo_ingreso === 1" value="Solo ingreso" severity="success"></Badge>
                        <Button @click="buscarAsistentes(evento.id)">Asistentes</Button>
                    </div>
                </template>
            </Card>
        </div>
    </div>
    <Dialog v-model:visible="visible" modal header="Lista de asistentes" :style="{ width: '50vw' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <div class="col-12 col-md-12">
            <input type="text" v-model="busqueda" placeholder="Buscar..." class="form-control mb-3">
            <div class="table-responsive ">
                <table v-if="this.usuariosEvento.length > 0" class="table table-bordered table-striped">
                    <!-- Encabezado de la tabla -->
                    <thead>
                        <tr class="py-4 border-gray-200 fw-semibold fs-7 border-bottom">
                            <th class="min-w-30px">Nro</th>
                            <th class="min-w-150px">CI</th>
                            <th class="min-w-150px">Paterno</th>
                            <th class="min-w-150px">Materno</th>
                            <th class="min-w-150px">Nombres</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(usuario, index) in usuariosFiltrados" :key="usuario.ci">
                            <td>{{ index + 1 }}</td>
                            <td>{{ usuario.ci ? usuario.ci : 'No registrado' }}</td>
                            <td>{{ usuario.paterno }}</td>
                            <td>{{ usuario.materno }}</td>
                            <td>{{ usuario.nombres }}</td>
                        </tr>
                    </tbody>
                </table>
                <!-- Mensaje cuando no hay documentos -->
                <div v-else class="text-center py-4">
                    <img src="/assets/ilustraciones/sin_doc.svg" alt="" width="150" height="150" />
                    <p>No hay documentos.</p>
                </div>
            </div>
        </div>
    </Dialog>
</template>

<script>
import axios from "axios";
import dayjs from "dayjs";
import "dayjs/locale/es";
import { useDataEventos } from "@/store/dataEventos";
import Avisos from './../Pages/dashboard/avisos.vue';
export default {
    name: "EventosDashboard",
    components: { Avisos },
    data() {
        return {
            usuariosEvento: [],
            eventos: [],
            visible: false,
            busqueda: '',
            esResponsivo: false,
        };
    },
    setup() {
        const store = useDataEventos();
        return { store }
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
            return this.usuariosEvento.filter(usuario => {
                return (
                    (usuario.ci && usuario.ci.toLowerCase().includes(busqueda)) ||
                    (usuario.paterno && usuario.paterno.toLowerCase().includes(busqueda)) ||
                    (usuario.materno && usuario.materno.toLowerCase().includes(busqueda)) ||
                    (usuario.nombres && usuario.nombres.toLowerCase().includes(busqueda))
                );
            });
        }
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
