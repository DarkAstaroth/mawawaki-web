<template>
  <div class="d-flex justify-content-end mb-5 gap-2">
    <Button type="button" @click="estadoModal(true)">
      <i class="text-white far fa-plus me-3"></i>
      <strong>Nuevo registro</strong>
    </Button>
    <Button type="button" class="btn btn-sm btn-danger" @click="generarPDF">
      <i class="text-white far fa-file"></i>
      PDF
    </Button>
  </div>
  <div class="table-responsive">
    <table class="table table-bordered">
      <thead>
        <tr class="py-4 border-gray-200 fw-semibold fs-7 border-bottom">
          <th class="min-w-150px">Ingreso</th>
          <th class="min-w-150px">Salida</th>
          <th class="min-w-150px">Total</th>
          <th class="max-w-10px">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="asistencia in this.storeAsistencias.asistencias"
          :key="asistencia.id"
          class="p-2"
        >
          <td
            class="align-middle"
            :class="{
              'bg-light-info': asistencia.fecha_hora_entrada !== null,
            }"
          >
            <div
              class="d-flex flex-column justify-content-center align-items-center min-h-40px"
              v-if="asistencia.fecha_hora_entrada !== null"
            >
              {{ asistencia.fecha_hora_entrada }}
              <strong
                v-if="asistencia.ingreso_verificado === 0"
                class="text-danger"
                >No verificado</strong
              >
              <strong v-else class="text-success">Verificado</strong>
            </div>
            <div v-else>No marcado</div>
          </td>
          <td
            class="align-middle"
            :class="{
              'bg-light-info': asistencia.fecha_hora_salida !== null,
            }"
          >
            <div
              class="d-flex flex-column justify-content-center align-items-center min-h-40px"
              v-if="asistencia.fecha_hora_salida !== null"
            >
              {{ asistencia.fecha_hora_salida }}
              <strong
                v-if="asistencia.salida_verificado === 0"
                class="text-danger"
                >No verificado</strong
              >
              <strong v-else class="text-success">Verificado</strong>
            </div>
            <div
              class="d-flex justify-content-center align-items-center min-h-40px"
              v-else
            >
              No marcó
            </div>
          </td>

          <td class="align-middle">
            <div v-if="asistencia.fecha_hora_salida !== null">
              <div
                class="d-flex justify-content-center align-items-center min-h-40px"
              >
                {{ asistencia.horas }} hora(s)
                {{ asistencia.minutos }} minuto(s)
                {{ asistencia.segundos }} segundos
              </div>
            </div>
            <div
              class="d-flex justify-content-center align-items-center min-h-40px"
              v-else
            >
              No Termino su registro
            </div>
          </td>
          <td>
            <Button
              v-if="asistencia.ingreso_verificado === 0"
              @click="confirm2($event, asistencia.id)"
              icon="pi pi-times"
              severity="danger"
              text
              rounded
              aria-label="Cancel"
            />
          </td>
        </tr>

        <tr v-if="this.storeAsistencias.asistencias.length === 0">
          <td colspan="4" class="text-center">No hay datos</td>
        </tr>
      </tbody>
    </table>
  </div>

  <Dialog
    v-model:visible="modalRegistro"
    modal
    header="Registrar asistencia"
    position="center"
    :style="{ width: '50rem' }"
    :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
  >
    <Message severity="info" :closable="false"
      >El registro de asistencias deben ser verificadas por un administrador
      para ser sumadas en el total global</Message
    >

    <div class="d-flex flex-column mb-5">
      <label for="calendar-12h" class="font-bold block mb-2">
        Fecha asistencia
      </label>
      <Calendar v-model="fechaAsistencia" dateFormat="dd/mm/yy" />
    </div>

    <div class="d-flex flex-column mb-5">
      <label for="calendar-12h" class="font-bold block mb-2">
        Hora de ingreso
      </label>
      <Calendar
        v-model="horaIngreso"
        iconDisplay="input"
        timeOnly
        hourFormat="12"
      >
      </Calendar>
    </div>

    <div class="d-flex flex-column mb-5">
      <label for="calendar-12h" class="font-bold block mb-2">
        Hora de salida
      </label>
      <Calendar
        v-model="horaSalida"
        iconDisplay="input"
        timeOnly
        hourFormat="12"
      >
        <template #inputicon="{ clickCallback }">
          <InputIcon
            class="pi pi-clock cursor-pointer"
            @click="clickCallback"
          />
        </template>
      </Calendar>
    </div>

    <div class="d-flex flex-column">
      <Button class="d-flex justify-content-center" @click="registrarAsistencia"
        >Registrar</Button
      >
    </div>
  </Dialog>

  <nav>
    <ul class="pagination">
      <li
        class="page-item"
        :class="{
          disabled: this.storeAsistencias.paginacion.paginaActual === 1,
        }"
      >
        <a class="page-link" href="#" @click="cambiarPaginacion(1)"
          ><Icon
            icon="material-symbols:keyboard-double-arrow-left"
            width="24"
            height="24"
          />
        </a>
      </li>
      <li
        class="page-item"
        :class="{
          disabled: this.storeAsistencias.paginacion.paginaActual === 1,
        }"
      >
        <a
          class="page-link"
          href="#"
          @click="
            cambiarPaginacion(this.storeAsistencias.paginacion.paginaActual - 1)
          "
          ><Icon icon="iconamoon:arrow-left-2" width="24" height="24"
        /></a>
      </li>
      <li
        class="page-item"
        v-for="page in this.storeAsistencias.paginacion.ultimaPagina"
        :key="page"
        :class="{
          active: this.storeAsistencias.paginacion.paginaActual === page,
        }"
      >
        <a class="page-link" href="#" @click="cambiarPaginacion(page)">{{
          page
        }}</a>
      </li>
      <li
        class="page-item"
        :class="{
          disabled:
            this.storeAsistencias.paginacion.paginaActual ===
            this.storeAsistencias.paginacion.ultimaPagina,
        }"
      >
        <a
          class="page-link"
          href="#"
          @click="
            cambiarPaginacion(this.storeAsistencias.paginacion.paginaActual + 1)
          "
          ><Icon icon="iconamoon:arrow-right-2" width="24" height="24"
        /></a>
      </li>
      <li
        class="page-item"
        :class="{
          disabled:
            this.storeAsistencias.paginacion.paginaActual ===
            this.storeAsistencias.paginacion.ultimaPagina,
        }"
      >
        <a
          class="page-link"
          href="#"
          @click="
            cambiarPaginacion(this.storeAsistencias.paginacion.ultimaPagina)
          "
          ><Icon
            icon="material-symbols:keyboard-double-arrow-right"
            width="24"
            height="24"
        /></a>
      </li>
    </ul>
  </nav>

  <Message :closable="false" severity="success">
    Total Acumulado: {{ totalGlobal.horas }} horas
    {{ totalGlobal.minutos }} minutos
    {{ totalGlobal.segundos }} segundos</Message
  >
  <ConfirmPopup></ConfirmPopup>
