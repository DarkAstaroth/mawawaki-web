<template>
  <div>
    <h3 class="mb-8">Detalles del servicio</h3>
    <div class="card">
      <div class="card-body d-flex flex-row gap-4 w-100">
        <div class="w-100">
          <h5 class="card-title">Detalles del Servicio</h5>
          <p class="card-text">
            Tipo de Servicio: {{ servicio.tipo_servicio }}
          </p>
        </div>
        <div class="w-100">
          <h5 class="card-title">Detalles del Paciente</h5>
          <p class="card-text">
            Nombre del Paciente: {{ servicio.paciente.persona.nombre }}
            {{ servicio.paciente.persona.paterno }}
            {{ servicio.paciente.persona.materno }}
          </p>
          <p class="card-text">
            Estado de Salud: {{ servicio.paciente.estado_salud }}
          </p>

          <p class="card-text">
            Precondicion: {{ servicio.paciente.precondicion }}
          </p>
        </div>
        <div class="w-100">
          <h5 class="card-title">Detalles de Contacto de Emergencia</h5>
          <p class="card-text">
            Nombre: {{ servicio.paciente.contacto_emergencia_nombre }}
          </p>
          <p class="card-text">
            Teléfono: {{ servicio.paciente.contacto_emergencia_telefono }}
          </p>
        </div>
      </div>
    </div>
  </div>

  <div class="card card-bordered mt-5">
    <div class="card-header">
      <h3 class="card-title">Listado de sesiones</h3>
      <div class="div card-toolbar">
        <a class="btn btn-sm btn-success" @click="estadoModal(true)">
          <i class="text-white far fa-plus"></i>
          Nuevo sesion
        </a>
      </div>
    </div>
  </div>

  <Dialog
    v-model:visible="modalSesion"
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
            id="estadoSesion"
            v-model="datosSesion.estado_sesion"
            :options="['En Progreso', 'Completado', 'Cancelado', 'Pendiente']"
            placeholder="Seleccione un estado"
          />
        </div>

        <div class="d-flex flex-column gap-2 mb-5">
          <label for="username">Responsable</label>
          <Dropdown
            v-model="responsableSeleccionado"
            :options="personal"
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

        <div class="d-flex flex-column gap-2 mb-5">
          <label for="username">Terapeuta</label>
          <Dropdown
            v-model="terapeutaSeleccionado"
            :options="personal"
            filter
            optionLabel="nombre"
            placeholder="Selecciona Terapeuta"
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

        <div class="d-flex flex-column gap-2 mb-5">
          <label for="username">Co-Terapeuta</label>
          <Dropdown
            v-model="coterapeutaSeleccionado"
            :options="personal"
            filter
            optionLabel="nombre"
            placeholder="Selecciona co-terapeuta"
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

        <div class="d-flex flex-column gap-2 mb-5">
          <label for="username">Apoyo</label>
          <Dropdown
            v-model="apoyoSeleccionado"
            :options="personal"
            filter
            optionLabel="nombre"
            placeholder="Selecciona apoyo"
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
            <label for="username">Fecha Inicio</label>
            <Calendar
              showTime
              hourFormat="12"
              showIcon
              class="w-100"
              id="calendar-12h"
              v-model="datosSesion.fecha_sesion"
            />
          </div>
        </div>

        <div class="d-flex flex-column gap-2">
          <label for="username"> Observaciones</label>
          <Textarea v-model="datosSesion.observaciones" rows="3" cols="30" />
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
  name: "DetallesServicio",
  props: ["servicio"],
  data() {
    return {
      personal: [],
      modalSesion: false,
      datosSesion: {
        estado: "",
        fecha_sesion: null,
        observaciones: "",
      },
    };
  },
  setup() {
    const responsableSeleccionado = ref(null);
    const terapeutaSeleccionado = ref(null);
    const coterapeutaSeleccionado = ref(null);
    const apoyoSeleccionado = ref(null);

    const toast = useToast();

    const store = useDataPacientes();
    return {
      store,
      toast,
      responsableSeleccionado,
      terapeutaSeleccionado,
      coterapeutaSeleccionado,
      apoyoSeleccionado,
    };
  },
  mounted() {
    this.store.obtenerPersonal();
    this.personal = this.store.personal.map((personal) => {
      const label = `${personal.usuario.persona.nombre} ${personal.usuario.persona.paterno} ${personal.usuario.persona.materno} | ${personal.carrera}`;
      return {
        nombre: label,
        value: personal.id,
      };
    });
    console.log(this.personal);
  },
  methods: {
    estadoModal: function (estado) {
      this.modalSesion = estado;
    },
  },
};
</script>
