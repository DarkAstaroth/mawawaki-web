<template>
  <div class="row">
    <!-- ... (Fichas de pacientes similares) ... -->
  </div>

  <div class="card card-bordered">
    <div class="card-header">
      <h3 class="card-title">Listado de pacientes</h3>
      <div class="div card-toolbar">
        <button type="button" class="btn btn-sm btn-success">
          <i class="text-white far fa-plus"></i>
          Nuevo
        </button>
      </div>
    </div>

    <div class="card-body">
      <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
        <!-- ... (Pestañas de filtrado similares) ... -->
      </ul>
      <input
        class="mb-5 form-control"
        type="text"
        v-model="busqueda"
        @input="filtrarPacientes"
        placeholder="Buscar..."
      />
      <div class="table-responsive">
        <table class="table table-striped table-bordered">
          <thead>
            <tr class="py-4 border-gray-200 fw-semibold fs-7 border-bottom">
              <th class="min-w-150px">Nombre</th>
              <th class="min-w-150px">Tipo</th>
              <th class="max-w-100px">Estado</th>
              <th class="max-w-100px">Verificado</th>
              <th class="min-w-150px">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="paciente in pacientes" :key="paciente.id">
              <td class="align-middle">
                {{ paciente.persona.nombre }} {{ paciente.persona.paterno }}
                {{ paciente.persona.materno }}
              </td>
              <td class="align-middle">
                {{ paciente.tipo_paciente }}
              </td>
              <td class="align-middle">
                <InputSwitch
                  v-model="paciente.estado"
                  @change="cambiarEstadoSwitch(paciente)"
                />
              </td>
              <td class="align-middle">
                <span
                  v-if="paciente.verificado"
                  class="badge badge-success badge-lg"
                  >Verificado</span
                >
                <span v-else class="badge badge-warning badge-lg"
                  >No verificado</span
                >
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
                    <ul
                      class="dropdown-menu"
                      aria-labelledby="dropdownMenuButton1"
                    >
                      <li>
                        <a
                          href="#"
                          class="dropdown-item"
                          data-bs-toggle="modal"
                          data-bs-target="#kt_modal_1"
                          ><i class="bi bi-clipboard2-data"></i>

                          Ver datos</a
                        >
                      </li>
                      <li>
                        <a
                          href="#"
                          class="dropdown-item"
                          data-bs-toggle="modal"
                          data-bs-target="#kt_modal_1"
                          @click="verificarPaciente(paciente)"
                          ><i class="bi bi-check-circle-fill"></i>

                          Verificar</a
                        >
                      </li>
                    </ul>
                  </div>
                </div>
              </td>
            </tr>
            <tr v-if="pacientes.length === 0">
              <td colspan="4" class="text-center">No hay datos</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
  
  <script>
import axios from "axios";
import { useToast } from "vue-toastification";
import InputSwitch from "primevue/inputswitch";

export default {
  name: "PacientesIndex",
  components: { InputSwitch },
  setup() {
    const toast = useToast();
    return { toast };
  },
  data() {
    return {
      ficha: {
        activos: 0,
        no_activos: 0,
        solicitudes: 0,
        verificados: 0,
        por_verificar: 0,
      },
      pacientes: [],
      busqueda: "",
      parametro: "todos",
      paginacion: {
        total: 0,
        porPagina: 10,
        paginaActual: 1,
        ultimaPagina: 1,
        desde: 0,
        hasta: 0,
      },
    };
  },
  validations() {},
  mounted() {
    this.cargarPacientes(1);
    //   this.cargarFichasPaciente();
  },
  methods: {
    filtrarPacientes() {
      this.cargarPacientes(1);
    },
    cambiarParametro(tabSeleccionada) {
      this.parametro = tabSeleccionada;
      this.cargarPacientes(1);
    },
    cargarPacientes(pagina) {
      const url =
        "/api/pacientes?page=" +
        pagina +
        "&busqueda=" +
        this.busqueda +
        "&parametro=" +
        this.parametro;
      axios
        .get(url)
        .then((response) => {
          this.pacientes = response.data.pacientes;
        })
        .catch((error) => {});
    },
    cargarFichasPaciente() {
      const url = `/api/fichasPacientes`;
      axios
        .get(url)
        .then((response) => {
          this.ficha.activos = response.data.activos;
          this.ficha.no_activos = response.data.no_activos;
          this.ficha.solicitudes = response.data.solicitudes;
          this.ficha.verificados = response.data.verificados;
          this.ficha.por_verificar = response.data.por_verificar;
        })
        .catch((error) => {});
    },
    verificarPaciente(paciente) {
      axios
        .put(`/api/verificar-paciente/${paciente.id}`)
        .then((response) => {
          this.toast.success(response.data.message);
          this.cargarPacientes(1);
        })
        .catch((error) => {
          this.toast.warning(error.response.data.message);
        });
    },
    cambiarEstadoSwitch(paciente) {
      this.cambiarEstadoPaciente(paciente);
    },
    cambiarEstadoPaciente(paciente) {
      const nuevoEstado = paciente.estado ? 0 : 1;
      axios
        .put(`/api/cambiar-estado-paciente/${paciente.id}`, {
          estado: nuevoEstado,
        })
        .then((response) => {
          this.toast.success(response.data.message);
        })
        .catch((error) => {
          this.toast.warning(error.response.data.message);
        });
    },
  },
};
</script>
  