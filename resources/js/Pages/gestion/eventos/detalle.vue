<template>
  <!-- <div class="card card-bordered mb-3">
    <div class="card-header">
      <h3 class="card-title">Evento: {{ nombre }}</h3>
      <div class="card-toolbar">
        <button
          type="button"
          class="btn btn-sm btn-success"
          data-bs-toggle="modal"
          data-bs-target="#kt_modal_1"
          @click="asignarPermisos()"
        >
          <i class="far fa-save text-white"></i>
          Guardar cambios
        </button>
      </div>
    </div>
    <div class="card-body">
      <div class="form-group mb-5">
        <input
          type="text"
          v-model="nombre"
          id=""
          class="form-control"
          placeholder="Nombre rol"
          aria-describedby="helpId"
        />
        <div
          v-if="v$?.nombre.$error"
          class="m-2 fv-plugins-message-container invalid-feedback"
        >
          <div data-field="text_input" data-validator="notEmpty">
            El nombre de rol es requerido
          </div>
        </div>
      </div>
      <div class="form-group">
        <textarea
          class="form-control"
          v-model="descripcion"
          id=""
          rows="3"
          placeholder="Descripción"
        ></textarea>
        <div
          v-if="v$?.descripcion.$error"
          class="m-2 fv-plugins-message-container invalid-feedback"
        >
          <div data-field="text_input" data-validator="notEmpty">
            La descripcion es requerida
          </div>
        </div>
      </div>
    </div>
  </div> -->
  <form class="input-feild" v-on:submit.prevent="modificar">
    <div class="d-flex justify-content-between gap-5 mb-5">
      <h3 class="card-title">Detalles evento</h3>
      <button type="submit" class="btn btn-success btn-sm">
        Actualizar datos
      </button>
    </div>
    <div
      :class="[
        'd-flex gap-5 mb-10',
        esResponsivo ? ' flex-column ' : 'flex-row',
      ]"
    >
      <div class="card p-5 flex-grow-1">
        <div class="card flex justify-content-center">
          <div class="d-flex flex-column gap-2">
            <label for="titulo">Nombre del evento</label>
            <InputText v-model="nombre" class="text-capitalize" />
            <div
              v-if="v$?.nombre.$error"
              class="fv-plugins-message-container invalid-feedback"
            >
              <div data-field="text_input" data-validator="notEmpty">
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
              <div data-field="text_input" data-validator="notEmpty">
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
              <div data-field="text_input" data-validator="notEmpty">
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
              <label for="tipoEvento1" class="ml-5 px-2">Público</label>
            </div>
            <div class="d-flex align-items-center p-2">
              <RadioButton
                v-model="tipoEvento"
                inputId="tipoEvento2"
                name="privado"
                value="privado"
              />
              <label for="tipoEvento2" class="ml-5 px-2">Privado</label>
            </div>
          </div>

          <div class="d-flex align-items-center flex-grow-1">
            <label for="ingreso" class="ml-5 px-2">Solo ingreso</label>
            <div class="p-1 mt-1">
              <InputSwitch v-model="soloIngreso" />
            </div>
          </div>
        </div>

        <div class="card flex justify-content-center">
          <div class="d-flex flex-column gap-2">
            <label for="titulo">Lugar del evento</label>
            <InputText v-model="lugar" class="text-capitalize" />
            <div
              v-if="v$?.lugar.$error"
              class="fv-plugins-message-container invalid-feedback"
            >
              <div data-field="text_input" data-validator="notEmpty">
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
            class="w-100 text-capitalize"
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
        <MapaComponent
          @obtenerDatos="establecerDatos"
          :latitud="latitud"
          :longitud="longitud"
        />
      </div>
    </div>
  </form>
  <Toast />
</template>


<script>
import { useVuelidate } from "@vuelidate/core";
import { required, email } from "@vuelidate/validators";
import MapaComponent from "./mapa.vue";

import dayjs from "dayjs";
import "dayjs/locale/es";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { useDataEventos } from "@/store/dataEventos";
import { useToast } from "primevue/usetoast";
dayjs.locale("es");

export default {
  name: "EventoDetalle",
  components: { VueDatePicker, MapaComponent },
  props: ["evento"],
  setup() {
    const store = useDataEventos();
    const toast = useToast();

    return { v$: useVuelidate(), store, toast };
  },
  data() {
    return {
      qrs: [],
      id: this.evento.id,
      nombre: this.evento.nombre,
      fechaInicio: this.convertirFecha(this.evento.fecha_hora_inicio),
      fechaFin: this.convertirFecha(this.evento.fecha_hora_fin),
      tipoEvento: this.evento.tipo,
      soloIngreso: this.evento.solo_ingreso === 1 ? true : false,
      lugar: this.evento.lugar,
      descripcion: this.evento.descripcion,
      usuariosSeleccionados: JSON.parse(this.evento.usuarios_ids),
      usuariosFiltro: null,
      latitud: this.evento.latitud,
      longitud: this.evento.longitud,
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
      esResponsivo: false,
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
  },
  methods: {
    mostrarMensaje(tipo, titulo, texto) {
      this.toast.add({
        severity: tipo,
        summary: titulo,
        detail: texto,
        life: 2000,
      });
    },
    fechaHoraLegible(fecha) {
      return dayjs.unix(fecha).format("D [de] MMMM [-] H:mm");
    },
    resetModalData: function () {
      this.nombre = "";
      this.description = "";
    },
    convertirFecha(timestamp) {
      const date = new Date(timestamp * 1000);
      return date;
    },
    modificar() {
      this.v$.$touch();
      if (!this.v$.$error) {
        this.store
          .modificarEvento(
            this.id,
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
          .then(() => {
            this.mostrarMensaje(
              "success",
              "Operación Exitosa",
              "Evento modificado correctamente"
            );

            this.busqueda = "";
            this.store.cargarEventos(1, this.busqueda);
            this.modalCrearEvento = !this.modalCrearEvento;
          })
          .catch((respuesta) => {
            this.mostrarMensaje("error", "Error al crear registro", respuesta);
          });
      }
    },
    verificarResponsivo() {
      this.esResponsivo = window.innerWidth < 768;
    },
    establecerDatos(obj) {
      this.latitud = obj.lat;
      this.longitud = obj.lng;
    },
  },
};
</script>
