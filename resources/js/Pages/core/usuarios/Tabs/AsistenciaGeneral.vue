<template>
  <div class="d-flex justify-content-end mb-5">
    <button type="button" class="btn btn-sm btn-danger" @click="generarPDF">
      <i class="text-white far fa-file"></i>
      PDF
    </button>
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
  <Message :closable="false" severity="success">
    Total Acumulado: {{ totalGlobal.horas }} horas
    {{ totalGlobal.minutos }} minutos
    {{ totalGlobal.segundos }} segundos</Message
  >
</template>


<script>
import { useDataEventos } from "@/store/dataEventos";
import { useDataPerfil } from "@/store/dataPerfil";
import jsPDF from "jspdf";
import autoTable from "jspdf-autotable";

export default {
  name: "Asistencia General",
  data() {
    return {
      asistencias: [],
      busqueda: "",
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
  setup() {
    const storeEventos = useDataEventos();
    const storeUsuarios = useDataPerfil();

    return { storeUsuarios, storeEventos };
  },
  mounted() {
    this.storeEventos.obtenerEventoPrincipal();
    this.obtenerAsistencias();
    this.storeEventos
      .calcularTotalAsistencias(
        this.storeUsuarios.usuario.id,
        this.storeEventos.eventoPrincipal.id
      )
      .then((respuesta) => {
        this.totalGlobal.horas = respuesta.horas;
        this.totalGlobal.minutos = respuesta.minutos;
        this.totalGlobal.segundos = respuesta.segundos;
      });
  },
  methods: {
    obtenerAsistencias() {
      axios
        .post(`/api/asistencias/usuario/${this.storeUsuarios.usuario.id}`, {
          idEvento: this.storeEventos.eventoPrincipal.id,
        })
        .then((response) => {
          this.asistencias = response.data.asistencias;
          this.paginacion = response.data.paginacion;
          this.total = response.data.total;
          this.calcularTiempoTotal();
        })
        .catch((error) => {});
    },
    generarPDF() {
      this.storeEventos
        .asistenciasPrincipalPDF(
          this.storeUsuarios.usuario.id,
          this.storeEventos.eventoPrincipal.id
        )
        .then((respuesta) => {
          var doc = new jsPDF();

          // var img = new Image();
          // img.src = "http://localhost:8000/assets/media/logos/logo-equino.png";
          // doc.addImage(img, "JPEG", 10, 10, 25, 6);

          var titulo = "Reporte Asistencias";
          var tituloWidth =
            (doc.getStringUnitWidth(titulo) * doc.internal.getFontSize()) /
            doc.internal.scaleFactor;
          var x = (doc.internal.pageSize.getWidth() - tituloWidth) / 2;
          doc.text(titulo, x, 20);
          doc.setFontSize(10); // Tamaño de letra en puntos

          doc.text(
            `Nombres y apellidos: ${this.storeUsuarios.usuario.persona.nombre} ${this.storeUsuarios.usuario.persona.paterno} ${this.storeUsuarios.usuario.persona.materno}`,
            15,
            27
          );
          doc.text(
            `Total Global: ${this.totalGlobal.horas} horas ${this.totalGlobal.minutos} minutos ${this.totalGlobal.segundos} segundos`,
            15,
            32
          );

          var startY = 35;

          var footer = function (data) {
            var pageCount = doc.internal.getNumberOfPages();
            var str = "Página " + data.pageNumber;
            var pageWidth = doc.internal.pageSize.getWidth();
            var textWidth =
              (doc.getStringUnitWidth(str) * doc.internal.getFontSize()) /
              doc.internal.scaleFactor;
            doc.setFontSize(10);
            doc.text(
              str,
              pageWidth - data.settings.margin.right - textWidth,
              doc.internal.pageSize.getHeight() - 10
            );
            doc.text(
              "Centro Integral de Terapias Equinas",
              doc.internal.pageSize.getWidth() / 2,
              doc.internal.pageSize.getHeight() - 10,
              { align: "center" }
            );
          };

          var options = {
            startY: startY,
            head: respuesta.data.cabecera,
            body: respuesta.data.datos,
            margin: { top: 20, bottom: 20 },
            theme: "grid",
            didDrawPage: footer,
          };

          // Obtener la fecha actual
          var today = new Date();
          var dd = String(today.getDate()).padStart(2, "0");
          var mm = String(today.getMonth() + 1).padStart(2, "0"); // Enero es 0
          var yyyy = today.getFullYear();
          var fecha = dd + "-" + mm + "-" + yyyy;

          // Generar el nombre del archivo PDF con la fecha actual
          var nombreArchivo = "Reporte_Asistencias_" + fecha + ".pdf";

          doc.autoTable(options);

          doc.save(nombreArchivo);
        });
    },
  },
};
</script>