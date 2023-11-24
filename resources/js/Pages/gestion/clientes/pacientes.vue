<template>
  <div class="card card-bordered mb-10">
    <div class="card-header">
      <h3 class="card-title">Listado de Pacientes Asignados</h3>
      <div class="card-toolbar">
        <button
          type="button"
          class="btn btn-sm btn-success"
          data-bs-toggle="modal"
          data-bs-target="#kt_modal_1"
          @click="
            modo = 'crear';
            estadoModal(true);
          "
        >
          <i class="far fa-plus text-white"></i>
          Nuevo
        </button>
      </div>
      <Dialog
        v-model:visible="modalSolicitud"
        maximizable
        modal
        header="Crear Solicitud"
        :style="{ width: '50rem' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
      >
        <form @submit.prevent="enviarSolicitud">
          <div class="row">
            <div class="col">
              <h5>Datos Personales</h5>
            </div>
            <div class="col-md-12">
              <div class="d-flex flex-column gap-2 mb-5">
                <label for="username">Nombre</label>
                <InputText
                  id="username"
                  v-model="datosPersona.nombre"
                  aria-describedby="username-help"
                />
              </div>
            </div>

            <div class="col-md-6">
              <div class="d-flex flex-column gap-2 mb-5">
                <label for="username">Apellido Paterno</label>
                <InputText
                  id="username"
                  v-model="datosPersona.paterno"
                  aria-describedby="username-help"
                />
              </div>
            </div>

            <div class="col-md-6">
              <div class="d-flex flex-column gap-2 mb-5">
                <label for="username">Apellido materno</label>
                <InputText
                  id="username"
                  v-model="datosPersona.materno"
                  aria-describedby="username-help"
                />
              </div>
            </div>

            <div class="col-md-6">
              <div class="d-flex flex-column gap-2 mb-5">
                <label for="username">Carnet de Identidad</label>
                <InputText
                  id="username"
                  v-model="datosPersona.ci"
                  aria-describedby="username-help"
                />
              </div>
            </div>

            <div class="col-md-6">
              <div class="d-flex flex-column gap-2 mb-5">
                <label for="username">Fecha de Nacimiento</label>
                <Calendar
                  class="w-100"
                  id="calendar-12h"
                  v-model="datosPersona.fechaNacimiento"
                  :disabled="controlFecha"
                />
              </div>
            </div>

            <div class="col-md-6">
              <div class="d-flex flex-column gap-2 mb-5">
                <label for="username">Tipo de Paciente</label>
                <Dropdown
                  id="tipoPaciente"
                  v-model="datosPaciente.tipoPaciente"
                  :options="['interno', 'externo']"
                  placeholder="Seleccione"
                />
              </div>
            </div>

            <div class="col-12">
              <h5>Datos Paciente</h5>
            </div>

            <div class="col-md-12">
              <div class="d-flex flex-column gap-2 mb-5">
                <label for="username">Estado de Salud</label>
                <InputText
                  id="username"
                  v-model="datosPaciente.estadoSalud"
                  aria-describedby="username-help"
                />
              </div>
            </div>

            <div class="col-md-12">
              <div class="d-flex flex-column gap-2 mb-5">
                <label for="username">Precondicion</label>
                <InputText
                  id="username"
                  v-model="datosPaciente.precondicion"
                  aria-describedby="username-help"
                />
              </div>
            </div>

            <div class="col-md-6">
              <div class="d-flex flex-column gap-2 mb-5">
                <label for="contactoEmergenciaNombre"
                  >Contacto de Emergencia - Nombre</label
                >
                <InputText
                  id="contactoEmergenciaNombre"
                  v-model="datosPaciente.contactoEmergenciaNombre"
                />
              </div>
            </div>

            <div class="col-md-6">
              <div class="d-flex flex-column gap-2 mb-5">
                <label for="contactoEmergenciaTelefono"
                  >Contacto de Emergencia - Teléfono</label
                >
                <InputText
                  id="contactoEmergenciaTelefono"
                  v-model="datosPaciente.contactoEmergenciaTelefono"
                />
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-primary">
            Enviar Solicitud
          </button>
        </form>
      </Dialog>
    </div>
    <div class="card-body">
      <input
        class="form-control mb-5"
        type="text"
        v-model="busqueda"
        @input="filtrarPacientes"
        placeholder="Buscar..."
      />
      <!-- Paginación... -->
    </div>
  </div>

  <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
    <div class="col-xxl-4" v-for="(paciente, index) in pacientes" :key="index">
      <div class="card card-flush h-xl-100">
        <div class="card-body">
          <div class="row gx-9 h-100">
            <div class="col-sm-12">
              <div class="d-flex flex-column h-100 w-100">
                <div class="mb-7">
                  <div class="d-flex align-items-center flex-stack w-100">
                    <div class="flex-shrink-0 me-5">
                      <span
                        class="text-gray-400 fs-7 fw-bold me-2 d-block lh-1 pb-1"
                        >Paciente</span
                      >
                      <span class="text-gray-800 fs-2 fw-bold">{{
                        paciente.persona.nombre
                      }}</span
                      ><br /><span class="text-gray-800 fs-2 fw-bold"
                        >{{ paciente.persona.paterno }}
                        {{ paciente.persona.materno }}</span
                      >
                      <span class="text-gray-800 fs-2 fw-bold"></span>
                    </div>

                    <span
                      class="badge badge-success flex-shrink-0 align-self-center py-3 px-4 fs-7"
                      :class="
                        paciente.verificado === 1
                          ? 'badge-success'
                          : 'badge-warning'
                      "
                    >
                      {{ paciente.verificado ? "Verificado" : "No Verificado" }}
                    </span>
                  </div>
                </div>

                <div
                  class="d-flex gap-2 flex-stack mt-auto bd-highlight justify-content-start"
                >
                  <a
                    href="#"
                    class="btn btn-outline btn-outline-dashed btn-outline-default"
                  >
                    <i class="ki-duotone ki-like-2 fs-1 text-danger">
                      <span class="path1"></span>
                      <span class="path2"></span>
                    </i>

                    Terapias</a
                  >

                  <a
                    href="#"
                    class="btn btn-outline btn-outline-dashed btn-outline-default"
                  >
                    <i class="ki-duotone ki-like-2 fs-1 text-primary">
                      <span class="path1"></span>
                      <span class="path2"></span>
                    </i>

                    Detalles</a
                  >
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <nav>
    <ul class="pagination">
      <li
        class="page-item"
        :class="{ disabled: paginacion.paginaActual === 1 }"
      >
        <a class="page-link" href="#" @click="cargarRoles(1)">Primera</a>
      </li>
      <li
        class="page-item"
        :class="{ disabled: paginacion.paginaActual === 1 }"
      >
        <a
          class="page-link"
          href="#"
          @click="cargarRoles(paginacion.paginaActual - 1)"
          >Anterior</a
        >
      </li>
      <li
        class="page-item"
        v-for="page in paginacion.ultimaPagina"
        :key="page"
        :class="{ active: paginacion.paginaActual === page }"
      >
        <a class="page-link" href="#" @click="cargarRoles(page)">{{ page }}</a>
      </li>
      <li
        class="page-item"
        :class="{
          disabled: paginacion.paginaActual === paginacion.ultimaPagina,
        }"
      >
        <a
          class="page-link"
          href="#"
          @click="cargarRoles(paginacion.paginaActual + 1)"
          >Siguiente</a
        >
      </li>
      <li
        class="page-item"
        :class="{
          disabled: paginacion.paginaActual === paginacion.ultimaPagina,
        }"
      >
        <a
          class="page-link"
          href="#"
          @click="cargarRoles(paginacion.ultimaPagina)"
          >Última</a
        >
      </li>
    </ul>
  </nav>
