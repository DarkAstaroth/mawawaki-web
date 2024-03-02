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
            <tr class="py-4 border-gray-200 fw-semibold fs-7 border-bottom">
              <th class="min-w-150px">Nombre</th>
              <th class="min-w-150px">Fecha Inicio</th>
              <th class="min-w-150px">Fecha Fin</th>
              <th class="min-w-200px">Lugar</th>
              <th class="min-w-150px">Tipo Ingreso</th>
              <th class="min-w-150px">Visibilidad</th>
              <th class="min-w-50px">Acciones</th>
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
                      <Badge
                        :value="
                          evento.solo_ingreso
                            ? 'Solo Ingreso'
                            : 'Ingreso - Salida'
                        "
                        :severity="evento.solo_ingreso ? 'info' : 'warning'"
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
                          evento.tipo.toLowerCase() === 'privado'
                            ? 'Privado'
                            : 'Público'
                        "
                        :severity="
                          evento.tipo.toLowerCase() === 'privado'
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
                <div class="d-flex">
                  <div class="dropdown">
                    <button
                      class="btn btn-secondary dropdown-toggle btn-sm"
                      type="button"
                      id="dropdownMenuButton1"
                      data-bs-toggle="dropdown"
                      aria-expanded="false"
                      data-boundary="viewport"
                    ></button>
                    <ul
                      class="dropdown-menu"
                      aria-labelledby="dropdownMenuButton1"
                    >
                      <li></li>
                      <li>
                        <a
                          :href="route('evento.detalle', { id: evento.id })"
                          class="dropdown-item"
                          ><i class="bi bi-pencil-square fs-4"></i>

                          Detalles</a
                        >
                      </li>
                    </ul>
                  </div>
                </div>
              </td>
            </tr>
            <tr v-if="store.eventos.length === 0">
              <td colspan="5" class="text-center">No hay datos</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <Toast />
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

export default {
  name: "EventosIndex",
  components: {
    VueDatePicker,
    LMap,
    LTileLayer,
    LCircleMarker,
    LMarker,
    MapaComponent,
  },
  setup() {
    const store = useDataEventos();
    const toast = useToast();

    return { store, toast };
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
  },
};
</script>
