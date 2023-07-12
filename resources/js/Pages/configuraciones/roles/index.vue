<template>
  <div class="card-header">
    <h3 class="card-title">Listado de roles</h3>
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
              {{ modo === "crear" ? "Crear Rol" : "Editar Rol" }}
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
                  v-model="name"
                  id=""
                  class="form-control"
                  placeholder="Nombre rol"
                  aria-describedby="helpId"
                />
                <div
                  v-if="v$?.name.$error"
                  class="m-2 fv-plugins-message-container invalid-feedback"
                >
                  <div data-field="text_input" data-validator="notEmpty">
                    El nombre de rol es requerido
                  </div>
                </div>
              </div>

              <div class="form-group">
                <textarea
                  class="form-control"
                  v-model="description"
                  id=""
                  rows="3"
                  placeholder="Descripción"
                ></textarea>
                <div
                  v-if="v$?.description.$error"
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
      <table class="table table-striped table-sm table-bordered">
        <thead>
          <tr class="fw-semibold fs-7 border-bottom border-gray-200 py-4">
            <th class="min-w-150px">Nombre</th>
            <th>Descripción</th>
            <th class="min-w-150px">Creado en</th>
            <th>Acciones</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="rol in roles" :key="rol.id">
            <td>{{ rol.name }}</td>
            <td>{{ rol.description }}</td>
            <td>
              {{
                new Date(rol.created_at).toLocaleString("es-ES", {
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
                    class="btn btn-secondary dropdown-toggle"
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
                    <li><a class="dropdown-item" href="#">Ver</a></li>
                    <li>
                      <a
                        href="#"
                        class="dropdown-item"
                        data-bs-toggle="modal"
                        data-bs-target="#kt_modal_1"
                        @click="
                          modo = 'editar';
                          editarRol(rol.id);
                        "
                        >Editar</a
                      >
                    </li>
                    <li>
                      <a
                        href="#"
                        class="dropdown-item"
                        data-bs-toggle="tooltip"
                        data-bs-custom-class="tooltip-inverse"
                        data-bs-placement="bottom"
                        title="Eliminar rol"
                        @click="eliminarRol(rol.id)"
                        >Eliminar</a
                      >
                    </li>
                  </ul>
                </div>
              </div>
            </td>
          </tr>
          <tr v-if="roles.length === 0">
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
          <a class="page-link" href="#" @click="cargarRoles(page)">{{
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
  </div>
  <div class="card-footer">roles del sistema</div>
</template>

<script>
import { useVuelidate } from "@vuelidate/core";
import { required, email } from "@vuelidate/validators";

export default {
  name: "RolIndex",
  setup() {
    return { v$: useVuelidate() };
  },
  data() {
    return {
      rolId: "",
      name: "",
      description: "",
      roles: [],
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
      name: { required },
      description: { required },
    };
  },
  mounted() {
    this.cargarRoles(1);
  },
  methods: {
    filtrarRoles() {
      this.cargarRoles(1);
    },
    cargarRoles(pagina) {
      const url = "/api/roles?page=" + pagina + "&busqueda=" + this.busqueda;

      axios
        .get(url)
        .then((response) => {
          console.log(response);
          this.roles = response.data.roles;
          this.paginacion = response.data.paginacion;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    crearRol: function () {
      this.v$.$touch();

      if (!this.v$.$error) {
        axios
          .post("/api/roles/agregar", {
            name: this.name,
            description: this.description,
          })
          .then((response) => {
            this.roles = response.data.data;
            Swal.fire({
              title: "Éxito",
              text: "El rol se creó correctamente",
              icon: "success",
              buttonsStyling: false,
              confirmButtonText: "Aceptar",
              customClass: {
                confirmButton: "btn btn-primary",
              },
            });
            this.cargarRoles(1);
          })
          .catch((error) => {
            Swal.fire({
              title: "Upss..",
              text: "Hubo un error al crear el rol",
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
        console.log("error de formulario");
      }
    },
    editarRol: function (rolId) {
      axios
        .get(`/api/roles/${rolId}`)
        .then((response) => {
          this.rolId = response.data.rol.id;
          this.name = response.data.rol.name;
          this.description = response.data.rol.description;
        })
        .catch((error) => {});
    },
    actualizarRol: function () {
      console.log("Actualizar rol");
      axios
        .put(`/api/roles/${this.rolId}`, {
          name: this.name,
          description: this.description,
        })
        .then((response) => {
          Swal.fire({
            title: "Éxito",
            text: "El rol se modificó correctamente",
            icon: "success",
            buttonsStyling: false,
            confirmButtonText: "Aceptar",
            customClass: {
              confirmButton: "btn btn-primary",
            },
          });
          $("#kt_modal_1").modal("hide");
          this.busqueda = "";
          this.cargarRoles(1);
        })
        .catch((error) => {});
    },
    eliminarRol: function (rolId) {
      Swal.fire({
        title: "¿Estás seguro?",
        text: "¡Esta acción eliminará el rol!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .delete(`/api/roles/${rolId}`)
            .then((response) => {
              Swal.fire({
                title: "Éxito",
                text: "El rol se eliminó correctamente",
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Aceptar",
                customClass: {
                  confirmButton: "btn btn-primary",
                },
              });
              this.busqueda = "";
              this.cargarRoles(1);
            })
            .catch((error) => {});
        }
      });
    },
    resetModalData: function () {
      this.name = "";
      this.description = "";
    },
  },
};
</script>