</template>

<script>
import { useVuelidate } from "@vuelidate/core";
import { required, email } from "@vuelidate/validators";
import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext";
import Dropdown from "primevue/dropdown";
import Calendar from "primevue/calendar";

export default {
  name: "PacientesAsignados",
  props: ["usuario"],
  components: { Dialog, InputText, Dropdown, Calendar },
  setup() {
    return { v$: useVuelidate() };
  },
  data() {
    return {
      usuario: this.usuario,
      datosPersona: {
        nombre: "",
        paterno: "",
        materno: "",
        ci: "",
        fechaNacimiento: "",
      },
      datosPaciente: {
        tipoPaciente: "",
        fechaIngreso: "",
        estadoSalud: "",
        precondicion: "",
        contactoEmergenciaNombre: "",
        contactoEmergenciaTelefono: "",
      },
      pacienteId: "",
      nombre: "",
      descripcion: "",
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
      modo: "crear",
      enviado: false,
      modalSolicitud: false,
    };
  },
  mounted() {
    this.cargarPacientes(1);
  },
  methods: {
    cargarPacientes(pagina) {
      const url =
        "/api/pacientes/cliente/" +
        this.usuario.id +
        "?page=" +
        pagina +
        "&busqueda=" +
        this.busqueda +
        "&parametro=" +
        this.parametro;
      axios
        .get(url)
        .then((response) => {
          console.log(response);
          this.pacientes = response.data.pacientes;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    estadoModal: function (estado) {
      this.modalSolicitud = estado;
    },
    async enviarSolicitud() {
      try {
        // Realizar la solicitud al servidor
        const response = await axios.post("/api/pacientes/registrar", {
          cliente: this.usuario.cliente.id,
          nombre: this.datosPersona.nombre,
          paterno: this.datosPersona.paterno,
          materno: this.datosPersona.materno,
          ci: this.datosPersona.ci,
          fechaNacimiento: this.datosPersona.fechaNacimiento,
          tipoPaciente: this.datosPaciente.tipoPaciente,
          estadoSalud: this.datosPaciente.estadoSalud,
          precondicion: this.datosPaciente.precondicion,
          contactoEmergenciaNombre: this.datosPaciente.contactoEmergenciaNombre,
          contactoEmergenciaTelefono:
            this.datosPaciente.contactoEmergenciaTelefono,
        });

        // Manejar la respuesta del servidor
        console.log(response.data.message); // Mensaje del servidor
        console.log(response.data.paciente); // Datos del paciente registrado

        // Cerrar el modal después de enviar la solicitud
        this.modalSolicitud = false;

        // Recargar la lista de pacientes (opcional)
        this.cargarPacientes(1);

        // Limpiar los datos del formulario después de enviar la solicitud (opcional)
        this.datosPersona = {
          nombre: "",
          paterno: "",
          materno: "",
          ci: "",
          fechaNacimiento: "",
        };
        this.datosPaciente = {
          tipoPaciente: "",
          estadoSalud: "",
          precondicion: "",
          contactoEmergenciaNombre: "",
          contactoEmergenciaTelefono: "",
        };
      } catch (error) {
        console.error(error);
        // Manejar errores según tus necesidades
      }
    },
  },
};
</script>
