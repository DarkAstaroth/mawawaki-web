<template>
  <div class="card-header">
    <h3 class="card-title">Listado de módulos</h3>
    <div class="card-toolbar">
      <button
        type="button"
        class="btn btn-sm btn-success"
        data-bs-toggle="modal"
        data-bs-target="#kt_modal_1"
        @click="
          modo = 'crear';
          resetModalData();
        "
      >
        <i class="far fa-plus text-white"></i>
        Nuevo
      </button>
    </div>

    <div class="modal fade" tabindex="-1" id="kt_modal_1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title">
              {{ modo === "crear" ? "Crear Módulo" : "Editar Módulo" }}
            </h3>

            <!--begin::Close-->
            <div
              class="btn btn-icon btn-sm btn-active-light-primary ms-2"
              data-bs-dismiss="modal"
              aria-label="Close"
            >
              <i class="ki-duotone ki-cross fs-1"
                ><span class="path1"></span><span class="path2"></span
              ></i>
            </div>
            <!--end::Close-->
          </div>
          <!-- <form action=""> -->
          <form
            class="input-feild"
            v-on:submit.prevent="
              modo === 'crear' ? crearRol() : actualizarRol()
            "
          >
            <div class="modal-body">
              <div class="form-group mb-5">
                <input
                  type="text"
                  v-model="nombre"
                  id=""
                  class="form-control"
                  placeholder="Nombre módulo"
                  aria-describedby="helpId"
                />
                <div
                  v-if="v$?.nombre.$error"
                  class="m-2 fv-plugins-message-container invalid-feedback"
                >
                  <div data-field="text_input" data-validator="notEmpty">
                    El nombre de módulo es requerido
                  </div>
                </div>
              </div>

              <div class="form-group">
                <textarea
                  class="form-control"
                  v-model="descripcion"
                  id=""
                  rows="3"
                  placeholder="Descripción"
                ></textarea>
                <div
                  v-if="v$?.descripcion.$error"
                  class="m-2 fv-plugins-message-container invalid-feedback"
                >
                  <div data-field="text_input" data-validator="notEmpty">
                    La descripcion es requerida
                  </div>
                </div>
              </div>
            </div>

            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-light"
                data-bs-dismiss="modal"
              >
                Cerrar
              </button>
              <button type="submit" class="btn btn-success">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="card-body">
    <input
      class="form-control mb-5"
      type="text"
      v-model="busqueda"
      @input="filtrarRoles"
      placeholder="Buscar..."
    />

    <div class="table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr class="fw-semibold fs-6 border-bottom border-gray-200 py-4">
            <th>Nombre</th>
            <th class="min-w-100px">Descripción</th>
            <th class="min-w-200px">Creado en</th>
            <th>Acciones</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="modulo in modulos" :key="modulo.id">
            <td>{{ modulo.nombre }}</td>
            <td>{{ modulo.descripcion }}</td>
            <td>
              {{
                new Date(modulo.created_at).toLocaleString("es-ES", {
                  weekday: "long",
                  day: "numeric",
                  month: "long",
                  year: "numeric",
                  hour: "numeric",
                  minute: "numeric",
                  second: "numeric",
                })
              }}
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
                      <a class="dropdown-item" href="#"
                        ><i class="bi bi-eye-fill fs-4"></i> Ver</a
                      >
                    </li>
                    <li>
                      <a
                        href="#"
                        class="dropdown-item"
                        data-bs-toggle="modal"
                        data-bs-target="#kt_modal_1"
                        @click="
                          modo = 'editar';
                          editarModulo(modulo.id);
                        "
                        ><i class="bi bi-pencil-square fs-4"></i> Editar</a
                      >
                    </li>
                    <li>
                      <a
                        href="#"
                        class="dropdown-item"
                        data-bs-toggle="tooltip"
                        data-bs-custom-class="tooltip-inverse"
                        data-bs-placement="bottom"
                        title="Eliminar permiso"
                        @click="eliminarModulo(modulo.id)"
                        ><i class="bi bi-trash3-fill fs-4"></i> Eliminar</a
                      >
                    </li>
                  </ul>
                </div>
              </div>
            </td>
          </tr>
          <tr v-if="modulos.length === 0">
            <td colspan="4" class="text-center">No hay datos</td>
          </tr>
        </tbody>
      </table>
    </div>
    <nav>
      <ul class="pagination">
        <li
          class="page-item"
          :class="{ disabled: paginacion.paginaActual === 1 }"
        >
          <a class="page-link" href="#" @click="cargarModulos(1)">Primera</a>
        </li>
        <li
          class="page-item"
          :class="{ disabled: paginacion.paginaActual === 1 }"
        >
          <a
            class="page-link"
            href="#"
            @click="cargarModulos(paginacion.paginaActual - 1)"
            >Anterior</a
          >
        </li>
        <li
          class="page-item"
          v-for="page in paginacion.ultimaPagina"
          :key="page"
          :class="{ active: paginacion.paginaActual === page }"
        >
          <a class="page-link" href="#" @click="cargarModulos(page)">{{
            page
          }}</a>
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
            @click="cargarModulos(paginacion.paginaActual + 1)"
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
            @click="cargarModulos(paginacion.ultimaPagina)"
            >Última</a
          >
        </li>
      </ul>
    </nav>
  </div>
  <div class="card-footer">roles del sistema</div>
