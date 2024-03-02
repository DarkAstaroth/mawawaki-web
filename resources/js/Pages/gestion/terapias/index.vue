<template>
  <div class="card card-bordered mb-5">
    <div class="card-header">
      <h3 class="card-title">Listado de servicios</h3>
      <div class="div card-toolbar">
        <a class="btn btn-sm btn-success" @click="estadoModal(true)">
          <i class="text-white far fa-plus"></i>
          Nuevo servicio
        </a>
      </div>
    </div>
  </div>

  <div>
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
      <div
        class="col-xxl-4"
        v-for="servicio in store.servicios"
        :key="servicio.id"
      >
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
                        <span class="text-gray-800 fs-2 fw-bold"
                          >{{ servicio.paciente.persona.nombre }}
                          {{ servicio.paciente.persona.paterno }}
                          {{ servicio.paciente.persona.materno }}</span
                        ><br />
                      </div>

                      <span
                        :class="badgeClass(servicio.tipo_servicio)"
                        class="flex-shrink-0 align-self-center py-3 px-4 fs-7"
                      >
                        {{ servicio.tipo_servicio.toLowerCase().capitalize() }}
                      </span>
                    </div>
                  </div>

                  <div
                    class="d-flex gap-2 flex-stack mt-auto bd-highlight justify-content-start"
                  >
                    <a
                      :href="route('servicio.detalles', servicio.id)"
                      class="btn btn-outline btn-outline-dashed btn-outline-default"
                    >
                      <i class="ki-duotone ki-like-2 fs-1 text-primary">
                        <span class="path1"></span>
                        <span class="path2"></span>
                      </i>
                      Detalles
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <Dialog
    v-model:visible="modalTerapia"
    modal
    header="Crear nuevo servicio"
    position="top"
    :style="{ width: '50rem' }"
    :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
  >
    <form class="input-feild" v-on:submit.prevent="registrar">
      <div class="">
        <div class="d-flex flex-column gap-2 mb-5">
          <label for="username">Tipo de servicio</label>
          <Dropdown
            id="tipoPaciente"
            v-model="datosServicio.tipo_servicio"
            :options="['EQUITACION', 'EQUINOTERAPIA']"
            placeholder="Seleccione una opción"
          />
        </div>

        <div class="d-flex flex-column gap-2 mb-5">
          <label for="username">Paciente</label>
          <Dropdown
            v-model="pacienteSeleccionado"
            :options="pacientes"
            filter
            optionLabel="nombre"
            placeholder="Selecciona paciente"
            class="w-100 md:w-14rem"
          >
            <template #value="slotProps">
              <div v-if="slotProps.value">{{ slotProps.value.nombre }}</div>
              <span v-else>{{ slotProps.placeholder }}</span>
            </template>
            <template #option="slotProps">
              <div>{{ slotProps.option.nombre }}</div>
            </template>
          </Dropdown>
        </div>

        <div class="d-flex gap-2 mb-5 w-100">
          <div class="w-100">
            <label for="username">Fecha Ingreso</label>
            <Calendar
              showIcon
              class="w-100"
              id="calendar-12h"
              v-model="datosServicio.fecha_ingreso"
            />
          </div>
          <div class="w-100">
            <label for="username">Fecha Salida</label>
            <Calendar
              showIcon
              class="w-100"
              id="calendar-12h"
              v-model="datosServicio.fecha_salida"
            />
          </div>
        </div>

        <div class="d-flex flex-column gap-2">
          <label for="username"> Observaciones</label>
          <Textarea v-model="datosServicio.observaciones" rows="3" cols="30" />
        </div>
        <div class="form-group">
          <div class="row mt-5">
            <div class="col">
              <button class="btn btn-sm btn-success">Crear servicio</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </Dialog>
  <Toast />
</template>

<script>
import { useDataPacientes } from "../../../store/dataPaciente";
import { useToast } from "primevue/usetoast";
import { ref } from "vue";

export default {
  name: "TerapiasIndex",
  data() {
    return {
      pacientes: [],
      modalTerapia: false,
      datosServicio: {
        tipo_servicio: "",
        fecha_ingreso: null,
        fecha_salida: null,
        observaciones: "",
      },
    };
  },
  setup() {
    const pacienteSeleccionado = ref(null);
    const toast = useToast();

    const store = useDataPacientes();
    return { store, toast, pacienteSeleccionado };
  },

  mounted() {
    this.store.cargarPacientes();
    this.pacientes = this.store.pacientes.map((paciente) => {
      let nombreCompleto = `${paciente.persona.nombre} ${paciente.persona.paterno} ${paciente.persona.materno}`;
      return {
        nombre: nombreCompleto.trim(),
        value: paciente.id,
      };
    });
    this.store.cargarServicios();
  },
  methods: {
    badgeClass(tipoServicio) {
      return {
        "badge-success": tipoServicio === "EQUINOTERAPIA",
        "badge-info": tipoServicio === "EQUITACION",
      };
    },
    recargar() {
      this.datosServicio = {
        tipo_servicio: "",
        fecha_ingreso: null,
        fecha_salida: null,
        observaciones: "",
      };
      this.pacienteSeleccionado = null;
    },
    mostrarMensaje(tipo, titulo, texto) {
      this.toast.add({
        severity: tipo,
        summary: titulo,
        detail: texto,
        life: 2000,
      });
    },
    estadoModal: function (estado) {
      this.modalTerapia = estado;
    },
    registrar() {
      this.store
        .registrarServicio(this.pacienteSeleccionado.value, this.datosServicio)
        .then((respuesta) => {
          this.mostrarMensaje(
            "success",
            "Operación Exitosa",
            "Servicio registrado éxito"
          );
        })
        .catch((error) => {
          this.mostrarMensaje(
            "error",
            "Error",
            "No se puedo enviar la notificación"
          );
        });
      this.modalTerapia = false;
      this.recargar();
      this.store.cargarServicios();
    },
  },
};

String.prototype.capitalize = function () {
  return this.charAt(0).toUpperCase() + this.slice(1);
};
</script>