</template>


<script>
import { useDataAsistencias } from "@/store/dataAsistencias";
import { useDataEventos } from "@/store/dataEventos";
import { useDataPerfil } from "@/store/dataPerfil";
import jsPDF from "jspdf";
import autoTable from "jspdf-autotable";
import { ref } from "vue";
import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast";

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
      modalRegistro: false,
      fechaAsistencia: null,
      horaIngreso: null,
      horaSalida: null,
    };
  },
  setup() {
    const storeEventos = useDataEventos();
    const storeUsuarios = useDataPerfil();
    const storeAsistencias = useDataAsistencias();
    const confirm = useConfirm();
    const toast = useToast();

    const confirm2 = (event, idAsistencia) => {
      confirm.require({
        target: event.currentTarget,
        message: "¿Quieres borrar el registro?",
        icon: "pi pi-info-circle",
        rejectClass: "p-button-secondary p-button-outlined p-button-sm me-5",
        acceptClass: "p-button-danger p-button-sm",
        rejectLabel: "Cancelar",
        acceptLabel: "Borrar",
        accept: () => {
          storeAsistencias.eliminarAsistencia(idAsistencia).then(() => {
            storeAsistencias.obtenerAsistencias(
              storeUsuarios.usuario.id,
              storeEventos.eventoPrincipal.id,
              1
            );
            toast.add({
              severity: "success",
              summary: "Confirmado",
              detail: "Se borró el registro",
              life: 3000,
            });
          });
        },
        reject: () => {
          toast.add({
            severity: "error",
            summary: "Cancelado",
            detail: "No se realizó la acción",
            life: 3000,
          });
        },
      });
    };

    return {
      storeUsuarios,
      storeEventos,
      storeAsistencias,
      confirm,
      toast,
      confirm2,
    };
  },
  mounted() {
    this.storeEventos.obtenerEventoPrincipal();
    this.storeAsistencias.obtenerAsistencias(
      this.storeUsuarios.usuario.id,
      this.storeEventos.eventoPrincipal.id,
      1
    );
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
    cambiarPaginacion(pagina) {
      this.storeAsistencias.obtenerAsistencias(
        this.storeUsuarios.usuario.id,
        this.storeEventos.eventoPrincipal.id,
        pagina
      );
    },
    estadoModal(estado) {
      this.modalRegistro = estado;
    },
    registrarAsistencia() {
      const fechaActual = new Date();
      const fechaIngreso = new Date(this.fechaAsistencia);
      const fechaSalida = new Date(this.fechaAsistencia);

      fechaIngreso.setHours(
        this.horaIngreso.getHours(),
        this.horaIngreso.getMinutes(),
        this.horaIngreso.getSeconds()
      );
      fechaSalida.setHours(
        this.horaSalida.getHours(),
        this.horaSalida.getMinutes(),
        this.horaSalida.getSeconds()
      );

      if (fechaIngreso > fechaActual) {
        this.mostrarMensaje(
          "error",
          "Error en la operación",
          "La fecha de ingreso no puede ser mayor que la fecha actual."
        );
        return;
      }

      if (fechaIngreso >= fechaSalida) {
        this.mostrarMensaje(
          "error",
          "Error en la operación",
          "La fecha de ingreso debe ser anterior a la fecha de salida."
        );
        return;
      }

      const fechaIngresoUnix = fechaIngreso.getTime() / 1000;
      const fechaSalidaUnix = fechaSalida.getTime() / 1000;

      this.storeAsistencias
        .registrarAsistencias(
          this.storeUsuarios.usuario.id,
          this.storeEventos.eventoPrincipal.id,
          fechaIngresoUnix,
          fechaSalidaUnix
        )
        .then(() => {
          this.mostrarMensaje(
            "success",
            "Operación Exitosa",
            "Actividad registrada con éxito"
          );
          this.estadoModal(false);
          this.fechaAsistencia = null;
          this.horaIngreso = null;
          this.horaSalida = null;
          this.storeAsistencias.obtenerAsistencias(
            this.storeUsuarios.usuario.id,
            this.storeEventos.eventoPrincipal.id,
            1
          );
        })
        .catch(() => {
          this.mostrarMensaje(
            "error",
            "Error en la operación",
            "Ya existe una asistencia en esa fecha"
          );
        });
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
    mostrarMensaje(tipo, titulo, texto) {
      this.toast.add({
        severity: tipo,
        summary: titulo,
        detail: texto,
        life: 3000,
      });
    },
  },
};
</script>