</template>

<script>
import { useVuelidate } from "@vuelidate/core";
import { required } from "@vuelidate/validators";

export default {
  name: "ModuloIndex",
  setup() {
    return { v$: useVuelidate() };
  },
  data() {
    return {
      moduloId: "",
      nombre: "",
      descripcion: "",
      modulos: [],
      busqueda: "",
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
    };
  },
  validations() {
    return {
      nombre: { required },
      descripcion: { required },
    };
  },
  mounted() {
    this.cargarModulos(1);
  },
  methods: {
    filtrarRoles() {
      this.cargarModulos(1);
    },
    cargarModulos(pagina) {
      const url = "/api/modulos?page=" + pagina + "&busqueda=" + this.busqueda;

      axios
        .get(url)
        .then((response) => {
          this.modulos = response.data.modulos;
          this.paginacion = response.data.paginacion;
        })
        .catch((error) => {});
    },
    crearRol: function () {
      this.v$.$touch();

      if (!this.v$.$error) {
        axios
          .post("/api/modulos/agregar", {
            nombre: this.nombre,
            descripcion: this.descripcion,
          })
          .then((response) => {
            this.modulos = response.data.data;
            Swal.fire({
              title: "Éxito",
              text: "El modulo se creó correctamente",
              icon: "success",
              buttonsStyling: false,
              confirmButtonText: "Aceptar",
              customClass: {
                confirmButton: "btn btn-primary",
              },
            });
            this.cargarModulos(1);
          })
          .catch((error) => {
            Swal.fire({
              title: "Upss..",
              text: "Hubo un error al crear el módulo",
              icon: "error",
              buttonsStyling: false,
              confirmButtonText: "Aceptar",
              customClass: {
                confirmButton: "btn btn-primary",
              },
            });
          });
        $("#kt_modal_1").modal("hide");
        this.v$.$reset();
      } else {
      }
    },
    editarModulo: function (moduloId) {
      axios
        .get(`/api/modulos/${moduloId}`)
        .then((response) => {
          this.moduloId = response.data.modulo.id;
          this.nombre = response.data.modulo.nombre;
          this.descripcion = response.data.modulo.descripcion;
        })
        .catch((error) => {});
    },
    actualizarRol: function () {
      axios
        .put(`/api/modulos/${this.moduloId}`, {
          nombre: this.nombre,
          descripcion: this.descripcion,
        })
        .then((response) => {
          Swal.fire({
            title: "Éxito",
            text: "El módulo se modificó correctamente",
            icon: "success",
            buttonsStyling: false,
            confirmButtonText: "Aceptar",
            customClass: {
              confirmButton: "btn btn-primary",
            },
          });
          $("#kt_modal_1").modal("hide");
          this.busqueda = "";
          this.cargarModulos(1);
        })
        .catch((error) => {});
    },
    eliminarModulo: function (moduloId) {
      Swal.fire({
        title: "¿Estás seguro?",
        text: "¡Esta acción eliminará el módulo!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .delete(`/api/modulos/${moduloId}`)
            .then((response) => {
              Swal.fire({
                title: "Éxito",
                text: "El módulo se eliminó correctamente",
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Aceptar",
                customClass: {
                  confirmButton: "btn btn-primary",
                },
              });
              this.busqueda = "";
              this.cargarModulos(1);
            })
            .catch((error) => {});
        }
      });
    },
    resetModalData: function () {
      this.nombre = "";
      this.descripcion = "";
    },
  },
};
</script>
