<template>
  <div class="card">
    <div class="card-body">
      <div>
        <h5 class="card-title">Configuraciones Asistencias</h5>
        <div v-if="!eventoPrincipal">
          <p></p>
          <Message severity="warn" :closable="false"
            >No hay evento principal disponible para marcar asistencia.</Message
          >
          <Dropdown
            v-model="selectEvento"
            :options="eventosFiltro"
            filter
            optionLabel="nombre"
            placeholder="Selecciona evento"
            class="w-100 md:w-14rem mb-5"
          >
            <template #value="slotProps">
              <div v-if="slotProps.value">{{ slotProps.value.nombre }}</div>
              <span v-else>{{ slotProps.placeholder }}</span>
            </template>
            <template #option="slotProps">
              <div>{{ slotProps.option.nombre }}</div>
            </template>
          </Dropdown>
          <Button
            label="Establecer como principal"
            @click="establecerPrincipal"
          />
        </div>
        <div v-else>
          <Message severity="success" :closable="false"
            >Control de asistencias configurado correctamente</Message
          >
        </div>
      </div>
    </div>
  </div>
</template>
  
  <script>
import { useDataEventos } from "@/store/dataEventos";
import { useToast } from "primevue/usetoast";
import { ref } from "vue";

export default {
  name: "ConfigGeneral",
  data() {
    return {
      eventoPrincipal: false,
      eventosFiltro: [],
      selectEvento: null,
    };
  },
  setup() {
    const store = useDataEventos();
    const toast = useToast();
    return { store, toast };
  },
  mounted() {
    this.store
      .verificarEvento()
      .then((res) => {
        this.eventoPrincipal = res.data.existe;
      })
      .catch((error) => {});

    this.store
      .eventosFiltro()
      .then((res) => {
        this.eventosFiltro = res.data;
      })
      .catch((error) => {});
  },
  methods: {
    establecerPrincipal() {
      if (!this.selectEvento) {
        return;
      }
      this.store
        .establecerPrincipal(this.selectEvento)
        .then((res) => {
          this.eventoPrincipal = true; // Actualizar la variable eventoPrincipal
          this.selectEvento = null; // Reiniciar el selectEvento después de establecer el evento principal
        })
        .catch((error) => {});
    },
  },
};
</script>
  