<template>
  <div class="mb-6">
    <Dropdown
      v-model="selectEvento"
      :options="options"
      filter
      optionLabel="nombre"
      placeholder="Selecciona evento"
      class="w-100 md:w-14rem"
      @change="obtenerAsistencias"
    >
      >
      <template #value="slotProps">
        <div v-if="slotProps.value">{{ slotProps.value.name }}</div>
        <span v-else>{{ slotProps.placeholder }}</span>
      </template>
      <template #option="slotProps">
        <div>{{ slotProps.option.name }}</div>
      </template>
    </Dropdown>
  </div>

  <div class="table-responsive">
    <table class="table table-striped table-sm table-bordered">
      <thead>
        <tr class="py-4 border-gray-200 fw-semibold fs-7 border-bottom">
          <th class="min-w-150px">Ingreso</th>
          <th class="min-w-150px">Salida</th>
          <th class="min-w-150px">Total</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="asistencia in asistencias" :key="asistencia.id">
          <td class="align-middle">
            {{ asistencia.fecha_hora_entrada }}
          </td>
          <td class="align-middle">
            {{ asistencia.fecha_hora_salida }}
          </td>
          <td class="align-middle">
            {{ asistencia.horas }} hora(s) {{ asistencia.minutos }} minuto(s)
            {{ asistencia.segundos }} segundos
          </td>
        </tr>
        <tr v-if="asistencias.length === 0">
          <td colspan="4" class="text-center">No hay datos</td>
        </tr>
      </tbody>
    </table>
  </div>
  <div>
    <Message :closable="false" severity="info">
      Total Horas: {{ totalGlobal.horas }} minutos:
      {{ totalGlobal.minutos }} segundos: {{ totalGlobal.segundos }}</Message
    >
  </div>
</template>

<script>
import { useDataPerfil } from "../../../../store/dataPerfil";
import VueMultiselect from "vue-multiselect";
import { ref } from "vue";

export default {
  name: "AsistenciasUsuario",
  components: { VueMultiselect },

  setup() {
    const store = useDataPerfil();
    const selectEvento = ref(null);
    return { store, selectEvento };
  },

  data() {
    return {
      asistencias: [],
      busqueda: "",
      eventoSeleccionado: "",
      value: null,
      options: [],
      paginacion: {
        total: 0,
        porPagina: 10,
        paginaActual: 1,
        ultimaPagina: 1,
        desde: 0,
        hasta: 0,
      },
      total: {},
      totalGlobal: { horas: 0, minutos: 0, segundos: 0 },
    };
  },
  validations() {
    return {};
  },
  mounted() {
    this.obtenerEventos();
  },
  methods: {
    onChange(value) {},
    obtenerEventos() {
      axios
        .get(`/api/eventos/usuario/${this.store.usuario.id}`)
        .then((response) => {
          this.options = response.data;
        })
        .catch((error) => {});
    },
    obtenerAsistencias() {
      axios
        .post(`/api/asistencias/usuario/${this.store.usuario.id}`, {
          idEvento: this.selectEvento.value,
        })
        .then((response) => {
          this.asistencias = response.data.asistencias;
          this.paginacion = response.data.paginacion;
          this.total = response.data.total;
          this.calcularTiempoTotal();
        })
        .catch((error) => {});
    },
    calcularTiempoTotal() {
      let totalSegundos = 0;

      // Convertir cada entrada a segundos y sumarlos
      this.asistencias.forEach((asistencia) => {
        totalSegundos +=
          asistencia.horas * 3600 +
          asistencia.minutos * 60 +
          asistencia.segundos;
      });

      // Convertir el total de segundos a horas, minutos y segundos
      this.totalGlobal.horas = Math.floor(totalSegundos / 3600);
      this.totalGlobal.minutos = Math.floor((totalSegundos % 3600) / 60);
      this.totalGlobal.segundos = totalSegundos % 60;
    },
  },
};
</script>


<style src="vue-multiselect/dist/vue-multiselect.css"></style>
