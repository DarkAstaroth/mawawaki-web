<template>
  <div class="mb-6">
    <VueMultiselect
      v-model="value"
      :options="options"
      placeholder="Selecciona un Evento"
      label="name"
      track-by="name"
      @select="obtenerAsistencias"
    >
      <template v-slot:noResult>
        <span>No se encontraron resultados</span>
      </template>
    </VueMultiselect>
  </div>

  <div class="table-responsive">
    <table class="table table-striped table-sm table-bordered">
      <thead>
        <tr class="py-4 border-gray-200 fw-semibold fs-7 border-bottom">
          <th class="min-w-150px">Ingreso</th>
          <th class="min-w-150px">Salida</th>
          <th class="min-w-150px">Total</th>
          <th class="min-w-150px">Acciones</th>
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
                >
                  Acciones
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                  <li>
                    <a
                      class="dropdown-item"
                      data-bs-toggle="tooltip"
                      data-bs-custom-class="tooltip-inverse"
                      data-bs-placement="bottom"
                      title="Ver perfil"
                      :href="route('usuario.perfil', { id: usuario.id })"
                      ><i class="bi bi-eye-fill fs-4"></i> Ver Perfil</a
                    >
                  </li>
                  <li>
                    <a
                      href="#"
                      class="dropdown-item"
                      data-bs-toggle="modal"
                      data-bs-target="#kt_modal_1"
                      ><i class="bi bi-pencil-square fs-4"></i>

                      Editar</a
                    >
                  </li>
                </ul>
              </div>
            </div>
          </td>
        </tr>
        <tr v-if="asistencias.length === 0">
          <td colspan="4" class="text-center">No hay datos</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import { useVuelidate } from "@vuelidate/core";
import VueMultiselect from "vue-multiselect";

export default {
  name: "AsistenciasUsuario",
  props: ["usuario"],
  components: { VueMultiselect },

  setup() {
    return { v$: useVuelidate() };
  },

  data() {
    return {
      usuario: this.usuario,
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
    };
  },
  validations() {
    return {};
  },
  mounted() {
    this.obtenerEventos();
  },
  methods: {
    onChange(value) {
      console.log("hola");
    },
    obtenerEventos() {
      axios
        .get(`/api/eventos/usuario/${this.usuario.id}`)
        .then((response) => {
          this.options = response.data;
          console.log(response);
        })
        .catch((error) => {
          console.error(error);
        });
    },
    obtenerAsistencias(evento) {
      axios
        .post(`/api/asistencias/usuario/${this.usuario.id}`, {
          idEvento: evento.value,
        })
        .then((response) => {
          this.asistencias = response.data.asistencias;
          this.paginacion = response.data.paginacion;
          this.total = response.data.total;
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
};
</script>


<style src="vue-multiselect/dist/vue-multiselect.css"></style>
