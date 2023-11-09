<template>
  <div class="card card-bordered mb-3">
    <div class="card-header">
      <h3 class="card-title">Evento: {{ nombre }}</h3>
      <div class="card-toolbar">
        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#kt_modal_1"
          @click="asignarPermisos()">
          <i class="far fa-save text-white"></i>
          Guardar cambios
        </button>
      </div>
    </div>
    <div class="card-body">
      <div class="form-group mb-5">
        <input type="text" v-model="nombre" id="" class="form-control" placeholder="Nombre rol"
          aria-describedby="helpId" />
        <div v-if="v$?.nombre.$error" class="m-2 fv-plugins-message-container invalid-feedback">
          <div data-field="text_input" data-validator="notEmpty">
            El nombre de rol es requerido
          </div>
        </div>
      </div>
      <div class="form-group">
        <textarea class="form-control" v-model="descripcion" id="" rows="3" placeholder="Descripción"></textarea>
        <div v-if="v$?.descripcion.$error" class="m-2 fv-plugins-message-container invalid-feedback">
          <div data-field="text_input" data-validator="notEmpty">
            La descripcion es requerida
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import { useVuelidate } from "@vuelidate/core";
import { required, email } from "@vuelidate/validators";

import dayjs from 'dayjs';
import 'dayjs/locale/es'
import axios from "axios";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
dayjs.locale('es')


export default {
  name: "EventoDetalle",
  components: { VueDatePicker },
  props: ['evento'],
  setup() {
    return { v$: useVuelidate() };
  },
  data() {
    return {
      qrs: [],
      nombre: this.evento.nombre,
      fechaInicio: null,
      fechaFin: null,
      lugar: "",
      descripcion: this.evento.descripcion,
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
    };
  },
  validations() {
    return {
      nombre: { required },
      descripcion: { required },
    };
  },
  mounted() {

  },
  methods: {

    fechaHoraLegible(fecha) {
      return dayjs.unix(fecha).format('D [de] MMMM [-] H:mm');
    },
    resetModalData: function () {
      this.nombre = "";
      this.description = "";
    },
  },
};
</script>
