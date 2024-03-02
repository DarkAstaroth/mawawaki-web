<template>
  <div
    class="d-flex w-100 justify-content-center flex-column align-items-center gap-5"
  >
    <Card
      v-for="evento in eventos"
      :key="evento.id"
      style="width: 25rem; overflow: hidden"
    >
      <template #header> </template>
      <template #title>{{ evento.nombre }}</template>
      <template #subtitle>
        <Badge
          :value="
            evento.tipo.toLowerCase() === 'privado' ? 'Privado' : 'Público'
          "
          :severity="
            evento.tipo.toLowerCase() === 'privado' ? 'info' : 'warning'
          "
        ></Badge>
        <Badge
          v-if="!haFinalizado(evento.fecha_hora_fin)"
          value="Programado"
          severity="warning"
        ></Badge>
        <Badge v-else value="Finalizado" severity="danger"></Badge>
      </template>
      <template #content>
        <strong>Fecha: {{ fechaHoraLegible(evento.fecha_hora_inicio) }}</strong>
        <p class="m-0">{{ evento.descripcion }}</p>
      </template>
      <template #footer>
        <Badge
          v-if="evento.solo_ingreso === 1"
          value="Solo ingreso"
          severity="success"
        ></Badge>
      </template>
    </Card>
  </div>
</template>
  
  <script>
import axios from "axios";
import dayjs from "dayjs";
import "dayjs/locale/es";

export default {
  name: "EventosDashboard",
  data() {
    return {
      eventos: [],
    };
  },
  mounted() {
    this.obtenerEventos();
  },
  methods: {
    obtenerEventos() {
      axios
        .get("api/eventos/privados")
        .then((resultado) => {
          this.eventos = resultado.data;
        })
        .catch((err) => {
          console.error("Error al obtener eventos:", err);
        });
    },
    fechaHoraLegible(fecha) {
      return dayjs.unix(fecha).format("D [de] MMMM [-] h:mm A");
    },
    haFinalizado(fechaFin) {
      return dayjs().isAfter(dayjs.unix(fechaFin));
    },
  },
};
</script>
  